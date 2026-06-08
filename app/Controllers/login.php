<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index() 
    { 
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            if ($role === 'ADMIN') {
                return redirect()->to(base_url('admin/dashboard'));
            } elseif ($role === 'KASIR') {
                return redirect()->to(base_url('kasir/dashboard'));
            } elseif ($role === 'KITCHEN') {
                return redirect()->to(base_url('kitchen/dashboard'));
            }
        }
        
        return view('login'); 
    }

    public function process()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id_user'      => $user['id_user'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role'         => strtoupper($user['role']),
                'isLoggedIn'   => true,
                'foto_user'    => $user['foto_user'] ?? ''
            ]);
            
            $role = strtoupper($user['role']);
            if ($role === 'ADMIN') {
                return redirect()->to(base_url('admin/dashboard'));
            } elseif ($role === 'KASIR') {
                return redirect()->to(base_url('kasir/dashboard'));
            } elseif ($role === 'KITCHEN') {
                return redirect()->to(base_url('kitchen/dashboard'));
            } else {
                return redirect()->to(base_url('/'));
            }
        }
        
        return redirect()->back()->with('error', 'Username atau Password Salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }


    public function lupa_password()
    {
        return view('lupa_password');
    }

    public function proses_lupa_password()
    {
        $model = new UserModel();
        $emailInput = $this->request->getPost('email');
        $user = $model->where('email', $emailInput)->first();

        if ($user) {
            session()->set('email_reset', $emailInput);
            return redirect()->to(base_url('reset_password'));
        } else {
            return redirect()->back()->with('error', 'Email tidak terdaftar!');
        }
    }

    public function reset_password()
    {
        if (!session()->get('email_reset')) {
            return redirect()->to(base_url('lupa_password'))->with('error', 'Sesi berakhir, masukkan email lagi.');
        }
        return view('reset_password');
    }

    public function update_password()
    {
        $model = new UserModel();
        
        $email = session()->get('email_reset');
        $pw_baru = $this->request->getPost('password_baru');

        if (!$email) {
            return redirect()->to(base_url('lupa_password'))->with('error', 'Sesi kedaluwarsa, silakan masukkan email kembali.');
        }

        $user = $model->where('email', $email)->first();

        if ($user) {
            $model->update($user['id_user'], [
                'password' => password_hash($pw_baru, PASSWORD_DEFAULT)
            ]);

            session()->remove('email_reset');
            
            return redirect()->to(base_url('/'))->with('success', 'Password berhasil diubah, silakan login!');
        }
        
        return redirect()->to(base_url('lupa_password'))->with('error', 'Gagal memperbarui data.');
    }
}