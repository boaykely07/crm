<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitsModel extends Model
{
    protected $table = 'produits';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'id_categorie', 'prix_unitaire'];
    protected $useTimestamps = false;
}