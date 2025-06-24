<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateursModel extends Model
{
    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'id_departement'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    
    // Méthode pour vérifier les identifiants
    public function verifierIdentifiants($email, $mot_de_passe)
    {
        return $this->where('email', $email)
                    ->first();
    }
    
    // Méthode pour créer un nouvel utilisateur
    public function creerUtilisateur($data)
    {
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }
    
    // Méthode pour mettre à jour un utilisateur
    public function mettreAJourUtilisateur($id, $data)
    {
        if (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }
        return $this->update($id, $data);
    }
    
    // Méthode pour récupérer tous les utilisateurs (sans mot de passe)
    public function recupererTousUtilisateurs()
    {
        return $this->select('id, nom, prenom, email, role, id_departement, created_at')
                    ->findAll();
    }
}
