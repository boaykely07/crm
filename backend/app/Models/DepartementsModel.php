<?php
namespace App\Models;

use CodeIgniter\Model;

class DepartementsModel extends Model
{
    protected $table = 'departements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom'];
    public $timestamps = false;
}