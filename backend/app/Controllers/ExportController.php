<?php

namespace App\Controllers;

use App\Models\PrevisionsModel;
use App\Models\RealisationsModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportController extends BaseController
{
    public function exportPDF()
    {
        $departement_id = $this->request->getGet('departement_id');
        $annee = $this->request->getGet('annee');
        $mois = $this->request->getGet('mois');

        $previsionsModel = new PrevisionsModel();
        $realisationsModel = new RealisationsModel();

        // Récupérer les données
        $data = [
            'previsions' => $previsionsModel->getPrevisionsFiltered($departement_id, $annee, $mois !== 'all' ? $mois : null),
            'realisations' => $realisationsModel->getRealisationsFiltered($departement_id, $annee, $mois !== 'all' ? $mois : null),
            'annee' => $annee,
            'mois' => $mois !== 'all' ? date('F', mktime(0, 0, 0, $mois, 1)) : 'Tous les mois'
        ];

        // Options pour Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        // Instancier Dompdf
        $dompdf = new Dompdf($options);
        
        // Charger la vue HTML
        $html = view('pdf/rapport_financier', $data);
        
        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Configuration du papier
        $dompdf->setPaper('A4', 'portrait');

        // Rendu du PDF
        $dompdf->render();

        // Téléchargement du PDF
        $dompdf->stream('rapport_financier.pdf', ['Attachment' => false]);
    }
}
