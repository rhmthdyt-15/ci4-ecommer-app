<?php

namespace App\Models;

use CodeIgniter\Model;

class RegisterModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'email', 'password', 'role', 'is_active'];
    protected $useTimestamps = true;


    protected $session;

    public function __construct(){
        parent::__construct();
        $this->session = \Config\Services::session();
    }

    public function getDefaultValues() {
        return [
            'name' => '',
            'email' => '',
            'password' => '',
            'role' => '',
            'is_active' => '',
        ];
    }


    public function hashEncrypt($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function run($input) {
        $data = [
            'name' => $input['name'],
            'email' => strtolower($input['email']),
            'password' => $this->hashEncrypt($input['password']),
            'role' => 'member',
        ];

        $user = $this->insert($data);

        $sess_data = [
            'id' => $user,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'is_login' => true,
        ];

        $this->session->set($sess_data);
        return true;
    }
}