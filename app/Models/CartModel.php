<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart'; // Nama tabel yang akan digunakan

    protected $primaryKey = 'id'; // Kolom primary key

    protected $allowedFields = ['id_user', 'id_product', 'qty', 'subtotal'];

    // Dates
    protected $useTimestamps = true;
}