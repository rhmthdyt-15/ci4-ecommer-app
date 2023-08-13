<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MyOrderModel;
use App\Models\OrderModel;

class Order extends BaseController
{
    protected $session;
    protected $order;
    protected $myorder;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->order = new OrderModel;
        $this->myorder = new MyOrderModel;
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
            $order = $this->order->like('invoice', $keyword)->paginate(5, 'orders');
        } else {
            $this->session->remove('keyword');
            $order = $this->order->orderBy('date', 'DESC')->paginate(5, 'orders');
        }

        $data = [
            'title' => 'Admin: Order',
            'order' => $order,
            'pager' => $this->order->pager,
            'currentPage' => $currentPage
        ];

        return view('pages/order/index', $data);
    }

    public function reset()
    {
        $this->session->remove('keyword');
        return redirect()->to(base_url('order'));
    }

    public function detail($invoice)
    {
        $data['order'] = $this->order->where('invoice', $invoice)->first();
        if (!$data['order']) {
            $this->session->setFlashdata('warning', 'Data tidak ditemukan!');
            return redirect()->to(base_url('order'));
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

        return view('pages/order/detail', $data);
    }



    // public function detail($id)
    // { 
    //     // Ubah sesuai dengan namespace Anda
    //     $data['order'] = $this->order->find($id);
    
    //     if (!$data['order']) {
    //         $this->session->setFlashdata('warning', 'Data tidak ditemukan!');
    //         return redirect()->to(base_url('order'));
    //     }
    
    //     $db = \Config\Database::connect();
    
    //     $query = $db->table('orders_detail')
    //         ->select([
    //             'orders_detail.id_orders', 'orders_detail.id_product', 'orders_detail.qty',
    //             'orders_detail.subtotal', 'products.title', 'products.image', 'products.price'
    //         ])
    //         ->join('products', 'products.id = orders_detail.id_product')
    //         ->where('orders_detail.id_orders', $data['order']['id'])
    //         ->get();
    
    //     $data['order_detail'] = $query->getResult();
    
    //     if ($data['order']['status'] !== 'waiting') {
    //         $orderConfirmQuery = $db->table('orders_confirm')
    //             ->where('id_orders', $data['order']['id'])
    //             ->get();
    
    //         $data['order_confirm'] = $orderConfirmQuery->getRow();
    //     }
    
    //     $subtotals = [];
    //     foreach ($data['order_detail'] as $row) {
    //         $subtotals[] = $row->subtotal;
    //     }
    //     $totalSubtotal = array_sum($subtotals);
    
    //     $data['totalSubtotal'] = $totalSubtotal;
    //     $data['title'] = 'Detail Order';
    
    //     return view('pages/myorder/detail', $data);
    // }

    // public function update($id)
    // {
    //     if (!$this->request->getPost()) {
    //         $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan!');
    //         return redirect()->to(base_url("order/detail/$id"));
    //     }

    //     $status = $this->request->getPost('status');

    //     if ($status !== null) {
    //         $updatedData = [
    //             'status' => $status
    //         ];

    //         if ($this->order->update($id, $updatedData)) {
    //             $this->session->setFlashdata('success', 'Data berhasil diperbaharui.');
    //         } else {
    //             $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan!');
    //         }
    //     } else {
    //         $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan!');
    //     }

    //     return redirect()->to(base_url("order/detail/$id"));
    // }

    public function update($invoice)
    {
        if (!$this->request->getPost()) {
            $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan!');
            return redirect()->to(base_url("order/detail/$invoice"));
        }

        $status = $this->request->getPost('status');

        if ($status !== null) {
            $updatedData = [
                'status' => $status
            ];

            if ($this->order->update($invoice, $updatedData)) {
                $this->session->setFlashdata('success', 'Data berhasil diperbaharui.');
            } else {
                $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan!');
            }
        } else {
            $this->session->setFlashdata('error', 'Oops! Terjadi kesalahan!');
        }

        return redirect()->to(base_url("order/detail/$invoice"));
    }
    
    
}