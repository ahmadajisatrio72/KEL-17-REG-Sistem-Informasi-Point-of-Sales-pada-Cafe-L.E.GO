<?php
/**
 * @var array|null $pengaturan
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Pengaturan - Caffe Lego' ?></title>
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
        .card-custom { border: none; border-radius: 25px; padding: 35px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-label-custom { font-weight: 600; color: #4A5568; font-size: 0.9rem; margin-bottom: 8px; }
        .form-control-custom { border-radius: 12px; padding: 12px 15px; border: 1px solid #E2E8F0; background: #F8FAFC; transition: 0.3s; }
        .form-control-custom:focus { border-color: #6c4cff; box-shadow: 0 0 0 3px rgba(108, 76, 255, 0.1); outline: none; background: white; }
        .logo-wrapper { position: relative; width: 140px; height: 140px; margin: 0 auto; }
        .logo-preview-img { width: 100%; height: 100%; border-radius: 20px; object-fit: cover; border: 4px solid #F4F7FE; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .btn-edit-logo { position: absolute; bottom: -5px; right: -5px; background: var(--button-gradient); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }

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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Pengaturan Cafe</h1>
                <p class="text-muted small mb-0">Kelola identitas dan informasi dasar bisnis Anda</p>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted align-items-center pt-2">
            <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card-custom mb-4">
                <form action="<?= base_url('admin/update_pengaturan') ?>" method="post" enctype="multipart/form-data" id="formPengaturan">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4 border-end d-flex flex-column justify-content-center">
                            <label class="form-label-custom d-block mb-3">Logo Cafe</label>
                            <div class="logo-wrapper mb-3">
                                <img src="<?= base_url('img/' . ($pengaturan['logo_cafe'] ?? 'logo_default.png') . '?v=' . time()) ?>"  class="logo-preview-img" id="img-preview">
                                <div class="btn-edit-logo" onclick="document.getElementById('logo-input').click()">
                                    <i class="bi bi-camera-fill"></i>
                                </div>
                            </div>
                            <input type="file" name="logo_cafe" id="logo-input" class="d-none" accept="image/*">
                            <p class="small text-muted mt-2" style="font-size: 11px;">Maksimal 2MB<br>Format: JPG, PNG, WEBP</p>
                        </div>

                        <div class="col-md-8 ps-md-4">
                            <div class="mb-3">
                                <label class="form-label-custom">Nama Cafe</label>
                                <input type="text" name="nama_cafe" class="form-control form-control-custom" value="<?= $pengaturan['nama_cafe'] ?? '' ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-7 mb-3">
                                    <label class="form-label-custom">Nomor Telepon / WA</label>
                                    <input type="text" name="no_telp" class="form-control form-control-custom" value="<?= $pengaturan['no_telp'] ?? '' ?>" placeholder="0812xxxx">
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label-custom">Pajak Resto (%)</label>
                                    <input type="number" name="pajak" class="form-control form-control-custom" value="<?= $pengaturan['pajak'] ?? 10 ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control form-control-custom" rows="2"><?= $pengaturan['alamat'] ?? '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Pesan Footer Struk</label>
                                <textarea name="pesan_struk" class="form-control form-control-custom" rows="2"><?= $pengaturan['pesan_struk'] ?? '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Ukuran Printer Struk (Thermal)</label>
                                <select name="lebar_kertas" class="form-select form-control-custom" style="cursor: pointer;">
                                    <option value="58" <?= (($pengaturan['lebar_kertas'] ?? '58') == '58') ? 'selected' : '' ?>>Printer Portable / Kecil (58mm)</option>
                                    <option value="80" <?= (($pengaturan['lebar_kertas'] ?? '58') == '80') ? 'selected' : '' ?>>Printer Desktop / Besar (80mm)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-light px-4 py-2" style="border-radius: 12px; font-weight: 600;">Batal</a>
                        <button type="submit" class="btn text-white px-5 py-2" style="background: var(--button-gradient); border-radius: 12px; font-weight: 600; border:none;" id="btnSimpan">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card-custom bg-white border-0" style="padding: 25px;">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> Tips</h6>
                <p class="small text-muted mb-2">1. <strong>Logo:</strong> Gunakan gambar rasio 1:1 untuk tampilan terbaik.</p>
                <p class="small text-muted mb-2">2. <strong>Pajak:</strong> Persentase pajak ini muncul otomatis di kasir.</p>
                <p class="small text-muted">3. <strong>Pesan:</strong> Akan muncul di bagian footer struk belanja.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const logoInput = document.getElementById('logo-input');
    const imgPreview = document.getElementById('img-preview');
    
    if (logoInput) {
        logoInput.onchange = evt => {
            const [file] = logoInput.files;
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Error', 'Ukuran file terlalu besar! Maksimal 2MB.', 'error');
                    logoInput.value = "";
                    return;
                }
                imgPreview.src = URL.createObjectURL(file);
            }
        }
    }

    const btnSimpan = document.getElementById('btnSimpan');
    const form = document.getElementById('formPengaturan');
    if (form && btnSimpan) {
        form.onsubmit = () => {
            btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';
            btnSimpan.disabled = true;
        };
    }

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', showConfirmButton: false, timer: 2000 });
    <?php endif; ?>
</script>
</body>
</html>