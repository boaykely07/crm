<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailsRealisationModel extends Model
{
    protected $table = 'detailsRealisations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'id_realisation',
        'id_categorie',
        'montant'
    ];

    // Désactiver les timestamps car nous n'avons que created_at
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';  // Désactiver updated_at
    protected $deletedField = '';  // Désactiver deleted_at

    // Récupérer les détails d'une réalisation avec les catégories
    public function getDetailsAvecCategories($id_realisation)
    {
        return $this->select('detailsRealisations.*, detailsRealisations.created_at, categories.nom as categorie_nom, categories.type')
                    ->join('categories', 'categories.id = detailsRealisations.id_categorie')
                    ->where('id_realisation', $id_realisation)
                    ->orderBy('categories.type', 'ASC')
                    ->findAll();
    }

    // Calculer le total des dépenses
    public function getTotalDepenses($id_realisation)
    {
        return $this->select('SUM(detailsRealisations.montant) as total')
                    ->join('categories', 'categories.id = detailsRealisations.id_categorie')
                    ->where('id_realisation', $id_realisation)
                    ->where('categories.type', 'depense')
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    }

    // Calculer le total des gains
    public function getTotalGains($id_realisation)
    {
        return $this->select('SUM(detailsRealisations.montant) as total')
                    ->join('categories', 'categories.id = detailsRealisations.id_categorie')
                    ->where('id_realisation', $id_realisation)
                    ->where('categories.type', 'gain')
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    }
}
