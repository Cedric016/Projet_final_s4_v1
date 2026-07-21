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

        $typeId   = (int) $this->request->getPost('type_operation_id');
        $clientId = (int) $this->request->getPost('client_id');
        $montant  = (float) $this->request->getPost('montant');
        $destId   = $this->request->getPost('client_dest_id');
        $destId   = !empty($destId) ? (int) $destId : null;

        $type   = $this->typeModel->find($typeId);
        $client = $this->clientModel->find($clientId);

        if (!$type || !$client) {
            return redirect()->back()->with('error', 'Type d\'opération ou client invalide.');
        }

        if ($type['code'] === 'depot') {
            return $this->executerDepot($client, $montant);
        }

        if ($type['code'] === 'retrait') {
            return $this->executerRetrait($client, $type, $montant);
        }

        if ($type['code'] === 'transfert') {
            return $this->executerTransfert($client, $type, $montant, $destId);
        }

        return redirect()->back()->with('error', 'Type d\'opération non supporté.');
    }

    protected function executerDepot(array $client, float $montant)
    {
        $this->clientModel->update($client['id'], ['solde' => $client['solde'] + $montant]);

        $this->transModel->insert([
            'reference'         => $this->transModel->genererReference(),
            'type_operation_id' => 1,
            'client_id'         => $client['id'],
            'client_dest_id'    => null,
            'montant'           => $montant,
            'frais'             => 0,
            'commission_autre'  => 0,
            'frais_retrait_dest'=> 0,
            'gain_operateur'    => 0,
            'statut'            => 'succes',
        ]);

        return redirect()->to(site_url('operations/historique'))
            ->with('success', "Dépôt de " . number_format($montant, 0, ',', ' ') . ' Ar effectué.');
    }

    protected function executerRetrait(array $client, array $type, float $montant)
    {
        $fraisInclus = (bool) $this->request->getPost('frais_inclus_retrait');
        $frais = $this->baremeModel->calculerFrais((int) $type['id'], $montant);

        if ($fraisInclus) {
            $totalDebit = $montant + $frais;
            $netRecu    = $montant;
            if ($client['solde'] < $totalDebit) {
                return redirect()->back()->with('error', 'Solde insuffisant pour le retrait (montant + frais).');
            }
            $this->clientModel->update($client['id'], ['solde' => $client['solde'] - $totalDebit]);
        } else {
            $totalDebit = $montant;
            $netRecu    = max(0.0, $montant - $frais);
            if ($frais > 0 && $montant <= $frais) {
                return redirect()->back()->with('error', 'Le montant doit être supérieur aux frais de retrait.');
            }
            if ($client['solde'] < $totalDebit) {
                return redirect()->back()->with('error', 'Solde insuffisant pour le retrait.');
            }
            $this->clientModel->update($client['id'], ['solde' => $client['solde'] - $totalDebit]);
        }

        $gain = $frais;

        $this->transModel->insert([
            'reference'         => $this->transModel->genererReference(),
            'type_operation_id' => $type['id'],
            'client_id'         => $client['id'],
            'client_dest_id'    => null,
            'montant'           => $montant,
            'frais'             => $frais,
            'commission_autre'  => 0,
            'frais_retrait_dest'=> 0,
            'gain_operateur'    => $gain,
            'statut'            => 'succes',
        ]);

        $msg = ($fraisInclus ? 'Retrait effectué (frais inclus). Net reçu : ' : 'Retrait effectué. Net reçu : ')
            . number_format($netRecu, 0, ',', ' ') . ' Ar. Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar.';

        return redirect()->to(site_url('operations/historique'))->with('success', $msg);
    }

    protected function executerTransfert(array $client, array $type, float $montantTotal, ?int $destId)
    {
        $numerosSaisis = trim((string) $this->request->getPost('numeros'));
        $fraisInclus   = (bool) $this->request->getPost('frais_inclus_transfert');

        $numeros = [];
        if ($numerosSaisis !== '') {
            $parts = preg_split('/[\s,;]+/', $numerosSaisis, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($parts as $n) {
                $numeros[] = preg_replace('/\s+/', '', $n);
            }
        }

        if ($destId) {
            $destSelect = $this->clientModel->find($destId);
            if ($destSelect) {
                $numeros[] = $destSelect['telephone'];
            }
        }

        $numeros = array_values(array_unique($numeros));

        if (empty($numeros)) {
            return redirect()->back()->with('error', 'Veuillez indiquer au moins un destinataire.');
        }

        $destinataires = [];
        foreach ($numeros as $num) {
            if ($num === $client['telephone']) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même.');
            }
            if (!$this->prefixeModel->valideNumeroComplet($num)) {
                return redirect()->back()->with('error', "Numéro invalide : {$num}. Format attendu : 10 chiffres, préfixe valide.");
            }
            $destinataires[] = $num;
        }

        $nb = count($destinataires);

        if ($nb > 1) {
            $prefixeEnv = $this->prefixeModel->prefixePourNumero($client['telephone']);
            $estAutreEnv = $prefixeEnv ? (bool) $prefixeEnv['autre_operateur'] : false;

            foreach ($destinataires as $num) {
                $prefixeRec = $this->prefixeModel->prefixePourNumero($num);
                $estAutreRec = $prefixeRec ? (bool) $prefixeRec['autre_operateur'] : false;

                if ($estAutreEnv !== $estAutreRec) {
                    return redirect()->back()->with('error', 'L\'envoi multiple n\'est autorisé que vers le même opérateur.');
                }
            }
        }

        $base     = floor(($montantTotal / $nb) * 100) / 100;
        $reliquat = round($montantTotal - ($base * $nb), 2);
        $montants = [];
        foreach ($destinataires as $i => $num) {
            $montants[$num] = $base + ($i === 0 ? $reliquat : 0.0);
        }

        $idRetrait = $this->baremeModel->getIdTypeRetrait();

        $totalDebit = 0.0;
        foreach ($destinataires as $num) {
            $m       = $montants[$num];
            $frais   = $this->baremeModel->calculerFrais((int) $type['id'], $m);
            $commission = round($m * 1 / 100, 2);
            $fraisRetraitDest = 0.0;

            if ($fraisInclus && $idRetrait !== null) {
                $prefixeRec = $this->prefixeModel->prefixePourNumero($num);
                if ($prefixeRec && (int) $prefixeRec['autre_operateur'] === 0) {
                    $fraisRetraitDest = $this->baremeModel->calculerFrais($idRetrait, $m);
                }
            }

            $totalDebit += $m + $frais + $commission + $fraisRetraitDest;
        }

        if ($client['solde'] < $totalDebit) {
            return redirect()->back()->with('error', 'Solde insuffisant pour le transfert vers ' . $nb . ' numéro(s).');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($destinataires as $num) {
            $m              = $montants[$num];
            $frais          = $this->baremeModel->calculerFrais((int) $type['id'], $m);
            $commission     = round($m * 1 / 100, 2);
            $fraisRetraitDest = 0.0;

            if ($fraisInclus && $idRetrait !== null) {
                $prefixeRec = $this->prefixeModel->prefixePourNumero($num);
                if ($prefixeRec && (int) $prefixeRec['autre_operateur'] === 0) {
                    $fraisRetraitDest = $this->baremeModel->calculerFrais($idRetrait, $m);
                }
            }

            $dest = $this->clientModel->findByTelephone($num);
            if (!$dest) {
                $id   = $this->clientModel->insert(['nom' => 'Client ' . $num, 'telephone' => $num, 'solde' => 0], true);
                $dest = $this->clientModel->find($id);
            }

            $prefixeEnv = $this->prefixeModel->prefixePourNumero($client['telephone']);
            $prefixeRec = $this->prefixeModel->prefixePourNumero($num);
            $operateurEnvId = $prefixeEnv['id'] ?? null;
            $operateurRecId = $prefixeRec['id'] ?? null;

            $this->clientModel->update($client['id'], ['solde' => $this->clientModel->find($client['id'])['solde'] - $m - $frais - $commission - $fraisRetraitDest]);
            $this->clientModel->update($dest['id'], ['solde' => $dest['solde'] + $m]);

            $this->transModel->insert([
                'reference'             => $this->transModel->genererReference(),
                'type_operation_id'     => $type['id'],
                'client_id'             => $client['id'],
                'client_dest_id'        => $dest['id'],
                'prefixe_dest_id'       => $operateurRecId,
                'operateur_envoyeur_id' => $operateurEnvId,
                'operateur_recepteur_id'=> $operateurRecId,
                'montant'               => $m,
                'frais'                 => $frais,
                'commission_autre'      => $commission,
                'frais_retrait_dest'    => $fraisRetraitDest,
                'gain_operateur'        => $frais + $commission + $fraisRetraitDest,
                'statut'                => 'succes',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erreur lors du transfert multiple.');
        }

        $totalFrais = $totalDebit - $montantTotal;
        return redirect()->to(site_url('operations/historique'))->with('success',
            "Transfert multiple effectué vers {$nb} numéro(s). Par numéro : " . number_format($base, 0, ',', ' ') .
            ' Ar. Total frais/commissions : ' . number_format($totalFrais, 0, ',', ' ') . ' Ar.');
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
