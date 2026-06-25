<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\KategoriModel;
use App\Models\PengaturanModel;

class Kitchen extends BaseController
{
    protected $pengaturanModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
    }
    private function _commonData($title)
    {
        return [
            'title'      => $title,
            'username'   => session()->get('nama_lengkap') ?? 'Kitchen',
            'role'       => session()->get('role') ?? 'KITCHEN',
            'tanggal'    => date('l, d M Y'),
            'pengaturan' => $this->pengaturanModel->first()
        ];
    }
public function index()
{
    $db = \Config\Database::connect();
    $total_aktif = $db->table('transaksi')
        ->select('transaksi.id_transaksi')
        ->join(
            'detail_transaksi',
            'detail_transaksi.id_transaksi = transaksi.id_transaksi'
        )
        ->whereIn(
            'detail_transaksi.status',
            ['Menunggu', 'Sedang Dibuat']
        )
        ->groupBy('transaksi.id_transaksi')
        ->countAllResults();
    $total_proses = $db->table('transaksi')
        ->select('transaksi.id_transaksi')
        ->join(
            'detail_transaksi',
            'detail_transaksi.id_transaksi = transaksi.id_transaksi'
        )
        ->where(
            'detail_transaksi.status',
            'Sedang Dibuat'
        )
        ->groupBy('transaksi.id_transaksi')
        ->countAllResults();

    $total_menunggu = $db->table('transaksi')
        ->select('transaksi.id_transaksi')
        ->join(
            'detail_transaksi',
            'detail_transaksi.id_transaksi = transaksi.id_transaksi'
        )
        ->where(
            'detail_transaksi.status',
            'Menunggu'
        )
        ->groupBy('transaksi.id_transaksi')
        ->countAllResults();
    $data = array_merge(
        $this->_commonData('Dashboard Kitchen'),
        [
            'total_aktif' => $total_aktif,
            'total_proses' => $total_proses,
            'total_menunggu' => $total_menunggu,
            'transaksiTerakhir' => []
        ]
    );
    return view('kitchen/dashboard', $data);
}

    public function pesanan_status()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('transaksi');
        $builder->select('transaksi.id_transaksi, transaksi.tgl_transaksi, transaksi.nama_pelanggan, transaksi.deskripsi, detail_transaksi.qty, detail_transaksi.status as status_item, menu.nama_menu');
        $builder->join('detail_transaksi', 'detail_transaksi.id_transaksi = transaksi.id_transaksi');
        $builder->join('menu', 'menu.id_menu = detail_transaksi.id_menu');
        $builder->whereIn('detail_transaksi.status', ['Menunggu', 'Sedang Dibuat', 'Selesai']);
        $builder->orderBy('transaksi.id_transaksi', 'DESC');
        $results = $builder->get()->getResultArray();

        $pesananGrouped = [];
        foreach ($results as $row) {
            $id = $row['id_transaksi'];
            $st_raw = $row['status_item'] ?? 'Menunggu';

            if (!isset($pesananGrouped[$id])) {
                $pesananGrouped[$id]['info'] = [
                    'id'        => $id,
                    'no_trx'    => 'TRX-' . $id,
                    'pelanggan' => $row['nama_pelanggan'],
                    'waktu'     => $row['tgl_transaksi'],
                    'status'    => 'Menunggu', 
                    'deskripsi' => $row['deskripsi'],
                ];
            }

            if ($st_raw == 'Sedang Dibuat') {
                $pesananGrouped[$id]['info']['status'] = 'Proses';
            } 
            elseif ($st_raw == 'Selesai' && $pesananGrouped[$id]['info']['status'] != 'Proses') {
                $pesananGrouped[$id]['info']['status'] = 'Selesai';
            }

            $pesananGrouped[$id]['items'][] = [
                'menu' => $row['nama_menu'],
                'qty'  => $row['qty']
            ];
        }

        $data = array_merge($this->_commonData('Status Pesanan'), [
            'pesanan' => $pesananGrouped
        ]);

        return view('kitchen/pesanan_status', $data);
    }
public function update_status($id, $status_baru)
{
    $db = \Config\Database::connect();
    $status_cek = strtolower(trim($status_baru));
    $status_db = '';

    if ($status_cek == 'proses') {
        $status_db = 'Sedang Dibuat';
    } elseif ($status_cek == 'selesai') {
        $status_db = 'Selesai';
    }

    if (in_array($status_db, ['Sedang Dibuat', 'Selesai'])) {
        $db->table('detail_transaksi')
        ->where('id_transaksi', $id)
        ->update(['status' => $status_db]);

        $pesan = ($status_db == 'Sedang Dibuat') ? 'Pesanan sedang dibuat!' : 'Pesanan telah selesai!';
        return redirect()->to(base_url('kitchen/pesanan_status'))->with('success', $pesan);
    }

    return redirect()->back()->with('error', 'Gagal update status. Status tidak valid.');
}

    public function menu_status()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('menu');
        $builder->select('menu.*, kategori.nama_kategori'); 
        $builder->join('kategori', 'kategori.id_kategori = menu.id_kategori', 'left');
        
        $menuData = $builder->get()->getResultArray();
        
        $kategoriModel = new KategoriModel();

        $data = array_merge($this->_commonData('Kelola Menu'), [
            'menu'            => $menuData,
            'daftar_kategori' => $kategoriModel->findAll()
        ]);

        return view('kitchen/kelola_menu', $data);
    }


    public function update_status_menu()
    {
        $menuModel = new MenuModel();
        $id = $this->request->getPost('id_menu');
        $statusSekarang = $this->request->getPost('status_sekarang');
        $statusBaru = ($statusSekarang == 'Tersedia') ? 'Habis' : 'Tersedia';
        
        $menuModel->update($id, ['status' => $statusBaru]);
        return redirect()->to(base_url('kitchen/menu_status'))->with('success', 'Status stok diperbarui!');
    }

    public function profil()
{
    $userModel = new \App\Models\UserModel();
    $id_user = session()->get('id_user');
    
    $dataKitchen = $userModel->find($id_user);

    $db = \Config\Database::connect();
    $pengaturan = $db->table('pengaturan')->get()->getRowArray();

    $data = [
        'title'      => 'Edit Profil Saya - Caffe Lego',
        'user'       => $dataKitchen,
        'pengaturan' => $pengaturan
    ];

    return view('kitchen/profil', $data); 
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
    session()->set('username', $dataUpdate['username']);

    return redirect()->to(base_url('kitchen/profil'))->with('success', 'Profil Dapur berhasil diperbarui!');
}
}