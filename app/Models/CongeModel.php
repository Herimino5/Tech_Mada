<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'traite_par',
        'created_at',
    ];

    protected $createdField = 'created_at';
    // Le schéma ne contient pas de champ `updated_at`, éviter l'utilisation
    // automatique des timestamps pour les mises à jour.
    protected $useTimestamps = false;
    /**
     * Récupère toutes les demandes en attente avec infos employe et solde
     */
    public function getDemandEnAttente()
    {
        return $this->db->table('conges c')
            ->select('c.*, e.nom, e.prenom, e.email, e.departement_id, d.nom AS dept_nom, tc.libelle AS type_conge')
            ->join('employes e', 'c.employe_id = e.id', 'left')
            ->join('departments d', 'e.departement_id = d.id', 'left')
            ->join('types_conge tc', 'c.type_conge_id = tc.id', 'left')
            ->where('c.statut', 'en_attente')
            ->orderBy('c.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Récupère les demandes en attente avec filtrage optionnel
     */
    public function getDemandEnAttenteFiltered(?string $departement = null, ?string $statut = null)
    {
        $builder = $this->db->table('conges c')
            ->select('c.*, e.nom, e.prenom, e.email, e.departement_id, d.nom AS dept_nom, tc.libelle AS type_conge')
            ->join('employes e', 'c.employe_id = e.id', 'left')
            ->join('departments d', 'e.departement_id = d.id', 'left')
            ->join('types_conge tc', 'c.type_conge_id = tc.id', 'left')
            ->where('c.statut', 'en_attente');

        if ($departement && $departement !== 'all') {
            $builder->where('e.departement_id', $departement);
        }

        if ($statut && $statut !== 'all') {
            $builder->where('c.statut', $statut);
        }

        return $builder
            ->orderBy('c.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Récupère le détail d'une demande avec nom d'employé et type de congé
     */
    public function getDemandeDetail(int $id)
    {
        return $this->db->table('conges c')
            ->select('c.*, e.nom, e.prenom, e.email, e.departement_id, d.nom AS dept_nom, tc.libelle AS type_conge')
            ->join('employes e', 'c.employe_id = e.id', 'left')
            ->join('departments d', 'e.departement_id = d.id', 'left')
            ->join('types_conge tc', 'c.type_conge_id = tc.id', 'left')
            ->where('c.id', $id)
            ->get()
            ->getRowArray();
    }

    /**
     * Approuve une demande et met à jour le solde
     */
    public function approuverDemande(int $id, int $traiteParId): bool
    {
        $demande = $this->find($id);
        if (!$demande) {
            return false;
        }

        // Vérifier le solde
        $solde = $this->getSoldeEmploye($demande['employe_id'], $demande['type_conge_id']);
        if (!$solde) {
            return false;
        }

        $jours_restant = $solde['jours_attribues'] - $solde['jours_pris'];
        if ($jours_restant < $demande['nb_jours']) {
            return false; // Solde insuffisant
        }

        // Start transaction
        $this->db->transStart();

        try {
            // Mettre à jour la demande
            $this->update($id, [
                'statut' => 'approuvee',
                'traite_par' => $traiteParId,
            ]);

            // Déduire le solde en utilisant une expression SQL (pas d'échappement)
            $this->db->table('soldes')
                ->set('jours_pris', 'jours_pris + ' . (int)$demande['nb_jours'], false)
                ->where([
                    'employe_id' => $demande['employe_id'],
                    'type_conge_id' => $demande['type_conge_id'],
                    'annee' => date('Y'),
                ])
                ->update();

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Refuse une demande
     */
    public function refuserDemande(int $id, string $commentaire = null, int $traiteParId = null): bool
    {
        $data = [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire,
        ];

        if ($traiteParId !== null) {
            $data['traite_par'] = $traiteParId;
        }

        return $this->update($id, $data);
    }

    /**
     * Récupère le solde d'un employé pour un type de congé
     */
    public function getSoldeEmploye(int $employeId, int $typeCongeId, ?int $annee = null)
    {
        $annee = $annee ?? (int)date('Y');

        return $this->db->table('soldes')
            ->where([
                'employe_id' => $employeId,
                'type_conge_id' => $typeCongeId,
                'annee' => $annee,
            ])
            ->get()
            ->getRowArray();
    }

    /**
     * Récupère tous les soldes d'un employé pour une année
     */
    public function getSoldesEmploye(int $employeId, ?int $annee = null): array
    {
        $annee = $annee ?? (int)date('Y');

        return $this->db->table('soldes s')
            ->select('s.*, tc.libelle')
            ->join('types_conge tc', 's.type_conge_id = tc.id', 'left')
            ->where([
                's.employe_id' => $employeId,
                's.annee' => $annee,
            ])
            ->get()
            ->getResultArray();
    }

    /**
     * Récupère tous les soldes par département
     */
    public function getSoldesByDepartement(?int $departementId = null, ?int $annee = null): array
    {
        $annee = $annee ?? (int)date('Y');

        $builder = $this->db->table('soldes s')
            ->select('s.*, e.nom, e.prenom, e.email, d.nom AS dept_nom, tc.libelle')
            ->join('employes e', 's.employe_id = e.id', 'left')
            ->join('departments d', 'e.departement_id = d.id', 'left')
            ->join('types_conge tc', 's.type_conge_id = tc.id', 'left')
            ->where('s.annee', $annee);

        if ($departementId) {
            $builder->where('e.departement_id', $departementId);
        }

        return $builder
            ->orderBy('e.nom', 'ASC')
            ->get()
            ->getResultArray();
    }
}
