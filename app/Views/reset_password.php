<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password Baru - LEGO Caffe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg,#0f0c29,#302b63,#5f4bd8); font-family: 'Segoe UI', sans-serif; }
        .card { width: 380px; border-radius: 15px; border: none; }
        .input-group-text { cursor: pointer; background: white; border-left: none; }
        .form-control { border-right: none; }
        .form-control:focus { border-color: #dee2e6; box-shadow: none; }
    </style>
</head>
<body>
    <div class="card p-4 shadow-lg text-center">
        <h4 class="fw-bold mb-3">Password Baru</h4>
        <p class="text-muted small">Silakan buat password baru untuk akun:<br><b><?= session()->getFlashdata('email_reset') ?></b></p>
        
        <form action="<?= base_url('reset_password/update') ?>" method="POST">
            <input type="hidden" name="email" value="<?= session()->getFlashdata('email_reset') ?>">
            
            <div class="mb-4 text-start">
                <label class="form-label small fw-bold">Ketik Password Baru</label>
                <div class="input-group">
                    <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Minimal 5 karakter" required autofocus>
                    <span class="input-group-text" id="togglePassword">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold" style="background: #6c4cff; border:none;">SIMPAN & LOGIN</button>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password_baru');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Cek tipe input: jika password ubah ke text, dan sebaliknya
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Ubah icon mata
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>