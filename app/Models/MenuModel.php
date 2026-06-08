<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menu';
    protected $primaryKey       = 'id_menu';
    protected $allowedFields    = ['id_kategori', 'nama_menu', 'harga', 'foto', 'status'];

    public function getMenu($id_kategori = null)
    {
        $builder = $this->db->table($this->table)
            ->select('menu.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = menu.id_kategori');
        if ($id_kategori !== null) {
            $builder->where('menu.id_kategori', $id_kategori);
        }

        return $builder->get()->getResultArray();
    }
}