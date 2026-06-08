<?php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        // 1. Cek apakah user sudah login?
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('/'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $role = session()->get('role'); // Ambil role dari session (ADMIN/KASIR)
        $uri = $request->getUri()->getPath(); // Ambil alamat URL yang dituju

        // 2. ATURAN PENJAGAAN
        // Jika buka alamat admin/* tapi role-nya BUKAN ADMIN, tendang!
        if (strpos($uri, 'admin') !== false && $role !== 'ADMIN') {
            return redirect()->to(base_url('/'))->with('error', 'Akses Ditolak! Anda bukan Admin.');
        }

        // Jika buka alamat kasir/* tapi role-nya BUKAN KASIR, tendang!
        if (strpos($uri, 'kasir') !== false && $role !== 'KASIR') {
            return redirect()->to(base_url('/'))->with('error', 'Akses Ditolak! Anda bukan Kasir.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}