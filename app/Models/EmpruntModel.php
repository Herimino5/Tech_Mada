<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpruntModel extends Model
{
    protected $table = 'emprunts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'livre_id',
        'nom_emprunteur',
        'date_emprunt',
        'date_retour',
    ];

    protected $useTimestamps = true;

    public function getLastEmpruntByLivre(int $livreId): ?array
    {
        return $this->where('livre_id', $livreId)
            ->orderBy('date_emprunt', 'DESC')
            ->first();
    }
}
