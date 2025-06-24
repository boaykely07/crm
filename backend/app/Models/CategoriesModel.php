<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'type'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    // Récupérer toutes les catégories
    public function getAllCategories()
    {
        return $this->findAll();
    }

    // Récupérer les catégories par type (gain ou dépense)
    public function getCategoriesByType($type)
    {
        return $this->where('type', $type)->findAll();
    }
}
