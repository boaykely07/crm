<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\VentesModel;
use App\Models\StocksModel;
use App\Models\ActionsModel;
use App\Models\CRMBudgetModel;
use CodeIgniter\API\ResponseTrait;

class CRMController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $clientsModel = new ClientsModel();
        $ventesModel = new VentesModel();
        $stocksModel = new StocksModel();
        $actionsModel = new ActionsModel();

        $stats = [
            'total_clients' => $clientsModel->countAllClients(), // Nombre total de clients
            'ventes_mois' => $ventesModel->getVentesCeMois(), // Ventes du mois courant
            'produits_stock_faible' => $stocksModel->getProduitsStockFaible(), // Produits avec stock < 10
            'total_ventes' => $ventesModel->getTotalVentes(), // Total des ventes en Ariary
            'actions_en_attente' => $actionsModel->where('status', 'En attente')->countAllResults(), // Actions en attente
            'actions_terminees' => $actionsModel->where('status', 'Terminée')->countAllResults(), // Actions terminées
        ];

        return view('crm/index', ['stats' => $stats]);
    }

    public function createBudgetPage()
    {
        $session = session();
        $utilisateur = $session->get('utilisateur');
        
        if (!$utilisateur) {
            return $this->respond([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
        }

        $budgetModel = new CRMBudgetModel();
        $actionsModel = new ActionsModel();
        
        $actionId = $this->request->getPost('action_id');
        
        $data = [
            'id_departement' => $utilisateur['id_departement'],
            'mois' => $this->request->getPost('mois'),
            'annee' => $this->request->getPost('annee'),
            'montant' => $this->request->getPost('montant'),
            'description' => $this->request->getPost('description'),
            'id_action' => $actionId,
            'status' => 'En attente'
        ];

        try {
            $budgetModel->create_budget_CRM($data);
            
            // Mettre à jour le statut de l'action en "Terminée"
            $actionsModel->update($actionId, ['status' => 'En cours']);
            
            return $this->respond([
                'success' => true,
                'message' => 'Budget créé avec succès'
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Erreur lors de la création du budget: ' . $e->getMessage()
            ]);
        }
    }
}