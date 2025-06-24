<?php
namespace App\Controllers;

use App\Models\MessageClientModel;
use CodeIgniter\Controller;

class ClientController extends Controller
{
    public function dashboard()
    {
        if (!session()->get('isClientLoggedIn')) {
            return redirect()->to('/client/login');
        }
        $clientId = session()->get('client_id');
        $messageModel = new MessageClientModel();
        $nbMessages = $messageModel->where('id_client', $clientId)->countAllResults();
        // Tu peux ajouter d'autres stats ici
        $data = [
            'nbMessages' => $nbMessages
        ];
        return view('client/dasboard', $data);
    }
}
