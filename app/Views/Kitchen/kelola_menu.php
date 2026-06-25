<?php
/** * @var array  $menu
 * @var array  $pengaturan
 * @var array $menu
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kelola Menu Kitchen - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #F4F7FE;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { padding: 25px 40px; transition: 0.3s; }
        .table-card { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .img-menu { width: 48px; height: 48px; object-fit: cover; border-radius: 12px; }`~
        .btn-status { border: none; border-radius: 10px; padding: 8px 18px; font-size: 0.75rem; font-weight: 700; cursor: pointer; min-width: 120px; transition: 0.3s; }
        .status-tersedia { background: #E6FFFA; color: #38B2AC; border: 1px solid #38B2AC; }
        .status-habis { background: #FFF5F5; color: #E53E3E; border: 1px solid #E53E3E; }
        .main-content { padding: 20px; } 
        @media (max-width: 992px) { 
        
        }
    </style>
</head>
<body>

<?= view('sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-start mb-5 bg-white p-3 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn d-lg-none p-0 text-dark me-3" id="menu-toggle">
                <i class="bi bi-list fs-1"></i>
            </button>
            <div>
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Kelola Menu</h1>
                <p class="text-muted small mb-0">Atur ketersediaan menu dapur hari ini</p>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted align-items-center pt-2">
            <i class="bi bi-arrow-clockwise fs-3 text-dark" style="cursor:pointer" onclick="location.reload()" title="Refresh"></i>
        </div>
    </div>

    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="position-relative w-25">
                <i class="bi bi-search position-absolute text-muted" style="left: 15px; top: 11px;"></i>
                <input type="text" id="searchInput" class="form-control ps-5 border-0 bg-light shadow-none" placeholder="Cari Menu..." style="border-radius:12px; height: 45px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle" id="menuTable">
                <thead>
                    <tr class="text-center">
                        <th class="text-start" style="width: 45%;">MENU</th>
                        <th>KATEGORI</th>
                        <th>STATUS</th>
                        <th>GANTI STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($menu as $m) : ?>
                        <tr>
                            <td class="text-start">
                                <div class="d-flex align-items-center">
                                    <img src="<?= base_url('img/menu/' . $m['foto']) ?>" class="img-menu me-3 shadow-sm" onerror="this.src='https://placehold.co/100x100?text=Food'">
                                    <span class="fw-bold text-dark text-capitalize"><?= $m['nama_menu'] ?></span>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $m['nama_kategori'] ?></span></td>
                            <td>
                                <span class="btn-status <?= ($m['status'] == 'Tersedia') ? 'status-tersedia' : 'status-habis' ?> d-inline-block">
                                    <i class="bi <?= ($m['status'] == 'Tersedia') ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?> me-1"></i>
                                    <?= $m['status'] ?>
                                </span>
                            </td>
                            <td>
                                <form action="<?= base_url('kitchen/update_status_menu') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_menu" value="<?= $m['id_menu'] ?>">
                                    <input type="hidden" name="status_sekarang" value="<?= $m['status'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold">
                                        Set <?= ($m['status'] == 'Tersedia') ? 'Habis' : 'Tersedia' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelector('#menuTable tbody').rows;
        for (let row of rows) {
            let name = row.cells[0].textContent.toLowerCase();
            row.style.display = name.includes(filter) ? "" : "none";
        }
    });
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', timer: 2000, showConfirmButton: false });
    <?php endif; ?>
</script>
</body>
</html>