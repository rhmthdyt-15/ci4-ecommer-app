<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model
{  
    protected $table = 'orders'; 
    protected $primaryKey = 'id'; 
    protected $useTimestamps = true;
    
    protected $allowedFields = ['id_user', 'date', 'invoice', 'total', 'name', 'address', 'phone', 'status'];

    public function getDefaultValues()
	{
		return [
			'name'		=> '',
			'address'	=> '',
			'phone'		=> '',
			'status'	=> ''
		];
	}

    // Validation
    // protected $validationRules      = [
    //     [
    //         'field'	=> 'name',
    //         'label'	=> 'Nama',
    //         'rules'	=> 'trim|required'
    //     ],
    //     [
    //         'field'	=> 'address',
    //         'label'	=> 'Alamat',
    //         'rules'	=> 'trim|required'
    //     ],
    //     [
    //         'field'	=> 'phone',
    //         'label'	=> 'Telepon',
    //         'rules'	=> 'trim|required|max_length[15]'
    //     ],
    // ];

    public function getCheckoutData($id_user)
    {
        return $this->select([
                'cart.id', 'cart.qty', 'cart.subtotal', 'products.title', 'products.image', 'products.price'
            ])
            ->join('products', 'products.id = cart.id_product')
            ->where('cart.id_user', $id_user)
            ->findAll();
    }
}