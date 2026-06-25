<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Kasir - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --button-gradient: linear-gradient(135deg, #6c4cff, #8b6cff);
            --bg-body: #F4F7FE;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        .main-content { padding: 25px 40px; transition: 0.3s; }
        .card-stat { border: none; border-radius: 25px; padding: 30px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05); height: 160px; position: relative; display: flex; flex-direction: column; justify-content: center; }
        .label-gray { color: #8A92A6; font-size: 0.75rem; font-weight: 500; }
        .val-dark { font-size: 1.6rem; font-weight: 700; color: #0f0c29; margin-top: 5px; }
        .date-small { font-size: 0.65rem; color: #ADB5BD; position: absolute; top: 25px; right: 25px; }
        .card-icon { position: absolute; bottom: 25px; right: 25px; font-size: 1.8rem; color: #0f0c29; opacity: 0.8; }
        .card-recent { border: none; border-radius: 25px; padding: 35px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-top: 20px; }

        @media (max-width: 768px) { 
            .card-stat { 
                height: auto; 
                min-height: 140px; 
                padding: 20px 15px; 
                align-items: center; 
                text-align: center;
            }
            
            .date-small { display: none; }
            .card-icon {
                position: relative;
                top: 0; right: 0; bottom: 0;
                font-size: 2rem;
                margin-bottom: 10px;
                opacity: 0.3; 
            
            .label-gray { font-size: 0.7rem; margin-bottom: 3px; }
            .val-dark { font-size: 1.1rem; margin-top: 0; }
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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Dashboard Kasir</h1>
                <p class="text-muted small mb-0">Ringkasan bisnis hari ini</p>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted align-items-center pt-2">
            <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
        </div>
    </div>

    <div class="row g-4 mb-2">
        <div class="col-6">
    <div class="card-stat">
        <div class="date-small">Hari ini</div>
        <div class="label-gray">Jumlah Transaksi</div>
        
        <div class="val-dark mt-2">
            <?= number_format($jmlTransaksi ?? 0, 0, ',', '.') ?>
        </div>
        
        <i class="bi bi-cart-check-fill card-icon text-success opacity-50"></i>
    </div>
</div>
        <div class="col-6">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Total Pendapatan</div>
                <div class="val-dark">Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?></div>
                <i class="bi bi-cart-check card-icon"></i>
            </div>
        </div>
        <div class="col-6">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Menu Terlaris</div>
                <div class="val-dark text-capitalize"><?= $menuTerlaris ?? '-' ?></div>
                <i class="bi bi-exclamation-circle card-icon"></i>
            </div>
        </div>
        <div class="col-6">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Total Menu</div>
                <div class="val-dark"><?= $totalProduk ?></div>
                <i class="bi bi-clipboard-check card-icon"></i>
            </div>
        </div>
    </div>

    <div class="card-recent">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-clock-fill me-3 fs-4 text-primary"></i>
            <h5 class="fw-bold mb-0">Transaksi Terakhir</h5>
        </div>
        
        <?php if (empty($transaksiTerakhir)) : ?>
            <div class="text-center py-5 text-muted">Belum ada transaksi hari ini</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <tbody>
                        <?php foreach ($transaksiTerakhir as $tr) : ?>
                            <tr>
                                <td width="50px">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-receipt text-primary"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">ID Transaksi #<?= $tr['id_transaksi'] ?? $tr['Id_transaksi'] ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($tr['tgl_transaksi'])) ?> WIB</small>
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    Rp <?= number_format($tr['total_bayar'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' };
        const clockEl = document.getElementById('clock');
        if(clockEl) clockEl.innerText = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateTime, 1000); updateTime();
</script>
</body>
</html>