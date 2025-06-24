<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RealisationsModel;
use App\Models\PrevisionsModel;

class RealisationsController extends ResourceController
{
    protected $realisationsModel;
    protected $previsionsModel;
    protected $db;

    public function __construct()
    {
        $this->realisationsModel = new RealisationsModel();
        $this->previsionsModel = new PrevisionsModel();
        $this->db = \Config\Database::connect();
    }

    public function getRealisations()
    {
        $departement_id = $this->request->getVar('departement_id');
        $annee = $this->request->getVar('annee');
        $mois = $this->request->getVar('mois');

        if (!$departement_id || !$annee) {
            return $this->respond(['status' => 'error', 'message' => 'Paramètres manquants'], 400);
        }

        $realisations = $this->realisationsModel->getRealisationsFiltered($departement_id, $annee, $mois);
        return $this->respond(['status' => 'success', 'data' => $realisations], 200);
    }

    public function getDetails($id = null)
    {
        if ($id === null) {
            return $this->respond(['status' => 'error', 'message' => 'ID non fourni'], 400);
        }

        $details = $this->realisationsModel->getRealisationDetails($id);
        return $this->respond(['status' => 'success', 'data' => $details], 200);
    }
    
    public function viewDetails($id)
    {
        $realisation = $this->realisationsModel->getRealisationAvecDetails($id);
        $details = $this->realisationsModel->getRealisationDetails($id);

        if (!$realisation) {
            return redirect()->to('/vue-annuel')->with('error', 'Réalisation non trouvée');
        }

        return view('realisations/details', [
            'realisation' => $realisation,
            'details' => $details,
            'title' => 'Détails de la réalisation'
        ]);
    }

    public function validation($id)
    {
        $prevision = $this->previsionsModel->find($id);
        if (!$prevision) {
            return redirect()->to('/vue-annuel')->with('error', 'Prévision non trouvée');
        }

        // Récupérer les détails de la prévision
        $details = $this->db->table('detailsPrevisions dp')
            ->select('dp.*, c.nom as categorie_nom, c.type')
            ->join('categories c', 'c.id = dp.id_categorie')
            ->where('dp.id_prevision', $id)
            ->get()->getResultArray();

        $gains = array_filter($details ?? [], fn($item) => $item['type'] === 'gain');
        $depenses = array_filter($details ?? [], fn($item) => $item['type'] === 'depense');

        return view('realisations/validation_realisation', [
            'prevision' => $prevision,
            'gains' => $gains ?: [],
            'depenses' => $depenses ?: []
        ]);
    }

    public function save()
    {
        $previsionId = $this->request->getPost('prevision_id');
        $gains = $this->request->getPost('gains') ?? [];
        $depenses = $this->request->getPost('depenses') ?? [];

        $this->db->transStart();

        try {
            // Récupérer la prévision
            $prevision = $this->previsionsModel->find($previsionId);
            if (!$prevision) {
                throw new \Exception('Prévision non trouvée');
            }

            // Créer la réalisation
            $realisationId = $this->realisationsModel->insert([
                'titre' => $prevision['titre'],
                'id_departement' => $prevision['id_departement'],
                'montant' => $prevision['solde_depart'],
                'mois' => $prevision['mois'],
                'annee' => $prevision['annee'],
                'statut' => 'en_attente'
            ]);

            // Sauvegarder les détails
            if (!empty($gains)) {
                foreach ($gains as $detailId => $montant) {
                    $this->db->table('detailsRealisations')->insert([
                        'id_realisation' => $realisationId,
                        'id_categorie' => $detailId,
                        'montant' => $montant
                    ]);
                }
            }

            if (!empty($depenses)) {
                foreach ($depenses as $detailId => $montant) {
                    $this->db->table('detailsRealisations')->insert([
                        'id_realisation' => $realisationId,
                        'id_categorie' => $detailId,
                        'montant' => $montant
                    ]);
                }
            }

            $this->db->transCommit();
            return redirect()->to('/vue-annuel')->with('success', 'Réalisation enregistrée avec succès');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }
}
