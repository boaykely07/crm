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
        $lastMessage = $messageModel->where('id_client', $clientId)->orderBy('date_message', 'DESC')->first();
        $data = [
            'nbMessages' => $nbMessages,
            'lastMessage' => $lastMessage
        ];
        return view('client/dasboard', $data);
    }

    public function listeMessages()
    {
        if (!session()->get('isClientLoggedIn')) {
            return redirect()->to('/client/login');
        }
        $clientId = session()->get('client_id');
        $messageModel = new MessageClientModel();
        $messages = $messageModel->where('id_client', $clientId)->orderBy('date_message', 'DESC')->findAll();
        return view('client/listeMessage', ['messages' => $messages]);
    }

    public function addMessage()
    {
        if (!session()->get('isClientLoggedIn')) {
            return redirect()->to('/client/login');
        }
        $clientId = session()->get('client_id');
        $message = trim($this->request->getPost('message'));
        if (empty($message)) {
            return redirect()->back()->with('error', 'Le message est obligatoire.');
        }
        $fichierUrl = null;
        $file = $this->request->getFile('fichier');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($file->getClientMimeType() !== 'application/pdf') {
                return redirect()->back()->with('error', 'Le fichier doit être un PDF.');
            }
            $newName = uniqid('msg_').'.pdf';
            $file->move(ROOTPATH.'public/upload', $newName);
            $fichierUrl = $newName;
        }
        $data = [
            'id_client' => $clientId,
            'message' => $message,
            'fichier_url' => $fichierUrl
        ];
        $model = new \App\Models\MessageClientModel();
        $model->insert($data);
        return redirect()->to('/client/liste-messages')->with('success', 'Message envoyé avec succès.');
    }

    public function commentaireMessage($id)
    {
        if (!session()->get('isClientLoggedIn')) {
            return redirect()->to('/client/login');
        }
        $messageModel = new \App\Models\MessageClientModel();
        $commentModel = new \App\Models\CommentaireMessageModel();
        $message = $messageModel->find($id);
        if (!$message) {
            return redirect()->to('/client/liste-messages')->with('error', 'Message non trouvé.');
        }
        $commentaires = $commentModel->where('id_message_client', $id)->orderBy('date_commentaire', 'ASC')->findAll();
        return view('client/commentaireMessage', [
            'message' => $message,
            'commentaires' => $commentaires
        ]);
    }

    public function addCommentaireMessage($id)
    {
        if (!session()->get('isClientLoggedIn')) {
            return redirect()->to('/client/login');
        }
        $commentModel = new \App\Models\CommentaireMessageModel();
        $commentaire = trim($this->request->getPost('commentaire'));
        if ($commentaire) {
            $commentModel->insert([
                'id_message_client' => $id,
                'id_utilisateur' => session('client_id'),
                'auteur' => 'client',
                'commentaire' => $commentaire
            ]);
            return redirect()->to('/client/commentaire-message/'.$id)->with('success', 'Commentaire ajouté.');
        } else {
            return redirect()->to('/client/commentaire-message/'.$id)->with('error', 'Le commentaire ne peut pas être vide.');
        }
    }
}
