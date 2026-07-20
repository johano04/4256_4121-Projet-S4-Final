<?php

namespace App\Controllers\Client;

use App\Libraries\FraisCalculator;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;

class TransfertController extends ClientBaseController
{
    public function index()
    {
        return view('client/transfert', ['client' => $this->clientCourant()]);
    }

    public function effectuer()
    {
        $telephoneDestinataire = trim((string) $this->request->getPost('telephone_destinataire'));
        $montant                = (float) $this->request->getPost('montant');

        $client = $this->clientCourant();

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('erreur', 'Le montant doit être supérieur à 0.');
        }

        if ($telephoneDestinataire === $client['telephone']) {
            return redirect()->back()->withInput()->with('erreur', 'Vous ne pouvez pas vous transférer de l\'argent à vous-même.');
        }

        $destinataire = $this->clientModel->trouverParTelephone($telephoneDestinataire);

        if ($destinataire === null) {
            return redirect()->back()->withInput()->with('erreur', 'Ce numéro de destinataire est introuvable.');
        }

        $typeOperationModel = new TypeOperationModel();
        $typeTransfert        = $typeOperationModel->trouverParCode('TRANSFERT');

        $fraisCalculator = new FraisCalculator();
        $frais            = $fraisCalculator->calculer($typeTransfert['id'], $montant);

        $soldeAvantEnvoyeur = (float) $client['solde'];
        $totalDebit          = $montant + $frais;

        if ($totalDebit > $soldeAvantEnvoyeur) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Solde insuffisant pour ce transfert (montant + frais = ' . number_format($totalDebit, 0, ',', ' ') . ' Ar).');
        }

        $soldeApresEnvoyeur    = $soldeAvantEnvoyeur - $totalDebit;
        $soldeAvantDestinataire = (float) $destinataire['solde'];
        $soldeApresDestinataire = $soldeAvantDestinataire + $montant;

        $db = db_connect();
        $db->transStart();

        $this->clientModel->update($client['id'], ['solde' => $soldeApresEnvoyeur]);
        $this->clientModel->update($destinataire['id'], ['solde' => $soldeApresDestinataire]);

        $operationModel = new OperationModel();

        $operationModel->insert([
            'type_operation_id'      => $typeTransfert['id'],
            'client_id'              => $client['id'],
            'client_destinataire_id' => $destinataire['id'],
            'montant'                => $montant,
            'frais'                  => $frais,
            'solde_avant'            => $soldeAvantEnvoyeur,
            'solde_apres'            => $soldeApresEnvoyeur,
            'statut'                 => 'REUSSI',
        ]);

        $operationModel->insert([
            'type_operation_id'      => $typeTransfert['id'],
            'client_id'              => $destinataire['id'],
            'client_destinataire_id' => $client['id'],
            'montant'                => $montant,
            'frais'                  => 0,
            'solde_avant'            => $soldeAvantDestinataire,
            'solde_apres'            => $soldeApresDestinataire,
            'statut'                 => 'REUSSI',
        ]);

        $db->transComplete();

        return redirect()->to('/client/tableau-de-bord')
            ->with('succes', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $destinataire['telephone'] . ' effectué avec succès.');
    }
}
