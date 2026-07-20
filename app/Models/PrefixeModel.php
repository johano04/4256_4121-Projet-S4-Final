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

    protected $allowedFields = ['prefixe', 'operateur_id', 'actif'];

    /**
     * Détecte automatiquement l'opérateur à partir du numéro de téléphone,
     * en comparant le début du numéro à chaque préfixe actif.
     * Retourne le préfixe accompagné du nom et de la commission de son opérateur.
     */
    public function trouverParTelephone(string $telephone): ?array
    {
        $prefixesActifs = $this->select('prefixes.*, operateurs.nom_operateur, operateurs.commission_inter_operateur')
            ->join('operateurs', 'operateurs.id = prefixes.operateur_id')
            ->where('prefixes.actif', 1)
            ->findAll();

        foreach ($prefixesActifs as $prefixe) {
            if (str_starts_with($telephone, $prefixe['prefixe'])) {
                return $prefixe;
            }
        }

        return null;
    }

    /**
     * Liste des préfixes avec le nom de leur opérateur (pour l'affichage admin).
     */
    public function avecOperateur(): array
    {
        return $this->select('prefixes.*, operateurs.nom_operateur')
            ->join('operateurs', 'operateurs.id = prefixes.operateur_id')
            ->orderBy('prefixes.prefixe', 'ASC')
            ->findAll();
    }
}
