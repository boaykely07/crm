<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UtilisateursModel;
use App\Models\GroupeModel;
use App\Models\TicketAgentsModel;

class AgentController extends BaseController
{
    protected $ticketModel;
    protected $utilisateurModel;
    protected $groupeModel;
    protected $ticketAgentsModel;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->utilisateurModel = new UtilisateursModel();
        $this->groupeModel = new GroupeModel();
        $this->ticketAgentsModel = new TicketAgentsModel();
    }

    public function dashboard()
    {
        $userId = session()->get('utilisateur')['id'];
        $user = $this->utilisateurModel->find($userId);
        
        // Récupérer les IDs des tickets assignés à cet agent
        $ticketIds = $this->ticketAgentsModel->where('id_agent', $userId)
                                           ->findColumn('id_ticket');
        
        // Statistiques des tickets individuels
        $tickets_individuels = [];
        if (!empty($ticketIds)) {
            $tickets_individuels = $this->ticketModel->whereIn('id', $ticketIds)->findAll();
        }
        
        $stats_individuelles = [
            'total' => count($tickets_individuels),
            'en_cours' => count(array_filter($tickets_individuels, fn($t) => $t['statut'] === 'en_cours')),
            'resolus' => count(array_filter($tickets_individuels, fn($t) => $t['statut'] === 'resolu')),
            'nouveaux' => count(array_filter($tickets_individuels, fn($t) => $t['statut'] === 'ouvert'))
        ];

        // Statistiques des tickets du groupe
        $stats_groupe = null;
        if ($user['id_groupe']) {
            $tickets_groupe = $this->ticketModel->where('id_groupe', $user['id_groupe'])->findAll();
            $stats_groupe = [
                'total' => count($tickets_groupe),
                'en_cours' => count(array_filter($tickets_groupe, fn($t) => $t['statut'] === 'en_cours')),
                'resolus' => count(array_filter($tickets_groupe, fn($t) => $t['statut'] === 'resolu')),
                'nouveaux' => count(array_filter($tickets_groupe, fn($t) => $t['statut'] === 'ouvert'))
            ];
        }

        // Derniers tickets assignés à cet agent
        $derniers_tickets = [];
        if (!empty($ticketIds)) {
            $derniers_tickets = $this->ticketModel->select('tickets.*, clients.nom as client_nom, ticket_categories.nom as categorie_nom')
                                               ->join('clients', 'clients.id = tickets.id_client')
                                               ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                                               ->whereIn('tickets.id', $ticketIds)
                                               ->orderBy('tickets.created_at', 'DESC')
                                               ->limit(5)
                                               ->findAll();
        }

        return view('agent/dashboard', [
            'stats_individuelles' => $stats_individuelles,
            'stats_groupe' => $stats_groupe,
            'derniers_tickets' => $derniers_tickets,
            'user' => $user
        ]);
    }

    public function mesTickets()
    {
        $userId = session()->get('utilisateur')['id'];
        
        // Récupérer les IDs des tickets assignés à cet agent
        $ticketIds = $this->ticketAgentsModel->where('id_agent', $userId)
                                           ->findColumn('id_ticket');
        
        $tickets = [];
        if (!empty($ticketIds)) {
            $tickets = $this->ticketModel->select('tickets.*, clients.nom as client_nom, 
                                                 ticket_categories.nom as categorie_nom,
                                                 message_client.id as id_message')
                                           ->join('clients', 'clients.id = tickets.id_client')
                                           ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                                           ->join('message_client', 'message_client.id_ticket = tickets.id', 'left')
                                           ->whereIn('tickets.id', $ticketIds)
                                           ->orderBy('tickets.created_at', 'DESC')
                                           ->findAll();
            
            // Ajouter les informations des autres agents assignés pour chaque ticket
            foreach ($tickets as &$ticket) {
                $autresAgents = $this->ticketAgentsModel
                    ->select('ticket_agents.*, utilisateurs.nom as agent_nom, utilisateurs.prenom as agent_prenom')
                    ->join('utilisateurs', 'utilisateurs.id = ticket_agents.id_agent')
                    ->where('ticket_agents.id_ticket', $ticket['id'])
                    ->where('ticket_agents.id_agent !=', $userId)
                    ->findAll();
                
                $ticket['autres_agents'] = $autresAgents;
            }
        }

        return view('agent/mes_tickets', [
            'tickets' => $tickets,
            'user' => $this->utilisateurModel->find($userId)
        ]);
    }

    public function ticketsGroupe()
    {
        $userId = session()->get('utilisateur')['id'];
        $user = $this->utilisateurModel->find($userId);

        if (!$user['id_groupe']) {
            return redirect()->to('/agent/dashboard')
                           ->with('error', 'Vous n\'êtes pas assigné à un groupe');
        }

        // Récupérer tous les tickets du groupe
        $tickets = $this->ticketModel->select('tickets.*, clients.nom as client_nom, ticket_categories.nom as categorie_nom')
                                   ->join('clients', 'clients.id = tickets.id_client')
                                   ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                                   ->where('tickets.id_groupe', $user['id_groupe'])
                                   ->orderBy('tickets.created_at', 'DESC')
                                   ->findAll();

        // Ajouter les informations des agents assignés pour chaque ticket
        foreach ($tickets as &$ticket) {
            $agentsAssignes = $this->ticketAgentsModel
                ->select('ticket_agents.*, utilisateurs.nom as agent_nom, utilisateurs.prenom as agent_prenom')
                ->join('utilisateurs', 'utilisateurs.id = ticket_agents.id_agent')
                ->where('ticket_agents.id_ticket', $ticket['id'])
                ->findAll();
            
            $ticket['agents_assignes'] = $agentsAssignes;
            
            // Vérifier si l'agent actuel est assigné à ce ticket
            $ticket['est_assigne'] = false;
            foreach ($agentsAssignes as $agent) {
                if ($agent['id_agent'] == $userId) {
                    $ticket['est_assigne'] = true;
                    break;
                }
            }
        }

        return view('agent/tickets_groupe', [
            'tickets' => $tickets,
            'user' => $user
        ]);
    }

    public function updateTicketStatus($ticketId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $userId = session()->get('utilisateur')['id'];
        
        // Vérifier si l'agent est assigné à ce ticket
        $assignation = $this->ticketAgentsModel->where([
            'id_ticket' => $ticketId,
            'id_agent' => $userId
        ])->first();

        if (!$assignation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Vous n\'êtes pas assigné à ce ticket']);
        }

        $ticket = $this->ticketModel->find($ticketId);
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket non trouvé']);
        }

        $newStatus = $this->request->getJSON()->status;
        $allowedStatuses = ['en_cours', 'resolu'];

        if (!in_array($newStatus, $allowedStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Statut non autorisé']);
        }

        $this->ticketModel->update($ticketId, ['statut' => $newStatus]);
        return $this->response->setJSON(['success' => true]);
    }

    public function takeTicket($ticketId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $userId = session()->get('utilisateur')['id'];
        $user = $this->utilisateurModel->find($userId);
        $ticket = $this->ticketModel->find($ticketId);

        if (!$ticket || $ticket['id_groupe'] !== $user['id_groupe'] || $ticket['statut'] !== 'ouvert') {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket non disponible']);
        }

        // Vérifier si l'agent n'est pas déjà assigné
        $existingAssignment = $this->ticketAgentsModel->where([
            'id_ticket' => $ticketId,
            'id_agent' => $userId
        ])->first();

        if ($existingAssignment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Vous êtes déjà assigné à ce ticket']);
        }

        // Assigner l'agent au ticket
        $this->ticketAgentsModel->insert([
            'id_ticket' => $ticketId,
            'id_agent' => $userId
        ]);

        // Mettre à jour le statut du ticket
        $this->ticketModel->update($ticketId, ['statut' => 'en_cours']);

        return $this->response->setJSON(['success' => true]);
    }

    public function removeAssignment($ticketId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $userId = session()->get('utilisateur')['id'];
        
        // Vérifier si l'agent est assigné à ce ticket
        $assignation = $this->ticketAgentsModel->where([
            'id_ticket' => $ticketId,
            'id_agent' => $userId
        ])->first();

        if (!$assignation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Vous n\'êtes pas assigné à ce ticket']);
        }

        // Supprimer l'assignation
        $this->ticketAgentsModel->where([
            'id_ticket' => $ticketId,
            'id_agent' => $userId
        ])->delete();

        // Vérifier s'il reste d'autres agents assignés
        $autresAssignations = $this->ticketAgentsModel->where('id_ticket', $ticketId)->findAll();
        
        // Si plus aucun agent assigné, remettre le ticket en statut "ouvert"
        if (empty($autresAssignations)) {
            $this->ticketModel->update($ticketId, ['statut' => 'ouvert']);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function viewMessage($id)
    {
        $messageModel = new \App\Models\MessageClientModel();
        $commentaireModel = new \App\Models\CommentaireMessageModel();
        $ticketModel = new \App\Models\TicketModel();

        // Récupérer le message avec les informations du client
        $message = $messageModel
            ->select('message_client.*, clients.nom as client_nom')
            ->join('clients', 'clients.id = message_client.id_client')
            ->where('message_client.id', $id)
            ->first();

        if (!$message) {
            return redirect()->back()->with('error', 'Message non trouvé');
        }

        // Récupérer le ticket associé si existe
        $ticket = null;
        if ($message['id_ticket']) {
            $ticket = $ticketModel->find($message['id_ticket']);
        }

        // Récupérer les commentaires avec les informations des auteurs
        $commentaires = $commentaireModel
            ->select('commentaire_message.*, clients.nom as nom_client, utilisateurs.nom as nom_agent')
            ->join('clients', 'clients.id = commentaire_message.id_client', 'left')
            ->join('utilisateurs', 'utilisateurs.id = commentaire_message.id_utilisateur', 'left')
            ->where('commentaire_message.id_message_client', $id)
            ->orderBy('date_commentaire', 'ASC')
            ->findAll();

        return view('agent/message', [
            'message' => $message,
            'ticket' => $ticket,
            'commentaires' => $commentaires
        ]);
    }

    public function addMessageComment($id)
    {
        $commentaireModel = new \App\Models\CommentaireMessageModel();
        
        $data = [
            'id_message_client' => $id,
            'id_utilisateur' => session()->get('id'),
            'auteur' => 'agent',
            'commentaire' => $this->request->getPost('commentaire')
        ];

        if ($commentaireModel->insert($data)) {
            return redirect()->back()->with('success', 'Commentaire ajouté avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'ajout du commentaire');
    }
}