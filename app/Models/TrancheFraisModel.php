<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheFraisModel extends Model
{
    protected $table            = 'tranches_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];

    public function pourType(int $typeOperationId): array
    {
        return $this->where('type_operation_id', $typeOperationId)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }

    public function trouverTranche(int $typeOperationId, float $montant): ?array
    {
        return $this->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->groupStart()
                ->where('montant_max >=', $montant)
                ->orWhere('montant_max', null)
            ->groupEnd()
            ->orderBy('montant_min', 'DESC')
            ->first();
    }
}
