<?php
namespace App\Controllers;
use App\Models\UserModel;

class Register extends BaseController {
    
    public function index() { 
        $db = \Config\Database::connect();
        $data['pengaturan'] = $db->table('pengaturan')->where('id_pengaturan', 1)->get()->getRowArray();

        return view('register', $data); 
    }

    public function save() {
        $role = $this->request->getPost('role');
        $kodeInput = $this->request->getPost('kode_verifikasi');
        
        $emailInput = $this->request->getPost('email');
        $usernameInput = $this->request->getPost('username');
        
        $model = new UserModel();

        $cekEmail = $model->where('email', $emailInput)->first();
        if ($cekEmail) {
            // Kalau nemu email yang sama, tendang balik bawa pesan eror
            return redirect()->back()->withInput()->with('error', 'Maaf, email sudah terdaftar atas akun lain!');
        }

        $cekUsername = $model->where('username', $usernameInput)->first();
        if ($cekUsername) {
            return redirect()->back()->withInput()->with('error', 'Maaf, username sudah dipakai, silakan cari yang lain!');
        }

        if ($role == 'Admin') {
            $db = \Config\Database::connect();
            $pengaturan = $db->table('pengaturan')->where('id_pengaturan', 1)->get()->getRowArray();
            
            $kodeDatabase = $pengaturan['password_registrasi_admin']; 

            if ($kodeInput !== $kodeDatabase) {
                return redirect()->back()->withInput()->with('error', 'Kode Verifikasi Owner Salah!');
            }
        }
        
        $model->save([
            'nama_lengkap' => $this->request->getPost('nama'),
            'username'     => $usernameInput,
            'email'        => $emailInput, 
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $role
        ]);

        return redirect()->to(base_url('/'))->with('success', 'Akun berhasil dibuat! Silakan Login.');
    }
}