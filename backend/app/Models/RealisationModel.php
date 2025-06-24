<?php

namespace App\Models;

use CodeIgniter\Model;

class RealisationModel extends Model
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

    protected $useTimestamps = false; // Disable automatic timestamps

    // Retrieve realizations with details
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
    // Retrieve realizations by department
    public function getRealisationsParDepartement($id_departement)
    {
        return $this->where('id_departement', $id_departement)
                    ->findAll();
    }

    // Create a new realization
    public function createRealisation($data)
    {
        return $this->insert($data);
    }

    // Get a realization by ID
    public function getRealisationById($id)
    {
        return $this->find($id);
    }

    // Update a realization by ID
    public function updateRealisation($id, $data)
    {
        return $this->update($id, $data);
    }

    // Delete a realization by ID
    public function deleteRealisation($id)
    {
        return $this->delete($id);
    }

    // Validate a realization by updating its status to 'validee'
    public function validerRealisation($id)
    {
        return $this->update($id, ['statut' => 'validee']);
    }
}
