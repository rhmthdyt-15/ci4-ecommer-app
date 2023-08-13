<?php

use CodeIgniter\Database\ConnectionInterface;

function getDropdownList($table, $columns)
{
    $db = \Config\Database::connect();
    $query = $db->table($table)->select($columns)->get();

    if ($query->getNumRows() >= 1) {
        $option1 = ['' => '- Select -'];
        $option2 = array_column($query->getResultArray(), $columns[1], $columns[0]);
        $options = $option1 + $option2;

        return $options;
    }

    return $options = ['' => '- Select -'];
}

function getCategories()
{
    $db = \Config\Database::connect();
    $query = $db->table('category')->get()->getResult();
    return $query;
}

function getCart()
{
    $session = session();
    $userId = $session->get('id');

    if ($userId) {
        $db = \Config\Database::connect();
        $query = $db->table('cart')->where('id_user', $userId)->countAllResults();
        return $query;
    }

    return false;
}

function hashEncrypt($input)
{
    return password_hash($input, PASSWORD_DEFAULT);
}

function hashEncryptVerify($input, $hash)
{
    if (password_verify($input, $hash)) {
        return true;
    } else {
        return false;
    }
}