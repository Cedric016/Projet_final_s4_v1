<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixeModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeModel;

class Operations extends BaseController
{
    protected $clientModel;
    protected $prefixeModel;
    protected $transModel;
    protected $typeModel;
    protected $baremeModel;

    public function __construct()
    {
        $this->clientModel  = new ClientModel();
        $this->prefixeModel = new PrefixeModel();
        $this->transModel   = new TransactionModel();
        $this->typeModel    = new TypeOperationModel();
        $this->baremeModel  = new BaremeModel();
    }

    public function index()
    {
        $types = $this->typeModel->orderBy('libelle', 'ASC')->findAll();
        $clients = $this->clientModel->orderBy('nom', 'ASC')->findAll();

        return view('operations/index', [
            'active'  => 'operations',
            'title'   => 'Effectuer une opération',
            'types'   => $types,
            'clients' => $clients,
        ]);
    }

    public function executer()
    {
        $rules = [
            'type_operation_id' => 'required|integer',
            'client_id'         => 'required|integer',
            'montant'           => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $typeId  = (int) $this->request->getPost('type_operation_id');
        $clientId = (int) $this->request->getPost('client_id');
        $montant  = (float) $this->request->getPost('montant');
        $destId   = $this->request->getPost('client_dest_id');
        $destId   = !empty($destId) ? (int) $destId : null;

        $type   = $this->typeModel->find($typeId);
        $client = $this->clientModel->find($clientId);

        if (!$type || !$client) {
            return redirect()->back()->with('error', 'Type d\'opération ou client invalide.');
        }

        $frais  = $this->baremeModel->calculerFrais($typeId, $montant);

        $gain   = 0.0;
        $statut = 'succes';

        if ($type['code'] === 'depot') {
            $this->clientModel->update($clientId, ['solde' => $client['solde'] + $montant]);

        } elseif ($type['code'] === 'retrait') {
            if ($client['solde'] < $montant + $frais) {
                return redirect()->back()->with('error', 'Solde insuffisant pour le retrait (montant + frais).');
            }
            $this->clientModel->update($clientId, ['solde' => $client['solde'] - $montant - $frais]);
            $gain = $frais;

        } elseif ($type['code'] === 'transfert') {
            if (!$destId) {
                return redirect()->back()->with('error', 'Un destinataire est requis pour un transfert.');
            }
            $dest = $this->clientModel->find($destId);
            if (!$dest) {
                return redirect()->back()->with('error', 'Destinataire introuvable.');
            }
            if ($destId === $clientId) {
                return redirect()->back()->with('error', 'Le destinataire doit être différent de l\'expéditeur.');
            }

            // Transfert vers un autre opérateur ? (préfixe du destinataire marqué autre_operateur)
            $commissionAutre = 0.0;
            $prefixeDestId   = null;
            $prefixeDest     = $this->prefixeModel->prefixePourNumero($dest['telephone']);
            if ($prefixeDest && (int) $prefixeDest['autre_operateur'] === 1) {
                $prefixeDestId   = $prefixeDest['id'];
                $taux            = (float) (new \App\Models\ConfigOperateurModel())->commissionAutreOperateur();
                $commissionAutre = round($montant * $taux / 100, 2);
            }

            $fraisTotal = $frais + $commissionAutre;
            if ($client['solde'] < $montant + $fraisTotal) {
                return redirect()->back()->with('error', 'Solde insuffisant pour le transfert (montant + frais + commission autre opérateur).');
            }
            $this->clientModel->update($clientId, ['solde' => $client['solde'] - $montant - $fraisTotal]);
            $this->clientModel->update($destId, ['solde' => $dest['solde'] + $montant]);
            $gain = $frais + $commissionAutre;
        }

        $inserted = $this->transModel->insert([
            'reference'         => $this->transModel->genererReference(),
            'type_operation_id' => $typeId,
            'client_id'         => $clientId,
            'client_dest_id'    => $destId,
            'prefixe_dest_id'   => $prefixeDestId ?? null,
            'montant'           => $montant,
            'frais'             => $frais,
            'commission_autre'  => $commissionAutre ?? 0.0,
            'gain_operateur'    => $gain,
            'statut'            => $statut,
        ]);

        if (!$inserted) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la transaction : ' . implode('<br>', $this->transModel->errors()));
        }

        return redirect()->to(site_url('operations/historique'))
            ->with('success', "Opération ({$type['libelle']}) effectuée. Frais: " . number_format($frais, 0, ',', ' ') . ' Ar, Gain opérateur: ' . number_format($gain, 0, ',', ' ') . ' Ar.');
    }

    public function historique()
    {
        $transactions = $this->transModel
            ->select('transactions.*, to.libelle as type_libelle, c.nom as client_nom, d.nom as dest_nom')
            ->join('types_operation to', 'to.id = transactions.type_operation_id')
            ->join('clients c', 'c.id = transactions.client_id', 'left')
            ->join('clients d', 'd.id = transactions.client_dest_id', 'left')
            ->orderBy('transactions.id', 'DESC')
            ->findAll();

        return view('operations/historique', [
            'active'        => 'operations',
            'title'         => 'Historique des opérations',
            'transactions'  => $transactions,
        ]);
    }
}
