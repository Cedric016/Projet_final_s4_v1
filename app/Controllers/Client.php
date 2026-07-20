<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixeModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeModel;

class Client extends BaseController
{
    protected $clientModel;
    protected $prefixeModel;
    protected $transModel;
    protected $typeModel;
    protected $baremeModel;

    public function __construct()
    {
        helper(['client_auth']);
        $this->clientModel  = new ClientModel();
        $this->prefixeModel = new PrefixeModel();
        $this->transModel   = new TransactionModel();
        $this->typeModel    = new TypeOperationModel();
        $this->baremeModel  = new BaremeModel();
    }

    public function login()
    {
        if (client_connecte()) {
            return redirect()->to(site_url('client'));
        }

        return view('client/login', [
            'title' => 'Connexion client',
        ]);
    }

    public function authentifier()
    {
        $telephone = trim((string) $this->request->getPost('telephone'));

        if (empty($telephone)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir votre numéro de téléphone.');
        }

        if (!$this->prefixeModel->valideNumeroComplet($telephone)) {
            return redirect()->back()->withInput()->with('error', 'Numéro invalide. Format attendu : préfixe (ex: 033, 037) suivi de 7 chiffres (ex: 033 00 000 00, soit 10 chiffres au total).');
        }

        $telephone = preg_replace('/\s+/', '', $telephone);

        $client = $this->clientModel->findByTelephone($telephone);

        if (!$client) {
            $nom = 'Client ' . $telephone;
            $id = $this->clientModel->insert(['nom' => $nom, 'telephone' => $telephone, 'solde' => 0], true);

            if (!$id) {
                $client = $this->clientModel->findByTelephone($telephone);

                if (!$client) {
                    return redirect()->back()->withInput()->with('error', implode('<br>', $this->clientModel->errors()));
                }
            } else {
                $client = $this->clientModel->find($id);
            }
        }

        service('session')->set('client_telephone', $client['telephone']);

        return redirect()->to(site_url('client'))->with('success', 'Bienvenue ' . esc($client['nom']) . ' !');
    }

    public function deconnecter()
    {
        client_deconnecter();

        return redirect()->to(site_url('client/login'))->with('success', 'Vous êtes déconnecté.');
    }

    public function index()
    {
        $client = client_connecte();

        if (!$client) {
            return redirect()->to(site_url('client/login'));
        }

        $types = $this->typeModel->orderBy('libelle', 'ASC')->findAll();
        $clients = $this->clientModel->orderBy('nom', 'ASC')->findAll();

        $historique = $this->transModel
            ->select('transactions.*, to.libelle as type_libelle, d.nom as dest_nom')
            ->join('types_operation to', 'to.id = transactions.type_operation_id')
            ->join('clients d', 'd.id = transactions.client_dest_id', 'left')
            ->where('transactions.client_id', $client['id'])
            ->orderBy('transactions.id', 'DESC')
            ->limit(5)
            ->findAll();

        return view('client/dashboard', [
            'title'      => 'Mon espace',
            'client'     => $client,
            'types'      => $types,
            'clients'    => $clients,
            'historique' => $historique,
        ]);
    }

    public function operations()
    {
        $client = client_connecte();

        if (!$client) {
            return redirect()->to(site_url('client/login'));
        }

        $types = $this->typeModel->orderBy('libelle', 'ASC')->findAll();
        $clients = $this->clientModel->orderBy('nom', 'ASC')->findAll();

        return view('client/operations', [
            'title'   => 'Mes opérations',
            'client'  => $client,
            'types'   => $types,
            'clients' => $clients,
        ]);
    }

    public function executer()
    {
        $client = client_connecte();

        if (!$client) {
            return redirect()->to(site_url('client/login'));
        }

        $rules = [
            'type_operation_id' => 'required|integer',
            'montant'           => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $typeId  = (int) $this->request->getPost('type_operation_id');
        $montant = (float) $this->request->getPost('montant');
        $destId  = $this->request->getPost('client_dest_id');
        $destId  = !empty($destId) ? (int) $destId : null;

        $type = $this->typeModel->find($typeId);

        if (!$type) {
            return redirect()->back()->with('error', 'Type d\'opération invalide.');
        }

        $frais = $this->baremeModel->calculerFrais($typeId, $montant);

        $gain   = 0.0;
        $libelle = $type['libelle'];

        if ($type['code'] === 'depot') {
            $this->clientModel->update($client['id'], ['solde' => $client['solde'] + $montant]);

        } elseif ($type['code'] === 'retrait') {
            if ($client['solde'] < $montant + $frais) {
                return redirect()->back()->with('error', 'Solde insuffisant pour le retrait (montant + frais).');
            }
            $this->clientModel->update($client['id'], ['solde' => $client['solde'] - $montant - $frais]);
            $gain = $frais;

        } elseif ($type['code'] === 'transfert') {
            if (!$destId) {
                return redirect()->back()->with('error', 'Un destinataire est requis pour un transfert.');
            }
            $dest = $this->clientModel->find($destId);
            if (!$dest) {
                return redirect()->back()->with('error', 'Destinataire introuvable.');
            }
            if ($destId === $client['id']) {
                return redirect()->back()->with('error', 'Le destinataire doit être différent de votre numéro.');
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
            $this->clientModel->update($client['id'], ['solde' => $client['solde'] - $montant - $fraisTotal]);
            $this->clientModel->update($destId, ['solde' => $dest['solde'] + $montant]);
            $gain = $frais + $commissionAutre;
        }

        $inserted = $this->transModel->insert([
            'reference'         => $this->transModel->genererReference(),
            'type_operation_id' => $typeId,
            'client_id'         => $client['id'],
            'client_dest_id'    => $destId,
            'prefixe_dest_id'   => $prefixeDestId ?? null,
            'montant'           => $montant,
            'frais'             => $frais,
            'commission_autre'  => $commissionAutre ?? 0.0,
            'gain_operateur'    => $gain,
            'statut'            => 'succes',
        ]);

        if (!$inserted) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement : ' . implode('<br>', $this->transModel->errors()));
        }

        return redirect()->to(site_url('client'))->with('success', "{$libelle} effectué. Frais: " . number_format($frais, 0, ',', ' ') . ' Ar.');
    }

    public function historique()
    {
        $client = client_connecte();

        if (!$client) {
            return redirect()->to(site_url('client/login'));
        }

        $transactions = $this->transModel
            ->select('transactions.*, to.libelle as type_libelle, d.nom as dest_nom')
            ->join('types_operation to', 'to.id = transactions.type_operation_id')
            ->join('clients d', 'd.id = transactions.client_dest_id', 'left')
            ->where('transactions.client_id', $client['id'])
            ->orderBy('transactions.id', 'DESC')
            ->findAll();

        return view('client/historique', [
            'title'        => 'Mon historique',
            'client'       => $client,
            'transactions' => $transactions,
        ]);
    }
}
