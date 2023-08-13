<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_category',
        'slug',
        'title',
        'description',
        'price',
        'is_available',
        'image'

    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDefaultValues()
    {
        return [
            'id_category' => '',
            'slug' => '',
            'title' => '',
            'description' => '',
            'price' => '',
            'is_available' => '',
            'image' => ''
        ];
    }

    public function getCategories()
    {
        $categoryModel = new \App\Models\CategoryModel();
        return $categoryModel->findAll();
    }

    public function updateImage($id, $fieldName, $fileName, $file)
    {
        $product = $this->find($id);

        // Simpan nama gambar lama
        $oldImage = $product['image'];

        // Jika gambar baru diunggah, unggah gambar baru dan update data di database
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $fileName . '.' . $file->getClientExtension();
            $file->move('./images/product', $newName);
            $this->update($id, ['image' => $newName]);

            // Hapus gambar lama dari folder jika bukan gambar default
            if ($oldImage && $oldImage !== 'default.png') {
                $this->deleteImage($oldImage);
            }

            return $newName;
        }

        // Jika tidak ada gambar baru diunggah, biarkan gambar lama tetap
        return $oldImage;
    }

    public function uploadImage($fieldName, $fileName, $file)
    {
        $uploadPath = './images/product';
    
        // Cek apakah file berhasil diupload
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $fileName . '.' . $file->getClientExtension();
            $file->move($uploadPath, $newName);
            return $newName;
        } else {
            session()->setFlashdata('error', $file->getErrorString());
            return false;
        }
    }

    public function deleteImage($fileName)
    {
        $path = './images/product/' . $fileName; // Tambahkan '/' setelah 'product'
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function search($keyword)
    {
        return $this->select('products.id, products.title AS products_title, products.image,
                        products.price, products.is_available, category.title AS category_title')
            ->join('category', 'category.id = products.id_category')
            ->like('products.title', $keyword)
            ->orLike('products.description', $keyword)
            ->paginate(5, 'products');
    }
  
}