<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginModel;
use CodeIgniter\Validation\Validation;

class Login extends BaseController
{
    protected $session;
    protected $login;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->login = new LoginModel;
        $is_login = $this->session->get('is_login');

        if ($is_login) {
            return redirect()->to(base_url());
        }
        
    }
    public function index()
    {
        $data = [];
        // $data['title'] = 'Login';

        $rules = [
            'email' => [
                'label' => 'E-Mail',
                'rules' => 'trim|required|valid_email',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'valid_email' => 'Format {field} tidak valid'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if ($this->request->getPost()) {
            if ($this->validate($rules)) {
                $input = (Object) $this->request->getPost();

                if ($this->login->run($input)) {
                    $this->session->setFlashdata('success', 'Berhasil melakukan login.');
                    return redirect()->to(base_url());
                } else {
                    $this->session->setFlashdata('errors', 'Email atau password salah atau akan anda sedang tidak aktif.');
                    return redirect()->to(base_url('login'));
                }   
            } else {
                $data['validation'] = $this->validator; 
            }
        }
        $data['title'] = 'Login';
        
        return view('pages/auth/login', $data);
    }
}