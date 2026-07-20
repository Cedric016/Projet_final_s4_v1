<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['prefixe', 'description', 'actif'];
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'prefixe'    => 'required|max_length[10]|is_unique[prefixes.prefixe,id,{id}]',
        'description' => 'permit_empty|max_length[100]',
        'actif'      => 'permit_empty|in_list[0,1]',
    ];

    public function validePrefixe(string $telephone): bool
    {
        if (empty($telephone)) {
            return false;
        }

        $prefixes = $this->where('actif', 1)->findAll();

        foreach ($prefixes as $p) {
            if (str_starts_with($telephone, $p['prefixe'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie un numéro malgache complet : préfixe valide + exactement
     * 7 chiffres après le préfixe (10 chiffres au total).
     * Les espaces sont ignorés (ex: 033 00 000 00).
     */
    public function valideNumeroComplet(string $telephone): bool
    {
        if (!$this->validePrefixe($telephone)) {
            return false;
        }

        $chiffres = preg_replace('/\D/', '', $telephone);

        return strlen($chiffres) === 10;
    }
}
