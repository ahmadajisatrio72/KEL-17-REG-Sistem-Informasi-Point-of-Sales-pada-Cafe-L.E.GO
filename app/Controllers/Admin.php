<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        $data = [
            'title'    => 'Dashboard - Caffe Lego',
            'username' => 'Admin',
            'role'     => 'ADMIN',
            'tanggal'  => date('l, d M Y') 
        ];
        return view('admin/dashboard', $data); 
    }

    public function menu()
    {
        $data = [
            'title'    => 'Kelola Menu Admin - Caffe Lego',
            'username' => 'Admin',
            'role'     => 'ADMIN'
        ];
        return view('admin/kelola_menu', $data); 
    }
}