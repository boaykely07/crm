<?php
namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UtilisateursModel;
use App\Models\GroupeModel;

class TicketController extends BaseController
{
    protected $ticketModel;
    protected $utilisateurModel;
    protected $groupeModel;
    protected $validStatuses = ['ouvert', 'en_cours', 'resolu', 'ferme'];

    // Transitions autorisées simplifiées et plus permissives
    protected $allowedTransitions = [
        'ouvert' => ['en_cours', 'resolu'],
        'en_cours' => ['resolu', 'ferme', 'ouvert'],
        'resolu' => ['ferme', 'en_cours'],
        'ferme' => ['en_cours', 'resolu', 'ouvert']
    ];

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->utilisateurModel = new UtilisateursModel();
        $this->groupeModel = new GroupeModel();
    }

    protected function validateStatus($status)
    {
        return in_array($status, $this->validStatuses);
    }

    protected function validateTransition($currentStatus, $newStatus)
    {
        if (!isset($this->allowedTransitions[$currentStatus])) {
            return false;
        }
        return in_array($newStatus, $this->allowedTransitions[$currentStatus]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        if (empty($status) || !$this->validateStatus($status)) {
            return redirect()->to('/agent/mes-tickets')->with('error', 'Statut invalide.');
        }
        $ticket = $this->ticketModel->find($id);
        if (!$ticket) {
            return redirect()->to('/agent/mes-tickets')->with('error', 'Ticket non trouvé.');
        }
        if ($ticket['statut'] === $status) {
            return redirect()->to('/agent/mes-tickets')->with('success', 'Le ticket a déjà ce statut.');
        }
        if (!$this->validateTransition($ticket['statut'], $status)) {
            return redirect()->to('/agent/mes-tickets')->with('error', 'Transition de statut non autorisée.');
        }
        $updateData = [
            'statut' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $userId = session()->get('id');
        $userRole = session()->get('role');
        if ($userRole === 'agent' && $status === 'en_cours' && empty($ticket['id_agent'])) {
            $updateData['id_agent'] = $userId;
        }
        if ($this->ticketModel->update($id, $updateData)) {
            return redirect()->to('/agent/mes-tickets')->with('success', 'Statut du ticket mis à jour avec succès.');
        }
        return redirect()->to('/agent/mes-tickets')->with('error', 'Erreur lors de la mise à jour du statut.');
    }

    // Méthode pour débugger les routes
    public function testRoute($id)
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Route fonctionnelle',
            'id' => $id,
            'method' => $this->request->getMethod()
        ]);
    }
}
?>