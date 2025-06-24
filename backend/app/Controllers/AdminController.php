<?php

namespace App\Controllers;

use App\Models\PrevisionsModel;
use App\Models\RealisationModel;
use App\Models\CRMBudgetModel;
use App\Models\ActionsModel;

class AdminController extends BaseController
{
    public function adminPage()
    {
        return view('admin/admin');
    }

    public function listePrevisionsPage()
    {
        $previsionsModel = new PrevisionsModel();
        $data['previsions'] = $previsionsModel->getPrevisionAvecDetails();

        return view('admin/admin', [
            'content' => view('admin/listePrevision', $data)
        ]);
    }

    public function listeRealisationsPage()
    {
        $realisationModel = new RealisationModel();
        $data['realisations'] = $realisationModel->getRealisationAvecDetails();

        return view('admin/admin', [
            'content' => view('admin/listeRealisation', $data)
        ]);
    }
    public function listeBudgetCRMPage()
    {
        $budgetModel = new CRMBudgetModel();
        $data['budgets'] = $budgetModel->findAll();

        return view('admin/admin', [
            'content' => view('admin/listeBudgetCRM', $data)
        ]);
    }

    public function listeTicketsPage()
    {
        $ticketModel = new \App\Models\TicketModel();
        $utilisateurModel = new \App\Models\UtilisateursModel();
        $groupeModel = new \App\Models\GroupeModel();

        $data = [
            'tickets' => $ticketModel->getTicketsWithDetails(),
            'agents' => $utilisateurModel->where('role', 'agent')->findAll(),
            'groupes' => $groupeModel->findAll()
        ];

        return view('admin/admin', [
            'content' => view('admin/listeTicket', $data)
        ]);
    }

    public function listeMessageClientPage()
    {
        $messageClientModel = new \App\Models\MessageClientModel();
        $data['messages'] = $messageClientModel->getMessagesWithClient();
        return view('admin/admin', [
            'content' => view('admin/listeMessageClient', $data)
        ]);
    }

    public function detailMessageClientPage($id)
    {
        $messageModel = new \App\Models\MessageClientModel();
        $message = $messageModel
            ->select('message_client.*, clients.nom as client_nom')
            ->join('clients', 'clients.id = message_client.id_client')
            ->where('message_client.id', $id)
            ->first();
        return view('admin/detailMessageClient', ['message' => $message]);
    }

    public function validerBudgetCRM($id)
    {
        $budgetModel = new CRMBudgetModel();
        $BudgetSelect = $budgetModel->getBudgetById($id);

        $previsionsModel = new PrevisionsModel();
        $detailsPrevisionModel = new \App\Models\DetailsPrevisionModel();

        // Check if there is a validated prevision for the same month and year
        $previsions = $previsionsModel->where([
            'id_departement' => $BudgetSelect['id_departement'],
            'mois' => $BudgetSelect['mois'],
            'annee' => $BudgetSelect['annee']
        ])->findAll();

        if (empty($previsions)) {
            return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Pas de prévisions pour cette date.');
        }

        $validatedPrevision = null;
        foreach ($previsions as $prevision) {
            if ($prevision['statut'] === 'validee') {
                $validatedPrevision = $prevision;
                break;
            }
        }

        if (!$validatedPrevision) {
            return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Il y a des prévisions mais aucune n\'est validée.');
        }

        // Check if there are CRM details in the validated prevision
        $details = $detailsPrevisionModel->where([
            'id_prevision' => $validatedPrevision['id'],
            'id_categorie' => (new \App\Models\CategoriesModel())->where('nom', 'CRM')->first()['id']
        ])->findAll();

        if (empty($details)) {
            return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Pas de détails prévisions CRM pour cette date.');
        }

        // Check if any detail montant exceeds the budget montant
        foreach ($details as $detail) {
            if ($detail['montant'] > $BudgetSelect['montant']) {
                // Log the values for debugging
                log_message('error', 'Validation échouée : detailMontant=' . $detail['montant'] . ', budgetMontant=' . $BudgetSelect['montant']);
                return redirect()->to('/admin/listeBudgetCRM')->with('error', 'Un montant des détails prévisions CRM dépasse le budget CRM.');
            }
        }

        // Validate the budget and update the action status
        $budgetModel->validateBudget($id);
        $actionModel = new ActionsModel();
        $actionModel->updateStatus($BudgetSelect['id_action'], 'Terminée');

        return redirect()->to('/admin/listeBudgetCRM')->with('success', 'Budget CRM validé avec succès.');
    }
}