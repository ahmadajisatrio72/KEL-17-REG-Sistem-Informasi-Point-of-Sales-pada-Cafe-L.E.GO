<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Profil Saya - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #F4F7FE;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { margin-left: 260px; padding: 25px 40px; transition: 0.3s; }
        .card-custom { border: none; border-radius: 25px; padding: 35px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-control { border-radius: 8px; background: #f8f9fa; font-size: 14px; padding: 10px 15px; }
        .form-control:focus { background: #fff; border-color: #6c4cff; box-shadow: 0 0 0 0.2rem rgba(108, 76, 255, 0.1); }
        .btn-purple { background: #6c4cff; color: white; border: none; border-radius: 8px; font-weight: 600; padding: 12px; transition: 0.3s; }
        .btn-purple:hover { background: #5a3ee6; color: white; }
        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<?= view('sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5 bg-white p-3 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn d-lg-none p-0 text-dark me-3" id="menu-toggle">
                <i class="bi bi-list fs-1"></i>
            </button>
            <div>
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Pengaturan Profil </h1>
                <p class="text-muted small mb-0">Kelola informasi pribadi dan foto akun Anda </p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
    </div>

    <div class="card-custom">
        <form action="<?= base_url('kitchen/profil/update') ?>" method="POST" enctype="multipart/form-data">
            <div class="row g-4">
                <div class="col-md-4 text-center border-end d-flex flex-column justify-content-center align-items-center">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle bg-light shadow-sm d-flex align-items-center justify-content-center fw-bold text-primary" style="width: 140px; height: 140px; font-size: 3rem; overflow: hidden;">
                            <?php if (!empty($user['foto_user']) && file_exists(FCPATH . 'img/profile/' . $user['foto_user'])) : ?>
                                <img src="<?= base_url('img/profile/' . $user['foto_user'] . '?v=' . time()) ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php else : ?>
                                <?= strtoupper(substr($user['nama_lengkap'] ?? 'K', 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <label class="form-label small fw-bold text-muted">Foto Profil Kitchen</label>
                    <input type="file" name="foto_user" class="form-control form-control-sm shadow-sm mb-2" accept="image/*" style="max-width: 250px;">
                    <small class="text-muted d-block" style="font-size: 11px;">Format: JPG/PNG. Maks: 2MB</small>
                </div>

                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Bagian / Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control shadow-sm" value="<?= $user['nama_lengkap'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Username Dapur</label>
                        <input type="text" name="username" class="form-control shadow-sm" value="<?= $user['username'] ?? '' ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Password Baru (Kosongkan jika tidak diganti)</label>
                        <input type="password" name="password_baru" class="form-control shadow-sm" placeholder="Masukkan password baru">
                    </div>
                    
                    <button type="submit" class="btn btn-purple px-4 shadow">SIMPAN PERUBAHAN</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', showConfirmButton: false, timer: 2000 });
    <?php endif; ?>
</script>
</body>
</html>