<?php

namespace App\Libraries;

use App\Models\TrancheFraisModel;

class FraisCalculator
{
    protected TrancheFraisModel $trancheFraisModel;

    public function __construct()
    {
        $this->trancheFraisModel = new TrancheFraisModel();
    }

    public function calculer(int $typeOperationId, float $montant): float
    {
        $tranche = $this->trancheFraisModel->trouverTranche($typeOperationId, $montant);

        return $tranche ? (float) $tranche['frais'] : 0.0;
    }

    /**
     * Commission supplémentaire appliquée quand un transfert change d'opérateur
     * (ex: 2% du montant envoyé, taux défini sur l'opérateur émetteur).
     */
    public function calculerCommissionInterOperateur(float $montant, float $tauxPourcent): float
    {
        return round($montant * $tauxPourcent / 100, 2);
    }
}
