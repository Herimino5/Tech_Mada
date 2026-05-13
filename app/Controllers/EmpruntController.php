<?php

namespace App\Controllers;

use App\Models\LivreModel;
use App\Models\EmpruntModel;

class EmpruntController extends BaseController
{
    // =========================
    // EMPRUNTER UN LIVRE
    // =========================
    public function emprunter($id)
    {
        $livreModel = new LivreModel();
        $empruntModel = new EmpruntModel();

        $livre = $livreModel->find($id);

        // ✔ Vérifier existence
        if (!$livre) {
            return redirect()->to('/')
                             ->with('error', 'Livre introuvable');
        }

        // ✔ Vérifier disponibilité
        if ($livre['statut'] === 'prete') {
            return redirect()->to('/')
                             ->with('error', 'Livre déjà prêté');
        }

        // ✔ Récupérer nom emprunteur
        $nom = $this->request->getPost('nom_emprunteur');

        if (!$nom) {
            return redirect()->to('/')
                             ->with('error', 'Nom emprunteur requis');
        }

        // ✔ Enregistrer emprunt
        $empruntModel->insert([
            'livre_id' => $id,
            'nom_emprunteur' => $nom,
            'date_emprunt' => date('Y-m-d')
        ]);

        // ✔ Mettre à jour statut livre
        $livreModel->update($id, [
            'statut' => 'prete'
        ]);

        return redirect()->to('/')
                         ->with('success', 'Livre emprunté avec succès');
    }

    // =========================
    // RETOURNER UN LIVRE
    // =========================
    public function retourner($id)
    {
        $livreModel = new LivreModel();
        $empruntModel = new EmpruntModel();

        $livre = $livreModel->find($id);

        // ✔ Vérifier existence
        if (!$livre) {
            return redirect()->to('/')
                             ->with('error', 'Livre introuvable');
        }

        // ✔ Trouver emprunt actif
        $emprunt = $empruntModel->where('livre_id', $id)
                                ->where('date_retour', null)
                                ->first();

        if (!$emprunt) {
            return redirect()->to('/')
                             ->with('error', 'Aucun emprunt actif');
        }

        // ✔ Mettre à jour date retour
        $empruntModel->update($emprunt['id'], [
            'date_retour' => date('Y-m-d')
        ]);

        // ✔ Remettre statut disponible
        $livreModel->update($id, [
            'statut' => 'disponible'
        ]);

        return redirect()->to('/')
                         ->with('success', 'Livre retourné avec succès');
    }
}