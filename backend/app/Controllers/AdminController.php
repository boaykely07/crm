<?php

namespace App\Controllers;

use App\Models\PrevisionsModel;
use App\Models\RealisationModel;
use App\Models\CRMBudgetModel;
use App\Models\ActionsModel;

class AdminController extends BaseController
{
    public function adminPage()
    {
        return view('admin/admin');
    }

    public function listePrevisionsPage()
    {
        $previsionsModel = new PrevisionsModel();
        $data['previsions'] = $previsionsModel->getPrevisionAvecDetails();

        return view('admin/admin', [
            'content' => view('admin/listePrevision', $data)
        ]);
    }

    public function listeRealisationsPage()
    {
        $realisationModel = new RealisationModel();
        $data['realisations'] = $realisationModel->getRealisationAvecDetails();

        return view('admin/admin', [
            'content' => view('admin/listeRealisation', $data)
        ]);
    }
    public function listeBudgetCRMPage()
    {
        $budgetModel = new CRMBudgetModel();
        $data['budgets'] = $budgetModel->findAll();

        return view('admin/admin', [
            'content' => view('admin/listeBudgetCRM', $data)
        ]);
    }

    public function listeTicketsPage()
    {
        $ticketModel = new \App\Models\TicketModel();
        $utilisateurModel = new \App\Models\UtilisateursModel();
        $groupeModel = new \App\Models\GroupeModel();

        $data = [
            'tickets' => $ticketModel->getTicketsWithDetails(),
            'agents' => $utilisateurModel->where('role', 'agent')->findAll(),
            'groupes' => $groupeModel->findAll()
        ];

        return view('admin/admin', [
            'content' => view('admin/listeTicket', $data)
        ]);
    }

    public function listeMessageClientPage()
    {
        $messageClientModel = new \App\Models\MessageClientModel();
        $data['messages'] = $messageClientModel->getMessagesWithClient();
        return view('admin/admin', [
            'content' => view('admin/listeMessageClient', $data)
        ]);
    }

    public function detailMessageClientPage($id)
    {
        $messageModel = new \App\Models\MessageClientModel();
        $categorieModel = new \App\Models\TicketCategoriesModel();
        $utilisateurModel = new \App\Models\UtilisateursModel();
        $message = $messageModel
            ->select('message_client.*, clients.nom as client_nom')
            ->join('clients', 'clients.id = message_client.id_client')
            ->where('message_client.id', $id)
            ->first();
        $categories = $categorieModel->findAll();
        $agents = $utilisateurModel->where('role', 'agent')->findAll();
        return view('admin/detailMessageClient', ['message' => $message, 'categories' => $categories, 'agents' => $agents]);
    }

    public function creerTicketDepuisMessage()
    {
        $ticketModel = new \App\Models\TicketModel();
        $previsionsModel = new \App\Models\PrevisionsModel();
        $detailsPrevisionModel = new \App\Models\DetailsPrevisionModel();
        $categoriesModel = new \App\Models\CategoriesModel();
        $messageModel = new \App\Models\MessageClientModel();

        $id_client = $this->request->getPost('id_client');
        $id_categorie = $this->request->getPost('id_categorie');
        $id_agent = $this->request->getPost('id_agent') ?: null;
        $priorite = $this->request->getPost('priorite');
        $THoraire = $this->request->getPost('THoraire');
        $date_heure_debut = $this->request->getPost('date_heure_debut');
        $date_heure_fin = $this->request->getPost('date_heure_fin');
        $titre = $this->request->getPost('titre');
        $description = $this->request->getPost('description');
        $id_message_client = $this->request->getPost('id_message_client');

        // Récupérer le message client pour le fichier_url
        $message = $messageModel->find($id_message_client);
        $fichier_url = $message['fichier_url'] ?? null;

        // 1. Chercher la prévision pour le mois/année de date_heure_debut
        $mois = date('n', strtotime($date_heure_debut));
        $annee = date('Y', strtotime($date_heure_debut));
        $prevision = $previsionsModel
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->first();
        if (!$prevision) {
            return redirect()->to('/admin/detailMessageClient/' . $id_message_client)
                ->with('error', "Aucune prévision budgétaire trouvée pour ce mois/année.")
                ->withInput();
        }

        // 2. Chercher le détail avec id_categorie=11 ou nom='ticket'
        $categorie_ticket = $categoriesModel->where('nom', 'ticket')->first();
        $id_categorie_ticket = $categorie_ticket['id'] ?? 11;
        $detail = $detailsPrevisionModel
            ->where('id_prevision', $prevision['id'])
            ->groupStart()
                ->where('id_categorie', 11)
                ->orWhere('id_categorie', $id_categorie_ticket)
            ->groupEnd()
            ->first();
        if (!$detail) {
            return redirect()->to('/admin/detailMessageClient/' . $id_message_client)
                ->with('error', "Aucun détail de prévision pour la catégorie 'ticket' ou id 11.")
                ->withInput();
        }

        // 3. Vérifier le montant
        if ($detail['montant'] < $THoraire) {
            return redirect()->to('/admin/detailMessageClient/' . $id_message_client)
                ->with('error', "Le montant de la prévision pour les tickets est insuffisant (".$detail['montant']." < ".$THoraire.").")
                ->withInput();
        }

        // 4. Créer le ticket
        $data = [
            'titre' => $titre,
            'description' => $description,
            'id_client' => $id_client,
            'id_categorie' => $id_categorie,
            'id_agent' => $id_agent,
            'statut' => 'ouvert',
            'priorite' => $priorite,
            'THoraire' => $THoraire,
            'date_heure_debut' => $date_heure_debut,
            'date_heure_fin' => $date_heure_fin,
            'fichier_url' => $fichier_url,
        ];
        $ticketModel->insert($data);
        return redirect()->to('/admin/tickets')->with('success', 'Ticket créé avec succès à partir du message client.');
    }

    public function validerBudgetCRM($id)
    {
        $budgetModel = new CRMBudgetModel();
        $BudgetSelect = $budgetModel->getBudgetById($id);

        $previsionsModel = new PrevisionsModel();
        $detailsPrevisionModel = new \App\Models\DetailsPrevisionModel();

        // Check if there is a validated prevision for the same month and year
        $previsions = $previsionsModel->where([
            'id_departement' => $BudgetSelect['id_departement'],
            'mois' => $BudgetSelect['mois'],
            'annee' => $BudgetSelect['annee']
        ])->findAll();

        if (empty($previsions)) {
            return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Pas de prévisions pour cette date.');
        }

        $validatedPrevision = null;
        foreach ($previsions as $prevision) {
            if ($prevision['statut'] === 'validee') {
                $validatedPrevision = $prevision;
                break;
            }
        }

        if (!$validatedPrevision) {
            return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Il y a des prévisions mais aucune n\'est validée.');
        }

        // Check if there are CRM details in the validated prevision
        $details = $detailsPrevisionModel->where([
            'id_prevision' => $validatedPrevision['id'],
            'id_categorie' => (new \App\Models\CategoriesModel())->where('nom', 'CRM')->first()['id']
        ])->findAll();

        if (empty($details)) {
            return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Pas de détails prévisions CRM pour cette date.');
        }

        // Check if any detail montant exceeds the budget montant
        foreach ($details as $detail) {
            if ($detail['montant'] > $BudgetSelect['montant']) {
                // Log the values for debugging
                log_message('error', 'Validation échouée : detailMontant=' . $detail['montant'] . ', budgetMontant=' . $BudgetSelect['montant']);
                return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Un montant des détails prévisions CRM dépasse le budget CRM.');
            }
        }

        // Validate the budget and update the action status
        $budgetModel->validateBudget($id);
        $actionModel = new ActionsModel();
        $actionModel->updateStatus($BudgetSelect['id_action'], 'Terminée');

        return redirect()->to('/admin/listeBudgetCRM')->with('success', 'Budget CRM validé avec succès.');
    }
}