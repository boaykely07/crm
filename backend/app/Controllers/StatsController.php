<?php

namespace App\Controllers;

use App\Models\VentesModel;
use App\Models\StocksModel;
use App\Models\ClientsModel;
use App\Models\ProduitsModel;

class StatsController extends BaseController
{
    public function index()
    {
        $ventesModel = new VentesModel();
        $stocksModel = new StocksModel();
        $clientsModel = new ClientsModel();
        $produitsModel = new ProduitsModel();

        // Données pour les graphiques
        $ventesParMois = $ventesModel->getVentesParMois();
        $topProduits = $ventesModel->getTopProduits();
        $clientsParCategorie = $clientsModel->getClientsParCategorie();
        $stockParCategorie = $stocksModel->getStockParCategorie();

        // Calcul du chiffre d'affaires total
        $chiffreAffaires = $ventesModel->getChiffreAffaires();
        $chiffreAffairesAnnuel = $ventesModel->getChiffreAffairesAnnuel();

        $data = [
            'ventes_par_mois' => $ventesParMois,
            'top_produits' => $topProduits,
            'clients_par_categorie' => $clientsParCategorie,
            'stock_par_categorie' => $stockParCategorie,
            'chiffre_affaires' => $chiffreAffaires,
            'chiffre_affaires_annuel' => $chiffreAffairesAnnuel,
            // Données formatées pour JS
            'stats_json' => json_encode([
                'ventes_par_mois' => $ventesParMois,
                'top_produits' => $topProduits,
                'clients_par_categorie' => $clientsParCategorie,
                'stock_par_categorie' => $stockParCategorie,
                'chiffre_affaires' => $chiffreAffaires,
                'chiffre_affaires_annuel' => $chiffreAffairesAnnuel
            ])
        ];

        return view('crm/stats', $data);
    }
}