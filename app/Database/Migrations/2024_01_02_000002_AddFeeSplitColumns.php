<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFeeSplitColumns extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'operateur_envoyeur_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'préfixe de l\'opérateur de l\'expéditeur',
            ],
            'operateur_recepteur_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'préfixe de l\'opérateur du récepteur',
            ],
            'gain_envoyeur' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => '99% des frais reversés à l\'opérateur de l\'expéditeur',
            ],
            'gain_recepteur' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => '1% des frais reversés à l\'opérateur du récepteur',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', [
            'operateur_envoyeur_id',
            'operateur_recepteur_id',
            'gain_envoyeur',
            'gain_recepteur',
        ]);
    }
}
