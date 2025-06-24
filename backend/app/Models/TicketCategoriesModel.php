<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketCategoriesModel extends Model
{
    protected $table = 'ticket_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'description', 'created_at'];
    protected $useTimestamps = false;
} 