<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['nom', 'telephone', 'solde', 'actif'];
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'id'        => 'permit_empty|integer',
        'nom'       => 'required|max_length[100]',
        'telephone' => 'required|max_length[20]|is_unique[clients.telephone,id,{id}]',
        'solde'     => 'permit_empty|numeric',
        'actif'     => 'permit_empty|in_list[0,1]',
    ];

    public function findByTelephone(string $telephone)
    {
        return $this->where('telephone', $telephone)->first();
    }
}
