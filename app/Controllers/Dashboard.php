<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\PrefixeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $clientModel  = new ClientModel();
        $transModel   = new TransactionModel();
        $prefixeModel = new PrefixeModel();

        $totalClients   = $clientModel->countAll();
        $soldeGlobal    = (float) $clientModel->selectSum('solde')->first()['solde'] ?? 0;
        $totalGain      = $transModel->totalGainOperateur();
        $nbTransactions = $transModel->where('statut', 'succes')->countAllResults();
        $nbPrefixes     = $prefixeModel->where('actif', 1)->countAllResults();
        $gainsParType   = $transModel->totalGainParType();

        $dernieres = $transModel->select('transactions.*, to.libelle as type_libelle, c.nom as client_nom')
            ->join('types_operation to', 'to.id = transactions.type_operation_id')
            ->join('clients c', 'c.id = transactions.client_id')
            ->orderBy('transactions.id', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'active'         => 'dashboard',
            'title'          => 'Tableau de bord',
            'totalClients'   => $totalClients,
            'soldeGlobal'    => $soldeGlobal,
            'totalGain'      => $totalGain,
            'nbTransactions' => $nbTransactions,
            'nbPrefixes'     => $nbPrefixes,
            'gainsParType'   => $gainsParType,
            'dernieres'      => $dernieres,
        ]);
    }
}
