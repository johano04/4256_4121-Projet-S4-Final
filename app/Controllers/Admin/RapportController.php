<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OperationModel;

/**
 * Rapports opérateur V2 :
 *  - Situation des gains via les différents frais (intra vs inter-opérateur)
 *  - Situation des montants envoyés par opérateur
 */
class RapportController extends BaseController
{
    /**
     * "SITUATION GAIN VIA LES DIFFERENTS FRAIS"
     * Sépare les gains intra-opérateur des gains inter-opérateur, et distingue
     * la commission normale (frais de tranche) de la commission supplémentaire.
     */
    public function situationGains()
    {
        $db = db_connect();

        $lignes = $db->table('vue_gains_operateurs')->get()->getResultArray();

        $intra = null;
        $inter = null;

        foreach ($lignes as $ligne) {
            if ($ligne['categorie'] === 'INTRA') {
                $intra = $ligne;
            } else {
                $inter = $ligne;
            }
        }

        $intra ??= ['nb_operations' => 0, 'total_commission_normale' => 0, 'total_commission_supplementaire' => 0, 'total_gains' => 0];
        $inter ??= ['nb_operations' => 0, 'total_commission_normale' => 0, 'total_commission_supplementaire' => 0, 'total_gains' => 0];

        return view('admin/situation_gains', [
            'intra'                        => $intra,
            'inter'                        => $inter,
            'totalCommissionNormale'       => (float) $intra['total_commission_normale'] + (float) $inter['total_commission_normale'],
            'totalCommissionSupplementaire' => (float) $inter['total_commission_supplementaire'],
            'totalGeneral'                 => (float) $intra['total_gains'] + (float) $inter['total_gains'],
        ]);
    }

    /**
     * Total envoyé (transferts réussis) vers chaque opérateur.
     */
    public function situationMontants()
    {
        $db = db_connect();

        $montants = $db->table('vue_montants_par_operateur')->orderBy('total_envoye', 'DESC')->get()->getResultArray();

        return view('admin/situation_montants', [
            'montants'    => $montants,
            'totalEnvoye' => array_sum(array_column($montants, 'total_envoye')),
        ]);
    }

    /**
     * Journal complet des transactions faites par tous les clients MVola,
     * qu'elles restent internes (vers un autre membre MVola) ou sortent vers
     * un autre opérateur (numéro externe, jamais de compte créé pour lui).
     */
    public function journal()
    {
        $operationModel = new OperationModel();

        return view('admin/journal_transactions', [
            'operations' => $operationModel->journalMvola(),
        ]);
    }
}
