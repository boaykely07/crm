<?php

namespace App\Models;

use CodeIgniter\Model;

class StocksModel extends Model
{
    protected $table = 'stocks';
    protected $primaryKey = 'id';

    public function getProduitsStockFaible()
    {
        return $this->where('quantite <', 10)->countAllResults();
    }

    // Répartition du stock par catégorie
    public function getStockParCategorie()
    {
        return $this->db->table('stocks')
                        ->select('categorieProduit.nom, SUM(stocks.quantite) AS quantite')
                        ->join('produits', 'produits.id = stocks.id_produit')
                        ->join('categorieProduit', 'categorieProduit.id = produits.id_categorie')
                        ->groupBy('categorieProduit.id')
                        ->get()
                        ->getResultArray();
    }
}