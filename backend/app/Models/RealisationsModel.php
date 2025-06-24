<?php

namespace App\Models;

use CodeIgniter\Model;

class RealisationsModel extends Model
{
    protected $table = 'realisations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'titre', 
        'id_departement', 
        'montant', 
        'statut', 
        'mois', 
        'annee'
    ];

    // Désactiver complètement la gestion des timestamps de CodeIgniter
    protected $useTimestamps = false;

    public function insert($data = null, bool $returnID = true)
    {
        // Assurez-vous que created_at n'est pas inclus car il est géré par MySQL
        unset($data['created_at']);
        return parent::insert($data, $returnID);
    }

    public function getRealisationAvecDetails($id = null)
    {
        $builder = $this->db->table('realisations r');
        $builder->select('r.*, d.nom as nom_departement');
        $builder->join('departements d', 'd.id = r.id_departement');

        if ($id !== null) {
            $builder->where('r.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }


    public function getRealisationsFiltered($departement_id, $annee, $mois = null)
    {
        $builder = $this->db->table('realisations r');
        $builder->select('r.*, d.nom as nom_departement');
        $builder->join('departements d', 'd.id = r.id_departement');
        $builder->where('r.id_departement', $departement_id);
        $builder->where('r.annee', $annee);
        
        // Ne filtrer par mois que si un mois spécifique est demandé
        if ($mois !== null && $mois !== 'all' && $mois !== '') {
            $builder->where('r.mois', $mois);
        }
        
        $realisations = $builder->get()->getResultArray();

        // Ajouter les détails des catégories pour chaque réalisation
        foreach ($realisations as &$realisation) {
            $details = $this->db->table('detailsRealisations dr')
                ->select('dr.montant, c.type')
                ->join('categories c', 'c.id = dr.id_categorie')
                ->where('dr.id_realisation', $realisation['id'])
                ->get()
                ->getResultArray();
            
            $realisation['details'] = $details;
        }
        
        return $realisations;
    }

    public function existsForPrevision($departement_id, $mois, $annee)
    {
        return $this->where([
            'id_departement' => $departement_id,
            'mois' => $mois,
            'annee' => $annee
        ])->countAllResults() > 0;
    }
}
