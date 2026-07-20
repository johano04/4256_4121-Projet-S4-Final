<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['telephone', 'nom', 'prefixe_id', 'solde'];

    public function trouverParTelephone(string $telephone): ?array
    {
        return $this->where('telephone', $telephone)->first();
    }

    public function ajusterSolde(int $clientId, float $montant): float
    {
        $client = $this->find($clientId);
        $nouveauSolde = $client['solde'] + $montant;

        $this->update($clientId, ['solde' => $nouveauSolde]);

        return $nouveauSolde;
    }
}
