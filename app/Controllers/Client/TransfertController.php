<?php

namespace App\Controllers\Client;

use App\Libraries\FraisCalculator;
use App\Models\OperateurModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;

class TransfertController extends ClientBaseController
{
    protected OperateurModel $operateurModel;
    protected TypeOperationModel $typeOperationModel;
    protected FraisCalculator $fraisCalculator;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->operateurModel     = new OperateurModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->fraisCalculator    = new FraisCalculator();
    }

    public function index()
    {
        return view('client/transfert', ['client' => $this->clientCourant()]);
    }

    public function multiple()
    {
        return view('client/transfert_multiple', ['client' => $this->clientCourant()]);
    }

    public function effectuer()
    {
        $telephoneDestinataire = trim((string) $this->request->getPost('telephone_destinataire'));
        $montant                = (float) $this->request->getPost('montant');
        $inclureFraisRetrait    = (bool) $this->request->getPost('inclure_frais_retrait');

        $client = $this->clientCourant();

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('erreur', 'Le montant doit être supérieur à 0.');
        }

        if ($telephoneDestinataire === $client['telephone']) {
            return redirect()->back()->withInput()->with('erreur', "Vous ne pouvez pas vous transférer de l'argent à vous-même.");
        }

        $destinataire = $this->clientModel->trouverParTelephone($telephoneDestinataire);

        if ($destinataire === null) {
            return redirect()->back()->withInput()->with('erreur', 'Ce numéro de destinataire est introuvable.');
        }

        $detail = $this->calculerDetailTransfert($client['id'], $destinataire['id'], $montant, $inclureFraisRetrait);

        if ($detail['total_debit'] > (float) $client['solde']) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Solde insuffisant pour ce transfert (montant + frais = ' . number_format($detail['total_debit'], 0, ',', ' ') . ' Ar).');
        }

        $db = db_connect();
        $db->transStart();

        $this->enregistrerTransfert($client, $destinataire, $montant, $detail, null);

        $db->transComplete();

        $message = 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $destinataire['telephone']
            . ' effectué avec succès. Frais : ' . number_format($detail['frais'], 0, ',', ' ') . ' Ar.';

        if ($detail['est_inter_operateur']) {
            $message .= ' Commission inter-opérateur : ' . number_format($detail['commission_supplementaire'], 0, ',', ' ') . ' Ar.';
        }

        if ($inclureFraisRetrait) {
            $message .= ' Frais de retrait inclus (offert au destinataire) : ' . number_format($detail['frais_retrait_inclus'], 0, ',', ' ') . ' Ar.';
        }

        $message .= ' Total payé : ' . number_format($detail['total_debit'], 0, ',', ' ') . ' Ar.';

        return redirect()->to('/client/tableau-de-bord')->with('succes', $message);
    }

    /**
     * Envoi multiple : un même expéditeur envoie de l'argent à plusieurs numéros
     * en une seule opération, soit en divisant automatiquement un montant total,
     * soit avec un montant personnalisé par numéro.
     */
    public function effectuerMultiple()
    {
        $client = $this->clientCourant();

        $mode                  = $this->request->getPost('mode') === 'personnalise' ? 'personnalise' : 'division';
        $montantTotal          = (float) $this->request->getPost('montant_total');
        $montantsPersonnalises = (array) $this->request->getPost('montants');
        $inclureFraisRetrait   = (bool) $this->request->getPost('inclure_frais_retrait');

        $numerosBruts = array_map('trim', (array) $this->request->getPost('numeros'));
        $numeros      = array_unique(array_filter($numerosBruts, static fn ($n) => $n !== ''));

        if (count($numeros) < 2) {
            return redirect()->back()->withInput()->with('erreur', 'Indiquez au moins deux numéros de destinataires différents.');
        }

        // 1. Résoudre chaque destinataire (les clés d'origine relient numeros[] et montants[])
        $destinataires = [];
        $erreurs       = [];

        foreach ($numeros as $index => $numero) {
            if ($numero === $client['telephone']) {
                $erreurs[] = "Vous ne pouvez pas vous transférer de l'argent à vous-même ({$numero}).";
                continue;
            }

            $destinataireClient = $this->clientModel->trouverParTelephone($numero);

            if ($destinataireClient === null) {
                $erreurs[] = "Numéro introuvable : {$numero}.";
                continue;
            }

            $destinataires[] = [
                'client'  => $destinataireClient,
                'montant' => $mode === 'personnalise' ? (float) ($montantsPersonnalises[$index] ?? 0) : 0.0,
            ];
        }

        if (! empty($erreurs)) {
            return redirect()->back()->withInput()->with('erreurs', $erreurs);
        }

        // 2. Déterminer le montant de chaque destinataire
        if ($mode === 'personnalise') {
            foreach ($destinataires as $d) {
                if ($d['montant'] <= 0) {
                    return redirect()->back()->withInput()->with('erreur', 'Chaque montant personnalisé doit être supérieur à 0.');
                }
            }
        } else {
            if ($montantTotal <= 0) {
                return redirect()->back()->withInput()->with('erreur', 'Le montant total doit être supérieur à 0.');
            }

            // Division automatique : chacun reçoit une part égale, le reliquat
            // (dû aux arrondis) est ajouté au dernier destinataire.
            $nb        = count($destinataires);
            $partEgale = floor($montantTotal / $nb);
            $reliquat  = $montantTotal - ($partEgale * $nb);

            foreach ($destinataires as $i => &$d) {
                $d['montant'] = $partEgale + ($i === $nb - 1 ? $reliquat : 0);
            }
            unset($d);
        }

        // 3. Calculer le coût de chaque envoi SANS écrire en base, pour vérifier
        //    le solde total avant de valider quoi que ce soit.
        $totalDebit = 0.0;

        foreach ($destinataires as &$d) {
            $d['detail'] = $this->calculerDetailTransfert($client['id'], $d['client']['id'], $d['montant'], $inclureFraisRetrait);
            $totalDebit += $d['detail']['total_debit'];
        }
        unset($d);

        if ($totalDebit > (float) $client['solde']) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Solde insuffisant pour cet envoi multiple (total à payer = ' . number_format($totalDebit, 0, ',', ' ') . ' Ar).');
        }

        // 4. Enregistrement atomique, regroupé sous une même référence de lot
        $referenceGroupe = 'LOT-' . date('YmdHis') . '-' . $client['id'];

        $db = db_connect();
        $db->transStart();

        foreach ($destinataires as $d) {
            $this->enregistrerTransfert($client, $d['client'], $d['montant'], $d['detail'], $referenceGroupe);
            $client = $this->clientModel->find($client['id']); // solde à jour pour l'itération suivante
        }

        $db->transComplete();

        return redirect()->to('/client/tableau-de-bord')
            ->with('succes', 'Envoi multiple réussi vers ' . count($destinataires) . ' destinataires, pour un total payé de '
                . number_format($totalDebit, 0, ',', ' ') . ' Ar.');
    }

    /**
     * Calcule frais normaux, commission inter-opérateur et frais de retrait
     * inclus pour un transfert donné, sans toucher à la base de données.
     * Réutilisée par effectuer() et effectuerMultiple() pour éviter la duplication.
     */
    private function calculerDetailTransfert(int $expediteurId, int $destinataireId, float $montant, bool $inclureFraisRetrait): array
    {
        $typeTransfert = $this->typeOperationModel->trouverParCode('TRANSFERT');
        $frais         = $this->fraisCalculator->calculer((int) $typeTransfert['id'], $montant);

        $operateurExpediteur   = $this->operateurModel->pourClient($expediteurId);
        $operateurDestinataire = $this->operateurModel->pourClient($destinataireId);

        $estInterOperateur = $operateurExpediteur !== null && $operateurDestinataire !== null
            && (int) $operateurExpediteur['id'] !== (int) $operateurDestinataire['id'];

        $commissionSupplementaire = 0.0;
        if ($estInterOperateur) {
            // La commission supplémentaire est calculée sur le taux de l'opérateur émetteur
            $commissionSupplementaire = $this->fraisCalculator->calculerCommissionInterOperateur(
                $montant,
                (float) $operateurExpediteur['commission_inter_operateur']
            );
        }

        $fraisRetraitInclus = 0.0;
        if ($inclureFraisRetrait) {
            $typeRetrait        = $this->typeOperationModel->trouverParCode('RETRAIT');
            $fraisRetraitInclus = $this->fraisCalculator->calculer((int) $typeRetrait['id'], $montant);
        }

        return [
            'type_transfert_id'         => (int) $typeTransfert['id'],
            'frais'                     => $frais,
            'commission_supplementaire' => $commissionSupplementaire,
            'est_inter_operateur'       => $estInterOperateur,
            'frais_retrait_inclus'      => $fraisRetraitInclus,
            // Total débité à l'expéditeur : montant + commission normale + commission inter-opérateur + frais de retrait prépayé
            'total_debit'               => $montant + $frais + $commissionSupplementaire + $fraisRetraitInclus,
            // Le destinataire reçoit le montant, augmenté du frais de retrait prépayé s'il a été inclus
            'credit_destinataire'       => $montant + $fraisRetraitInclus,
        ];
    }

    /**
     * Débite l'expéditeur, crédite le destinataire et journalise les deux
     * mouvements. À appeler à l'intérieur d'une transaction db_connect().
     */
    private function enregistrerTransfert(array $expediteur, array $destinataire, float $montant, array $detail, ?string $referenceGroupe): void
    {
        $soldeAvantEnvoyeur     = (float) $expediteur['solde'];
        $soldeApresEnvoyeur     = $soldeAvantEnvoyeur - $detail['total_debit'];
        $soldeAvantDestinataire = (float) $destinataire['solde'];
        $soldeApresDestinataire = $soldeAvantDestinataire + $detail['credit_destinataire'];

        $this->clientModel->update($expediteur['id'], ['solde' => $soldeApresEnvoyeur]);
        $this->clientModel->update($destinataire['id'], ['solde' => $soldeApresDestinataire]);

        $operationModel = new OperationModel();

        $operationModel->insert([
            'type_operation_id'         => $detail['type_transfert_id'],
            'client_id'                 => $expediteur['id'],
            'client_destinataire_id'    => $destinataire['id'],
            'montant'                   => $montant,
            'frais'                     => $detail['frais'],
            'commission_supplementaire' => $detail['commission_supplementaire'],
            'est_inter_operateur'       => $detail['est_inter_operateur'] ? 1 : 0,
            'frais_retrait_inclus'      => $detail['frais_retrait_inclus'],
            'reference_groupe'          => $referenceGroupe,
            'role'                      => 'PRINCIPAL',
            'solde_avant'               => $soldeAvantEnvoyeur,
            'solde_apres'               => $soldeApresEnvoyeur,
            'statut'                    => 'REUSSI',
        ]);

        $operationModel->insert([
            'type_operation_id'         => $detail['type_transfert_id'],
            'client_id'                 => $destinataire['id'],
            'client_destinataire_id'    => $expediteur['id'],
            'montant'                   => $montant,
            'frais'                     => 0,
            'commission_supplementaire' => 0,
            'est_inter_operateur'       => $detail['est_inter_operateur'] ? 1 : 0,
            'frais_retrait_inclus'      => $detail['frais_retrait_inclus'],
            'reference_groupe'          => $referenceGroupe,
            'role'                      => 'MIROIR',
            'solde_avant'               => $soldeAvantDestinataire,
            'solde_apres'               => $soldeApresDestinataire,
            'statut'                    => 'REUSSI',
        ]);
    }
}
