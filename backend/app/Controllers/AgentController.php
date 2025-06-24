<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UtilisateursModel;
use App\Models\GroupeModel;

class AgentController extends BaseController
{
    protected $ticketModel;
    protected $utilisateurModel;
    protected $groupeModel;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->utilisateurModel = new UtilisateursModel();
        $this->groupeModel = new GroupeModel();
    }

    public function dashboard()
    {
        $userId = session()->get('utilisateur')['id'];
        $user = $this->utilisateurModel->find($userId);
        
        // Statistiques des tickets individuels
        $tickets_individuels = $this->ticketModel->where('id_agent', $userId)->findAll();
        $stats_individuelles = [
            'total' => count($tickets_individuels),
            'en_cours' => count(array_filter($tickets_individuels, fn($t) => $t['statut'] === 'en_cours')),
            'resolus' => count(array_filter($tickets_individuels, fn($t) => $t['statut'] === 'resolu')),
            'nouveaux' => count(array_filter($tickets_individuels, fn($t) => $t['statut'] === 'nouveau'))
        ];

        // Statistiques des tickets du groupe
        $stats_groupe = null;
        if ($user['id_groupe']) {
            $tickets_groupe = $this->ticketModel->where('id_groupe', $user['id_groupe'])->findAll();
            $stats_groupe = [
                'total' => count($tickets_groupe),
                'en_cours' => count(array_filter($tickets_groupe, fn($t) => $t['statut'] === 'en_cours')),
                'resolus' => count(array_filter($tickets_groupe, fn($t) => $t['statut'] === 'resolu')),
                'nouveaux' => count(array_filter($tickets_groupe, fn($t) => $t['statut'] === 'nouveau'))
            ];
        }

        // Derniers tickets assignés
        $derniers_tickets = $this->ticketModel->select('tickets.*, clients.nom as client_nom, ticket_categories.nom as categorie_nom')
                                           ->join('clients', 'clients.id = tickets.id_client')
                                           ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                                           ->where('tickets.id_agent', $userId)
                                           ->orderBy('tickets.created_at', 'DESC')
                                           ->limit(5)
                                           ->findAll();

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
        $tickets = $this->ticketModel->select('tickets.*, clients.nom as client_nom, ticket_categories.nom as categorie_nom')
                                   ->join('clients', 'clients.id = tickets.id_client')
                                   ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                                   ->where('tickets.id_agent', $userId)
                                   ->orderBy('tickets.created_at', 'DESC')
                                   ->findAll();

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

        $tickets = $this->ticketModel->select('tickets.*, clients.nom as client_nom, ticket_categories.nom as categorie_nom, utilisateurs.nom as agent_nom, utilisateurs.prenom as agent_prenom')
                                   ->join('clients', 'clients.id = tickets.id_client')
                                   ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                                   ->join('utilisateurs', 'utilisateurs.id = tickets.id_agent', 'left')
                                   ->where('tickets.id_groupe', $user['id_groupe'])
                                   ->orderBy('tickets.created_at', 'DESC')
                                   ->findAll();

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
        $ticket = $this->ticketModel->find($ticketId);

        if (!$ticket || $ticket['id_agent'] !== $userId) {
            return $this->response->setJSON(['success' => false]);
        }

        $newStatus = $this->request->getJSON()->status;
        $allowedStatuses = ['en_cours', 'resolu'];

        if (!in_array($newStatus, $allowedStatuses)) {
            return $this->response->setJSON(['success' => false]);
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

        if (!$ticket || $ticket['id_groupe'] !== $user['id_groupe'] || $ticket['id_agent'] || $ticket['statut'] !== 'nouveau') {
            return $this->response->setJSON(['success' => false]);
        }

        $this->ticketModel->update($ticketId, [
            'id_agent' => $userId,
            'statut' => 'en_cours'
        ]);

        return $this->response->setJSON(['success' => true]);
    }
} 