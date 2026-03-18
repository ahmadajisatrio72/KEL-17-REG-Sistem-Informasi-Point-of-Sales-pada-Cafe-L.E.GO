<?php

namespace App\Controllers;

use App\Models\UserModel; // Baris ini penting untuk hubungi database

class Register extends BaseController
{
    // Menampilkan halaman pendaftaran
    public function index()
    {
        return view('register'); 
    }

    // Menyimpan data ke database
    public function save()
    {
        $userModel = new \App\Models\UserModel();

        $data = [
            'nama_lengkap' => $this->request->getPost('nama'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role'),
        ];

        $userModel->insert($data);
        return redirect()->to(base_url('login'))->with('success', 'Akun Berhasil Dibuat!');
    }
}