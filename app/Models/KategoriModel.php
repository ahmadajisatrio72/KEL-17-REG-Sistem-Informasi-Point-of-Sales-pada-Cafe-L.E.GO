<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id_kategori';
    protected $allowedFields    = ['nama_kategori', 'foto_kategori'];

    public function getKategoriDenganJumlahMenu()
    {
        return $this->db->table('kategori')
            ->select('kategori.*, COUNT(menu.id_menu) as total_menu')
            ->join('menu', 'menu.id_kategori = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori')
            ->get()->getResultArray();
    }

    public function hapusKategori($id)
    {
        $cekMenu = $this->db->table('menu')
        ->where('id_kategori', $id)
        ->countAllResults();
        
        if ($cekMenu > 0) {
            return false; 
        }
        return $this->delete($id);
    }
}