<?php

namespace App\Controllers;

use App\Models\PrevisionsModel;
use App\Models\RealisationsModel;

class VueAnnuelController extends BaseController
{
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->get('utilisateur')) {
            return redirect()->to('/login');
        }

        $data = [
            'user' => session()->get('utilisateur'),
            'title' => 'Vue Annuelle'
        ];

        return view('vue-annuel', $data);
    }

    public function getFilteredData()
    {
        $departement_id = $this->request->getGet('departement_id');
        $annee = $this->request->getGet('annee');
        $mois = $this->request->getGet('mois');

        $previsionsModel = new PrevisionsModel();
        $realisationsModel = new RealisationsModel();

        $data = [
            'previsions' => $previsionsModel->getPrevisionsFiltered($departement_id, $annee, $mois),
            'realisations' => $realisationsModel->getRealisationsFiltered($departement_id, $annee, $mois)
        ];

        return $this->response->setJSON($data);
    }
}
