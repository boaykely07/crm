<?php

namespace App\Controllers;

use App\Models\DetailsRealisationModel;

class DetailsRealisationController extends BaseController
{
    public function index($id_realisation)
    {
        $detailsModel = new DetailsRealisationModel();

        // Fetch details by realization ID
        $details = $detailsModel->getDetailsByRealisationId($id_realisation);

        if (empty($details)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun détail trouvé pour cette réalisation.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'details' => $details
        ]);
    }

    public function delete($id_realisation)
    {
        $detailsModel = new DetailsRealisationModel();

        try {
            // Delete details by realization ID
            $deleted = $detailsModel->deleteDetailsByRealisationId($id_realisation);

            if (!$deleted) {
                throw new \Exception("Erreur lors de la suppression des détails de la réalisation.");
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Détails supprimés avec succès.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
