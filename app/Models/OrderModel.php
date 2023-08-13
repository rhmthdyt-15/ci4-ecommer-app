<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    public $table            = 'orders';
    protected $useTimestamps = true;

    protected $allowedFields = ['status']; 

    public function search($keyword) {
        if (empty($keyword)) {
            return $this;
        }

        $this->like('invoice', $keyword);
        return $this;
    }
}