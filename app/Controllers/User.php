<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{

    protected $session;
    protected $user;
    protected $validation;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->user = new UserModel();
        $is_login = $this->session->get('is_login');

        if ($is_login) {
            return redirect()->to(base_url());
        }
    }
    public function index()
    {
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
    
        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $this->session->set('keyword', $keyword);
            $user = $this->user->search($keyword)->paginate(5, 'users'); // Menggunakan method 'search' pada model
        } else {
            $this->session->remove('keyword');
            $user = $this->user->paginate(5, 'users');
        }
    
        $data = [
            'title' => 'Admin: User',
            'user' => $user,
            'pager' => $this->user->pager,
            'currentPage' => $currentPage
        ];
    
        return view('pages/user/index', $data);
    }
    

    public function reset()
    {
        $this->session->remove('keyword');
        return redirect()->to(base_url('user'));
    }

    public function create()
    {
        $roles = [
            'member' => 'Member',
            'admin' => 'Admin'
        ];

        if (!$this->request->getPost()) {
            $input = (object) $this->user->getDefaultValues();
        } else {
            $input = (object) $this->user->getPost();
        }

        $data = [
            'title' => 'Tambah User',
            'validation' => \Config\Services::validation(),
            'form_action' => 'user/store',
            'input' => $input,
            'roles' => $roles
        ];

        return view('pages/user/form', $data);
    }

    public function store()
    {
        $input = $this->request->getPost(); 
        
        $roles = [
            'member' => 'Member',
            'admin' => 'Admin'
        ];
    
        $validationRules = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus diisi.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi.',
                    'valid_email' => 'Email harus berupa alamat email yang valid.',
                    'is_unique' => 'Email sudah digunakan oleh pengguna lain.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password harus diisi.',
                    'min_length' => 'Password minimal 8 karakter.'
                ]
            ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Role harus dipilih.'
                ]
            ],
            'is_active' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status harus dipilih.'
                ]
            ],
        ];
    
        if (!$this->validate($validationRules)) {
            $data = [
                'title' => 'Tambah User',
                'input' => (object) $input,
                'form_action' => base_url('user/store'),
                'roles' => $roles,
                'errors' => $this->validator->getErrors() 
            ];
    
            return view('pages/user/form', $data);
        }
    
        $fileImage = $this->request->getFile('image');
        if ($fileImage->isValid() && !$fileImage->hasMoved()) {
            $newImageName = $fileImage->getRandomName();
            $fileImage->move('./images/user', $newImageName);
            $input['image'] = $newImageName;
        } else {
            unset($input['image']);
        }

        $password = $this->request->getPost('password');
    
        // Hash password sebelum menyimpannya
        $input['password'] = $this->user->hashEncrypt($password);
    
        $this->user->save($input);
    
        return redirect()->to('user')->with('success', 'User berhasil ditambahkan.');
    }
    
    

    public function edit($id)
    {
        $roles = [
            'member' => 'Member',
            'admin' => 'Admin'
        ];

        $user = $this->user->find($id);
        if (!$user) {
            return redirect()->to(base_url('user'));
        }

        $data = [
            'title' => 'Edit User',
            'input' => (object) $user,
            'form_action' => base_url('user/update/' . $id),
            'roles' => $roles
        ];

        return view('pages/user/form', $data);
    }

    public function update($id)
    {
        $input = $this->request->getPost();

        $roles = [
            'member' => 'Member',
            'admin' => 'Admin'
        ];

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
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Role harus dipilih.'
                ]
            ],
            'is_active' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status harus dipilih.'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            $user = $this->user->find($id);

            $data = [
                'title' => 'Edit User',
                'input' => (object) $input,
                'form_action' => base_url('user/update/' . $id),
                'errors' => $this->validator->getErrors(),
                'roles' => $roles
            ];

            return view('pages/user/form', $data);
        }

        // Mengambil password dari input
        $password = $this->request->getPost('password');

        // Hash password sebelum menyimpannya
        $input['password'] = $this->user->hashEncrypt($password);

        $user = $this->user->find($id);
        $oldImageName = $user['image'];

        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $imageName = $user['name'] . '-' . date('YmdHis');
            $uploadResult = $this->user->uploadImage('image', $imageName, $file);

            if ($uploadResult) {
                $input['image'] = $uploadResult;

                if ($oldImageName && $oldImageName !== 'avatar-default.jpg') {
                    $this->user->deleteImage($oldImageName);
                }
            } else {
                session()->setFlashdata('error', 'Upload gambar gagal: ' . $file->getErrorString());
                return redirect()->to(base_url('user/edit/' . $id));
            }
        } else {
            $input['image'] = $oldImageName;
        }

        $this->user->update($id, $input);

        $this->session->setFlashdata('success', 'Data berhasil diupdate!');
        return redirect()->to(base_url('user'));
    }


    public function delete($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return redirect()->to(base_url('user'));
        }

        // Hapus gambar terkait dari folder jika bukan gambar default
        if ($user['image'] && $user['image'] !== 'avatar-default.jpg') {
            $this->user->deleteImage($user['image']);
        }

        $this->user->delete($id);

        $this->session->setFlashdata('success', 'Data berhasil dihapus!');
        return redirect()->to(base_url('user'));
    }
}