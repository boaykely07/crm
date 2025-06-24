<?php

namespace App\Models;

use CodeIgniter\Model;

class CRMBudgetModel extends Model
{
    protected $table = 'crmBudget';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_departement', 'mois', 'annee', 'status', 'montant', 'id_action','description'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function create_budget_CRM($data)
    {
        return $this->insert($data);
    }
    public function getBudgetEnAttente()
    {
        return $this->where('status', 'En attente')->findAll();
    }
    public function validateBudget($id)
    {
        return $this->update($id, ['status' => 'Terminée']);
    }

    public function getBudgetById($id)
    {
        return $this->find($id);
    }
    

    
}
