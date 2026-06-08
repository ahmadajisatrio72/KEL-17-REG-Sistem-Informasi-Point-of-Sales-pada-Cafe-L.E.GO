<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - LEGO Caffe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(135deg,#0f0c29,#302b63,#5f4bd8); margin: 0; font-family: 'Segoe UI', sans-serif; }
        .card { width: 380px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: none; }
        
        /* STYLE LOGO KOTAK DINAMIS SINKRON DENGAN LOGIN & SIDEBAR */
        .logo-box { width: 65px; height: 65px; background: #6c4cff; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .logo-box img { width: 100%; height: 100%; object-fit: cover; }
        
        .form-control { border-radius: 8px; border: 1px solid #ced4da; font-size: 14px; padding: 10px 15px; background: #f8f9fa; }
        .form-control:focus { background: #fff; border-color: #6c4cff; box-shadow: 0 0 0 0.2rem rgba(108, 76, 255, 0.1); }
        
        .btn-purple { background: #6c4cff; color: white; border: none; transition: 0.3s; }
        .btn-purple:hover { background: #5a3ee6; color: white; }
    </style>
</head>
<body>
    <div class="card text-center">
        
        <div class="logo-box shadow-sm">
            <?php if (file_exists(FCPATH . 'img/logo_cafe.png')) : ?>
                <img src="<?= base_url('img/logo_cafe.png?v=' . time()) ?>" alt="Logo Cafe">
            <?php else : ?>
                <span class="fw-bold">LC</span>
            <?php endif; ?>
        </div>

        <h4 class="fw-bold mb-1">
            <?php 
                $dbPengaturan = new \App\Models\PengaturanModel();
                $dataCafe = $dbPengaturan->first();
                
                if (!empty($dataCafe) && !empty($dataCafe['nama_cafe'])) {
                    echo $dataCafe['nama_cafe'];
                } else {
                    echo 'LEGO Caffe';
                }
            ?>
        </h4>
        <p class="text-muted small mb-4">Masukkan email Anda untuk verifikasi akun.</p>

        <form action="<?= base_url('lupa_password/proses') ?>" method="POST">
            <div class="mb-4 text-start">
                <label class="form-label small fw-bold text-muted">Email Terdaftar</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-purple w-100 rounded-pill py-2 fw-bold mb-3">CEK EMAIL</button>
            <a href="<?= base_url('/') ?>" class="text-decoration-none small text-muted">Kembali ke Login</a>
        </form>
    </div>

    <script>
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({ 
                icon: 'error', 
                title: 'Gagal', 
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#6c4cff'
            });
        <?php endif; ?>
    </script>
</body>
</html>