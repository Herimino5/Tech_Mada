<?php

namespace App\Controllers;

use App\Models\CongeModel;

class RhController extends BaseController
{
    protected $congeModel;

    public function __construct()
    {
        $this->congeModel = new CongeModel();
    }

    /**
     * Dashboard RH - affiche les stats des demandes
     */
    public function dashboard()
    {
        // Vérifier le rôle
        if (session()->get('role') !== 'rh') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Stats des demandes
        $stats = [
              'en_attente' => $this->congeModel->where('statut', 'en_attente')->countAllResults(),
              'approuvee' => $this->congeModel->where('statut', 'approuvee')->countAllResults(),
              'refusee' => $this->congeModel->where('statut', 'refusee')->countAllResults(),
        ];

        $data = [
            'title' => 'Dashboard RH',
            'stats' => $stats,
        ];

        return view('rh/dashboard', $data);
    }

    /**
     * Liste des demandes en attente avec filtrage optionnel
     */
    public function demandes()
    {
        // Vérifier le rôle
        if (session()->get('role') !== 'rh') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $departement = $this->request->getGet('departement');
        $statut = $this->request->getGet('statut');

        // Récupérer les demandes
        if ($departement || $statut) {
            $demandes = $this->congeModel->getDemandEnAttenteFiltered($departement, $statut);
        } else {
            $demandes = $this->congeModel->getDemandEnAttente();
        }

        // Récupérer la liste des départements pour le filtrage
        $db = \Config\Database::connect();
        $departements = $db->table('departments')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Demandes de congés',
            'demandes' => $demandes,
            'departements' => $departements,
            'selected_dept' => $departement,
        ];

        return view('rh/demandes', $data);
    }

    /**
     * Approuver une demande de congé
     */
    public function approuver(int $id)
    {
        if (session()->get('role') !== 'rh') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $demande = $this->congeModel->find($id);
        if (!$demande) {
            return redirect()->to(base_url('rh/demandes'))->with('error', 'Demande non trouvée');
        }

        // Récupérer le solde
        $solde = $this->congeModel->getSoldeEmploye($demande['employe_id'], $demande['type_conge_id']);
        if (!$solde) {
            return redirect()->to(base_url('rh/demandes'))->with('error', 'Solde non trouvé');
        }

        $jours_restant = $solde['jours_attribues'] - $solde['jours_pris'];
        if ($jours_restant < $demande['nb_jours']) {
            return redirect()->to(base_url('rh/demandes'))->with('error', "Solde insuffisant (restant: {$jours_restant} jours)");
        }

        // Approuver
        if ($this->congeModel->approuverDemande($id, session()->get('id'))) {
            return redirect()->to(base_url('rh/demandes'))->with('success', 'Demande approuvée et solde mis à jour');
        } else {
            return redirect()->to(base_url('rh/demandes'))->with('error', 'Erreur lors de l\'approbation');
        }
    }

    /**
     * Refuser une demande de congé
     */
    public function refuser(int $id)
    {
        if (session()->get('role') !== 'rh') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $demande = $this->congeModel->find($id);
        if (!$demande) {
            return redirect()->to(base_url('rh/demandes'))->with('error', 'Demande non trouvée');
        }

        $commentaire = $this->request->getPost('commentaire_rh');

        // Refuser
        if ($this->congeModel->refuserDemande($id, $commentaire, session()->get('id'))) {
            return redirect()->to(base_url('rh/demandes'))->with('success', 'Demande refusée');
        } else {
            return redirect()->to(base_url('rh/demandes'))->with('error', 'Erreur lors du refus');
        }
    }

    /**
     * Vue des soldes par département et type de congé
     */
    public function soldes()
    {
        if (session()->get('role') !== 'rh') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $departement = $this->request->getGet('departement');
        $annee = $this->request->getGet('annee') ?? date('Y');

        // Récupérer les soldes
        $soldes = $this->congeModel->getSoldesByDepartement($departement, (int)$annee);

        // Récupérer la liste des départements
        $db = \Config\Database::connect();
        $departements = $db->table('departments')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Vue des soldes',
            'soldes' => $soldes,
            'departements' => $departements,
            'selected_dept' => $departement,
            'annee' => $annee,
        ];

        return view('rh/soldes', $data);
    }
}
