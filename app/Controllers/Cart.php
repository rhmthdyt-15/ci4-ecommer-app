<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\ProductModel;

class Cart extends BaseController
{
    private $id;

    protected $session;
    protected $cartModel;
    protected $productModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->cartModel = new CartModel;
        $this->productModel = new ProductModel;
        $is_login = $this->session->get('is_login');
        $this->id = $this->session->get('id');

        if (!$is_login) {
            return redirect()->to(base_url('/'));
        }
    }
    
    public function index()
    {
        
        $data = [
            'title' => 'Keranjang Belanja',
            'content' => $this->cartModel->select([
                            'cart.id', 'cart.qty', 'cart.subtotal', 
                            'products.title AS product_title', // Menggunakan alias untuk mengakses kolom title pada tabel 'product'
                            'products.image', 'products.price'])
                            ->join('products', 'products.id = cart.id_product')
                            ->where('cart.id_user', $this->id)
                            ->get()
        ];

        $subtotals = [];
        foreach ($data['content']->getResult() as $row) {
            $subtotals[] = $row->subtotal;
        }
        $totalSubtotal = array_sum($subtotals);
    
        $data['totalSubtotal'] = $totalSubtotal;
        
        return view('pages/cart/index', $data);
    }

  
    public function add()
    {
        // if (!$this->session->has('id_user')) {
        //     $this->session->setFlashdata('error', 'Silakan login terlebih dahulu.');
        //     return redirect()->to(base_url('login'));
        // }
        if (!isset($_SESSION['id_user'])) {
            redirect()->to(base_url('login'));
        }

        
        if (!$this->request->getPost() ||  $this->request->getPost('qty') < 1) {
            $this->session->setFlashdata('error', 'Kuantitas produk tidak boleh kosong!');
            return redirect()->to(base_url());
        } else {
            $input = (object) $this->request->getPost();

            if ($input->id_product > 0) {
                $product = $this->productModel->where('id', $input->id_product)->first();
                $subtotal = $product['price'] * $input->qty;
            } else {
                $this->session->setFlashdata('error', 'ID produk tidak valid!');
                return redirect()->to(base_url());
            }

            $cart = $this->cartModel->where('id_user', $this->id)->where('id', $input->id_product)->first();

            if ($cart) {
                $data = [
                    'qty' => $cart['qty'] + $input->qty,
                    'subtotal' => $cart['subtotal'] + $subtotal
                ];
            
                if ($this->cartModel->update($cart['id'], $data)) {
                    $this->session->setFlashdata('success', 'Produk berhasil ditambahkan!');
                } else {
                    $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan.');
                }
            
                return redirect()->to(base_url(''));
            }
            

            $data = [
                'id_user' => $this->id,
                'id_product' => $input->id_product,
                'qty' => $input->qty,
                'subtotal' => $subtotal
            ];

            if ($this->cartModel->insert($data)) {
                $this->session->setFlashdata('success', 'Produk berhasil ditambahkan!');
            } else {
                $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan.');
            }

            return redirect()->to(base_url(''));
        }
    }

    public function update($id)
    {
        if (!$this->request->getPost() || $this->request->getPost('qty') < 1) {
            $this->session->setFlashdata('error', 'Kuantitas produk tidak boleh kosong!');
            return redirect()->to(base_url('cart'));
        }

        $cartData = $this->cartModel->where('id', $id)->first();
        if (!$cartData) {
            $this->session->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to(base_url('cart'));
        }

        $productData = $this->productModel->where('id', $cartData['id_product'])->first();
        if (!$productData) {
            $this->session->setFlashdata('error', 'Data produk tidak ditemukan');
            return redirect()->to(base_url('/'));
        }

        $input = (object) $this->request->getPost();
        $subtotal = $input->qty * $productData['price'];
        $cart = [
            'qty' => $input->qty,
            'subtotal' => $subtotal
        ];

        if ($this->cartModel->update($id, $cart)) {
            $this->session->setFlashdata('success', 'Produk berhasil ditambahkan!');
        } else {
            $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan.');
        }

        return redirect()->to(base_url('cart'));
    }

    public function delete($id)
    {
        if ($this->request->getMethod() === 'post') {
            $this->cartModel->delete($id);

            $this->session->setFlashdata('success', 'Data Berhasil di hapus!');
        }

        return redirect()->to(base_url('cart'));
    }
    
}