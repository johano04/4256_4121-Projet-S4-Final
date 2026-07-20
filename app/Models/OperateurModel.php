<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['nom_operateur', 'commission_inter_operateur'];

    /**
     * Retrouve l'opérateur d'un client via clients -> prefixes -> operateurs.
     * C'est ce qui permet de détecter automatiquement l'opérateur d'un compte.
     */
    public function pourClient(int $clientId): ?array
    {
        return $this->select('operateurs.*')
            ->join('prefixes', 'prefixes.operateur_id = operateurs.id')
            ->join('clients', 'clients.prefixe_id = prefixes.id')
            ->where('clients.id', $clientId)
            ->first();
    }
}
