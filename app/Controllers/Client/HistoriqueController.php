<?php

namespace App\Controllers\Client;

use App\Models\OperationModel;

class HistoriqueController extends ClientBaseController
{
    public function index()
    {
        $client = $this->clientCourant();

        $operationModel = new OperationModel();

        return view('client/historique', [
            'client'     => $client,
            'operations' => $operationModel->historiqueClient($client['id']),
        ]);
    }
}
