<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'type_operation_id',
        'client_id',
        'client_destinataire_id',
        'montant',
        'frais',
        'solde_avant',
        'solde_apres',
        'statut',
    ];

    public function historiqueClient(int $clientId): array
    {
        return $this->select('operations.*, types_operation.code as type_code, types_operation.libelle as type_libelle')
            ->join('types_operation', 'types_operation.id = operations.type_operation_id')
            ->where('operations.client_id', $clientId)
            ->orderBy('operations.created_at', 'DESC')
            ->findAll();
    }

    public function totalFrais(): float
    {
        $result = $this->selectSum('frais')
            ->where('statut', 'REUSSI')
            ->first();

        return (float) ($result['frais'] ?? 0);
    }
}
