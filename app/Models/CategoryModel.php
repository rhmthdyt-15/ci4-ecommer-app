<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'category';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['title', 'slug'];
    protected $perpage       = 5;
    protected $useTimestamps = true;
    public function getDeafaultValues()
    {
        return [
            'id' => '',
            'title' => '',
            'slug' => ''
        ];
    }

    public function search($keyword) {
        if (empty($keyword)) {
            return $this;
        }

        $this->like('title', $keyword);
        return $this;
    }

    

}