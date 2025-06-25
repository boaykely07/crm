<?php

namespace App\Controllers;

use App\Models\PrevisionsModel;
use App\Models\RealisationModel;
use App\Models\CRMBudgetModel;
use App\Models\ActionsModel;
use App\Models\TicketModel;
use App\Models\TicketAgentsModel;
use App\Models\UtilisateursModel;

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
        $ticketModel = new TicketModel();
        $utilisateurModel = new UtilisateursModel();
        $groupeModel = new \App\Models\GroupeModel();
        $ticketAgentsModel = new TicketAgentsModel();

        // Récupérer tous les tickets avec leurs détails
        $tickets = $ticketModel->getTicketsWithDetails();
        
        // Pour chaque ticket, récupérer la liste des agents assignés
        foreach ($tickets as &$ticket) {
            $agentsAssignes = $ticketAgentsModel
                ->select('ticket_agents.*, utilisateurs.nom as agent_nom, utilisateurs.email as agent_email')
                ->join('utilisateurs', 'utilisateurs.id = ticket_agents.id_agent')
                ->where('ticket_agents.id_ticket', $ticket['id'])
                ->findAll();
            
            $ticket['agents_assignes'] = $agentsAssignes;
        }

        $data = [
            'tickets' => $tickets,
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
        $utilisateurModel = new UtilisateursModel();
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
        $ticketModel = new TicketModel();
        $previsionsModel = new PrevisionsModel();
        $detailsPrevisionModel = new \App\Models\DetailsPrevisionModel();
        $categoriesModel = new \App\Models\CategoriesModel();
        $messageModel = new \App\Models\MessageClientModel();
        $ticketAgentsModel = new TicketAgentsModel();

        $id_client = $this->request->getPost('id_client');
        $id_categorie = $this->request->getPost('id_categorie');
        $id_agents = $this->request->getPost('id_agents') ?? [];
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
            'statut' => 'ouvert',
            'priorite' => $priorite,
            'THoraire' => $THoraire,
            'date_heure_debut' => $date_heure_debut,
            'date_heure_fin' => $date_heure_fin,
            'fichier_url' => $fichier_url,
        ];
        $ticket_id = $ticketModel->insert($data, true);

        // 5. Mettre à jour le message_client avec l'id du ticket créé
        $messageModel->update($id_message_client, ['id_ticket' => $ticket_id]);

        // 6. Ajout des agents sélectionnés dans ticket_agents
        foreach ($id_agents as $id_agent) {
            $ticketAgentsModel->insert([
                'id_ticket' => $ticket_id,
                'id_agent' => $id_agent
            ]);
        }

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

    public function rapportPerformancePage()
    {
        $ticketModel = new TicketModel();
        $ticketAgentsModel = new TicketAgentsModel();
        $utilisateurModel = new UtilisateursModel();

        // Temps moyen de résolution (en heures)
        $avgResolutionTime = $ticketModel->select('AVG(TIMESTAMPDIFF(HOUR, date_ouverture, date_heure_fin)) as avg_time')
            ->where('statut', 'ferme')
            ->first()['avg_time'] ?? 0;

        // Satisfaction moyenne par agent
        $satisfactionByAgent = $ticketModel->select('utilisateurs.nom, utilisateurs.prenom, AVG(tickets.etoiles) as avg_satisfaction')
            ->join('ticket_agents', 'ticket_agents.id_ticket = tickets.id')
            ->join('utilisateurs', 'utilisateurs.id = ticket_agents.id_agent')
            ->where('tickets.etoiles IS NOT NULL')
            ->groupBy('utilisateurs.id')
            ->findAll();

        // Nombre de tickets ouverts/fermés par semaine (dernières 4 semaines)
        $ticketsByWeek = $ticketModel->select("
            YEARWEEK(date_ouverture, 1) as week,
            SUM(CASE WHEN statut = 'ouvert' THEN 1 ELSE 0 END) as ouverts,
            SUM(CASE WHEN statut = 'ferme' THEN 1 ELSE 0 END) as fermes
        ")
            ->where('date_ouverture >=', date('Y-m-d', strtotime('-4 weeks')))
            ->groupBy('week')
            ->orderBy('week', 'DESC')
            ->findAll();

        // Formater les semaines pour l'affichage
        foreach ($ticketsByWeek as &$week) {
            $weekNumber = substr($week['week'], -2);
            $year = substr($week['week'], 0, 4);
            $week['week_label'] = "Semaine $weekNumber, $year";
        }

        $data = [
            'title' => 'Rapport Performance',
            'avg_resolution_time' => round($avgResolutionTime, 2),
            'satisfaction_by_agent' => $satisfactionByAgent,
            'tickets_by_week' => $ticketsByWeek
        ];

        return view('admin/admin', [
            'content' => view('admin/rapport_performance', $data)
        ]);
    }
}