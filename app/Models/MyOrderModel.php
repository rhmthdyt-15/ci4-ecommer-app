<?php 

namespace App\Models;

use CodeIgniter\Model;

class MyOrderModel extends Model
{
    protected $table = 'orders';

    protected $useTimestamps = true;

    public function getDefaultValues()
    {
        return [
            'id_orders' => '',
            'account_name' => '',
            'account_number' => '',
            'nominal' => '',
            'note' => '',
            'image' => ''
        ];
    }

    public function uploadImage($fieldName, $fileName, $file)
    {
        $uploadPath = './images/confirm';

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $fileName . '.' . $file->getClientExtension();
            $file->move($uploadPath, $newName);
            return ['file_name' => $newName];
        } else {
            session()->setFlashdata('error', $file->getErrorString());
            return false;
        }
    }

    // MyOrderModel.php
    public function insertOrderConfirm($data)
    {
        return $this->db->table('orders_confirm')->insert($data);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $this->db->table('orders')->where('id', $orderId)->update(['status' => $status]);
    }

}



?>