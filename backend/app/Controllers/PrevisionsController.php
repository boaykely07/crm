<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoriesModel;
use App\Models\PrevisionsModel;
use App\Models\DetailsPrevisionModel;

class PrevisionsController extends ResourceController
{
    protected $db;
    protected $previsionsModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->previsionsModel = new PrevisionsModel();
        helper(['form', 'url']); // Load the form helper
    }

    public function index()
    {
        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->findAll();
        
        return view('previsions', ['categories' => $categories]);
    }

    public function save($id = null)
    {
        // Désactiver la sortie automatique de la vue
        $this->response->setContentType('application/json');
        
        try {
            $data = [
                'titre' => $this->request->getPost('titre'),
                'solde_depart' => $this->request->getPost('solde_depart'),
                'mois' => $this->request->getPost('mois'),
                'annee' => $this->request->getPost('annee'),
                'id_departement' => session()->get('utilisateur')['id_departement'],
                'statut' => 'en_attente'
            ];

            $this->db->transStart();

            // Insérer la prévision
            $prevision_id = $this->previsionsModel->insert($data);

            // Insérer les détails
            $details = json_decode($this->request->getPost('details'), true);
            foreach ($details as $detail) {
                $this->db->table('detailsPrevisions')->insert([
                    'id_prevision' => $prevision_id,
                    'id_categorie' => $detail['categorie'],
                    'montant' => $detail['montant']
                ]);
            }

            $this->db->transCommit();

            return $this->response->setBody(json_encode([
                'status' => 'success',
                'message' => 'Prévision enregistrée avec succès'
            ]));

        } catch (\Exception $e) {
            $this->db->transRollback();
            
            return $this->response->setStatusCode(500)->setBody(json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]));
        }
    }

    public function update($id = null)
    {
        try {
            if (!$id) {
                return redirect()->back()->with('error', 'ID manquant');
            }

            $data = [
                'titre' => $this->request->getPost('titre'),
                'solde_depart' => $this->request->getPost('solde_depart'),
                'mois' => $this->request->getPost('mois'),
                'annee' => $this->request->getPost('annee')
            ];

            $this->db->transStart();

            // Mise à jour de la prévision
            $this->previsionsModel->update($id, $data);

            // Supprimer les anciens détails
            $this->db->table('detailsPrevisions')
                ->where('id_prevision', $id)
                ->delete();

            // Insérer les nouveaux détails
            $details = json_decode($this->request->getPost('details'), true);
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $this->db->table('detailsPrevisions')->insert([
                        'id_prevision' => $id,
                        'id_categorie' => $detail['categorie'],
                        'montant' => $detail['montant']
                    ]);
                }
            }

            $this->db->transCommit();
            return redirect()->to('/admin/listePrevisions')
                ->with('success', 'Prévision mise à jour avec succès');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getPrevisions()
    {
        $departement_id = $this->request->getGet('departement_id');
        $annee = $this->request->getGet('annee');
        $mois = $this->request->getGet('mois');

        // Si mois est vide ou égal à "all", on ne filtre pas par mois
        if (empty($mois) || $mois === 'all') {
            $mois = null;
        }

        $previsions = $this->previsionsModel->getPrevisionsFiltered($departement_id, $annee, $mois);
        
        // Vérifier pour chaque prévision si une réalisation existe
        $realisationsModel = new \App\Models\RealisationsModel();
        foreach ($previsions as &$prevision) {
            $prevision['has_realisation'] = $realisationsModel->existsForPrevision(
                $prevision['id_departement'],
                $prevision['mois'],
                $prevision['annee']
            );
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $previsions
        ]);
    }

    public function getDetails($id = null)
    {
        if ($id === null) {
            return $this->respond(['status' => 'error', 'message' => 'ID non fourni'], 400);
        }

        $details = $this->previsionsModel->getPrevisionsDetails($id);
        return $this->respond(['status' => 'success', 'data' => $details], 200);
    }

    public function viewDetails($id)
    {
        $prevision = $this->previsionsModel->getPrevisionAvecDetails($id);
        $details = $this->previsionsModel->getPrevisionsDetails($id);

        if (!$prevision) {
            return redirect()->to('/vue-annuel')->with('error', 'Prévision non trouvée');
        }

        return view('previsions/details', [
            'prevision' => $prevision,
            'details' => $details,
            'title' => 'Détails de la prévision'
        ]);
    }

    public function supprimerPrevision($id)
    {
        try {
            $this->db->transStart();
            
            // Vérifier si la prévision existe
            $prevision = $this->previsionsModel->find($id);
            if (!$prevision) {
                throw new \Exception('Prévision non trouvée');
            }

            // Supprimer les détails
            $this->db->table('detailsPrevisions')
                    ->where('id_prevision', $id)
                    ->delete();
            
            // Supprimer la prévision
            $this->previsionsModel->delete($id);

            $this->db->transCommit();

            return redirect()
                ->to('/admin/listePrevisions')
                ->with('success', 'Prévision supprimée avec succès');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function modifierPage($id)
    {
        $prevision = $this->previsionsModel->find($id);
        
        if (!$prevision) {
            return redirect()->to('/admin/listePrevisions')
                           ->with('error', 'Prévision non trouvée');
        }

        // Récupérer les détails
        $details = $this->db->table('detailsPrevisions dp')
            ->select('dp.*, c.nom as categorie_nom, c.type')
            ->join('categories c', 'c.id = dp.id_categorie')
            ->where('dp.id_prevision', $id)
            ->get()->getResultArray();

        // Récupérer les catégories
        $categories = $this->db->table('categories')->get()->getResultArray();

        return view('admin/modifier_prevision', [
            'prevision' => $prevision,
            'details' => $details,
            'categories' => $categories,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function validerPrevision($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('ID de prévision manquant');
            }

            $prevision = $this->previsionsModel->find($id);
            if (!$prevision) {
                throw new \Exception('Prévision non trouvée');
            }

            // Mettre à jour le statut
            $this->previsionsModel->update($id, [
                'statut' => 'validee'
            ]);

            return redirect()->to('/admin/listePrevisions')
                ->with('success', 'Prévision validée avec succès');

        } catch (\Exception $e) {
            return redirect()->to('/admin/listePrevisions')
                ->with('error', 'Erreur lors de la validation : ' . $e->getMessage());
        }
    }
}
