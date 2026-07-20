<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'types_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['code', 'libelle', 'actif'];

    public function trouverParCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}
