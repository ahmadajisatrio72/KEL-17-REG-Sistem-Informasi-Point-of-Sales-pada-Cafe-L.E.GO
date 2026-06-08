<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table            = 'pengaturan';
    protected $primaryKey       = 'id_pengaturan';
    protected $allowedFields    = [
        'nama_cafe', 
        'logo_cafe', 
        'no_telp', 
        'alamat', 
        'pajak', 
        'pesan_struk',
        'lebar_kertas', 
    ];
    
    protected $useTimestamps    = false;
}