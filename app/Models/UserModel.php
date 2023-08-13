<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'email', 'password', 'role', 'is_active', 'image'];


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
            'image' => ''
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


    public function uploadImage($fieldName, $fileName, $file)
    {
        $uploadPath = './images/user';
    
        // Cek apakah file berhasil diupload
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $fileName . '.' . $file->getClientExtension();
            $file->move($uploadPath, $newName);
            return $newName;
        } else {
            session()->setFlashdata('error', $file->getErrorString());
            return false;
        }
    }

    public function deleteImage($fileName)
    {
        $path = './images/user/' . $fileName; // Tambahkan '/' setelah 'product'
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function search($keyword) {
        if (empty($keyword)) {
            return $this;
        }

        $this->like('name', $keyword);
        return $this;
    }

    // Dates
    protected $useTimestamps = true;
}