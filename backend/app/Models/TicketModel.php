<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'titre', 'description', 'id_client', 'id_categorie', 
        'id_groupe', 'statut', 'priorite', 'date_ouverture', 'THoraire', 'etoiles', 'date_heure_debut', 'date_heure_fin', 'fichier_url', 'created_at', 'updated_at'
    ];

    protected $validationRules = [
        'statut' => 'required|in_list[ouvert,en_cours,resolu,ferme]'
    ];

    public function getTicketsWithDetails()
    {
        return $this->select('tickets.*, clients.nom as client_nom, 
                            ticket_categories.nom as categorie_nom,
                            groupes.nom as groupe_nom')
                    ->join('clients', 'clients.id = tickets.id_client')
                    ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                    ->join('groupes', 'groupes.id = tickets.id_groupe', 'left')
                    ->findAll();
    }

    public function getTicketById($id)
    {
        return $this->select('tickets.*, clients.nom as client_nom, 
                            ticket_categories.nom as categorie_nom,
                            groupes.nom as groupe_nom')
                    ->join('clients', 'clients.id = tickets.id_client')
                    ->join('ticket_categories', 'ticket_categories.id = tickets.id_categorie')
                    ->join('groupes', 'groupes.id = tickets.id_groupe', 'left')
                    ->where('tickets.id', $id)
                    ->first();
    }
}
