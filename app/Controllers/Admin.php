<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\PengaturanModel;

class Admin extends BaseController
{
    private function _commonData($title)
    {
        $pengaturanModel = new PengaturanModel();
        return [
            'title'      => $title,
            'username'   => session()->get('nama_lengkap') ?? 'Admin',
            'role'       => session()->get('role') ?? 'ADMIN',
            'tanggal'    => date('l, d M Y'),
            'pengaturan' => $pengaturanModel->first() 
        ];
    }
public function index()
    {
        $db = \Config\Database::connect();

        
        $omzet = $db->table('transaksi')
                    ->selectSum('total_bayar') 
                    ->where('DATE(tgl_transaksi)', date('Y-m-d'))
                    ->get()->getRow()->total_bayar ?? 0;
        $jmlTransaksi = $db->table('transaksi')
                    ->where('DATE(tgl_transaksi)', date('Y-m-d'))
                    ->countAllResults();

        $totalProduk = $db->table('menu')->countAllResults();

        $stokMenipisCount = $db->table('menu')
        ->where('status', 'Habis')
        ->countAllResults();

        $listStokMenipis = $db->table('menu')
        ->select('menu.*, kategori.nama_kategori')
        ->join('kategori', 'kategori.id_kategori = menu.id_kategori', 'left')
        ->where('status', 'Habis')
        ->get()->getResultArray();

        $transaksiTerakhir = $db->table('transaksi')
                                ->orderBy('tgl_transaksi', 'DESC')
                                ->limit(5)
                                ->get()->getResultArray();

        $data = array_merge($this->_commonData('Dashboard - Caffe Lego'), [
            'omzet'             => $omzet,
            'jmlTransaksi'      => $jmlTransaksi,
            'totalProduk'       => $totalProduk,
            'stokMenipisCount'  => $stokMenipisCount,
            'listStokMenipis'   => $listStokMenipis,
            'transaksiTerakhir' => $transaksiTerakhir
        ]);

        return view('admin/dashboard', $data);
    }
    public function pengaturan()
{
    $data = $this->_commonData('Pengaturan - Caffe Lego');
    return view('admin/pengaturan', $data);
}

public function update_pengaturan()
{
    $pengaturanModel = new PengaturanModel();
    $existing = $pengaturanModel->first();
    $dataUpdate = [
        'nama_cafe'   => $this->request->getPost('nama_cafe'),
        'no_telp'     => $this->request->getPost('no_telp'),
        'alamat'      => $this->request->getPost('alamat'),
        'pajak'       => $this->request->getPost('pajak'), 
        'pesan_struk' => $this->request->getPost('pesan_struk'), 
        'lebar_kertas' => $this->request->getPost('lebar_kertas'),
    ];


    $fileLogo = $this->request->getFile('logo_cafe');
    if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
        $namaLogoBaru = 'logo_cafe.png'; 
        $fileLogo->move('img', $namaLogoBaru, true); 
        $dataUpdate['logo_cafe'] = $namaLogoBaru;
    }
    if ($existing) {
        $pengaturanModel->update($existing['id_pengaturan'], $dataUpdate);
    } else {
        $pengaturanModel->insert($dataUpdate);
    }

    return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Identitas cafe berhasil diperbarui!');
}
    public function kelola_akun()
    {
        $userModel = new UserModel();
        $data = array_merge($this->_commonData('Kelola Akun - Caffe Lego'), [
            'users' => $userModel->findAll()
        ]);
        return view('admin/kelola_akun', $data);
    }

    public function simpan_user()
    {
        $userModel = new UserModel();
        
        $usernameInput = $this->request->getPost('username');
        $emailInput    = $this->request->getPost('email');
        if ($userModel->where('email', $emailInput)->first()) {
            return redirect()->back()->with('error', 'Maaf, email sudah terdaftar!');
        }
        if ($userModel->where('username', $usernameInput)->first()) {
            return redirect()->back()->with('error', 'Maaf, username sudah dipakai!');
        }
        $userModel->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $usernameInput,
            'email'        => $emailInput, // 
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role')
        ]);
        
        return redirect()->to(base_url('admin/kelola_akun'))->with('success', 'User berhasil ditambahkan!');
    }

    public function hapus_user(int $id) 
    {
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->to(base_url('admin/kelola_akun'))->with('success', 'User berhasil dihapus!');
    }
    public function update_akun()
    {
        $userModel = new UserModel();
        $id = $this->request->getPost('id_user');
        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'role'         => $this->request->getPost('role'),
        ];
        $passwordBaru = $this->request->getPost('password');
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }
        $userModel->update($id, $dataUpdate);

        return redirect()->to(base_url('admin/kelola_akun'))->with('success', 'Akun berhasil diperbarui!');
    }
    public function kategori()
    {
        $kategoriModel = new KategoriModel();
        $menuModel = new MenuModel();
        $id_dipilih = $this->request->getGet('id');

        $data = array_merge($this->_commonData('Kategori - Caffe Lego'), [
            'kategori'      => $kategoriModel->getKategoriDenganJumlahMenu(),
            'id_aktif'      => $id_dipilih,
            'menu_kategori' => ($id_dipilih) ? $menuModel->getMenu($id_dipilih) : []
        ]);
        return view('admin/kategori', $data);
    }

    public function tambah_kategori()
    {
        $kategoriModel = new KategoriModel();
        $fileFoto = $this->request->getFile('foto_kategori');
        $namaFoto = 'default.png';

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/kategori', $namaFoto);
        }

        $kategoriModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'foto_kategori' => $namaFoto
        ]);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori ditambahkan!');
    }

    public function update_kategori()
    {
        $kategoriModel = new KategoriModel();
        $id = $this->request->getPost('id_kategori');
        $fotoLama = $this->request->getPost('foto_lama');

        $dataUpdate = ['nama_kategori' => $this->request->getPost('nama_kategori')];
        $fileFoto = $this->request->getFile('foto_kategori');

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFotoBaru = $fileFoto->getRandomName();
            $fileFoto->move('img/kategori', $namaFotoBaru);
            $dataUpdate['foto_kategori'] = $namaFotoBaru;

            if ($fotoLama != 'default.png' && file_exists('img/kategori/' . $fotoLama)) {
                @unlink('img/kategori/' . $fotoLama);
            }
        }

        $kategoriModel->update($id, $dataUpdate);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori diperbarui!');
    }

    public function hapus_kategori(int $id) 
    {
        $kategoriModel = new KategoriModel();
        $kat = $kategoriModel->find($id);

        if ($kategoriModel->hapusKategori($id)) {
            if ($kat && $kat['foto_kategori'] != 'default.png' && file_exists('img/kategori/' . $kat['foto_kategori'])) {
                @unlink('img/kategori/' . $kat['foto_kategori']);
            }
            return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori dihapus!');
        }
        return redirect()->to(base_url('admin/kategori'))->with('error', 'Gagal! Kategori masih berisi menu.');
    }

    public function menu()
    {
        $menuModel = new MenuModel();
        $kategoriModel = new KategoriModel();
        $data = array_merge($this->_commonData('Kelola Menu - Caffe Lego'), [
            'menu'            => $menuModel->getMenu(),
            'daftar_kategori' => $kategoriModel->findAll()
        ]);
        return view('admin/kelola_menu', $data);
    }

    public function simpan()
    {
        $rules = [
            'nama_menu'   => 'required|is_unique[menu.nama_menu]',
            'id_kategori' => 'required',
            'harga'       => 'required|numeric|greater_than_equal_to[0]',
        ];

        $errors = [
            'nama_menu' => [
                'is_unique' => 'Gagal! Nama menu sudah terdaftar, gunakan nama lain.'
            ],
            'harga' => [
                'greater_than_equal_to' => 'Gagal! Harga menu tidak boleh bernilai negatif.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            $errorMsg = $this->validator->getError('nama_menu') ?: $this->validator->getError('harga');
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $fileFoto = $this->request->getFile('foto');
        $namaFoto = 'default.jpg';
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/menu', $namaFoto);
        }

        $menuModel = new MenuModel();
        $menuModel->insert([
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_menu'   => $this->request->getPost('nama_menu'),
            'harga'       => $this->request->getPost('harga'),
            'foto'        => $namaFoto,
            'status'      => 'Tersedia'
        ]);
        return redirect()->to(base_url('admin/menu'))->with('success', 'Menu baru berhasil ditambah!');
    }

    public function update_menu()
    {
        $menuModel = new MenuModel();
        $id = $this->request->getPost('id_menu');
        $fotoLama = $this->request->getPost('foto_lama');

        $rules = [
            'nama_menu'   => "required|is_unique[menu.nama_menu,id_menu,{$id}]",
            'id_kategori' => 'required',
            'harga'       => 'required|numeric|greater_than_equal_to[0]',
        ];

        $errors = [
            'nama_menu' => [
                'is_unique' => 'Gagal! Nama menu tersebut sudah digunakan oleh menu lain.'
            ],
            'harga' => [
                'greater_than_equal_to' => 'Gagal memperbarui! Harga tidak boleh bernilai negatif.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {

            $errorMsg = $this->validator->getError('nama_menu') ?: $this->validator->getError('harga');
            return redirect()->back()->with('error', $errorMsg);
        }

        $dataUpdate = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_menu'   => $this->request->getPost('nama_menu'),
            'harga'       => $this->request->getPost('harga'),
            'status'      => $this->request->getPost('status')
        ];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/menu', $namaFoto);
            $dataUpdate['foto'] = $namaFoto;

            if ($fotoLama != 'default.jpg' && file_exists('img/menu/' . $fotoLama)) {
                @unlink('img/menu/' . $fotoLama);
            }
        }

        $menuModel->update($id, $dataUpdate);
        return redirect()->to(base_url('admin/menu'))->with('success', 'Data menu diperbarui!');
    }

    public function update_status()
    {
        $menuModel = new MenuModel();
        $id = $this->request->getPost('id_menu');
        $statusBaru = $this->request->getPost('status_baru'); 
        $menuModel->update($id, ['status' => $statusBaru]);
        return redirect()->to(base_url('admin/menu'))->with('success', 'Status menu berhasil diubah!');
    }

    public function hapus_menu(int $id) 
    {
        $menuModel = new MenuModel();
        $menuModel->delete($id);
        return redirect()->to(base_url('admin/menu'))->with('success', 'Menu berhasil dihapus!');
    }

    public function histori_transaksi()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('transaksi');
        $builder->select('transaksi.*, user.nama_lengkap as nama_kasir');
        $builder->join('user', 'user.id_user = transaksi.id_user', 'left'); 
        $builder->orderBy('transaksi.tgl_transaksi', 'DESC');
        $all_transaksi = $builder->get()->getResultArray();
        $data = array_merge($this->_commonData('Histori Transaksi - Admin'), [
            'all_transaksi' => $all_transaksi
        ]);

        return view('admin/histori_transaksi', $data);
    }

    public function detail_transaksi($id)
{
    $db = \Config\Database::connect();

    $transaksi = $db->table('transaksi')
                    ->select('transaksi.*, user.nama_lengkap as nama_kasir')
                    ->join('user', 'user.id_user = transaksi.id_user', 'left')
                    ->where('id_transaksi', $id)
                    ->get()->getRowArray();

    $detail = $db->table('detail_transaksi')
    ->select('detail_transaksi.*, menu.nama_menu')
    ->join('menu', 'menu.id_menu = detail_transaksi.id_menu')
    ->where('id_transaksi', $id)
    ->get()->getResultArray();

    $data = array_merge($this->_commonData('Detail Transaksi #'.$id), [
        'transaksi' => $transaksi,
        'detail'    => $detail
    ]);

    return view('admin/detail_history', $data);
}

public function laporan_keuangan()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('transaksi')
                    ->select('transaksi.*, detail_transaksi.qty, menu.nama_menu, menu.harga')
                    ->join('detail_transaksi', 'detail_transaksi.id_transaksi = transaksi.id_transaksi', 'left')
                    ->join('menu', 'menu.id_menu = detail_transaksi.id_menu', 'left')
                    ->orderBy('transaksi.tgl_transaksi', 'DESC')
                    ->get()->getResultArray();

        $laporan = [];
        foreach ($query as $row) {
            $id = $row['id_transaksi'];
            if (!isset($laporan[$id])) {
                $laporan[$id] = [
                    'tanggal'     => $row['tgl_transaksi'],
                    'pelanggan'   => $row['nama_pelanggan'],
                    'subtotal'    => 0,
                    'total_bayar' => $row['total_bayar'], 
                    'pajak'       => 0,
                    'grand_total' => 0,
                    'items'       => []
                ];
            }
            if (!empty($row['nama_menu'])) {
                $harga = $row['harga'] ?? 0;
                $qty = $row['qty'] ?? 0;
                $laporan[$id]['subtotal'] += ($harga * $qty);
                $laporan[$id]['items'][] = [
                    'nama'  => $row['nama_menu'],
                    'harga' => $harga,
                    'qty'   => $qty
                ];
            }
        }


        foreach ($laporan as &$t) {
            $t['grand_total'] = $t['total_bayar']; 
            $t['pajak']       = $t['grand_total'] - $t['subtotal']; 
            

            if ($t['pajak'] < 0) {
                $t['pajak'] = 0;
            }
        }

        $data = array_merge($this->_commonData('Laporan Keuangan - Caffe Lego'), [
        'laporan' => $laporan,
        'pendapatanHariIni' => $db->table('transaksi')->selectSum('total_bayar')->where('DATE(tgl_transaksi)', date('Y-m-d'))->get()->getRow()->total_bayar ?? 0
    ]);

    return view('admin/laporan_keuangan', $data);
}
    public function profil()
    {
        $data = $this->_commonData('Profil Saya - Caffe Lego');
        $userModel = new \App\Models\UserModel();
        $data['user'] = $userModel->find(session()->get('id_user'));

        return view('admin/profil', $data);
    }

    public function update_profil()
    {
        $userModel = new \App\Models\UserModel();
        $id_user = session()->get('id_user');
        $userLama = $userModel->find($id_user);

        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
        ];

        $pw_baru = $this->request->getPost('password_baru');
        if (!empty($pw_baru)) {
            $dataUpdate['password'] = password_hash($pw_baru, PASSWORD_DEFAULT);
        }

        $fileFoto = $this->request->getFile('foto_user');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFotoBaru = 'user_' . $id_user . '_' . time() . '.' . $fileFoto->getExtension();
            $fileFoto->move('img/profile', $namaFotoBaru);
            
            $dataUpdate['foto_user'] = $namaFotoBaru;

            if (!empty($userLama['foto_user']) && file_exists(FCPATH . 'img/profile/' . $userLama['foto_user'])) {
                unlink(FCPATH . 'img/profile/' . $userLama['foto_user']);
            }

            session()->set('foto_user', $namaFotoBaru);
        }

        $userModel->update($id_user, $dataUpdate);

        session()->set('nama_lengkap', $dataUpdate['nama_lengkap']);

        return redirect()->to(base_url('admin/profil'))->with('success', 'Profil Anda berhasil diperbarui!');
    }

    
    public function update_password_admin()
{
    $passwordLama       = $this->request->getPost('password_lama');
    $passwordBaru       = $this->request->getPost('password_baru');
    $konfirmasiPassword = $this->request->getPost('konfirmasi_password');

    $db = \Config\Database::connect();
    $builder = $db->table('pengaturan');
    $pengaturan = $builder->where('id_pengaturan', 1)->get()->getRowArray();

    if ($passwordLama !== $pengaturan['password_registrasi_admin']) {
        return redirect()->back()->with('error', 'Password registrasi saat ini salah!');
    }

    if ($passwordBaru !== $konfirmasiPassword) {
        return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok!');
    }


    $builder->where('id_pengaturan', 1)->update([
        'password_registrasi_admin' => $passwordBaru
    ]);

    return redirect()->back()->with('success', 'Password registrasi admin berhasil diperbarui!');
}
}