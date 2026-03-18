<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - LEGO Caffe</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
        body {
            height:100vh; display:flex; justify-content:center; align-items:center;
            background: linear-gradient(135deg,#0f0c29,#302b63,#5f4bd8);
        }
        .register-card {
            width:360px; background:#fff; padding:35px; border-radius:14px;
            box-shadow:0 15px 35px rgba(0,0,0,0.25); text-align:center;
        }
        .logo {
            width:55px; height:55px; margin:auto; margin-bottom:10px;
            background:#6c4cff; border-radius:12px; display:flex;
            align-items:center; justify-content:center; color:#fff; font-weight:bold;
        }
        .title { font-size:20px; font-weight:600; }
        .subtitle { font-size:12px; color:#777; margin-bottom:20px; }
        .input-group { text-align:left; margin-bottom:15px; }
        .input-group label { font-size:12px; color:#555; }
        .input-group input, .input-group select {
            width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; margin-top:5px; outline: none;
        }
        button {
            width:100%; padding:12px; border:none; border-radius:8px;
            background:linear-gradient(135deg,#6c4cff,#8b6cff); color:white;
            font-size:14px; cursor:pointer; margin-top:10px; font-weight: 600;
        }
        button:hover { opacity:0.9; }
        .login-link { margin-top:15px; font-size:12px; color:#666; }
        .login-link a { color:#6c4cff; text-decoration:none; font-weight:500; }
    </style>
</head>
<body>

<div class="register-card">
    <div class="logo">LC</div>
    <div class="title">Buat Akun Baru</div>
    <div class="subtitle">Silakan isi data untuk bergabung dengan LEGO Caffe</div>

    <form method="post" action="<?= base_url('register/save') ?>">

        <div class="input-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Buat username" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Buat password" required>
        </div>

        <div class="input-group">
            <label>Role / Posisi</label>
            <select name="role" required>
                <option value="" disabled selected>Pilih Role</option>
                <option value="Admin">Admin</option>
                <option value="Kasir">Kasir</option>
                <option value="Kitchen">Kitchen</option>
            </select>
        </div>

        <button type="submit">Daftar Sekarang</button>

        <div class="login-link">
            Sudah punya akun? 
            <a href="<?= base_url('login') ?>">Masuk di sini</a>
        </div>
    </form>
</div>

</body>
</html>