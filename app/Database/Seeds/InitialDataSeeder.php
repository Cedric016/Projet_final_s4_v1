<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Évite les doublons si le seeder est relancé
        $prefixCount = $this->db->table('prefixes')->countAllResults();

        if ($prefixCount === 0) {
            // Préfixes valides de l'opérateur
            $this->db->table('prefixes')->insertBatch([
                ['prefixe' => '033', 'description' => 'Préfixe operateur 033', 'actif' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['prefixe' => '037', 'description' => 'Préfixe operateur 037', 'actif' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ]);

            // Types d'opération
            $this->db->table('types_operation')->insertBatch([
                ['code' => 'depot', 'libelle' => 'Dépôt', 'frais_actif' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['code' => 'retrait', 'libelle' => 'Retrait', 'frais_actif' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['code' => 'transfert', 'libelle' => 'Transfert', 'frais_actif' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ]);

            $retraitRow   = $this->db->table('types_operation')->where('code', 'retrait')->get()->getRow();
            $transfertRow = $this->db->table('types_operation')->where('code', 'transfert')->get()->getRow();
            $retraitId    = $retraitRow->id;
            $transfertId  = $transfertRow->id;

            // Barèmes (exemple fourni) pour retrait et transfert
            $baremes = [
                [100, 1000, 50],
                [1001, 5000, 50],
                [5001, 10000, 100],
                [10001, 25000, 200],
                [25001, 50000, 400],
                [50001, 100000, 800],
                [100001, 250000, 1500],
                [250001, 500000, 1500],
                [500001, 1000000, 2500],
                [1000001, 2000000, 3000],
            ];

            $now  = date('Y-m-d H:i:s');
            $rows = [];
            foreach ([$retraitId, $transfertId] as $typeId) {
                foreach ($baremes as $b) {
                    $rows[] = [
                        'type_operation_id' => $typeId,
                        'montant_min'       => $b[0],
                        'montant_max'       => $b[1],
                        'frais'             => $b[2],
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];
                }
            }

            $this->db->table('baremes')->insertBatch($rows);
        }

        // Préfixes des AUTRES opérateurs (032, 031, 034) — toujours ajoutés si absents
        $autres = [
            ['prefixe' => '032', 'description' => 'Autre operateur 032'],
            ['prefixe' => '031', 'description' => 'Autre operateur 031'],
            ['prefixe' => '034', 'description' => 'Autre operateur 034'],
        ];
        $now = date('Y-m-d H:i:s');
        foreach ($autres as $a) {
            if (!$this->db->table('prefixes')->where('prefixe', $a['prefixe'])->countAllResults()) {
                $this->db->table('prefixes')->insert([
                    'prefixe'         => $a['prefixe'],
                    'description'     => $a['description'],
                    'actif'           => 1,
                    'autre_operateur' => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
            }
        }

        // Paramètre : commission supplémentaire (% ) pour transfert vers un autre opérateur
        if (!$this->db->table('config_operateur')->where('cle', 'commission_autre_operateur')->countAllResults()) {
            $this->db->table('config_operateur')->insert([
                'cle'         => 'commission_autre_operateur',
                'valeur'      => '10',
                'libelle'     => 'Commission supplémentaire (%) pour transfert vers un autre opérateur',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
