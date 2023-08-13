<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Category extends BaseController
{
    protected $categoryModel;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->categoryModel = new CategoryModel;
        $role = $this->session->get('role');

        if ($role != 'admin') {
            return redirect()->to(base_url('/'));
        }
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $this->session->set('keyword', $keyword);
            $category = $this->categoryModel->like('title', $keyword)->paginate(5, 'category');
        } else {
            $this->session->remove('keyword');
            $category = $this->categoryModel->paginate(5, 'category');
        }

        $data = [
            'title' => 'Admin: Category',
            'category' => $category,
            'pager' => $this->categoryModel->pager,
            'currentPage' => $currentPage
        ];

        return view('pages/category/index', $data);
    }


    public function reset()
    {
        $this->session->remove('keyword');
        return redirect()->to(base_url('category'));
    }


    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'validation' => \Config\Services::validation(),
            'form_action' => 'category/store',
            'input' => $this->categoryModel->getDeafaultValues(),
        ];

        return view('pages/category/form', $data);
    }

    public function store()
    {
        $rules = [
            'title' => 'trim|required',
        ];

        $errors = [
            'title' => [
                'required' => 'Kategori harus diisi.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('validatin', $this->validator);
        }

        $slug = url_title($this->request->getVar('title'), '-', true);
        $this->categoryModel->save([
            'slug' => $slug,
            'title' => $this->request->getVar('title'),
        ]);

        $this->session->setFlashdata('success', 'Data berhasil tersimpan!');
        return redirect()->to(base_url('category'));
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kategori',
            'validation' => \Config\Services::validation(),
            'content' => $this->categoryModel->find($id),
            'form_action' => "category/update/{$id}",
            'input' => $this->categoryModel->find($id)
        ];

        if (empty($data['content'])) {
            $this->session->setFlashdata('warning', 'Maaf data tidak ditemukan');
            return redirect()->to(base_url('category'));
        }

        return view('pages/category/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'title' => 'trim|required',
        ];

        $errors = [
            'title' => [
                'required' => 'Kategori harus diisi.'
            ]
        ];

        
        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('validatin', $this->validator);
        }

        $slug = url_title($this->request->getVar('title'), '-', true);
        $this->categoryModel->update($id, [
            'slug' => $slug,
            'title' => $this->request->getVar('title'),
        ]);

        $this->session->setFlashdata('success', 'Data Berhasil diperbaharui!');
        return redirect()->to(base_url('category'));
    }


    public function delete($id)
    {
        if ($this->request->getMethod() === 'post') {
            $this->categoryModel->delete($id);

            $this->session->setFlashdata('success', 'Data Berhasil di hapus!');
        }

        return redirect()->to(base_url('category'));
    }


}