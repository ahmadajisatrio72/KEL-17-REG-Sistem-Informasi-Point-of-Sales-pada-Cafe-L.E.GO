<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LEGO Caffe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(135deg,#0f0c29,#302b63,#5f4bd8); margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .login-card { width: 380px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        
        /* STYLE UNTUK KOTAK LOGO DINAMIS */
        .logo-box { width: 65px; height: 65px; background: #6c4cff; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .logo-box img { width: 100%; height: 100%; object-fit: cover; }
        
        .form-control { border-radius: 8px; border: 1px solid #ced4da; font-size: 14px; padding: 10px 15px; background: #f8f9fa; }
        .form-control:focus { background: #fff; border-color: #6c4cff; box-shadow: 0 0 0 0.2rem rgba(108, 76, 255, 0.1); }
        
        /* STYLE UNTUK INPUT GROUP PASSWORD */
        .input-group-text { border-radius: 0 8px 8px 0; background: #f8f9fa; border: 1px solid #ced4da; border-left: none; cursor: pointer; color: #6c4cff; }
        .password-input { border-right: none; border-radius: 8px 0 0 8px; }

        .btn-purple { background: #6c4cff; border: none; border-radius: 8px; padding: 12px; font-size: 14px; font-weight: bold; color: white; transition: 0.3s; }
        .btn-purple:hover { background: #5a3ee6; color: white; }
        .forgot-link { font-size: 11px; color: #6c4cff; text-decoration: none; font-weight: 600; }
        .forgot-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-card text-center">
    
    <div class="logo-box shadow-sm">
    <?php if (file_exists(FCPATH . 'img/logo_cafe.png')) : ?>
        <img src="<?= base_url('img/logo_cafe.png?v=' . time()) ?>" alt="Logo Cafe">
    <?php else : ?>
        <span class="fw-bold">LC</span>
    <?php endif; ?>
</div>

<h4 class="fw-bold mb-1">
    <?php 
        // Memanggil database langsung dari View secara mandiri
        $dbPengaturan = new \App\Models\PengaturanModel();
        $dataCafe = $dbPengaturan->first();
        
        // Jika data di database ada dan kolom nama_cafe tidak kosong
        if (!empty($dataCafe) && !empty($dataCafe['nama_cafe'])) {
            echo $dataCafe['nama_cafe'];
        } else {
            // Jika database kosong, gunakan nama default ini
            echo 'LEGO Caffe';
        }
    ?>
</h4>

    <form action="<?= base_url('login/process') ?>" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-muted">Username</label>
            <input type="text" name="username" class="form-control shadow-sm" placeholder="Masukkan username" required>
        </div>
        
        <div class="mb-2 text-start">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label small fw-bold text-muted mb-0">Password</label>
                <a href="<?= base_url('lupa_password') ?>" class="forgot-link">Lupa Password?</a>
            </div>
            <div class="input-group shadow-sm">
                <input type="password" name="password" id="password" class="form-control password-input" placeholder="Masukkan password" required>
                <span class="input-group-text" id="togglePassword">
                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                </span>
            </div>
        </div>
        
        <button type="submit" class="btn btn-purple w-100 py-2 fw-bold shadow mt-3">MASUK</button>
        
        <div class="mt-4 small text-muted">
            Belum punya akun? <a href="<?= base_url('register') ?>" class="text-decoration-none fw-bold" style="color: #6c4cff;">Buat Akun</a>
        </div>
    </form>
</div>

<script>
    // LOGIKA LIHAT PASSWORD
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });

    // ALERT SWEETALERT
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', timer: 2000, showConfirmButton: false });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= session()->getFlashdata('error') ?>' });
    <?php endif; ?>
</script>

</body>
</html>