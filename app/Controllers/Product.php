<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Product extends BaseController
{
    protected $product;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->product = new ProductModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Admin: Produk',
            'content' => $this->product->select('products.id, products.title AS products_title, products.image,
                        products.price, products.is_available, category.title AS category_title')
                        ->join('category', 'category.id = products.id_category')
                        ->paginate(5, 'products'),
            'pager' => $this->product->pager,
            'total_rows' => $this->product->countAllResults()
        ];

        return view('pages/product/index', $data);
    }

    public function create()
    {
        $categories = $this->product->getCategories();

        $categoryOptions = [];
        foreach ($categories as $category) {
            $categoryOptions[$category['id']] = $category['title'];
        }

        session()->set('categories', $categoryOptions);

        if (!$this->request->getPost()) {
            $input = (object) $this->product->getDefaultValues();
        } else {
            $input = (object) $this->product->getPost();
        }

        $data = [
            'title' => 'Tambah Produk',
            'input' => $input,
            'form_action' => base_url('product/store'),
            'categories' => $categoryOptions
        ];

        return view('pages/product/form', $data); 
    }

    public function store()
    {
        $input = $this->request->getPost();

        if (!isset($input['is_available'])) {
            $input['is_available'] = 0;
        }

        $validationRules = [
            'id_category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori harus diisi.'
                ]
            ],
            'title' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Nama Produk harus diisi.'
                ]
            ],
            'description' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Deskripsi harus diisi.'
                ]
            ],
            'price' => [
                'rules' => 'trim|required|numeric',
                'errors' => [
                    'required' => 'Harga harus diisi.',
                    'numeric' => 'Harga harus berupa angka.'
                ]
            ],
            'is_available' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Ketersediaan harus dipilih.'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $data = [
                'title' => 'Tambah Produk',
                'input' => (object) $input,
                'form_action' => base_url('product/store'),
                'errors' => $this->validator->getErrors() 
            ];
    
            return view('pages/product/form', $data);
        }

        $slug = url_title($input['title'], '-', true);
        $input['slug'] = $slug;

        $this->product->insert($input);

        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $imageName = $slug . '-' . date('YmdHis');
            $uploadResult = $this->product->uploadImage('image', $imageName, $file);
    
            if ($uploadResult) {
                $this->product->update($this->product->getInsertID(), ['image' => $uploadResult]);
            } else {
                session()->setFlashdata('error', 'Upload gambar gagal: ' . $file->getErrorString());
                return redirect()->to(base_url('product/create'));
            }
        } else {
            $input['image'] = base_url('images/product/default.png'); 
        }

        $this->session->setFlashdata('success', 'Data berhasil tersimpan!');
        return redirect()->to(base_url('product'));
    
    }
    


    public function edit($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return redirect()->to(base_url('product'));
        }

        $categories = $this->product->getCategories();
        $categoryOptions = [];
        foreach ($categories as $category) {
            $categoryOptions[$category['id']] = $category['title'];
        }
        session()->set('categories', $categoryOptions);

        $data = [
            'title' => 'Edit Produk',
            'input' => (object) $product,
            'form_action' => base_url('product/update/' . $id),
            'categories' => $categoryOptions
        ];

        return view('pages/product/form', $data);
    }

    public function update($id)
    {
        $input = $this->request->getPost();

        if (!isset($input['is_available'])) {
            $input['is_available'] = 0;
        }

        $validationRules = [
            'id_category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih Kategori'
                ]
            ],
            'title' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Nama produk harus diisi.'
                ]
            ],
            'description' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Deskripsi harus diisi.'
                ]
            ],
            'price' => [
                'rules' => 'trim|required|numeric',
                'errors' => [
                    'required' => 'Harga harus diisi.',
                    'numeric' => 'Harga harus beruppa angka'
                ]
            ],
            'is_available' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Ketersediaan harus dipilih.'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $categories = $this->product->getCategories();
            $categoryOptions = [];
            foreach ($categories as $category) {
                $categoryOptions[$category['id']] = $category['title'];
            }
            session()->set('categories', $categoryOptions);

            $data = [
                'title' => 'Edit Produk',
                'input' => (object) $input,
                'form_action' => base_url('product/update/' . $id),
                'errors' => $this->validator->getErrors(),
                'categories' => $categoryOptions
            ];

            return view('pages/product/form', $data);
        }

        $slug = url_title($input['title'], '-', true);
        $input['slug'] = $slug;

        $product = $this->product->find($id);
        $oldImageName = $product['image'];

        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $imageName = $slug . '-' . date('YmdHis');
            $uploadResult = $this->product->uploadImage('image', $imageName, $file);
    
            if ($uploadResult) {
                $input['image'] = $uploadResult;
    
    
                if ($oldImageName && $oldImageName !== 'default.png') {
        
                    $this->product->deleteImage($oldImageName);
                }
            } else {
                session()->setFlashdata('error', 'Upload gambar gagal: ' . $file->getErrorString());
                return redirect()->to(base_url('product/edit/' . $id));
            }
        } else {

            $input['image'] = $oldImageName;
        }

        $this->product->update($id, $input);

        $this->session->setFlashdata('success', 'Data berhasil diupdate!');
        return redirect()->to(base_url('product'));
    }

    public function delete($id)
    {
        $product = $this->product->find($id);

        if (!$product) {
            return redirect()->to(base_url('product'));
        }

        if ($product['image'] && $product['image'] !== 'default.png') {
            $this->product->deleteImage($product['image']);
        }

        $this->product->delete($id);

        $this->session->setFlashdata('success', 'Data berhasil dihapus!');
        return redirect()->to(base_url('product'));
    }

}