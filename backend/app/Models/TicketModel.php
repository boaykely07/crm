<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'titre', 'description', 'id_client', 'id_categorie', 
        'id_agent', 'id_groupe', 'statut', 'priorite', 'etoiles'
    ];

    protected $validationRules = [
        'statut' => 'required|in_list[ouvert,en_cours,resolu,ferme]'
    ];

    public function getTicketsWithDetails()
    {
        return $this->select('tickets.*, clients.nom as client_nom, 
                            ticket_categories.nom as categorie_nom,
                            utilisateurs.nom as agent_nom,
                            groupes.nom as groupe_nom')
                    ->join('clients', 'clients.id = tickets.id_client')
                    ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                    ->join('utilisateurs', 'utilisateurs.id = tickets.id_agent', 'left')
                    ->join('groupes', 'groupes.id = tickets.id_groupe', 'left')
                    ->findAll();
    }

    public function getTicketById($id)
    {
        return $this->select('tickets.*, clients.nom as client_nom, 
                            ticket_categories.nom as categorie_nom,
                            utilisateurs.nom as agent_nom,
                            groupes.nom as groupe_nom')
                    ->join('clients', 'clients.id = tickets.id_client')
                    ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                    ->join('utilisateurs', 'utilisateurs.id = tickets.id_agent', 'left')
                    ->join('groupes', 'groupes.id = tickets.id_groupe', 'left')
                    ->where('tickets.id', $id)
                    ->first();
    }
}
