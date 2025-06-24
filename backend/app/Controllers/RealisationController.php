<?php

namespace App\Controllers;

use App\Models\CategoriesModel;
use App\Models\RealisationModel;
use App\Models\DetailsRealisationModel;

class RealisationController extends BaseController
{
    public function index()
    {
        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->findAll();

        return view('realisation', ['categories' => $categories]);
    }

    public function save()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'titre' => 'required|min_length[3]',
            'montant' => 'required|decimal',
            'mois' => 'required|integer|greater_than[0]|less_than[13]',
            'annee' => 'required|integer|min_length[4]',
            'details' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error', $validation->getErrors());
            return redirect()->back();
        }

        $realisationModel = new RealisationModel();
        $detailsModel = new DetailsRealisationModel();

        try {
            $session = session();
            if (!$session->has('utilisateur') || !isset($session->get('utilisateur')['id_departement'])) {
                throw new \Exception("Session invalide ou utilisateur non authentifié.");
            }

            $data = [
                'titre' => $this->request->getPost('titre'),
                'montant' => $this->request->getPost('montant'),
                'mois' => $this->request->getPost('mois'),
                'annee' => $this->request->getPost('annee'),
                'id_departement' => $session->get('utilisateur')['id_departement']
            ];

            $id = $this->request->getPost('id');

            if ($id) {
                // Update existing realization
                $realisationModel->updateRealisation($id, $data);

                // Delete old details and insert new ones
                $detailsModel->where('id_realisation', $id)->delete();
                $details = json_decode($this->request->getPost('details'), true);

                foreach ($details as $detail) {
                    $detailsModel->insert([
                        'id_realisation' => $id,
                        'id_categorie' => $detail['categorie'],
                        'montant' => $detail['montant']
                    ]);
                }

                session()->setFlashdata('success', 'Réalisation mise à jour avec succès.');
                return redirect()->to('/admin/listeRealisations');
            } else {
                // Add new realization
                $inserted = $realisationModel->insert($data);

                if (!$inserted) {
                    throw new \Exception("Erreur lors de l'ajout de la réalisation.");
                }

                $id_realisation = $realisationModel->getInsertID();
                $details = json_decode($this->request->getPost('details'), true);

                foreach ($details as $detail) {
                    $detailsModel->insert([
                        'id_realisation' => $id_realisation,
                        'id_categorie' => $detail['categorie'],
                        'montant' => $detail['montant']
                    ]);
                }

                session()->setFlashdata('success', 'Réalisation ajoutée avec succès.');
            }

            return redirect()->to('realisations');
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function modifierPage($id)
    {
        $realisationModel = new RealisationModel();
        $categoriesModel = new CategoriesModel();
        $detailsModel = new DetailsRealisationModel();

        // Fetch the realization by ID
        $realisation = $realisationModel->getRealisationById($id);
        if (!$realisation) {
            session()->setFlashdata('error', 'Réalisation introuvable.');
            return redirect()->to('/admin/listeRealisations');
        }

        // Fetch the details of the realization
        $details = $detailsModel->where('id_realisation', $id)->findAll();

        // Fetch all categories
        $categories = $categoriesModel->findAll();

        // Pass data to the view
        return view('realisation', [
            'realisation' => $realisation,
            'details' => $details,
            'categories' => $categories
        ]);
    }

    public function validerRealisation($id)
    {
        $realisationModel = new RealisationModel();

        try {
            // Validate the realization by updating its status
            $updated = $realisationModel->validerRealisation($id);

            if (!$updated) {
                throw new \Exception("Erreur lors de la validation de la réalisation.");
            }

            session()->setFlashdata('success', 'Réalisation validée avec succès.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
        }

        return redirect()->to('/admin/listeRealisations');
    }

    public function supprimerRealisation($id)
    {
        $realisationModel = new RealisationModel();
        $detailsModel = new DetailsRealisationModel();

        try {
            // Delete the details of the realizationzzzz
            $detailsModel->where('id_realisation', $id)->delete();

            // Delete the realization
            $deleted = $realisationModel->deleteRealisation($id);

            if (!$deleted) {
                throw new \Exception("Erreur lors de la suppression de la réalisation.");
            }

            session()->setFlashdata('success', 'Réalisation supprimée avec succès.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
        }

        return redirect()->to('/admin/listeRealisations');
    }

    
}
