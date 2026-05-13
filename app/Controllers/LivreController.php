<?php

namespace App\Controllers;

use App\Models\LivreModel;
use App\Models\EmpruntModel;

class LivreController extends BaseController
{
    public function index()
    {
        $model = new LivreModel();

        $keyword   = $this->request->getGet('keyword');
        $categorie = $this->request->getGet('categorie');

        if ($keyword) {
            $model->groupStart()
                ->like('titre', $keyword)
                ->orLike('auteur', $keyword)
                ->orLike('isbn', $keyword)
                ->groupEnd();
        }

        if ($categorie) {
            $model->where('categorie', $categorie);
        }

        $data['livres'] = $model->orderBy('id', 'DESC')->paginate(10);

        $data['pager'] = $model->pager;

        return view('livres/index', $data);
    }

    public function detail($id)
    {
        $model = new LivreModel();
        $empruntModel = new EmpruntModel();

        $livre = $model->find($id);

        if (!$livre) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $dernierEmprunt = $empruntModel->getLastEmpruntByLivre($id);

        return view('livres/show', [
            'livre' => $livre,
            'emprunt' => $dernierEmprunt,
            'errors' => session('errors') ?? []
        ]);
    }

    public function ajouter()
    {
        return view('livres/create', [
            'errors' => session('errors') ?? []
        ]);
    }

    public function enregistrer()
    {
        $model = new LivreModel();

        $data = $this->request->getPost();
        $annee = (int) ($data['annee'] ?? 0);

        if (!$model->isValidYear($annee)) {
            return redirect()->back()->withInput()->with('errors', [
                'annee' => 'L’année ne peut pas être supérieure à l’année courante.'
            ]);
        }

        $data['annee'] = $annee;

        $file = $this->request->getFile('couverture');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('errors', [
                    'couverture' => 'Format image invalide. Formats autorisés : JPG, PNG, WEBP.'
                ]);
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('errors', [
                    'couverture' => 'Image trop lourde (max 2Mo).'
                ]);
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);

            $data['couverture'] = $newName;
        }

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/')
                         ->with('success', 'Livre ajouté avec succès');
    }

    public function supprimer($id)
    {
        $model = new LivreModel();

        if (!$model->find($id)) {
            return redirect()->to('/')
                             ->with('error', 'Livre introuvable');
        }

        $model->delete($id);

        return redirect()->to('/')
                         ->with('success', 'Livre supprimé');
    }
}