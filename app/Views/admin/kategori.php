<?php
/**
 * @var array $kategori
 * @var string|null $id_aktif
 * @var array $menu_kategori
 * @var array|null $pengaturan
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kategori - Caffe Lego' ?></title>
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
        .table-card { background: white; border-radius: 25px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .category-card { background: #f8f9ff; border-radius: 20px; transition: 0.3s; border: 2px solid transparent; cursor: pointer; height: 100%; position: relative; }
        .category-card:hover { transform: translateY(-5px); border-color: #6c4cff; background: white; }
        .category-card.active-border { border-color: #6c4cff !important; background: white !important; box-shadow: 0 5px 15px rgba(108, 76, 255, 0.1); }
        .category-card:focus-within { z-index: 10; }
        .icon-box { width: 60px; height: 60px; background: white; border-radius: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .icon-box img { width: 100%; height: 100%; object-fit: cover; }
        .modal-rounded { border-radius: 25px !important; border: none !important; }
        .modal-body-custom { padding: 40px !important; }
        .label-minimal { font-size: 0.9rem; color: #718096; margin-bottom: 8px; font-weight: 600; }
        .input-minimal { background-color: #F8FAFC !important; border: 1px solid #E2E8F0 !important; border-radius: 12px !important; padding: 12px 15px !important; }
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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Kategori Menu</h1>
                <p class="text-muted small mb-0">Kelola kategori </p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <button class="btn text-white px-4 d-flex align-items-center justify-content-center" 
                data-bs-toggle="modal" data-bs-target="#modalTambahKategori" 
                style="background: var(--button-gradient); border-radius: 12px; font-weight: 600; border:none; height: 48px; box-shadow: 0 4px 15px rgba(108, 76, 255, 0.2);">
            <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="table-card mb-4">
        <div class="row g-4">
            <?php foreach ($kategori as $k) : ?>
                <div class="col-md-6 col-lg-4">
                    <div class="category-card p-3 d-flex align-items-center <?= ($id_aktif == $k['id_kategori']) ? 'active-border' : '' ?>" 
                         onclick="window.location.href='<?= base_url('admin/kategori?id=' . $k['id_kategori']) ?>'">
                        <div class="icon-box me-3">
                            <img src="<?= base_url('img/kategori/' . ($k['foto_kategori'] ?: 'default_kategori.png')) ?>" 
                                 onerror="this.src='https://placehold.co/100x100?text=Category'">
                        </div>
                        <div class="flex-grow-1 text-truncate">
                            <h6 class="fw-bold mb-0 text-dark"><?= $k['nama_kategori'] ?></h6>
                            <small class="text-muted"><?= $k['total_menu'] ?? 0 ?> Menu</small>
                        </div>
                        
                        <div class="dropdown" onclick="event.stopPropagation();" style="position: absolute; right: 15px; top: 15px;">
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm btn-edit-kategori" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false" 
                                    style="width: 32px; height: 32px; padding: 0;"
                                    data-id="<?= $k['id_kategori'] ?>" 
                                    data-nama="<?= $k['nama_kategori'] ?>" 
                                    data-foto="<?= $k['foto_kategori'] ?>">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow modal-rounded p-2" style="min-width: 120px; margin-top: 5px !important;">
                                <li>
                                    <a class="dropdown-item small fw-bold rounded-3 py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalEditKategori" data-bs-dismiss="dropdown">
                                        <i class="bi bi-pencil-square me-2 text-primary"></i> Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item text-danger small fw-bold btn-hapus rounded-3 py-2" href="<?= base_url('admin/hapus_kategori/' . $k['id_kategori']) ?>" data-bs-dismiss="dropdown">
                                        <i class="bi bi-trash me-2"></i> Hapus
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($id_aktif) : ?>
    <div class="table-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Daftar Menu</h5>
            <a href="<?= base_url('admin/kategori') ?>" class="btn btn-sm btn-light text-muted rounded-pill px-3">Tutup Filter</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted small">
                        <th>FOTO</th>
                        <th>NAMA MENU</th>
                        <th>HARGA</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menu_kategori)) : ?>
                        <?php foreach ($menu_kategori as $m) : ?>
                        <tr>
                            <td>
                                <img src="<?= base_url('img/menu/' . $m['foto']) ?>" class="rounded-3" style="width: 45px; height: 45px; object-fit: cover;" onerror="this.src='https://placehold.co/45x45'">
                            </td>
                            <td class="fw-bold"><?= $m['nama_menu'] ?></td>
                            <td>Rp <?= number_format($m['harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 <?= ($m['status'] == 'Tersedia') ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                                    <?= $m['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted small">Belum ada menu di kategori ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-rounded shadow-lg">
            <div class="modal-body modal-body-custom">
                <h2 class="text-center fw-bold mb-4" id="modalTambahLabel" style="color: #2D3748;">Tambah Kategori</h2>
                <form action="<?= base_url('admin/tambah_kategori') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="label-minimal">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control input-minimal" placeholder="Contoh: Coffee" required>
                    </div>
                    <div class="mb-4">
                        <label class="label-minimal">Foto Kategori</label>
                        <input type="file" name="foto_kategori" class="form-control input-minimal" required accept="image/*">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn text-white p-3" style="background: var(--button-gradient); border-radius: 12px; font-weight: 600; border:none;">Simpan Kategori</button>
                        <button type="button" class="btn btn-light p-3" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-rounded shadow-lg">
            <div class="modal-body modal-body-custom">
                <h2 class="text-center fw-bold mb-4" style="color: #2D3748;">Edit Kategori</h2>
                <form action="<?= base_url('admin/update_kategori') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_kategori" id="edit-id">
                    <input type="hidden" name="foto_lama" id="edit-foto-lama">
                    
                    <div class="mb-4">
                        <label class="label-minimal">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="edit-nama" class="form-control input-minimal" required>
                    </div>
                    
                    <div class="mb-4 text-center">
                        <label class="label-minimal d-block text-start">Pratinjau Foto Saat Ini</label>
                        <img src="" id="preview-foto-kat" class="rounded-3 shadow-sm mt-2" style="width: 120px; height: 120px; object-fit: cover; border: 1px solid #E2E8F0;">
                    </div>
                    
                    <div class="mb-4">
                        <label class="label-minimal">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto_kategori" class="form-control input-minimal" accept="image/*">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn text-white p-3" style="background: var(--button-gradient); border-radius: 12px; font-weight: 600; border:none;">Simpan Perubahan</button>
                        <button type="button" class="btn btn-light p-3" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-edit-kategori').forEach(btn => {
        btn.addEventListener('click', function() {
            const idKategori = this.getAttribute('data-id');
            const namaKategori = this.getAttribute('data-nama');
            const fotoKategori = this.getAttribute('data-foto');

            document.getElementById('edit-id').value = idKategori;
            document.getElementById('edit-nama').value = namaKategori;
            document.getElementById('edit-foto-lama').value = fotoKategori;
            
            const fotoNama = fotoKategori ? fotoKategori : 'default_kategori.png';
            document.getElementById('preview-foto-kat').src = '<?= base_url('img/kategori/') ?>' + fotoNama;
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const link = this.getAttribute('href');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori tidak bisa dihapus jika masih ada menu di dalamnya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c4cff',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = link; 
                }
            });
        });
    });

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', timer: 2000, showConfirmButton: false });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '<?= session()->getFlashdata('error') ?>' });
    <?php endif; ?>
</script>
</body>
</html>