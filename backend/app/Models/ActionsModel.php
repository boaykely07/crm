<?php

namespace App\Models;

use CodeIgniter\Model;

class ActionsModel extends Model
{
    protected $table = 'actions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['titre', 'id_client', 'date_action', 'status', 'description'];
    protected $useTimestamps = false;

    // Récupérer toutes les actions avec les informations des clients
    public function getAllActions()
    {
        return $this->select('actions.*, clients.nom as client_nom, clients.email as client_email')
                    ->join('clients', 'clients.id = actions.id_client')
                    ->findAll();
    }

    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}
