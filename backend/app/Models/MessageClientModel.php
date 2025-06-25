<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageClientModel extends Model
{
    protected $table = 'message_client';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_client', 'id_ticket', 'message', 'fichier_url', 'date_message'];
    protected $useTimestamps = false;

    public function getMessagesWithClient()
    {
        return $this->select('message_client.*, clients.nom as client_nom')
                    ->join('clients', 'clients.id = message_client.id_client')
                    ->orderBy('message_client.date_message', 'DESC')
                    ->findAll();
    }

    public function getMessagesWithTicketStatus($clientId)
    {
        return $this->select('message_client.*, tickets.statut as ticket_statut')
            ->join('tickets', 'tickets.id = message_client.id_ticket', 'left')
            ->where('message_client.id_client', $clientId)
            ->orderBy('message_client.date_message', 'DESC')
            ->findAll();
    }
} 