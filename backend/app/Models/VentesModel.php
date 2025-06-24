<?php

namespace App\Models;

use CodeIgniter\Model;

class VentesModel extends Model
{
    protected $table = 'ventes';
    protected $primaryKey = 'id';

    public function getVentesCeMois()
    {
        return $this->where('MONTH(date_vente)', date('m'))
                    ->where('YEAR(date_vente)', date('Y'))
                    ->countAllResults();
    }

    public function getTotalVentes()
    {
        return $this->selectSum('total')->get()->getRow()->total ?? 0;
    }

    // Évolution des ventes par mois (derniers 12 mois)
    public function getVentesParMois()
    {
        return $this->select("DATE_FORMAT(date_vente, '%Y-%m') AS mois, SUM(total) AS total")
                    ->groupBy("DATE_FORMAT(date_vente, '%Y-%m')")
                    ->orderBy('mois', 'ASC')
                    ->findAll();
    }

    // Top 5 produits les plus vendus
    public function getTopProduits()
    {
        return $this->db->table('detailsVentes')
                        ->select('produits.nom, SUM(detailsVentes.quantite) AS quantite')
                        ->join('produits', 'produits.id = detailsVentes.id_produit')
                        ->groupBy('produits.id')
                        ->orderBy('quantite', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
    }

    public function getChiffreAffaires()
    {
        return $this->selectSum('total')
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    }

    public function getChiffreAffairesAnnuel()
    {
        $currentYear = date('Y');
        return $this->selectSum('total')
                    ->where('YEAR(date_vente)', $currentYear)
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    }
}