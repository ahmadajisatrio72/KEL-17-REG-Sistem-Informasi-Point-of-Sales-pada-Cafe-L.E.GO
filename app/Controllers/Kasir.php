<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\PengaturanModel;

class Kasir extends BaseController
{
    private function _commonData($title)
    {
        $pengaturanModel = new PengaturanModel();
        return [
            'title'      => $title,
            'username'   => session()->get('nama_lengkap') ?? session()->get('username'),
            'role'       => session()->get('role'),
            'tanggal'    => date('l, d M Y'),
            'pengaturan' => $pengaturanModel->first()
        ];
    }
    
    public function index()
    {
        $db = \Config\Database::connect();
        $jmlTransaksi = $db->table('transaksi')
        ->where('DATE(tgl_transaksi)', date('Y-m-d'))
        ->countAllResults();
        $transaksiTerakhir = $db->table('transaksi')
                                ->orderBy('tgl_transaksi', 'DESC')
                                ->limit(5)
                                ->get()->getResultArray();

        $terlarisQuery = $db->table('detail_transaksi')
                            ->select('menu.nama_menu, SUM(detail_transaksi.qty) as total_qty')
                            ->join('menu', 'menu.id_menu = detail_transaksi.id_menu')
                            ->groupBy('detail_transaksi.id_menu')
                            ->orderBy('total_qty', 'DESC')
                            ->limit(1)
                            ->get()->getRow();
        
        $menuTerlaris = $terlarisQuery ? $terlarisQuery->nama_menu : 'Belum ada';

        $data = array_merge($this->_commonData('Dashboard Kasir - Caffe Lego'), [
            'totalPendapatan'   => $db->table('transaksi')
                                    ->selectSum('total_bayar')
                                    ->where('DATE(tgl_transaksi)', date('Y-m-d'))
                                    ->get()->getRow()->total_bayar ?? 0,
            'menuTerlaris'      => $menuTerlaris, // Sekarang ini sudah aman
            'totalProduk'       => $db->table('menu')->countAllResults(),
            'jmlTransaksi'      => $jmlTransaksi,
            'transaksiTerakhir' => $transaksiTerakhir
        ]);

        return view('kasir/dashboard', $data);
    }

    public function transaksi()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('menu');
        $builder->select('menu.*, kategori.nama_kategori'); 
        $builder->join('kategori', 'kategori.id_kategori = menu.id_kategori');
        $builder->where('menu.status', 'Tersedia');
        $menu = $builder->get()->getResultArray();

        $kategori = $db->table('kategori')->get()->getResultArray();

        $data = array_merge($this->_commonData('Transaksi Baru - Caffe Lego'), [
            'menu'     => $menu,
            'kategori' => $kategori
        ]);

        return view('kasir/transaksi', $data);
    }

    public function save_transaksi()
    {
        $db = \Config\Database::connect();
        
        $metode = $this->request->getPost('metode_pembayaran'); 
        $uang_bayar = $this->request->getPost('uang_bayar');
        $cartData = $this->request->getPost('cart_data');
        $cart = json_decode($cartData, true);

        if (empty($cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Keranjang kosong!']);
        }

        $pengaturanModel = new \App\Models\PengaturanModel();
        $pengaturan = $pengaturanModel->first();
        $persenPajak = $pengaturan['pajak'] ?? 0;

        $db->transStart();

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['qty']);
        }

        $nominalPajak = ($subtotal * $persenPajak) / 100;
        $totalBayar = $subtotal + $nominalPajak;

        $db->table('transaksi')->insert([
            'id_user'        => session()->get('id_user') ?? 1, 
            'tgl_transaksi'  => date('Y-m-d H:i:s'),
            'total_bayar'    => $totalBayar,   
            'pajak'          => $nominalPajak, 
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'uang_bayar'     => $uang_bayar,
            'status_bayar'   => 'Lunas',
            'metode_bayar'   => $metode, 
            'deskripsi'      => $this->request->getPost('deskripsi'),
        ]);

        $id_transaksi = $db->insertID();

        foreach ($cart as $item) {
            $db->table('detail_transaksi')->insert([
                'id_transaksi' => $id_transaksi,
                'id_menu'      => $item['id'],
                'qty'          => $item['qty'],
                'harga_satuan' => $item['price'],
                'subtotal'     => $item['price'] * $item['qty']
            ]);
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status'       => 'success',
            'id_transaksi' => $id_transaksi
        ]);
    }
    public function cetak_struk($id)
    {
        $db = \Config\Database::connect();
        $transaksi = $db->table('transaksi')
                        ->where('id_transaksi', $id)
                        ->get()->getRowArray();

        $detail = $db->table('detail_transaksi')
        ->select('detail_transaksi.*, menu.nama_menu')
        ->join('menu', 'menu.id_menu = detail_transaksi.id_menu')
        ->where('id_transaksi', $id)
        ->get()->getResultArray();

        $pengaturanModel = new \App\Models\PengaturanModel();
        $pengaturan = $pengaturanModel->first();

        $data = [
            'title'      => 'Cetak Struk - Caffe Lego',
            'transaksi'  => $transaksi,
            'detail'     => $detail,
            'pengaturan' => $pengaturan, 
            'username'   => session()->get('nama_lengkap') ?? 'Kasir'
        ];

        return view('kasir/cetak_struk', $data);
    }

    
public function histori_transaksi() 
{
    date_default_timezone_set('Asia/Jakarta');

    $db = \Config\Database::connect();
    $filter = $this->request->getGet('filter') ?? 'harian'; 
    $builder = $db->table('transaksi');

    if ($filter == 'harian') {
        $builder->where('DATE(tgl_transaksi)', date('Y-m-d'));
    } elseif ($filter == 'mingguan') {
        $builder->where('tgl_transaksi >=', date('Y-m-d 00:00:00', strtotime('-6 days')));
    } elseif ($filter == 'bulanan') {
        $builder->where('MONTH(tgl_transaksi)', date('m'))
                ->where('YEAR(tgl_transaksi)', date('Y'));
    }

    $transaksi = $builder->orderBy('id_transaksi', 'DESC')->get()->getResultArray();
    $totalPenjualan = 0;
    foreach ($transaksi as $tr) {
        $totalPenjualan += $tr['total_bayar'];
    }

    $data = array_merge($this->_commonData('History - Caffe Lego'), [
        'transaksiHariIni' => $transaksi, 
        'totalPenjualan'   => $totalPenjualan,
        'jmlTransaksi'     => count($transaksi),
        'filterAktif'      => $filter
    ]);

    return view('kasir/history', $data);
}


public function pesanan()
{
    $db = \Config\Database::connect();
    $transaksi = $db->table('transaksi')
        ->orderBy('tgl_transaksi', 'DESC')
        ->get()
        ->getResultArray();
    $orders = [];
    foreach ($transaksi as $row) {
        $detail = $db->table('detail_transaksi')
            ->select('detail_transaksi.qty, detail_transaksi.status, menu.nama_menu')
            ->join(
                'menu',
                'menu.id_menu = detail_transaksi.id_menu'
            )
            ->where(
                'detail_transaksi.id_transaksi',
                $row['id_transaksi']
            )
            ->get()
            ->getResultArray();
        $status = 'menunggu';

        if (!empty($detail)) {

            $statusItem = strtolower($detail[0]['status'] ?? 'menunggu');

            if ($statusItem == 'proses' || $statusItem == 'dimasak') {
                $status = 'sedang dibuat';
            } else {
                $status = $statusItem;
            }
        }
        $row['status'] = $status;
        $row['detail'] = $detail;
        $orders[] = $row;
    }


    $data = array_merge(
        $this->_commonData('Status Pesanan - Caffe Lego'),
        [
            'orders' => $orders
        ]
    );


    return view('kasir/pesanan', $data);
}

public function profil()
{
    $userModel = new \App\Models\UserModel();
    $id_user = session()->get('id_user');
    $dataKasir = $userModel->find($id_user);
    $db = \Config\Database::connect();
    $pengaturan = $db->table('pengaturan')->get()->getRowArray(); 

    $data = [
        'title'      => 'Edit Profil Saya - Caffe Lego',
        'user'       => $dataKasir,
        'pengaturan' => $pengaturan
    ];

    return view('kasir/profil', $data); 
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

    return redirect()->to(base_url('kasir/profil'))->with('success', 'Profil Anda berhasil diperbarui!');
}

public function get_transaksi_json($id)
    {
        try {
            $db = \Config\Database::connect();

            $transaksi = $db->table('transaksi')
                            ->select('transaksi.*, user.username')
                            ->join('user', 'user.id_user = transaksi.id_user', 'left')
                            ->where('id_transaksi', $id)
                            ->get()->getRowArray();

            if (!$transaksi) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi tidak ditemukan.']);
            }

            // 2. Ambil rincian menu yang dibeli
            $detail = $db->table('detail_transaksi')
            ->select('detail_transaksi.*, menu.nama_menu')
            ->join('menu', 'menu.id_menu = detail_transaksi.id_menu', 'left')
            ->where('id_transaksi', $id)
            ->get()->getResultArray();

            $pengaturanModel = new \App\Models\PengaturanModel();
            $pengaturan = $pengaturanModel->first();
            $persen_pajak = $pengaturan['pajak'] ?? 0;
            $subtotal_murni = $transaksi['total_bayar']; 
            $nilai_pajak = ($subtotal_murni * $persen_pajak) / 100;
            $total_akhir = $subtotal_murni + $nilai_pajak;
            $dataJson = [
                'status'            => 'success',
                'id'                => $transaksi['id_transaksi'],
                'pelanggan'         => $transaksi['nama_pelanggan'],
                'username'          => $transaksi['username'] ?? 'Kasir',
                'tgl_transaksi'     => $transaksi['tgl_transaksi'],
                'metode_pembayaran' => $transaksi['metode_bayar'] ?? 'CASH',
                'uang_bayar'        => (strtoupper($transaksi['metode_bayar'] ?? '') === 'QRIS') ? $total_akhir : ($transaksi['uang_bayar'] ?? $total_akhir),
                'deskripsi'         => $transaksi['deskripsi'] ?? '',
                
                'subtotal'          => round($subtotal_murni),
                'pajak'             => $persen_pajak,
                'nilai_pajak'       => round($nilai_pajak),
                'total_bayar'       => round($total_akhir),

                'nama_cafe'         => $pengaturan['nama_cafe'] ?? 'CAFFE LEGO',
                'alamat'            => $pengaturan['alamat'] ?? 'Subang, Jawa Barat',
                'pesan_struk'       => $pengaturan['pesan_struk'] ?? 'Terima Kasih!',

                'items'             => []
            ];

            foreach ($detail as $item) {
                $dataJson['items'][] = [
                    'nama'  => $item['nama_menu'] ?? 'Menu Dihapus',
                    'qty'   => $item['qty'] ?? 1,
                    'harga' => $item['harga_satuan'] ?? 0,
                    'total' => $item['subtotal'] ?? ($item['harga_satuan'] * $item['qty'])
                ];
            }

            return $this->response->setJSON($dataJson);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error Sistem: ' . $e->getMessage()
            ]);
        }
    }

    
}
