<?php

namespace App\Controllers\Client;

use App\Libraries\FraisCalculator;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;

class DepotController extends ClientBaseController
{
    public function index()
    {
        return view('client/depot', ['client' => $this->clientCourant()]);
    }

    public function effectuer()
    {
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('erreur', 'Le montant doit être supérieur à 0.');
        }

        $client = $this->clientCourant();

        $typeOperationModel = new TypeOperationModel();
        $typeDepot           = $typeOperationModel->trouverParCode('DEPOT');

        $fraisCalculator = new FraisCalculator();
        $frais            = $fraisCalculator->calculer($typeDepot['id'], $montant);

        $soldeAvant = (float) $client['solde'];
        $soldeApres = $soldeAvant + $montant - $frais;

        $db = db_connect();
        $db->transStart();

        $this->clientModel->update($client['id'], ['solde' => $soldeApres]);

        $operationModel = new OperationModel();
        $operationModel->insert([
            'type_operation_id' => $typeDepot['id'],
            'client_id'         => $client['id'],
            'montant'           => $montant,
            'frais'             => $frais,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeApres,
            'statut'            => 'REUSSI',
        ]);

        $db->transComplete();

        return redirect()->to('/client/tableau-de-bord')
            ->with('succes', 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès.');
    }
}
