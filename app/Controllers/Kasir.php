<?php

namespace App\Controllers;

class Kasir extends BaseController
{
    public function index()
    {
        $data = [
            'title'    => 'Dashboard Kasir - Caffe Lego',
            'username' => 'Kasir',
            'role'     => 'KASIR',
            'tanggal'  => date('l, d M Y'),
            'omzet'    => 0,
            'produk'   => 2 
        ];
        return view('kasir/dashboard', $data);
    }

    public function menu()
    {
        $data = [
            'title'    => 'Daftar Menu Kasir - Caffe Lego',
            'username' => 'Kasir',
            'role'     => 'KASIR'
        ];
        return view('kasir/kelola_menu', $data);
    }
}