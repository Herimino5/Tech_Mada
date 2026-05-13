<?php

namespace App\Models;

use CodeIgniter\Model;

class EspacePersonnelModel extends Model
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
    ];

    public function getDashboardData(int $employeId, int $annee): array
    {
        return [
            'stats' => $this->getStats($employeId),
            'soldes' => $this->getSoldes($employeId, $annee),
            'demandes' => $this->getLatestDemandes($employeId),
        ];
    }

    public function getStats(int $employeId): array
    {
        $rows = $this->db->table('conges')
            ->select('statut, COUNT(*) AS total')
            ->where('employe_id', $employeId)
            ->groupBy('statut')
            ->get()
            ->getResultArray();

        $stats = [
            'en_attente' => 0,
            'approuvee' => 0,
            'refusee' => 0,
        ];

        foreach ($rows as $row) {
            $statut = (string) ($row['statut'] ?? '');
            if (array_key_exists($statut, $stats)) {
                $stats[$statut] = (int) ($row['total'] ?? 0);
            }
        }

        return $stats;
    }

    public function getSoldes(int $employeId, int $annee): array
    {
        return $this->db->table('soldes s')
            ->select('s.type_conge_id, t.libelle, t.jours_annuels, t.deductible, s.jours_attribues, s.jours_pris')
            ->join('types_conge t', 't.id = s.type_conge_id')
            ->where('s.employe_id', $employeId)
            ->where('s.annee', $annee)
            ->orderBy('t.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getLatestDemandes(int $employeId, int $limit = 5): array
    {
        return $this->db->table('conges c')
            ->select('c.id, c.date_debut, c.date_fin, c.nb_jours, c.statut, c.commentaire_rh, t.libelle AS type_libelle')
            ->join('types_conge t', 't.id = c.type_conge_id')
            ->where('c.employe_id', $employeId)
            ->orderBy('c.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getAllDemandes(int $employeId): array
    {
        return $this->db->table('conges c')
            ->select('c.id, c.date_debut, c.date_fin, c.nb_jours, c.statut, c.commentaire_rh, c.motif, c.created_at, t.libelle AS type_libelle')
            ->join('types_conge t', 't.id = c.type_conge_id')
            ->where('c.employe_id', $employeId)
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getTypesAvecSolde(int $employeId, int $annee): array
    {
        return $this->db->table('types_conge t')
            ->select('t.id, t.libelle, t.jours_annuels, t.deductible, s.jours_attribues, s.jours_pris')
            ->join('soldes s', 's.type_conge_id = t.id AND s.employe_id = ' . (int) $employeId . ' AND s.annee = ' . (int) $annee, 'left')
            ->orderBy('t.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function hasOverlap(int $employeId, string $dateDebut, string $dateFin): bool
    {
        $count = $this->db->table('conges')
            ->where('employe_id', $employeId)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->where('date_debut <=', $dateFin)
            ->where('date_fin >=', $dateDebut)
            ->countAllResults();

        return $count > 0;
    }

    public function getTypeCongeById(int $typeCongeId): ?array
    {
        $row = $this->db->table('types_conge')
            ->where('id', $typeCongeId)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function getSoldeForType(int $employeId, int $typeCongeId, int $annee): ?array
    {
        $row = $this->db->table('soldes')
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function getProfile(int $employeId): ?array
    {
        $row = $this->db->table('employes e')
            ->select('e.id, e.nom, e.prenom, e.email, e.date_embauche, e.departement_id, e.actif, d.nom AS departement_nom')
            ->join('departments d', 'd.id = e.departement_id', 'left')
            ->where('e.id', $employeId)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function updateProfile(int $employeId, array $data): bool
    {
        return (bool) $this->db->table('employes')
            ->where('id', $employeId)
            ->update($data);
    }

    public function getDemandeByIdForEmployee(int $demandeId, int $employeId): ?array
    {
        $row = $this->db->table('conges c')
            ->select('c.id, c.statut, c.date_debut, c.date_fin, c.type_conge_id, t.libelle AS type_libelle')
            ->join('types_conge t', 't.id = c.type_conge_id')
            ->where('c.id', $demandeId)
            ->where('c.employe_id', $employeId)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function cancelPendingDemande(int $demandeId, int $employeId): bool
    {
        $demande = $this->getDemandeByIdForEmployee($demandeId, $employeId);
        if ($demande === null || (string) ($demande['statut'] ?? '') !== 'en_attente') {
            return false;
        }

        return (bool) $this->update($demandeId, [
            'statut' => 'annulee',
        ]);
    }
}
