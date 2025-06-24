<?php

namespace App\Controllers;

use App\Models\UtilisateursModel;
use App\Models\DepartementsModel;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;
    
    protected $utilisateursModel;

    public function __construct()
    {
        $this->utilisateursModel = new UtilisateursModel();
    }

    public function index(): string
    {
        return view('welcome_message');
    }

    // Affiche la page de connexion
    public function loginPage()
    {
        return view('auth/login');
    }

    // Affiche la page d'inscription
    public function registerPage()
    {
        $departementsModel = new DepartementsModel();
        $departements = $departementsModel->findAll();
        return view('auth/register', ['departements' => $departements]);
    }

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'mot_de_passe' => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Email ou mot de passe invalide.');
            return redirect()->back();
        }

        $email = $this->request->getVar('email');
        $mot_de_passe = $this->request->getVar('mot_de_passe');

        $utilisateur = $this->utilisateursModel->verifierIdentifiants($email, $mot_de_passe);

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $session = session();
            unset($utilisateur['mot_de_passe']);
            $session->set('utilisateur', $utilisateur);

            // Redirection basée sur le rôle
            switch ($utilisateur['role']) {
                case 'admin':
                    return redirect()->to(site_url('admin'));
                case 'agent':
                    return redirect()->to(site_url('agent/dashboard'));
                default:
                    return redirect()->to(site_url('dashboard'));
            }
        }

        session()->setFlashdata('error', 'Email ou mot de passe incorrect');
        return redirect()->back();
    }

    // Page d'accueil après connexion
    public function dashboard()
    {
        $user = session()->get('utilisateur');
        if (!$user) {
            return redirect()->to(site_url('login'));
        }

        // Redirection basée sur le rôle
        switch ($user['role']) {
            case 'admin':
                return redirect()->to(site_url('admin'));
            case 'agent':
                return redirect()->to(site_url('agent/dashboard'));
            default:
                return view('vue-annuel', ['user' => $user]);
        }
    }

    public function register()
    {
        log_message('debug', 'Données reçues pour inscription : ' . json_encode($this->request->getRawInput()));

        $rules = [
            'nom' => 'required|min_length[3]',
            'prenom' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[utilisateurs.email]',
            'mot_de_passe' => 'required|min_length[8]',
            'id_departement' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getVar('nom'),
            'prenom' => $this->request->getVar('prenom'),
            'email' => $this->request->getVar('email'),
            'mot_de_passe' => $this->request->getVar('mot_de_passe'),
            'role' => 'utilisateur',
            'id_departement' => $this->request->getVar('id_departement')
        ];

        if ($this->utilisateursModel->creerUtilisateur($data)) {
            session()->setFlashdata('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');
            return redirect()->to(site_url('login'));
        }

        session()->setFlashdata('error', 'Erreur lors de la création du compte');
        return redirect()->back();
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        
        return redirect()->to(site_url('login'));
    }

    public function getAllUsers()
    {
        $users = $this->utilisateursModel->recupererTousUtilisateurs();
        return $this->respond([
            'status' => 200,
            'users' => $users
        ]);
    }
    
}
