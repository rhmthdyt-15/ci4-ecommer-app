<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegisterModel;

class Register extends BaseController
{
    protected $session;
    protected $register;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->register = new RegisterModel;
        $is_login = $this->session->get('is_login');

        if ($is_login) {
            return redirect()->to(base_url());
        }
    }

    public function index()
    {
        $data = [];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'trim|required',
                'email' => 'trim|required|valid_email|is_unique[users.email]',
                'password' => 'trim|min_length[8]',
                'password_confirmation' => 'required|matches[password]'
            ];

            $messages = [
                'name' => [
                    'required' => 'Nama harus diisi.'
                ],
                'email' => [
                    'required' => 'Email harus diisi.',
                    'valid_email' => 'Email yang dimasukkan tidak valid',
                    'is_unique'=> 'Email telah digunakan, silakan gunakan email lain.'
                ],
                'password' => [
                    'required' => 'Password harus diisi.',
                    'min_length' => 'Password minimal harus 8 karakter'
                ],
                'password_confirmation' => [
                    'required' => 'Kolom konfirmasi password harus diisi.',
                    'matches' => 'Konfirmasi password tidak sesuai dengan password.'
                ]
            ];

            $validated = $this->validate($rules, $messages);

            if (!$validated) {
                $data = [
                    'title' => 'Register',
                    'validation' => $this->validator,
                    'page' => 'pages/auth/register'
                ];
            } else {
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash((string)$this->request->getPost('password'), PASSWORD_BCRYPT),
                ];

                $this->register->run($userData);

                $this->session->setFlashdata('success', 'Berhasil melakukan registrasi');
                return redirect()->to(base_url());
            }
        } else {
            $data['title'] = 'Register';
        }

        return view('pages/auth/register', $data);
    }
}