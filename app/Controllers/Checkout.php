<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\CheckoutModel;

class Checkout extends BaseController
{
    private $id;

    protected $session;
    protected $checkoutModel;
    protected $cartModel;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->checkoutModel = new CheckoutModel();
        $this->cartModel = new CartModel();
        $is_login = $this->session->get('is_login');
        $this->id = $this->session->get('id');
        $this->db = \Config\Database::connect();

        if (!$is_login) {
            return redirect()->to(base_url('/'));
        }
    }

    public function index($input = null)
    {
        $this->checkoutModel->setTable('cart');
    
        $data['cart'] = $this->checkoutModel->getCheckoutData($this->id);
    
        if (empty($data['cart'])) {
            $this->session->setFlashdata('warning', 'Tidak ada produk di dalam keranjang.');
            return redirect()->to(base_url('/'));
        }

        $totalSubtotal = array_sum(array_column($data['cart'], 'subtotal'));
        $data['totalSubtotal'] = $totalSubtotal;
        
        $data['input'] = $input ? $input : (object) $this->checkoutModel->getDefaultValues();
        $data['title'] = 'Checkout';
    
        return view('pages/checkout/index', $data);
    }

    public function create()
    {
        if (!$_POST) {
            return redirect()->to(site_url('checkout'));
        }
    
        $rules = [
            'name' => [
                'label' => 'Nama',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'phone' => [
                'label' => 'Telepon',
                'rules' => 'trim|required|max_length[15]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'max_length' => 'Panjang {field} tidak boleh melebihi {param} karakter.'
                ]
            ],
        ];
    
        if ($this->validate($rules)) {
            $input = $this->request->getPost();
            $totalResult = $this->db->table('cart')
                ->selectSum('subtotal')
                ->where('id_user', $this->id)
                ->get()
                ->getRowArray(); // Menggunakan getRowArray() untuk mendapatkan array
    
            $total = $totalResult['subtotal'];
            
            $data = [
                'id_user'   => $this->id,
                'date'      => date('Y-m-d'),
                'invoice'   => $this->id . date('YmdHis'),
                'total'     => $total,
                'name'      => $input['name'],
                'address'   => $input['address'],
                'phone'     => $input['phone'],
                'status'    => 'waiting',
            ];
            if ($this->checkoutModel->insert($data)) {
                $order = $this->checkoutModel->getInsertID();
                $cart = $this->db->table('cart')
                    ->where('id_user', $this->id)
                    ->get()
                    ->getResultArray();
        
                foreach ($cart as $row) {
                    $row['id_orders'] = $order;
                    unset($row['id'], $row['id_user'], $row['id_category']);
                    $this->db->table('orders_detail')->insert($row);
                }
        
                $this->db->table('cart')->where('id_user', $this->id)->delete();
        
                $this->session->setFlashdata('success', 'Data berhasil disimpan.');
        
                $data = [
                    'title'   => 'Checkout Success',
                    'content' => (object) $data,
                ];
        
                return view('pages/checkout/success', $data);
            } else {
                $this->session->setFlashdata('error', 'Oops! Terjadi suatu kesalahan.');
                return $this->index($input);
            }
        } else {
            $data['validation'] = $this->validator;
            return $this->index($this->request->getPost());
        }
    }

  


}