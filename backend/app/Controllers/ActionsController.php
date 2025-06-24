<?php

namespace App\Controllers;

use App\Models\ActionsModel;

class ActionsController extends BaseController
{
    public function index()
    {
        $actionsModel = new ActionsModel();
        $actions = $actionsModel->getAllActions();

        return view('crm/actions', ['actions' => $actions]);
    }

    public function modifierStatut($id)
    {
        $actionsModel = new ActionsModel();

        // Récupérer l'action
        $action = $actionsModel->find($id);
        if (!$action) {
            return $this->response->setJSON(['success' => false, 'message' => 'Action introuvable.']);
        }

        // Alterner le statut
        $nouveauStatut = $action['status'] === 'En attente' ? 'Terminée' : 'En attente';

        // Mettre à jour le statut
        $actionsModel->update($id, ['status' => $nouveauStatut]);

        return $this->response->setJSON(['success' => true, 'message' => 'Statut modifié avec succès.']);
    }
}
