<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAutresOperateurs extends Migration
{
    public function up()
    {
        // Distingue les préfixes de l'opérateur des préfixes des autres opérateurs
        $this->forge->addColumn('prefixes', [
            'autre_operateur' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'comment' => '1 = préfixe appartenant à un autre opérateur',
            ],
        ]);

        // Paramètres de l'opérateur (ex: % commission transfert vers autre opérateur)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cle' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'valeur' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'libelle' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('config_operateur');

        // Lien vers le préfixe de destination pour savoir vers quel opérateur le transfert a été fait
        $this->forge->addColumn('transactions', [
            'prefixe_dest_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'préfixe du destinataire (pour répartition par opérateur)',
            ],
            'commission_autre' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => 'commission supplémentaire transfert vers autre opérateur',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('prefixes', 'autre_operateur');
        $this->forge->dropTable('config_operateur');
        $this->forge->dropColumn('transactions', ['prefixe_dest_id', 'commission_autre']);
    }
}
