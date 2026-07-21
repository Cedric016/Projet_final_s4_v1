<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRetraitFeeColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'frais_retrait_dest' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => 'frais de retrait couverts par l\'expéditeur pour le destinataire',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'frais_retrait_dest');
    }
}
