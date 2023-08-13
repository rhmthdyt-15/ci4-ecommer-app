<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HomeModel;

class Shop extends BaseController
{
    protected $home;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->home = new HomeModel();
    }

    public function index($sortby)
    {
        $perPage = 4; // Jumlah data per halaman

        $data = [
            'title' => 'Belanja',
            'content' => $this->home->select('products.id, products.title AS products_title,
                        products.description, products.image,
                        products.price, products.is_available, category.title AS category_title, category.slug AS category_slug')
                        ->join('category', 'category.id = products.id_category')
                        ->where('products.is_available', 1)
                        ->orderBy('products.price', $sortby)
                        ->paginate($perPage, 'products'), // Perbaikan pada bagian ini
            'pager' => $this->home->pager,
            'total_rows' => $this->home->where('products.is_available', 1)->countAllResults()
        ];

        return view('pages/home/index', $data);
    }

    public function category($category = null, $sortby = null)
    {
        $perPage = 4; // Jumlah data per halaman
    
        $query = $this->home->select('products.id, products.title AS products_title,
                        products.description, products.image,
                        products.price, products.is_available, category.title AS category_title, category.slug AS category_slug')
                        ->join('category', 'category.id = products.id_category')
                        ->where('products.is_available', 1);
    
        if ($category !== null) {
            $query->where('category.slug', $category);
        }
    
        if ($sortby !== null) {
            $query->orderBy('products.price', $sortby);
        }
    
        $data = [
            'title' => 'Admin: Produk',
            'content' => $query->paginate($perPage, 'products'),
            'pager' => $this->home->pager,
            'total_rows' => $this->home->where('products.is_available', 1)->countAllResults(),
            'selected_category' => $category // Menambahkan informasi kategori yang dipilih
        ];
    
        return view('pages/home/index', $data);
    }

    public function search()
    {
        $perPage = 4; // Jumlah data per halaman
        $searchTerm = $this->request->getVar('search'); // Mengambil parameter pencarian dari URL

        $query = $this->home->select('products.id, products.title AS products_title,
                        products.description, products.image,
                        products.price, products.is_available, category.title AS category_title, category.slug AS category_slug')
                        ->join('category', 'category.id = products.id_category')
                        ->where('products.is_available', 1)
                        ->like('products.title', $searchTerm); // Menambahkan kondisi pencarian

        $data = [
            'title' => 'Admin: Produk',
            'content' => $query->paginate($perPage, 'products'),
            'pager' => $this->home->pager,
            'total_rows' => $this->home->where('products.is_available', 1)->countAllResults(),
            'selected_category' => null, // Tidak ada kategori yang dipilih dalam pencarian
            'search_term' => $searchTerm // Menambahkan informasi pencarian
        ];

        return view('pages/home/index', $data);
    }

    
    
    
    
    
}