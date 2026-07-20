<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigOperateurModel extends Model
{
    protected $table            = 'config_operateur';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['cle', 'valeur', 'libelle'];
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'cle'    => 'required|max_length[50]',
        'valeur' => 'permit_empty|max_length[100]',
    ];

    public function get(string $cle, $defaut = null)
    {
        $row = $this->where('cle', $cle)->first();
        return $row ? $row['valeur'] : $defaut;
    }

    public function setValeur(string $cle, string $valeur, ?string $libelle = null): bool
    {
        $row = $this->where('cle', $cle)->first();

        if ($row) {
            return (bool) $this->update($row['id'], [
                'valeur'  => $valeur,
                'libelle' => $libelle ?? $row['libelle'],
            ]);
        }

        return (bool) $this->insert([
            'cle'     => $cle,
            'valeur'  => $valeur,
            'libelle' => $libelle,
        ]);
    }

    /**
     * % de commission supplémentaire appliqué aux transferts vers un autre opérateur.
     */
    public function commissionAutreOperateur(): float
    {
        return (float) $this->get('commission_autre_operateur', 0);
    }
}
