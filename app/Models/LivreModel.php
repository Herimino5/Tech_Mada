<?php

namespace App\Models;

use CodeIgniter\Model;

class LivreModel extends Model
{
    protected $table = 'livres';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'titre',
        'auteur',
        'isbn',
        'annee',
        'categorie',
        'resume',
        'couverture',
        'statut'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'titre'   => 'required|min_length[3]',
        'auteur'  => 'required',
        'isbn'    => 'required|is_unique[livres.isbn]',
        'annee'   => 'required|integer'
    ];

    protected $validationMessages = [
        'titre' => [
            'required' => 'Le titre est obligatoire',
            'min_length' => 'Le titre doit contenir au moins 3 caractères'
        ],
        'auteur' => [
            'required' => 'L\'auteur est obligatoire'
        ],
        'isbn' => [
            'required' => 'ISBN obligatoire',
            'is_unique' => 'Cet ISBN existe déjà'
        ],
        'annee' => [
            'required' => 'L\'année est obligatoire',
            'integer' => 'L\'année doit être un nombre'
        ]
    ];

    // VALIDATION MÉTIER
    public function isValidYear($annee)
    {
        return (int) $annee <= (int) date('Y');
    }

    // RECHERCHE

    public function search($keyword = null, $categorie = null)
    {
        $builder = $this->builder();

        if ($keyword) {
            $builder->like('titre', $keyword);
        }

        if ($categorie) {
            $builder->where('categorie', $categorie);
        }

        return $builder;
    }

    // PAGINATION
    public function getPaginatedLivres($perPage = 10)
    {
        return $this->paginate($perPage);
    }
}