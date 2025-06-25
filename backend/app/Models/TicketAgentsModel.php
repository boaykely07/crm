<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketAgentsModel extends Model
{
    protected $table = 'ticket_agents';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_ticket', 'id_agent'];
    public $timestamps = false;
}
