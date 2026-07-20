<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['prefixe', 'operateur', 'actif'];

    public function trouverParTelephone(string $telephone): ?array
    {
        $prefixesActifs = $this->where('actif', 1)->findAll();

        foreach ($prefixesActifs as $prefixe) {
            if (str_starts_with($telephone, $prefixe['prefixe'])) {
                return $prefixe;
            }
        }

        return null;
    }
}
