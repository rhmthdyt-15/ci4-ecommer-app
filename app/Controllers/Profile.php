<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProfileModel;

class Profile extends BaseController
{
    protected $session;
    protected $profile;
    protected $validation;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->profile = new ProfileModel;
        $is_login = $this->session->get('is_login');


        if ($is_login) {
            return redirect()->to(base_url());
        }
    }
    public function index()
    {
        $currentUserId = $this->session->get('id'); 
        $data = [
            'title' => 'Profile',
            'profile' => $this->profile->getProfileById($currentUserId),
        ];
        
        return view('pages/profile/index', $data);
    }

    public function edit($id)
    {

        $profile = $this->profile->find($id);
        if (!$profile) {
            return redirect()->to(base_url('profile'));
        }

        $data = [
            'title' => 'Edit profile',
            'input' => (object) $profile,
            'form_action' => base_url('profile/update/' . $id),
        ];

        return view('pages/profile/form', $data);
    }

    public function update($id)
    {
        $input = $this->request->getPost();

        $validationRules = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus diisi.'
                ]
            ],
            'email' => [
                'rules' => "required|valid_email|is_unique[users.email,id,$id]",
                'errors' => [
                    'required' => 'Email harus diisi.',
                    'valid_email' => 'Email harus berupa alamat email yang valid.',
                    'is_unique' => 'Email sudah digunakan oleh pengguna lain.'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            $profile = $this->profile->find($id);

            $data = [
                'title' => 'Edit Profile',
                'input' => (object) $input,
                'form_action' => base_url('profile/update/' . $id),
                'errors' => $this->validator->getErrors(),

            ];

            return view('pages/profile/form', $data);
        }

        
        $profile = $this->profile->find($id);
        $oldImageName = $profile['image'];

        
        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $imageName = $profile['name'] . '-' . date('YmdHis');
            $uploadResult = $this->profile->uploadImage('image', $imageName, $file);

            if ($uploadResult) {
                $input['image'] = $uploadResult;

                
                if ($oldImageName && $oldImageName !== 'avatar-default.jpg') {
                    
                    $this->profile->deleteImage($oldImageName);
                }
            } else {
                session()->setFlashdata('error', 'Upload gambar gagal: ' . $file->getErrorString());
                return redirect()->to(base_url('profile/edit/' . $id));
            }
        } else {
            
            $input['image'] = $oldImageName;
        }

        $this->profile->update($id, $input);

        $this->session->setFlashdata('success', 'Data berhasil diupdate!');
        return redirect()->to(base_url('profile'));
    }
}