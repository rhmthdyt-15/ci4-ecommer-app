<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table            = 'users';
    protected $useTimestamps    = true;
    protected $session;

    public function __construct() {
        parent::__construct();
        $this->session = \Config\Services::session();
    }

    public function getDefaultValues() {
        return [
            'email' => '',
            'password' => ''
        ];
    }

    public function run($input) {
        $query = $this->where('email', strtolower($input->email))
            ->where('is_active', 1)
            ->first();
        
        if (!empty($query) && password_verify($input->password, $query['password'])) {
            $sess_data = [
                'id' => $query['id'],
                'name' => $query['name'],
                'email' => $query['email'],
                'role' => $query['role'],
                'is_login' => true,
            ];

            $this->session->set($sess_data);
            return true;
        }
        return false;
    }
   
}