<?php
namespace App\Controllers;

use App\Models\ClientsModel;
use CodeIgniter\Controller;

class ClientAuthController extends Controller
{
    public function loginPage()
    {
        return view('auth/clientAuth/login');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('mot_de_passe');
        $clientModel = new ClientsModel();
        $client = $clientModel->verifyPassword($email, $password);
        if ($client) {
            session()->set([
                'client_id' => $client['id'],
                'client_nom' => $client['nom'],
                'isClientLoggedIn' => true
            ]);
            return redirect()->to('/client/dashboard');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect');
        }
    }

    public function logout()
    {
        session()->remove(['client_id', 'client_nom', 'isClientLoggedIn']);
        return redirect()->to('/client/login');
    }
} 