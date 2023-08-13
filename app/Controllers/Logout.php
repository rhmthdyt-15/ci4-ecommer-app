<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();

        $is_login = $this->session->get('is_login');

        if (!$is_login) {
            return redirect()->to(base_url());
        }
    }
    public function index()
    {
        $sess_data = ['id', 'name', 'email', 'role', 'is_login'];

        foreach ($sess_data as $data ) {
            $this->session->remove($data);
        }

        return redirect()->to(base_url());
    }
}