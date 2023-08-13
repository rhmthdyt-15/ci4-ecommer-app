<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MyOrderModel;

class MyOrder extends BaseController
{
    private $id;

    private $redirectTo = null;

    protected $session;

    protected $myorder;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->myorder = new MyOrderModel;
        $is_login = $this->session->get('is_login');
        $this->id = $this->session->get('id');

        if (!$is_login) {
            return redirect()->to(base_url('/'));
        }
    }
    public function index()
    {
        $data = [
            'title'   => 'Daftar Order',
            'content' => $this->myorder->where('id_user', $this->id)
                                        ->orderBy('created_at', 'DESC')
                                        ->get()
                                        ->getResult(), // Use getResult() instead of get()
        ];
    
        return view('pages/myorder/index', $data);
    }


    public function detail($invoice)
    {
        $data['order'] = $this->myorder->where('invoice', $invoice)->first();
        if (!$data['order']) {
            $this->session->setFlashdata('warning', 'Data tidak ditemukan!');
            return redirect()->to(base_url('myorder'));
        }

        $db = \Config\Database::connect(); // Mendapatkan instance koneksi database

        $query = $db->table('orders_detail')
            ->select([
                'orders_detail.id_orders', 'orders_detail.id_product', 'orders_detail.qty',
                'orders_detail.subtotal', 'products.title', 'products.image', 'products.price'
            ])
            ->join('products', 'products.id = orders_detail.id_product')
            ->where('orders_detail.id_orders', $data['order']['id'])
            ->get(); // Eksekusi query

            $data['order_detail'] = $query->getResult();
        
           if ($data['order']['status'] !== 'waiting') {
            $orderConfirmQuery = $db->table('orders_confirm')
                ->where('id_orders', $data['order']['id'])
                ->get();

                $data['order_confirm'] = $orderConfirmQuery->getRow(); 
            }     
            

            $subtotals = [];
            foreach ($data['order_detail'] as $row) {
                $subtotals[] = $row->subtotal;
            }
            $totalSubtotal = array_sum($subtotals);
            
            $data['totalSubtotal'] = $totalSubtotal;
            $data['title'] = 'Detail Order';

        return view('pages/myorder/detail', $data);
    }

    public function confirm($invoice)
    {
        $myOrderModel = new MyOrderModel();
    
        $data['order'] = $myOrderModel->where('invoice', $invoice)->first();
        if (!$data['order']) {
            $this->session->setFlashdata('warning', 'Data tidak ditemukan.');
            return redirect()->to(base_url('/myorder'));
        }
    
        if ($data['order']['status'] !== 'waiting') {
            $this->session->setFlashdata('warning', 'Bukti transfer sudah dikirim.');
            return redirect()->to(base_url("myorder/detail/$invoice"));
        }
    
        $data['input'] = [];
        if (!$this->request->getPost()) {
            $data['input'] = (object) $myOrderModel->getDefaultValues();
        } else {
            $data['input'] = (object) $this->request->getPost();
        }
    
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = url_title($invoice, '-', true) . '-' . date('YmdHis');
            $uploadResult = $myOrderModel->uploadImage('image', $imageName, $imageFile);
    
            if ($uploadResult) {
                $data['input']->image = $uploadResult['file_name'];
            } else {
                $this->session->setFlashdata('error', 'Upload gambar gagal.');
                return redirect()->to(base_url("myorder/confirm/$invoice"));
            }
        }
    
        $validationRules = [
            'account_name' => [
                'label' => 'Nama Pemilik',
                'rules' => 'trim|required'
            ],
            'account_number' => [
                'label' => 'No. Rekening',
                'rules' => 'trim|required|max_length[50]'
            ],
            'nominal' => [
                'label' => 'Nominal',
                'rules' => 'trim|required|numeric'
            ]
        ];
    
        if (!$this->validate($validationRules)) {
            $data['title'] = 'Konfirmasi Order';
            $data['form_action'] = base_url("myorder/confirm/$invoice");
            $data['validation'] = \Config\Services::validation();
    
            return view('pages/myorder/confirm', $data); // Replace 'your_template' with your actual view template
        }
    
        try {
            $myOrderModel->insertOrderConfirm($data['input']);
            $myOrderModel->updateOrderStatus($data['input']->id_orders, 'paid');
            $this->session->setFlashdata('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    
        return redirect()->to(base_url("myorder/detail/$invoice"));
    }
    



    // public function confirm($invoice)
    // {
    //     $myOrderModel = new MyOrderModel();

    //     $data['order'] = $myOrderModel->where('invoice', $invoice)->first();
    //     if (!$data['order']) {
    //         $this->session->setFlashdata('warning', 'Data tidak ditemukan.');
    //         return redirect()->to(base_url('/myorder'));
    //     }

    //     if ($data['order']['status'] !== 'waiting') {
    //         $this->session->setFlashdata('warning', 'Bukti transfer sudah dikirim.');
    //         return redirect()->to(base_url("myorder/detail/$invoice"));
    //     }

    //     $data['input'] = [];
    //     if (!$this->request->getPost()) {
    //         $data['input'] = (object) $myOrderModel->getDefaultValues();
    //     } else {
    //         $data['input'] = (object) $this->request->getPost();
    //     }

    //     $imageFile = $this->request->getFile('image');
    //     if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
    //         $imageName = url_title($invoice, '-', true) . '-' . date('YmdHis');
    //         $uploadResult = $myOrderModel->uploadImage('image', $imageName, $imageFile);

    //         if ($uploadResult) {
    //             $data['input']->image = $uploadResult['file_name'];
    //         } else {
    //             return redirect()->to(base_url("myorder/confirm/$invoice"));
    //         }
    //     }

    //     $validationRules = [
    //         'account_name' => [
    //             'label' => 'Nama Pemilik',
    //             'rules' => 'trim|required'
    //         ],
    //         'account_number' => [
    //             'label' => 'No. Rekening',
    //             'rules' => 'trim|required|max_length[50]'
    //         ],
    //         'nominal' => [
    //             'label' => 'Nominal',
    //             'rules' => 'trim|required|numeric'
    //         ],
    //         'image' => [
    //             'label' => 'Bukti Transfer',
    //             'rules' => 'required|callback_validasiGambar'
    //         ],
    //     ];

    //     if (!$this->validate($validationRules)) {
    //         $data['title'] = 'Konfirmasi Order';
    //         $data['form_action'] = base_url("myorder/confirm/$invoice");
    //         $validation = \Config\Services::validation();
    //         $data['validation'] = $validation;
            

    //         return view('pages/myorder/confirm', $data); // Replace 'your_template' with your actual view template
    //     }

        // $db = \Config\Database::connect();

        // $myOrderModel = $db->table('orders_confirm');

    //     if ($this->myorder->insertOrderConfirm($data['input'])) {
    //         $this->myorder->updateOrderStatus($data['input']->id_orders, 'paid');
    //         $this->session->setFlashdata('success', 'Data berhasil disimpan!');
    //     } else {
    //         $this->session->setFlashdata('error', 'Oops! Terjadi suatu kesalahan');
    //     }

    //     return redirect()->to(base_url("myorder/detail/$invoice"));
    // }


    // public function confirm($invoice)
    // {
    //     $data['order'] = $this->myorder->where('invoice', $invoice)->first();
    
    //     if (!$data['order']) {
    //         $this->session->setFlashdata('warning', 'Data tidak ditemukan!');
    //         return redirect()->to(base_url('myorder'));
    //     }
    
    //     if ($data['order']['status'] !== 'waiting') {
    //         $this->session->setFlashdata('warning', 'Data transfer sudah dikirim.');
    //         return redirect()->to(base_url("myorder/detail/$invoice"));
    //     }
    
    //     if (!$this->request->getPost()) {
    //         $data['input'] = (object) $this->myorder->getDefaultValues();
    //     } else {
    //         $data['input'] = (object) $this->request->getPost();
    //     }
    
        // $validationRules = [
        //     'account_name' => [
        //         'label' => 'Nama Pemilik',
        //         'rules' => 'trim|required'
        //     ],
        //     'account_number' => [
        //         'label' => 'No. Rekening',
        //         'rules' => 'trim|required|max_length[50]'
        //     ],
        //     'nominal' => [
        //         'label' => 'Nominal',
        //         'rules' => 'trim|required|numeric'
        //     ],
        //     'image' => [
        //         'label' => 'Bukti Transfer',
        //         'rules' => 'callback_image_required'
        //     ],
        // ];
    
    //     if (!$this->myorder->validate($validationRules)) {
    //         $data = [
    //             'title' => 'Konfirmasi Order',
    //             'form_action' => base_url("myorder/confirm/$invoice"),
    //             'errors' => $this->validator->getErrors()
    //         ];
    
    //         return view('pages/myorder/confirm', $data);
    //     }
    
    //     if (!$this->request->getFile('image')) {
    //         $this->session->setFlashdata('image', 'Bukti transfer harus dipilih');
    //     } else {
    //         $file = $this->request->getFile('image');
    //         if ($file && $file->isValid() && !$file->hasMoved()) {
    //             $imageName = $invoice . '-' . date('YmdHis');
    //             $uploadResult = $this->myorder->uploadImage('image', $imageName, $file);
    
    //             if ($uploadResult) {
    //                 $this->myorder->update($this->myorder->getInsertID(), ['image' => $uploadResult]);
    //                 if (!isset($this->redirectTo)) {
    //                     $this->redirectTo = base_url("myorder/confirm/$invoice");
    //                 }
    //             } else {
    //                 session()->setFlashdata('error', 'Upload gambar gagal: ' . $file->getErrorString());
    //                 return redirect()->to(base_url("myorder/detail/$invoice"));
    //             }
    //         }
    //     }
    //     if (isset($this->redirectTo)) {
    //         return redirect()->to($this->redirectTo);
    //     }
    // }
    

    protected function validasiGambar($str = null, string &$error = null): bool
    {
        $file = $this->request->getFile('image');
        
        if (empty($file->getName())) {
            $error = 'Bukti transfer harus dipilih.';
            return false;
        }
    
        return true;
    }
    
}