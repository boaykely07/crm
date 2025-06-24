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
    protected $validStatuses = ['nouveau', 'en_attente', 'en_cours', 'resolu', 'ferme'];

    // Transitions autorisées simplifiées et plus permissives
    protected $allowedTransitions = [
        'nouveau' => ['en_attente', 'en_cours', 'resolu'],
        'en_attente' => ['en_cours', 'resolu', 'nouveau'],
        'en_cours' => ['resolu', 'en_attente', 'ferme'],
        'resolu' => ['ferme', 'en_cours'],
        'ferme' => ['en_cours', 'resolu']
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
        // Définir les en-têtes de sécurité
        $this->response->setHeader('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");
        $this->response->setHeader('X-Content-Type-Options', 'nosniff');
        $this->response->setHeader('X-Frame-Options', 'DENY');
        
        // Définir le type de réponse JSON
        $this->response->setContentType('application/json');
        
        try {
            // Log de démarrage
            log_message('debug', 'updateStatus appelé avec ID: ' . $id);
            
            // Vérifier si l'utilisateur est connecté
            if (!session()->get('isLoggedIn')) {
                log_message('warning', 'Utilisateur non connecté');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez vous reconnecter.',
                    'redirect' => site_url('login')
                ]);
            }

            // Vérifier la méthode HTTP
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                log_message('error', 'Méthode HTTP incorrecte: ' . $this->request->getMethod());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Méthode HTTP non autorisée'
                ]);
            }

            $status = $this->request->getPost('status');
            log_message('debug', 'Données reçues - ID: ' . $id . ', Nouveau statut: ' . $status);

            // Validation du statut
            if (empty($status) || !$this->validateStatus($status)) {
                log_message('error', 'Statut invalide ou vide: ' . $status);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Statut invalide: ' . $status
                ]);
            }

            // Récupérer le ticket
            $ticket = $this->ticketModel->find($id);
            if (!$ticket) {
                log_message('error', 'Ticket non trouvé. ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ticket non trouvé'
                ]);
            }

            log_message('debug', 'Ticket trouvé - Statut actuel: ' . $ticket['statut']);

            // Vérifier si le statut est déjà le même
            if ($ticket['statut'] === $status) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le ticket a déjà ce statut'
                ]);
            }

            // Valider la transition
            if (!$this->validateTransition($ticket['statut'], $status)) {
                log_message('error', 'Transition non autorisée: ' . $ticket['statut'] . ' -> ' . $status);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Transition de statut non autorisée de "' . $ticket['statut'] . '" vers "' . $status . '"'
                ]);
            }

            // Préparer les données de mise à jour
            $updateData = [
                'statut' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Si le ticket est pris en charge par un agent, l'assigner
            $userId = session()->get('id');
            $userRole = session()->get('role');
            
            if ($userRole === 'agent' && $status === 'en_cours' && empty($ticket['id_agent'])) {
                $updateData['id_agent'] = $userId;
                log_message('debug', 'Agent assigné au ticket: ' . $userId);
            }

            // Effectuer la mise à jour
            if ($this->ticketModel->update($id, $updateData)) {
                log_message('info', 'Statut mis à jour avec succès. ID: ' . $id . ', Nouveau statut: ' . $status);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Statut du ticket mis à jour avec succès vers "' . ucfirst(str_replace('_', ' ', $status)) . '"'
                ]);
            }

            log_message('error', 'Échec de la mise à jour en base. ID: ' . $id);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut en base de données'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Exception lors de la mise à jour du statut: ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une erreur inattendue est survenue: ' . $e->getMessage()
            ]);
        }
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