<?php

namespace App\Controllers\Client;

use App\Models\OperationModel;

class CompteController extends ClientBaseController
{
    public function index()
    {
        $client = $this->clientCourant();

        $operationModel = new OperationModel();
        $dernieresOperations = array_slice($operationModel->historiqueClient($client['id']), 0, 5);

        return view('client/tableau_de_bord', [
            'client'     => $client,
            'operations' => $dernieresOperations,
        ]);
    }
}
