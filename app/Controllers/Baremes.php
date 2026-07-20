<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\TypeOperationModel;

class Baremes extends BaseController
{
    protected $model;
    protected $typeModel;

    public function __construct()
    {
        $this->model     = new BaremeModel();
        $this->typeModel = new TypeOperationModel();
    }

    public function index()
    {
        $types = $this->typeModel->orderBy('libelle', 'ASC')->findAll();

        foreach ($types as &$t) {
            $t['baremes'] = $this->model->where('type_operation_id', $t['id'])
                ->orderBy('montant_min', 'ASC')->findAll();
        }

        return view('baremes/index', [
            'active' => 'baremes',
            'title'  => 'Barèmes de frais',
            'types'  => $types,
        ]);
    }

    public function create()
    {
        $types = $this->typeModel->orderBy('libelle', 'ASC')->findAll();

        return view('baremes/form', [
            'active' => 'baremes',
            'title'  => 'Nouveau barème',
            'bareme' => null,
            'types'  => $types,
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['montant_max'] = !empty($data['montant_max']) ? $data['montant_max'] : null;

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('baremes'))->with('success', 'Barème ajouté.');
    }

    public function edit($id = null)
    {
        $bareme = $this->model->find($id);

        if (!$bareme) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $types = $this->typeModel->orderBy('libelle', 'ASC')->findAll();

        return view('baremes/form', [
            'active'  => 'baremes',
            'title'   => 'Modifier barème',
            'bareme'  => $bareme,
            'types'   => $types,
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;
        $data['montant_max'] = !empty($data['montant_max']) ? $data['montant_max'] : null;

        if (!$this->model->save($data)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }

        return redirect()->to(site_url('baremes'))->with('success', 'Barème modifié.');
    }

    public function delete($id = null)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('baremes'))->with('success', 'Barème supprimé.');
    }
}
