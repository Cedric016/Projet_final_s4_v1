<?php

namespace App\Controllers;

use App\Models\ConfigOperateurModel;

class Settings extends BaseController
{
    protected $configModel;

    public function __construct()
    {
        $this->configModel = new ConfigOperateurModel();
    }

    public function index()
    {
        $commission = $this->configModel->get('commission_autre_operateur', 0);

        return view('settings/index', [
            'active'     => 'settings',
            'title'      => 'Paramètres opérateur',
            'commission' => $commission,
        ]);
    }

    public function update()
    {
        $commission = $this->request->getPost('commission_autre_operateur');

        if ($commission === null || $commission === '') {
            $commission = 0;
        }

        if (!is_numeric($commission) || (float) $commission < 0) {
            return redirect()->back()->withInput()->with('error', 'La commission doit être un nombre positif (%).');
        }

        $this->configModel->setValeur(
            'commission_autre_operateur',
            (string) $commission,
            'Commission supplémentaire (%) pour transfert vers un autre opérateur'
        );

        return redirect()->to(site_url('settings'))->with('success', 'Paramètres enregistrés.');
    }
}
