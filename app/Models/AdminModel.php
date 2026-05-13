<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif',
    ];

    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getStats(),
            'recentRequests' => $this->getRecentRequests(),
            'absentsToday' => $this->getAbsentsToday(),
            'criticalBalances' => $this->getCriticalBalances(),
        ];
    }

    public function getStats(): array
    {
        $db = $this->db;

        $activeEmployees = (int) $db->table('employes')->where('actif', 1)->countAllResults();
        $pendingRequests = (int) $db->table('conges')->where('statut', 'en_attente')->countAllResults();

        $approvedThisMonth = (int) $db->table('conges')
            ->where('statut', 'approuvee')
            ->where("strftime('%Y-%m', created_at)", date('Y-m'))
            ->countAllResults();

        $departments = (int) $db->table('departments')->countAllResults();
        $absentsToday = (int) $db->table('conges')
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->where('date_debut <=', date('Y-m-d'))
            ->where('date_fin >=', date('Y-m-d'))
            ->countAllResults();

        return [
            'activeEmployees' => $activeEmployees,
            'pendingRequests' => $pendingRequests,
            'approvedThisMonth' => $approvedThisMonth,
            'departments' => $departments,
            'absentsToday' => $absentsToday,
        ];
    }

    public function getRecentRequests(int $limit = 3): array
    {
        return $this->db->table('conges c')
            ->select('c.id, c.date_debut, c.date_fin, c.nb_jours, c.statut, t.libelle AS type_libelle, e.prenom, e.nom')
            ->join('types_conge t', 't.id = c.type_conge_id')
            ->join('employes e', 'e.id = c.employe_id')
            ->orderBy('c.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getAbsentsToday(): array
    {
        return $this->db->table('conges c')
            ->select('e.prenom, e.nom, t.libelle AS type_libelle, c.date_fin')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('types_conge t', 't.id = c.type_conge_id')
            ->whereIn('c.statut', ['en_attente', 'approuvee'])
            ->where('c.date_debut <=', date('Y-m-d'))
            ->where('c.date_fin >=', date('Y-m-d'))
            ->orderBy('c.date_fin', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getCriticalBalances(): array
    {
        return $this->db->table('soldes s')
            ->select('e.prenom, e.nom, t.libelle, (s.jours_attribues - s.jours_pris) AS restants')
            ->join('employes e', 'e.id = s.employe_id')
            ->join('types_conge t', 't.id = s.type_conge_id')
            ->where('s.jours_attribues - s.jours_pris <=', 2)
            ->orderBy('restants', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getEmployees(): array
    {
        return $this->db->table('employes e')
            ->select('e.id, e.nom, e.prenom, e.email, e.role, e.date_embauche, e.actif, d.nom AS departement_nom')
            ->join('departments d', 'd.id = e.departement_id', 'left')
            ->orderBy('e.prenom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getDepartments(): array
    {
        return $this->db->table('departments')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getTypesConge(): array
    {
        return $this->db->table('types_conge')
            ->orderBy('libelle', 'ASC')
            ->get()
            ->getResultArray();
    }
}
