<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'reference', 'type_operation_id', 'client_id', 'client_dest_id',
        'prefixe_dest_id', 'operateur_envoyeur_id', 'operateur_recepteur_id',
        'montant', 'frais', 'commission_autre', 'gain_operateur', 'statut',
    ];
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'reference'          => 'required|max_length[50]|is_unique[transactions.reference]',
        'type_operation_id'  => 'required|integer|is_not_unique[types_operation.id]',
        'client_id'          => 'required|integer|is_not_unique[clients.id]',
        'client_dest_id'     => 'permit_empty|integer',
        'montant'            => 'required|numeric|greater_than[0]',
        'frais'              => 'permit_empty|numeric',
        'gain_operateur'     => 'permit_empty|numeric',
        'statut'             => 'permit_empty|in_list[succes,echec,annule]',
    ];

    public function genererReference(): string
    {
        return 'TXN' . date('YmdHis') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }

    public function totalGainOperateur(): float
    {
        return (float) $this->selectSum('gain_operateur')
            ->where('statut', 'succes')
            ->first()['gain_operateur'] ?? 0.0;
    }

    public function totalGainParType(): array
    {
        return $this->db->table('transactions t')
            ->select('to.code, to.libelle, SUM(t.gain_operateur) as total')
            ->join('types_operation to', 'to.id = t.type_operation_id')
            ->where('t.statut', 'succes')
            ->groupBy('to.id')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }
}
