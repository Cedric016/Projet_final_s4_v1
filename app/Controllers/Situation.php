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
     * Tâche 3 : Situation gain via les différents frais,
     * séparée en « notre opérateur » et « autres opérateurs ».
     */
    public function gains()
    {
        $gainsOperateur = $this->transModel->db->table('transactions t')
            ->select('to.code, to.libelle, SUM(t.gain_operateur) as total_gain, SUM(t.frais) as total_frais')
            ->join('types_operation to', 'to.id = t.type_operation_id')
            ->where('t.statut', 'succes')
            ->where('t.prefixe_dest_id IS NULL')
            ->groupBy('to.id')
            ->orderBy('total_gain', 'DESC')
            ->get()
            ->getResultArray();

        $gainsAutres = $this->transModel->db->table('transactions t')
            ->select('p.prefixe, p.description, SUM(t.gain_operateur) as total_gain, SUM(t.frais) as total_frais, SUM(t.commission_autre) as total_commission')
            ->join('prefixes p', 'p.id = t.prefixe_dest_id')
            ->where('t.statut', 'succes')
            ->where('t.prefixe_dest_id IS NOT NULL')
            ->groupBy('p.id')
            ->orderBy('total_gain', 'DESC')
            ->get()
            ->getResultArray();

        $totalOperateur = (float) array_sum(array_column($gainsOperateur, 'total_gain'));
        $totalAutres    = (float) array_sum(array_column($gainsAutres, 'total_gain'));

        return view('situation/gains', [
            'active'          => 'situation_gains',
            'title'           => 'Situation gain par opérateur',
            'gainsOperateur'  => $gainsOperateur,
            'gainsAutres'     => $gainsAutres,
            'totalOperateur'  => $totalOperateur,
            'totalAutres'     => $totalAutres,
            'totalGeneral'    => $totalOperateur + $totalAutres,
        ]);
    }

    /**
     * Tâche 4 : Situation des montants à envoyer à chaque autre opérateur
     * (somme des montants transférés vers ses préfixes, hors frais).
     */
    public function operateurs()
    {
        $montants = $this->transModel->db->table('transactions t')
            ->select('p.id, p.prefixe, p.description, COUNT(t.id) as nb_transferts, SUM(t.montant) as total_montant, SUM(t.commission_autre) as total_commission')
            ->join('prefixes p', 'p.id = t.prefixe_dest_id')
            ->where('t.statut', 'succes')
            ->where('t.prefixe_dest_id IS NOT NULL')
            ->groupBy('p.id')
            ->orderBy('p.prefixe', 'ASC')
            ->get()
            ->getResultArray();

        $totalMontant = (float) array_sum(array_column($montants, 'total_montant'));
        $totalCommission = (float) array_sum(array_column($montants, 'total_commission'));

        return view('situation/operateurs', [
            'active'           => 'situation_operateurs',
            'title'            => 'Montants à envoyer à chaque opérateur',
            'montants'         => $montants,
            'totalMontant'     => $totalMontant,
            'totalCommission'  => $totalCommission,
        ]);
    }
}
