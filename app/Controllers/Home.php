<?php

namespace App\Controllers;

use App\Models\HomeModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    protected $home;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->home = new HomeModel();
    }
    public function index()
    {
        $searchTerm = session('search');
        $data = [
            'title' => 'Homepage',
            'content' => $this->home->select('products.id, products.title AS products_title,
                        products.description, products.image,
                        products.price, products.is_available, category.title AS category_title, category.slug AS category_slug') // Perbaikan pada bagian ini
                        ->join('category', 'category.id = products.id_category')
                        ->where('products.is_available', 1)
                        ->paginate(4, 'products'),
            'pager' => $this->home->pager,
            'total_rows' => $this->home->where('products.is_available', 1)->countAllResults(),
            'search_term' => $searchTerm
        ];
    
        return view('pages/home/index', $data);
    }
    
}