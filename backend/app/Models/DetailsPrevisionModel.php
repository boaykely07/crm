<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailsPrevisionModel extends Model
{
    protected $table = 'detailsPrevisions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'id_prevision',
        'id_categorie',
        'montant'
    ];

    protected $useTimestamps = false; // Désactiver les timestamps automatiques

    // Récupérer les détails d'une prévision avec les catégories
    public function getDetailsAvecCategories($id_prevision)
    {
        return $this->select('detailsPrevisions.*, detailsPrevisions.created_at, categories.nom as categorie_nom, categories.type')
                    ->join('categories', 'categories.id = detailsPrevisions.id_categorie')
                    ->where('id_prevision', $id_prevision)
                    ->orderBy('categories.type', 'ASC')
                    ->findAll();
    }

    // Calculer le total des dépenses
    public function getTotalDepenses($id_prevision)
    {
        return $this->select('SUM(detailsPrevisions.montant) as total')
                    ->join('categories', 'categories.id = detailsPrevisions.id_categorie')
                    ->where('id_prevision', $id_prevision)
                    ->where('categories.type', 'depense')
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    }

    // Calculer le total des gains
    public function getTotalGains($id_prevision)
    {
        return $this->select('SUM(detailsPrevisions.montant) as total')
                    ->join('categories', 'categories.id = detailsPrevisions.id_categorie')
                    ->where('id_prevision', $id_prevision)
                    ->where('categories.type', 'gain')
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    }
}
