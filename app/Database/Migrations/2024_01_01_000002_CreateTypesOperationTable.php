<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesOperationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'libelle' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'frais_actif' => [
                'type'    => 'TINYINT',
                'default' => 1,
                'comment' => '1 = applique des frais, 0 = gratuit',
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
        $this->forge->createTable('types_operation');
    }

    public function down()
    {
        $this->forge->dropTable('types_operation');
    }
}
