<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table            = 'baremes';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'type_operation_id' => 'required|integer|is_not_unique[types_operation.id]',
        'montant_min'       => 'required|integer|greater_than_equal_to[0]',
        'montant_max'       => 'permit_empty|integer|greater_than[montant_min]',
        'frais'             => 'required|integer|greater_than_equal_to[0]',
    ];

    public function calculerFrais(int $typeId, float $montant): float
    {
        $typeOp = model(TypeOperationModel::class)->find($typeId);

        if (!$typeOp || (int) $typeOp['frais_actif'] === 0) {
            return 0.0;
        }

        $row = $this->where('type_operation_id', $typeId)
            ->where('montant_min <=', (int) $montant)
            ->groupStart()
                ->where('montant_max', null)
                ->orWhere('montant_max >=', (int) $montant)
            ->groupEnd()
            ->orderBy('montant_min', 'DESC')
            ->first();

        if (!$row) {
            return 0.0;
        }

        return (float) $row['frais'];
    }
}
