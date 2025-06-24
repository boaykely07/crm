<?php

namespace Tests\App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\UtilisateursModel;

class UtilisateursModelTest extends CIUnitTestCase
{
    public function testCreerUtilisateurAdmin()
    {
        $model = new UtilisateursModel();

        $data = [
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@gmail.com',
            'mot_de_passe' => 'Admin123',
            'role' => 'admin',
            'id_departement' => 6
        ];

        $userId = $model->creerUtilisateur($data);

        $this->assertIsInt($userId);
        $user = $model->find($userId);

        $this->assertEquals('Admin', $user['nom']);
        $this->assertEquals('Super', $user['prenom']);
        $this->assertEquals('admin@gmail.com', $user['email']);
        $this->assertEquals('admin', $user['role']);
        $this->assertEquals(6, $user['id_departement']);
        $this->assertTrue(password_verify('AdminPassword123!', $user['mot_de_passe']));
    }
}
