<?php

namespace App\Models;

use CodeIgniter\Model;

class PrevisionsModel extends Model
{
    protected $table = 'previsions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'titre',
        'id_departement',
        'solde_depart',
        'statut',
        'mois',
        'annee'
    ];

    protected $useTimestamps = false; // Désactiver les timestamps automatiques

    // Récupérer les prévisions avec les détails
    public function getPrevisionAvecDetails($id = null)
    {
        $builder = $this->db->table('previsions p');
        $builder->select('p.*, d.nom as nom_departement');
        $builder->join('departements d', 'd.id = p.id_departement');
        
        if ($id !== null) {
            $builder->where('p.id', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    // Récupérer les prévisions par département
    public function getPrevisionsParDepartement($id_departement)
    {
        return $this->where('id_departement', $id_departement)
                    ->findAll();
    }

    // Récupérer les prévisions filtrées par département, année et mois
    public function getPrevisionsFiltered($departement_id, $annee, $mois = null)
    {
        $builder = $this->db->table('previsions p');
        $builder->select('p.*, d.nom as nom_departement');
        $builder->join('departements d', 'd.id = p.id_departement');
        $builder->where('p.id_departement', $departement_id);
        $builder->where('p.annee', $annee);
        
        if ($mois !== null) {
            $builder->where('p.mois', $mois);
        }
        
        $previsions = $builder->get()->getResultArray();

        // Ajouter les détails des catégories pour chaque prévision
        foreach ($previsions as &$prevision) {
            $details = $this->db->table('detailsPrevisions dp')
                ->select('dp.montant, c.type')
                ->join('categories c', 'c.id = dp.id_categorie')
                ->where('dp.id_prevision', $prevision['id'])
                ->get()
                ->getResultArray();
            
            $prevision['details_categories'] = $details;
        }
        
        return $previsions;
    }

    // Récupérer les détails des prévisions
    public function getPrevisionsDetails($prevision_id)
    {
        $builder = $this->db->table('detailsPrevisions dp');
        $builder->select('dp.*, dp.created_at, c.nom as categorie_nom, c.type');
        $builder->join('categories c', 'c.id = dp.id_categorie');
        $builder->where('dp.id_prevision', $prevision_id);
        $builder->orderBy('c.type', 'ASC'); // Trier par type (gains/dépenses)
        return $builder->get()->getResultArray();
    }
}

