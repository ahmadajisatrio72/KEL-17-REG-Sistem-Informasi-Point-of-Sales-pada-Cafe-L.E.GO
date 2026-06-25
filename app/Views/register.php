<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - LEGO Caffe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(135deg,#0f0c29,#302b63,#5f4bd8); margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .reg-card { width: 400px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .logo-box { width: 65px; height: 65px; background: #6c4cff; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .logo-box img { width: 100%; height: 100%; object-fit: cover; }
        .input-group .form-control { border-right: none; }
        .input-group .btn { border: 1px solid #ced4da; border-left: none; background: #f8f9fa; }
        .input-group .btn:hover { background: #e9ecef; }
        
        .form-control, .form-select { border-radius: 8px; border: 1px solid #ced4da; font-size: 14px; padding: 10px 15px; background: #f8f9fa; }
        .form-control:focus, .form-select:focus { background: #fff; border-color: #6c4cff; box-shadow: 0 0 0 0.2rem rgba(108, 76, 255, 0.1); }
        .btn-purple { background: #6c4cff; border: none; border-radius: 8px; padding: 12px; font-size: 14px; font-weight: bold; color: white; transition: 0.3s; }
        .btn-purple:hover { background: #5a3ee6; color: white; }
    </style>
</head>
<body>

<div class="reg-card text-center">
    
    <div class="logo-box shadow-sm">
        <?php if (file_exists(FCPATH . 'img/logo_cafe.png')) : ?>
            <img src="<?= base_url('img/logo_cafe.png?v=' . time()) ?>" alt="Logo Cafe">
        <?php else : ?>
            <span class="fw-bold">LC</span>
        <?php endif; ?>
    </div>
    
    <h4 class="fw-bold mb-1">Buat Akun Baru</h4>
    
    <p class="text-muted small mb-4"> 
    Silakan isi data untuk bergabung 
    </p>

    <form action="<?= base_url('register/save') ?>" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control shadow-sm" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-muted">Username</label>
            <input type="text" name="username" class="form-control shadow-sm" placeholder="Buat username" required>
        </div>
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-muted">Alamat Email</label>
            <input type="email" name="email" class="form-control shadow-sm" placeholder="Masukkan alamat email aktif" required>
        </div>
        
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-muted">Password</label>
            <div class="input-group shadow-sm">
                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Buat password" required>
                <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword">
                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label small fw-bold text-muted">Jabatan (Role)</label>
            <select name="role" id="roleSelect" class="form-select shadow-sm" onchange="toggleOwnerCode()" required>
                <option value="Kasir">Kasir</option>
                <option value="Kitchen">Kitchen</option>
                <option value="Admin">Admin (Owner)</option>
            </select>
        </div>

        <div id="ownerCodeDiv" class="mb-4 text-start" style="display: none;">
            <label class="form-label small fw-bold text-danger">Kode Verifikasi Owner</label>
            <input type="password" name="kode_verifikasi" class="form-control border-danger shadow-sm" placeholder="Masukkan kode rahasia">
        </div>
        
        <button type="submit" class="btn btn-purple w-100 py-2 fw-bold shadow">DAFTAR SEKARANG</button>
        
        <div class="mt-3 text-center small text-muted">
            Sudah punya akun? <a href="<?= base_url('/') ?>" class="text-decoration-none fw-bold" style="color: #6c4cff;">Login</a>
        </div>
    </form>
</div>

<script>
    function toggleOwnerCode() {
        const role = document.getElementById('roleSelect').value;
        const div = document.getElementById('ownerCodeDiv');
        div.style.display = (role === 'Admin') ? 'block' : 'none';
    }

    // 🔴 TAMBAHAN PENTING 2: Script buat fungsi klik tombol matanya
    document.getElementById('btnTogglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        }
    });

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