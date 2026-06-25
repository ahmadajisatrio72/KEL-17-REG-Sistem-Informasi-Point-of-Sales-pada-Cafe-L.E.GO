<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kelola Akun - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --button-gradient: linear-gradient(135deg, #6c4cff, #8b6cff);
            --bg-body: #F4F7FE;
        }
        
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        .main-content { margin-left: 260px; padding: 25px 40px; transition: 0.3s; }
        .user-card { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .modal-content { border-radius: 20px; border: none; }
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; background: #f8f9fa; border: 1px solid #eee; }
        .form-control:focus { box-shadow: none; border-color: #6c4cff; background: #fff; }
        .input-group .form-control { border-right: none; }
        .input-group .btn-eye { border: 1px solid #eee; border-left: none; background: #f8f9fa; }
        .input-group .btn-eye:hover { background: #e9ecef; }

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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Kelola User</h1>
                <p class="text-muted small mb-0">Atur akun user </p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
    </div>

    <div class="user-card">
        <div class="d-flex justify-content-end mb-4">
            <button class="btn text-white px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahUser" style="background: var(--button-gradient); border-radius: 12px; border:none; font-weight: 600; height: 46px;">
    <i class="bi bi-person-plus-fill me-2"></i> Tambah User
</button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="text-muted" style="font-size: 12px;">
                    <tr>
                        <th>NAMA LENGKAP</th>
                        <th>USERNAME</th>
                        <th>ROLE</th>
                        <th class="text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)) : ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada user terdaftar.</td></tr>
                    <?php else : ?>
                        <?php foreach ($users as $u) : ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= $u['nama_lengkap'] ?></td>
                                <td class="text-muted">@<?= $u['username'] ?></td>
                                <td><span class="badge bg-light text-primary border rounded-pill px-3 py-2"><?= $u['role'] ?></span></td>
                                <td class="text-end pe-4">
    <button class="btn btn-sm btn-light border rounded-circle me-1" 
            onclick="isiModalEdit('<?= $u['id_user'] ?>', '<?= $u['nama_lengkap'] ?>', '<?= $u['username'] ?>', '<?= $u['role'] ?>')"
            data-bs-toggle="modal" data-bs-target="#modalEditUser" style="width: 32px; height: 32px; padding: 0;">
        <i class="bi bi-pencil text-primary"></i>
    </button>
    
    <?php if ($u['id_user'] == session()->get('id_user')) : ?>
        <button class="btn btn-sm btn-light border rounded-circle opacity-50" 
                style="width: 32px; height: 32px; padding: 0; cursor: not-allowed;" 
                title="Anda tidak bisa menghapus akun yang sedang digunakan">
            <i class="bi bi-shield-lock text-secondary"></i>
        </button>
    <?php else : ?>
        <button class="btn btn-sm btn-light border rounded-circle" 
                onclick="konfirmasiHapus('<?= base_url('admin/kelola_akun/hapus/' . $u['id_user']) ?>')" style="width: 32px; height: 32px; padding: 0;">
            <i class="bi bi-trash text-danger"></i>
        </button>
    <?php endif; ?>
</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
    <table class="table align-middle">
        ...
    </table>
</div>

<hr class="my-5">

<div class="row">
    <div class="col-md-6">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-shield-lock me-2"></i>
        Pengaturan Akses Admin
    </h5>

    <form action="<?= base_url('admin/kelola_akun/update_password_admin') ?>" method="POST">

        <div class="mb-3">
            <label class="form-label">Password Registrasi Saat Ini</label>
            <div class="input-group">
                <input type="password" name="password_lama" class="form-control" required>
                <button class="btn btn-eye toggle-password" type="button">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Registrasi Baru</label>
            <div class="input-group">
                <input type="password" name="password_baru" class="form-control" required>
                <button class="btn btn-eye toggle-password" type="button">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password" name="konfirmasi_password" class="form-control" required>
                <button class="btn btn-eye toggle-password" type="button">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn text-white" style="background: var(--button-gradient);">
            <i class="bi bi-save me-2"></i>
            Simpan Perubahan
        </button>

    </form>
</div>
</div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Tambah Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/kelola_akun/tambah') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Karyawan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="user123" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            <button class="btn btn-eye toggle-password" type="button">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Role Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="ADMIN">ADMIN</option>
                            <option value="KASIR">KASIR</option>
                            <option value="KITCHEN">KITCHEN</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white rounded-pill px-4" style="background: var(--button-gradient);">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Edit Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/kelola_akun/update') ?>" method="POST">
                <input type="hidden" name="id_user" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru (Kosongkan jika tidak ganti)</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                            <button class="btn btn-eye toggle-password" type="button">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Role Akses</label>
                        <select name="role" id="edit_role" class="form-select" required>
                            <option value="ADMIN">ADMIN</option>
                            <option value="KASIR">KASIR</option>
                            <option value="KITCHEN">KITCHEN</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white rounded-pill px-4" style="background: var(--button-gradient);">Update Akun</button>
                </div>
            </form>
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. FUNGSI INJEKSI DATA KARYAWAN KE MODAL EDIT
    function isiModalEdit(id, nama, username, role) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_role').value = role;
    }

    // 2. SWEETALERT KONFIRMASI HAPUS AKSES USER
    function konfirmasiHapus(url) {
        Swal.fire({
            title: 'Hapus akun ini?',
            text: "Akses karyawan akan dicabut permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6c4cff',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) { 
                window.location.href = url; 
            }
        });
    }

    // 3. FLASH DATA ALERT NOTIFICATION SYSTEM
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ 
            icon: 'success', 
            title: 'Berhasil!', 
            text: '<?= session()->getFlashdata('success') ?>', 
            timer: 2000, 
            showConfirmButton: false 
        });
    <?php endif; ?>

    // 🔴 DISINI ADALAH TEMPAT NYELIPIN KODE ERRORNYA:
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({ 
            icon: 'error', 
            title: 'Gagal!', 
            text: '<?= session()->getFlashdata('error') ?>', 
            confirmButtonColor: '#6c4cff' 
        });
    <?php endif; ?>
    // FUNGSI TOGGLE PASSWORD MASSAL (Berlaku buat semua tombol mata)
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        // Cari kotak input yang posisinya pas di sebelah tombol ini
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
</script>
</body>
</html>