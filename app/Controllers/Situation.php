<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\PrefixeModel;

class Situation extends BaseController
{
    protected $transModel;
    protected $prefixeModel;

    public function __construct()
    {
        $this->transModel   = new TransactionModel();
        $this->prefixeModel = new PrefixeModel();
    }

    /**
     * Situation gain par opérateur : frais du barème par opérateur.
     */
    public function gains()
    {
        $gains = $this->transModel->db->table('transactions t')
            ->select('p.id as operateur_id, p.prefixe, p.description, SUM(t.frais) as total_frais, SUM(t.commission_autre) as total_commission, SUM(t.gain_operateur) as total_gain')
            ->join('prefixes p', 'p.id = t.operateur_envoyeur_id', 'left')
            ->where('t.statut', 'succes')
            ->where('t.type_operation_id IN (SELECT id FROM types_operation WHERE code = "transfert")')
            ->groupBy('p.id')
            ->orderBy('total_gain', 'DESC')
            ->get()
            ->getResultArray();

        $totalGeneral = (float) array_sum(array_column($gains, 'total_gain'));

        return view('situation/gains', [
            'active'       => 'situation_gains',
            'title'        => 'Situation gain par opérateur',
            'gains'        => $gains,
            'totalGeneral' => $totalGeneral,
        ]);
    }

    /**
     * Montants à envoyer à chaque opérateur : commission 1% par opérateur tiers.
     */
    public function operateurs()
    {
        $db = $this->transModel->db;

        $parOperateur = $db->table('transactions t')
            ->select('p.id, p.prefixe, p.description, SUM(t.montant) as total_montant, SUM(t.commission_autre) as total_commission')
            ->join('prefixes p', 'p.id = t.operateur_recepteur_id', 'left')
            ->where('t.statut', 'succes')
            ->where('t.type_operation_id IN (SELECT id FROM types_operation WHERE code = "transfert")')
            ->where('t.operateur_recepteur_id IS NOT NULL')
            ->groupBy('p.id')
            ->orderBy('p.prefixe', 'ASC')
            ->get()
            ->getResultArray();

        $montants = [];
        foreach ($parOperateur as $op) {
            $montants[] = [
                'id' => $op['id'],
                'prefixe' => $op['prefixe'],
                'description' => $op['description'],
                'total_montant' => (float) $op['total_montant'],
                'total_commission' => (float) $op['total_commission'],
            ];
        }

        $totalCommission = (float) array_sum(array_column($montants, 'total_commission'));

        return view('situation/operateurs', [
            'active'           => 'situation_operateurs',
            'title'            => 'Montants à envoyer à chaque opérateur',
            'montants'         => $montants,
            'totalCommission'  => $totalCommission,
        ]);
    }
}
