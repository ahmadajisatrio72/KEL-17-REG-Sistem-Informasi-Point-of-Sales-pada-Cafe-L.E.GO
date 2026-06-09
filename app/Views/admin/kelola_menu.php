<?php
/** * @var array $daftar_kategori 
 * @var array $menu
 * @var string $username
 * @var array $pengaturan
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kelola Menu Admin - Caffe Lego' ?></title>
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
        .table-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .btn-tambah { background: var(--button-gradient); border: none; color: white; border-radius: 12px; padding: 10px 24px; font-weight: 600; box-shadow: 0 4px 12px rgba(108, 76, 255, 0.3); transition: 0.3s; height: 45px; }
        .img-menu { width: 48px; height: 48px; object-fit: cover; border-radius: 12px; }
        .btn-status { border: none; border-radius: 8px; padding: 6px 15px; font-size: 0.75rem; font-weight: 700; cursor: pointer; min-width: 110px; transition: 0.3s; }
        .status-tersedia { background: #E6FFFA; color: #38B2AC; border: 1px solid #38B2AC; }
        .status-habis { background: #FFF5F5; color: #E53E3E; border: 1px solid #E53E3E; }
        .modal-rounded { border-radius: 25px !important; border: none !important; overflow: hidden; }
        .modal-body-custom { padding: 40px !important; }
        .modal-title-custom { color: #2D3748; font-weight: 800; font-size: 1.8rem; margin-bottom: 30px; }
        .label-minimal { font-size: 0.9rem; color: #718096; margin-bottom: 8px; font-weight: 500; }
        .input-minimal { background-color: #ffffff !important; border: 1px solid #E2E8F0 !important; border-radius: 12px !important; padding: 12px 15px !important; color: #4A5568 !important; }
        .btn-simpan-custom { background: #6c4cff !important; color: white !important; border: none !important; border-radius: 12px !important; padding: 12px 45px !important; font-weight: 600 !important; }
        .btn-batal-custom { background: #A3AED0 !important; color: white !important; border: none !important; border-radius: 12px !important; padding: 12px 45px !important; font-weight: 600 !important; }

        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<?= view('sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5 bg-white p-3 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn d-lg-none p-0 text-dark me-3" id="menu-toggle"><i class="bi bi-list fs-1"></i></button>
            <div>
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Kelola Menu</h1>
                <p class="text-muted small mb-0">Tambah, Edit dan Hapus menu</p>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted align-items-center pt-2">
            <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
        </div>
    </div>

    <div class="table-card">
    <div class="d-flex flex-column flex-md-row justify-content-start align-items-md-center gap-3 mb-4">
        
        <div class="d-flex gap-3">
            <div class="position-relative" style="width: 250px;">
                <i class="bi bi-search position-absolute text-muted" style="left: 15px; top: 11px;"></i>
                <input type="text" id="searchInput" class="form-control ps-5 border-0 bg-light shadow-none" placeholder="Cari Menu..." style="border-radius:12px; height: 45px;">
            </div>
            
            <div class="position-relative">
                <select id="categoryFilter" class="form-select border-0 bg-light shadow-none" style="border-radius:12px; height: 45px; min-width: 160px; cursor: pointer;">
                    <option value="all">Semua Kategori</option>
                    <?php foreach ($daftar_kategori as $kat) : ?>
                        <option value="<?= $kat['nama_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="ms-md-auto">
            <button class="btn btn-tambah text-nowrap" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-2"></i> Tambah Menu
            </button>
        </div>
    </div>
</div>

        <div class="table-responsive">
            <table class="table align-middle text-center" id="menuTable">
                <thead>
                    <tr>
                        <th class="text-start" style="width: 40%;">MENU</th>
                        <th>KATEGORI</th>
                        <th>HARGA</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menu)) : foreach ($menu as $m) : ?>
                    <tr class="menu-row">
                        <td class="text-start">
                            <div class="d-flex align-items-center">
                                <img src="<?= base_url('img/menu/' . $m['foto']) ?>" class="img-menu me-3 shadow-sm" onerror="this.src='https://placehold.co/100x100?text=Food'">
                                <span class="fw-bold text-dark text-capitalize menu-name"><?= $m['nama_menu'] ?></span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border menu-category"><?= $m['nama_kategori'] ?></span></td>
                        <td><span class="fw-bold text-dark">Rp <?= number_format($m['harga'], 0, ',', '.') ?></span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn-status <?= ($m['status'] == 'Tersedia') ? 'status-tersedia' : 'status-habis' ?> dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="bi <?= ($m['status'] == 'Tersedia') ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?> me-1"></i> 
                                    <?= (!empty($m['status'])) ? $m['status'] : 'Pilih Status' ?>
                                </button>
                                <ul class="dropdown-menu border-0 shadow modal-rounded p-2">
                                    <li>
                                        <form action="<?= base_url('admin/menu/update_status') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id_menu" value="<?= $m['id_menu'] ?>">
                                            <input type="hidden" name="status_baru" value="Tersedia">
                                            <button type="submit" class="dropdown-item rounded-3 small">Set Tersedia</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="<?= base_url('admin/menu/update_status') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id_menu" value="<?= $m['id_menu'] ?>">
                                            <input type="hidden" name="status_baru" value="Habis">
                                            <button type="submit" class="dropdown-item rounded-3 small">Set Habis</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary border-0 btn-edit-menu" 
                                data-bs-toggle="modal" data-bs-target="#modalEdit"
                                data-id="<?= $m['id_menu'] ?>" 
                                data-nama="<?= $m['nama_menu'] ?>"
                                data-harga="<?= $m['harga'] ?>" 
                                data-kategori="<?= $m['id_kategori'] ?>"
                                data-foto="<?= $m['foto'] ?>"
                                data-status="<?= $m['status'] ?>">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>
                            <a href="<?= base_url('admin/menu/hapus_menu/' . $m['id_menu']) ?>" class="btn btn-sm btn-outline-danger border-0 btn-hapus"><i class="bi bi-trash fs-5"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-rounded">
            <div class="modal-body modal-body-custom">
                <h2 class="modal-title-custom text-center">Tambah Menu</h2>
                <form action="<?= base_url('admin/menu/simpan') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="label-minimal">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control input-minimal" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-minimal">Kategori</label>
                        <select name="id_kategori" class="form-select input-minimal" required>
                            <?php foreach ($daftar_kategori as $kat) : ?>
                                <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-minimal">Harga</label>
                        <input type="number" name="harga" class="form-control input-minimal" required>
                    </div>
                    <div class="mb-4">
                        <label class="label-minimal">Foto Menu</label>
                        <input type="file" name="foto" class="form-control input-minimal" required accept="image/*">
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-simpan-custom">Simpan</button>
                        <button type="button" class="btn btn-batal-custom" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-rounded shadow-lg">
            <div class="modal-body modal-body-custom">
                <h2 class="modal-title-custom text-center">Edit Menu</h2>
                <form action="<?= base_url('admin/menu/update_menu') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_menu" id="edit-id">
                    <input type="hidden" name="foto_lama" id="edit-foto-lama">
                    
                    <div class="mb-3">
                        <label class="label-minimal">Nama Menu</label>
                        <input type="text" name="nama_menu" id="edit-nama" class="form-control input-minimal" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="label-minimal">Kategori</label>
                        <select name="id_kategori" id="edit-kategori" class="form-select input-minimal">
                            <?php foreach ($daftar_kategori as $kat) : ?>
                                <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="label-minimal">Harga (Rp)</label>
                        <input type="number" name="harga" id="edit-harga" class="form-control input-minimal" required>
                    </div>

                    <div class="mb-3">
                        <label class="label-minimal">Status</label>
                        <select name="status" id="edit-status" class="form-select input-minimal" required>
                            <option value="Tersedia">Tersedia</option>
                            <option value="Habis">Habis</option>
                        </select>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="label-minimal d-block text-start">Pratinjau Foto</label>
                        <img src="" id="preview-foto" class="rounded-3 shadow-sm mt-2" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;">
                    </div>
                    
                    <div class="mb-4">
                        <label class="label-minimal">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control input-minimal" accept="image/*">
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-simpan-custom shadow">Simpan Perubahan</button>
                        <button type="button" class="btn btn-batal-custom shadow" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterMenu() {
        const searchText = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const rows = document.querySelectorAll('.menu-row');

        rows.forEach(row => {
            const name = row.querySelector('.menu-name').textContent.toLowerCase();
            const category = row.querySelector('.menu-category').textContent;
            const matchesSearch = name.includes(searchText);
            const matchesCategory = (selectedCategory === 'all' || category === selectedCategory);
            row.style.display = (matchesSearch && matchesCategory) ? "" : "none";
        });
    }

    if(searchInput) searchInput.addEventListener('keyup', filterMenu);
    if(categoryFilter) categoryFilter.addEventListener('change', filterMenu);

    document.querySelectorAll('.btn-edit-menu').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-nama').value = this.dataset.nama;
            document.getElementById('edit-harga').value = this.dataset.harga;
            document.getElementById('edit-kategori').value = this.dataset.kategori;
            document.getElementById('edit-foto-lama').value = this.dataset.foto;
            document.getElementById('edit-status').value = this.dataset.status;
            document.getElementById('preview-foto').src = '<?= base_url('img/menu/') ?>' + this.dataset.foto;
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: 'Hapus Menu?',
                text: "Menu yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c4cff',
                cancelButtonColor: '#A3AED0',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = href; 
                }
            });
        });
    });

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', timer: 2000, showConfirmButton: false });
    <?php endif; ?>
</script>
</body>
</html>