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
        'telephone_destinataire',
        'montant',
        'frais',
        'commission_supplementaire',
        'est_inter_operateur',
        'frais_retrait_inclus',
        'reference_groupe',
        'role',
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

    /**
     * Journal complet des transactions initiées par des clients MVola (role
     * PRINCIPAL uniquement, pour ne pas afficher deux fois un même transfert
     * via sa ligne MIROIR). Le destinataire est soit un membre MVola (jointure
     * clients), soit un numéro externe stocké tel quel dans telephone_destinataire.
     */
    public function journalMvola(): array
    {
        return $this->select('
                operations.*,
                types_operation.code as type_code,
                types_operation.libelle as type_libelle,
                expediteur.telephone as expediteur_telephone,
                destinataire.telephone as destinataire_telephone_membre
            ')
            ->join('types_operation', 'types_operation.id = operations.type_operation_id')
            ->join('clients expediteur', 'expediteur.id = operations.client_id')
            ->join('clients destinataire', 'destinataire.id = operations.client_destinataire_id', 'left')
            ->where('operations.role', 'PRINCIPAL')
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
