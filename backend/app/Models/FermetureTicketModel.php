<?php

namespace App\Models;

use CodeIgniter\Model;

class FermetureTicketModel extends Model
{
    protected $table = 'fermetureticket';
    protected $primaryKey = 'id';
    protected $allowedFields = ['jour'];
    protected $useTimestamps = false;
} 