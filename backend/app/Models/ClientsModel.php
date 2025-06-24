<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientsModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'email', 'telephone', 'id_categorie', 'adresse'];
    protected $useTimestamps = false;

    public function countAllClients()
    {
        return $this->countAll();
    }

    // Répartition des clients par catégorie
    public function getClientsParCategorie()
    {
        return $this->select('categoriesClient.nom, COUNT(clients.id) AS nombre')
                    ->join('categoriesClient', 'categoriesClient.id = clients.id_categorie')
                    ->groupBy('categoriesClient.id')
                    ->findAll();
    }

    // Vérifie le mot de passe hashé
    public function verifyPassword($email, $password)
    {
        $client = $this->where('email', $email)->first();
        if ($client && password_verify($password, $client['mot_de_passe'])) {
            return $client;
        }
        return false;
    }
}