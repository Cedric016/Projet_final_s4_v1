<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'types_operation';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['code', 'libelle', 'frais_actif'];
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'id'          => 'permit_empty|integer',
        'code'        => 'required|max_length[30]|is_unique[types_operation.code,id,{id}]',
        'libelle'     => 'required|max_length[100]',
        'frais_actif' => 'permit_empty|in_list[0,1]',
    ];

    public function getBaremes(int $typeId)
    {
        return $this->db->table('baremes')
            ->where('type_operation_id', $typeId)
            ->orderBy('montant_min', 'ASC')
            ->get()
            ->getResultArray();
    }
}
