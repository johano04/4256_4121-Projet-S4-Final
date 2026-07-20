<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * VERSION 2 - Opérateurs, commission inter-opérateur et envoi multiple.
 *
 * - Crée la table `operateurs` (nom + commission_inter_operateur en %).
 * - Ajoute `prefixes.operateur_id` (FK) et bascule les données depuis
 *   l'ancienne colonne texte `prefixes.operateur`.
 * - Ajoute à `operations` les colonnes nécessaires aux rapports V2 :
 *   commission_supplementaire, est_inter_operateur, frais_retrait_inclus,
 *   reference_groupe (regroupement des envois multiples).
 */
class AjoutOperateursEtCommissions extends Migration
{
    public function up()
    {
        // 1. Table des opérateurs
        $this->forge->addField([
            'id'                         => ['type' => 'INTEGER', 'auto_increment' => true],
            'nom_operateur'              => ['type' => 'VARCHAR', 'constraint' => 50],
            'commission_inter_operateur' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'created_at'                 => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nom_operateur');
        $this->forge->createTable('operateurs', true);

        // 2. Rattacher les préfixes existants à un opérateur
        if ($this->db->tableExists('prefixes')) {
            $this->forge->addColumn('prefixes', [
                'operateur_id' => ['type' => 'INTEGER', 'null' => true, 'after' => 'operateur'],
            ]);

            // Crée un opérateur pour chaque valeur texte déjà présente dans prefixes.operateur
            $operateursExistants = $this->db->table('prefixes')
                ->select('operateur')
                ->distinct()
                ->get()
                ->getResultArray();

            foreach ($operateursExistants as $row) {
                $nom = trim((string) $row['operateur']);
                if ($nom === '') {
                    continue;
                }

                $existe = $this->db->table('operateurs')->where('nom_operateur', $nom)->get()->getRowArray();

                if ($existe === null) {
                    $this->db->table('operateurs')->insert([
                        'nom_operateur'              => $nom,
                        'commission_inter_operateur' => 2.00, // valeur par défaut, ajustable par l'admin
                        'created_at'                 => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // Backfill operateur_id à partir du nom
            $this->db->query(
                'UPDATE prefixes
                 SET operateur_id = (
                     SELECT id FROM operateurs WHERE operateurs.nom_operateur = prefixes.operateur
                 )'
            );

            // L'ancienne colonne texte n'est plus utilisée : on la retire
            $this->forge->dropColumn('prefixes', 'operateur');
        }

        // 3. Colonnes V2 sur operations (commission inter-opérateur, frais de
        //    retrait inclus, regroupement des envois multiples)
        if ($this->db->tableExists('operations')) {
            $this->forge->addColumn('operations', [
                'commission_supplementaire' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
                'est_inter_operateur'       => ['type' => 'INTEGER', 'default' => 0],
                'frais_retrait_inclus'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
                'reference_groupe'          => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
                // Distingue la ligne PRINCIPAL (expéditeur, porte les frais) de la
                // ligne MIROIR (destinataire) créées pour un même transfert, afin
                // que les rapports par opérateur ne comptent pas le montant 2 fois.
                'role'                      => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'PRINCIPAL'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('operations')) {
            $this->forge->dropColumn('operations', [
                'commission_supplementaire',
                'est_inter_operateur',
                'frais_retrait_inclus',
                'reference_groupe',
            ]);
        }

        if ($this->db->tableExists('prefixes')) {
            $this->forge->addColumn('prefixes', [
                'operateur' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            ]);

            $this->db->query(
                'UPDATE prefixes
                 SET operateur = (
                     SELECT nom_operateur FROM operateurs WHERE operateurs.id = prefixes.operateur_id
                 )'
            );

            $this->forge->dropColumn('prefixes', 'operateur_id');
        }

        $this->forge->dropTable('operateurs', true);
    }
}
