<?php

namespace App\Controllers\Client;

use App\Libraries\FraisCalculator;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;

class RetraitController extends ClientBaseController
{
    public function index()
    {
        return view('client/retrait', ['client' => $this->clientCourant()]);
    }

    public function effectuer()
    {
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('erreur', 'Le montant doit être supérieur à 0.');
        }

        $client = $this->clientCourant();

        $typeOperationModel = new TypeOperationModel();
        $typeRetrait          = $typeOperationModel->trouverParCode('RETRAIT');

        $fraisCalculator = new FraisCalculator();
        $frais            = $fraisCalculator->calculer($typeRetrait['id'], $montant);

        $soldeAvant = (float) $client['solde'];
        $totalDebit = $montant + $frais;

        if ($totalDebit > $soldeAvant) {
            return redirect()->back()->withInput()
                ->with('erreur', 'Solde insuffisant pour ce retrait (montant + frais = ' . number_format($totalDebit, 0, ',', ' ') . ' Ar).');
        }

        $soldeApres = $soldeAvant - $totalDebit;

        $db = db_connect();
        $db->transStart();

        $this->clientModel->update($client['id'], ['solde' => $soldeApres]);

        $operationModel = new OperationModel();
        $operationModel->insert([
            'type_operation_id' => $typeRetrait['id'],
            'client_id'         => $client['id'],
            'montant'           => $montant,
            'frais'             => $frais,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeApres,
            'statut'            => 'REUSSI',
        ]);

        $db->transComplete();

        return redirect()->to('/client/tableau-de-bord')
            ->with('succes', 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès (frais : ' . number_format($frais, 0, ',', ' ') . ' Ar).');
    }
}
