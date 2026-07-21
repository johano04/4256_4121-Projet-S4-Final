<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Les numéros dont le préfixe n'appartient pas à l'opérateur autorisé (MVola/038)
 * ne doivent jamais avoir de ligne dans `clients`. Pour un transfert vers un tel
 * numéro, on ne crée donc pas de compte : on trace uniquement la transaction, en
 * stockant le numéro du destinataire en clair sur la ligne `operations`.
 */
class AjoutTelephoneDestinataireExterne extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('operations')) {
            $this->forge->addColumn('operations', [
                'telephone_destinataire' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true, 'after' => 'client_destinataire_id'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('operations')) {
            $this->forge->dropColumn('operations', 'telephone_destinataire');
        }
    }
}
