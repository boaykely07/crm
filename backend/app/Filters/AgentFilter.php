<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AgentFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Déboguer la session
        log_message('debug', 'Session utilisateur : ' . print_r(session()->get('utilisateur'), true));
        
        if (!session()->get('utilisateur')) {
            log_message('debug', 'Aucune session utilisateur trouvée');
            return redirect()->to('/login');
        }

        $user = session()->get('utilisateur');
        if ($user['role'] !== 'agent') {
            log_message('debug', 'Role incorrect : ' . $user['role']);
            return redirect()->to('/login')->with('error', 'Accès réservé aux agents');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}