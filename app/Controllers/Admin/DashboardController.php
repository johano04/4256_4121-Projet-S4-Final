<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\OperationModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $operationModel = new OperationModel();
        $clientModel    = new ClientModel();

        $db = db_connect();

        $gainsParType = $db->table('vue_gains_par_type')->get()->getResultArray();

        $totalFrais = $operationModel->totalFrais();

        $comptes = $db->table('vue_situation_comptes')->orderBy('solde', 'DESC')->get()->getResultArray();

        $totalSoldeClients = array_sum(array_column($comptes, 'solde'));

        return view('admin/tableau_de_bord', [
            'gainsParType'      => $gainsParType,
            'totalFrais'        => $totalFrais,
            'comptes'           => $comptes,
            'totalSoldeClients' => $totalSoldeClients,
            'nombreClients'     => count($comptes),
        ]);
    }
}
