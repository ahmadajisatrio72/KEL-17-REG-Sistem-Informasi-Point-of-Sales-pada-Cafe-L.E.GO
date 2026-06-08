<?php
/** * @var string $username
 * @var array   $pesanan
 * @var array   $pengaturan
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Status Pesanan - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #F4F7FE;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { padding: 25px 40px; transition: 0.3s; }
        .order-container { background: white; border-radius: 25px; padding: 30px; box-shadow: var(--card-shadow); min-height: 80vh; }
        .order-card { background: #F8FAFC; border: 1px solid #EDF2F7; border-radius: 15px; padding: 15px; height: 100%; display: flex; flex-column; transition: 0.3s; }
        .badge-status { font-size: 10px; padding: 4px 10px; border-radius: 8px; font-weight: 600; }
        .status-dibuat { background: #E0E7FF; color: #4F46E5; } 
        .status-menunggu { background: #FEF3C7; color: #D97706; } 
        .status-selesai { background: #E6F9F1; color: #00C853; }
        .item-box { background: white; border: 1px solid #E2E8F0; border-radius: 10px; padding: 8px 12px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }
        .btn-action { border: none; border-radius: 10px; padding: 10px; font-weight: 700; width: 100%; transition: 0.3s; margin-top: auto; }
        .btn-mulai { background: #BFDBFE; color: #2563EB; } 
        .btn-selesai { background: #C6F6D5; color: #2F855A; } 

        @media (max-width: 992px) { 
            .main-content { padding: 20px; } 
        }
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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Status Pesanan</h1>
                <p class="text-muted small mb-0">Update Status Pesanan Dapur</p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-3 text-dark" style="cursor:pointer" onclick="location.reload()" title="Refresh"></i>
    </div>

    <div class="d-flex flex-column align-items-end gap-2 mb-4">
        <select id="statusFilter" class="form-select form-select-sm border-1 shadow-sm" style="border-radius: 10px; width: 170px; cursor:pointer; padding: 10px;">
            <option value="semua">Pesanan Aktif</option> 
            <option value="menunggu">Menunggu</option>
            <option value="proses">Sedang Dibuat</option> 
            <option value="selesai">Selesai</option>
        </select>
    </div>

    <div class="order-container">
        <h2 id="totalPesananText" class="fw-bold h4 mb-4" style="color: #0f0c29;">
        0 Pesanan Aktif </h2>

        <div class="row g-4"> 
            <?php if (!empty($pesanan)) : ?>
                <?php foreach ($pesanan as $p) : ?>
                    <div class="col-md-6 col-lg-4 col-xl-3 searchable-item" data-status="<?= strtolower($p['info']['status']) ?>">
                        <div class="order-card shadow-sm h-100 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-primary"><?= $p['info']['no_trx'] ?></h6>
                                    <small class="text-muted" style="font-size: 11px;">
                                        <?= $p['info']['pelanggan'] ?> | <?= date('H:i', strtotime($p['info']['waktu'])) ?>
                                    </small>
                                </div>
                                <span class="badge-status <?php 
                                if(strtolower($p['info']['status']) == 'proses') echo 'status-dibuat';
                                elseif(strtolower($p['info']['status']) == 'selesai') echo 'status-selesai';
                                else echo 'status-menunggu';?>">
                                    <?= (strtolower($p['info']['status']) == 'proses') ? 'Sedang Dibuat' : $p['info']['status'] ?>
                                </span>
                            </div>

                            <div class="flex-grow-1">
    <?php foreach ($p['items'] as $item) : ?>
        <div class="item-box shadow-sm">
            <span class="fw-bold small"><?= $item['menu'] ?></span>
            <span class="badge bg-primary rounded-pill">x<?= $item['qty'] ?></span>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($p['info']['deskripsi'])) : ?>
        <div class="alert alert-warning p-2 mt-2 mb-0 border-0 shadow-sm" style="border-radius: 10px; font-size: 12px;">
            <div class="fw-bold text-warning-dominant mb-1"><i class="bi bi-pencil-square"></i> CATATAN KASIR:</div>
            <span class="text-dark fw-medium"><?= $p['info']['deskripsi'] ?></span>
        </div>
    <?php endif; ?>
    </div>

                            <div class="mt-3">
    <?php if (strtolower($p['info']['status']) == 'menunggu') : ?>
        <a href="<?= base_url('kitchen/update_status/' . $p['info']['id'] . '/proses') ?>" class="btn-action btn-mulai text-center text-decoration-none d-block"> Mulai Buat </a>
    <?php elseif (strtolower($p['info']['status']) == 'proses') : ?>
        <a href="<?= base_url('kitchen/update_status/' . $p['info']['id'] . '/selesai') ?>" class="btn-action btn-selesai text-center text-decoration-none d-block">Selesai</a>
    <?php endif; ?>
</div>

                        </div>
                    </div> 
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-clipboard-x text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Tidak ada pesanan aktif yang perlu diproses.</p>
                </div>
            <?php endif; ?>
        </div> 
    </div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const statusFilter = document.getElementById('statusFilter');
    
    function jalankanFilter() {
        let statusTerm = statusFilter.value.toLowerCase();
        let cards = document.querySelectorAll('.searchable-item');
        let jumlahMuncul = 0; 
        
        cards.forEach(card => {
            let cardStatus = card.getAttribute('data-status').toLowerCase();
            
            if (statusTerm === 'semua') {
                if (cardStatus !== 'selesai') {
                    card.style.setProperty('display', 'block', 'important');
                    jumlahMuncul++; 
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            } else {
                if (cardStatus === statusTerm) {
                    card.style.setProperty('display', 'block', 'important');
                    jumlahMuncul++; 
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            }
        });

        let labelTeks = "Pesanan Aktif";
        if (statusTerm === 'menunggu') labelTeks = "Pesanan Menunggu";
        else if (statusTerm === 'proses') labelTeks = "Pesanan Sedang Dibuat";
        else if (statusTerm === 'selesai') labelTeks = "Pesanan Selesai";

        document.getElementById('totalPesananText').innerText = `${jumlahMuncul} ${labelTeks}`;
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', jalankanFilter);
        jalankanFilter(); 
    }

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>', timer: 1500, showConfirmButton: false });
    <?php endif; ?>
</script>
</body>
</html>