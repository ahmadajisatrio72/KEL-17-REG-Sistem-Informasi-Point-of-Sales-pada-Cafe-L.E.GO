<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Login::index');
$routes->post('login/process', 'Login::process');
$routes->get('login/process', 'Login::index');
$routes->get('logout', 'Login::logout');
$routes->get('register', 'Register::index');
$routes->post('register/save', 'Register::save');
$routes->get('lupa_password', 'Login::lupa_password');
$routes->post('lupa_password/proses', 'Login::proses_lupa_password');
$routes->get('reset_password', 'Login::reset_password');
$routes->post('reset_password/update', 'Login::update_password');



$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::index');
    $routes->get('kelola_akun', 'Admin::kelola_akun');           
    $routes->post('kelola_akun/tambah', 'Admin::simpan_user');         
    $routes->post('kelola_akun/update', 'Admin::update_akun'); 
    $routes->get('kelola_akun/hapus/(:num)', 'Admin::hapus_user/$1');  
    $routes->get('kategori', 'Admin::kategori');                
    $routes->post('tambah_kategori', 'Admin::tambah_kategori'); 
    $routes->post('update_kategori', 'Admin::update_kategori'); 
    $routes->get('hapus_kategori/(:num)', 'Admin::hapus_kategori/$1');
    $routes->get('menu', 'Admin::menu');                        
    $routes->post('menu/simpan', 'Admin::simpan');              
    $routes->post('menu/update_menu', 'Admin::update_menu');    
    $routes->get('menu/hapus_menu/(:num)', 'Admin::hapus_menu/$1'); 
    $routes->post('menu/update_status', 'Admin::update_status');
    $routes->get('profil', 'Admin::profil');
    $routes->post('profil/update', 'Admin::update_profil');
    $routes->get('laporan', 'Admin::laporan');
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('update_pengaturan', 'Admin::update_pengaturan');
    $routes->get('history', 'Admin::histori_transaksi');
    $routes->get('history/detail/(:num)', 'Admin::detail_transaksi/$1');
    $routes->get('laporan_keuangan', 'Admin::laporan_keuangan');
    $routes->post('kelola_akun/update_password_admin', 'Admin::update_password_admin');

});


$routes->group('kasir', function($routes) {
    $routes->get('/', 'Kasir::index');          
    $routes->get('dashboard', 'Kasir::index');  
    $routes->get('transaksi', 'Kasir::transaksi');      
    $routes->post('save_transaksi', 'Kasir::save_transaksi');
    $routes->get('history', 'Kasir::history');          
    $routes->get('cetak_struk/(:num)', 'Kasir::cetak_struk/$1');
    $routes->get('pesanan', 'Kasir::pesanan');
    $routes->get('histori_transaksi', 'Kasir::histori_transaksi');
    $routes->get('profil', 'Kasir::profil'); 
    $routes->post('profil/update', 'Kasir::update_profil');
    $routes->get('get_transaksi_json/(:num)', 'Kasir::get_transaksi_json/$1');
});


$routes->group('kitchen', function($routes) {
    $routes->get('dashboard', 'Kitchen::index');
    $routes->get('menu_status', 'Kitchen::menu_status'); 
    $routes->post('update_status_menu', 'Kitchen::update_status_menu'); 
    $routes->get('pesanan_status', 'Kitchen::pesanan_status');
    $routes->get('update_status/(:any)/(:any)', '\App\Controllers\Kitchen::update_status/$1/$2');
    $routes->get('profil', 'Kitchen::profil');
    $routes->post('profil/update', 'Kitchen::update_profil');
    
});