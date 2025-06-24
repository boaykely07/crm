<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentaireMessageModel extends Model
{
    protected $table = 'commentaire_message';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_message_client', 'id_utilisateur', 'auteur', 'commentaire', 'date_commentaire'];
    protected $useTimestamps = false;
} 