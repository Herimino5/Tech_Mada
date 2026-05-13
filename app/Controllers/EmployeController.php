<?php

namespace App\Controllers;

use App\Models\EspacePersonnelModel;

class EmployeController extends BaseController
{
    private EspacePersonnelModel $espaceModel;

    public function __construct()
    {
        $this->espaceModel = new EspacePersonnelModel();
    }

    public function dashboard()
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $employeId = (int) session()->get('id');
        $annee = (int) date('Y');

        $data = $this->espaceModel->getDashboardData($employeId, $annee);

        return view('employe/dashboard', [
            'stats' => $data['stats'],
            'soldes' => $data['soldes'],
            'demandes' => $data['demandes'],
            'annee' => $annee,
            'fullName' => trim((string) session()->get('prenom') . ' ' . (string) session()->get('nom')),
        ]);
    }

    public function createDemande()
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $employeId = (int) session()->get('id');
        $annee = (int) date('Y');

        return view('employe/create', [
            'types' => $this->espaceModel->getTypesAvecSolde($employeId, $annee),
            'annee' => $annee,
            'fullName' => trim((string) session()->get('prenom') . ' ' . (string) session()->get('nom')),
        ]);
    }

    public function demandes()
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $employeId = (int) session()->get('id');

        return view('employe/index', [
            'demandes' => $this->espaceModel->getAllDemandes($employeId),
            'fullName' => trim((string) session()->get('prenom') . ' ' . (string) session()->get('nom')),
        ]);
    }

    public function profile()
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $employeId = (int) session()->get('id');

        return view('employe/profil', [
            'profile' => $this->espaceModel->getProfile($employeId),
            'fullName' => trim((string) session()->get('prenom') . ' ' . (string) session()->get('nom')),
        ]);
    }

    public function updateProfile()
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $employeId = (int) session()->get('id');
        $currentEmail = (string) session()->get('email');

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]|is_unique[employes.email,id,' . $employeId . ']',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $prenom = trim((string) $this->request->getPost('prenom'));
        $email = trim((string) $this->request->getPost('email'));

        $updated = $this->espaceModel->updateProfile($employeId, [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]);

        if (! $updated) {
            return redirect()->back()->withInput()->with('error', 'La mise a jour du profil a echoue.');
        }

        session()->set([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]);

        return redirect()->to('/employe/profil')->with('success', 'Votre profil a ete mis a jour avec succes.');
    }

    public function cancelDemande(int $id)
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $employeId = (int) session()->get('id');

        if (! $this->espaceModel->cancelPendingDemande($id, $employeId)) {
            return redirect()->to('/employe/demandes')->with('error', 'Seule une demande en attente peut etre annulee.');
        }

        return redirect()->to('/employe/demandes')->with('success', 'La demande a ete annulee.');
    }

    public function storeDemande()
    {
        if ((string) session()->get('role') !== 'employe') {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin' => 'required|valid_date[Y-m-d]',
            'motif' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeId = (int) session()->get('id');
        $annee = (int) date('Y');
        $typeCongeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');
        $motif = trim((string) $this->request->getPost('motif'));

        if ($dateFin < $dateDebut) {
            return redirect()->back()->withInput()->with('error', 'La date de fin doit etre superieure ou egale a la date de debut.');
        }

        if ($this->espaceModel->hasOverlap($employeId, $dateDebut, $dateFin)) {
            return redirect()->back()->withInput()->with('error', 'Chevauchement detecte avec une demande existante.');
        }

        $type = $this->espaceModel->getTypeCongeById($typeCongeId);
        if ($type === null) {
            return redirect()->back()->withInput()->with('error', 'Type de conge invalide.');
        }

        $nbJours = $this->computeNbJours($dateDebut, $dateFin);
        $deductible = (int) ($type['deductible'] ?? 1) === 1;

        if ($deductible) {
            $solde = $this->espaceModel->getSoldeForType($employeId, $typeCongeId, $annee);
            $attribues = (int) ($solde['jours_attribues'] ?? 0);
            $pris = (int) ($solde['jours_pris'] ?? 0);
            $restant = max($attribues - $pris, 0);

            if ($restant < $nbJours) {
                return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour ce type de conge.');
            }
        }

        $this->espaceModel->insert([
            'employe_id' => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif === '' ? null : $motif,
            'statut' => 'en_attente',
        ]);

        return redirect()->to('/employe/dashboard')->with('success', 'Votre demande de conge a bien ete soumise.');
    }

    private function computeNbJours(string $dateDebut, string $dateFin): int
    {
        $start = new \DateTimeImmutable($dateDebut);
        $end = new \DateTimeImmutable($dateFin);
        $diff = $start->diff($end);

        return (int) $diff->days + 1;
    }
}
