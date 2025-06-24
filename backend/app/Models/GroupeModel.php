<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupeModel extends Model
{
    protected $table = 'groupes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'description'];
    protected $useTimestamps = false;

    public function getGroupesWithMembers()
    {
        return $this->select('groupes.*, COUNT(utilisateurs.id) as nombre_membres')
                    ->join('utilisateurs', 'utilisateurs.id_groupe = groupes.id', 'left')
                    ->groupBy('groupes.id')
                    ->findAll();
    }

    public function getGroupeById($id)
    {
        return $this->find($id);
    }
}
