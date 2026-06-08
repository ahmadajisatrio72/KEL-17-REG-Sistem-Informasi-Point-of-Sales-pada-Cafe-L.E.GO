<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'History - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sidebar-gradient: linear-gradient(135deg, #0f0c29, #302b63, #5f4bd8);
            --button-gradient: linear-gradient(135deg, #6c4cff, #8b6cff);
            --bg-body: #F4F7FE;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { padding: 25px 40px; transition: 0.3s; }
        .card-summary { border: none; border-radius: 25px; padding: 25px 35px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px; }
        .summary-label { color: #8A92A6; font-size: 0.85rem; }
        .summary-value { font-size: 1.8rem; font-weight: 700; color: #0f0c29; }
        .summary-count { font-size: 2.2rem; font-weight: 700; color: #5f4bd8; text-align: right; }
        .card-list-history { border: none; border-radius: 30px; padding: 40px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.04); }
        .item-history { background: #F4F7FE; border-radius: 15px; padding: 15px 25px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .item-history:hover { background: #fff; border: 1px solid #5f4bd8; transform: translateY(-2px); }
        .btn-filter { border-radius: 12px; padding: 8px 20px; font-size: 0.9rem; font-weight: 600; border: none; background: white; color: #8A92A6; box-shadow: 0 4px 10px rgba(0,0,0,0.02); transition: 0.3s; text-decoration: none; }
        .btn-filter.active { background: var(--sidebar-gradient); color: white; }

        @media (max-width: 992px) { .main-content { padding: 20px; } }
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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">History</h1>
                <p class="text-muted small mb-0">Data penjualan berdasarkan periode</p>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted align-items-center pt-2">
            <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
        </div>
    </div>
    
    <div class="d-flex gap-2 mb-4">
        <a href="?filter=harian" class="btn-filter <?= ($filterAktif == 'harian') ? 'active' : '' ?>">Harian</a>
        <a href="?filter=mingguan" class="btn-filter <?= ($filterAktif == 'mingguan') ? 'active' : '' ?>">Mingguan</a>
        <a href="?filter=bulanan" class="btn-filter <?= ($filterAktif == 'bulanan') ? 'active' : '' ?>">Bulanan</a>
    </div>

    <div class="card-summary">
        <div class="row align-items-center">
            <div class="col-8">
                <div class="summary-label">Total Penjualan <?= ucfirst($filterAktif) ?></div>
                <div class="summary-value">Rp <?= number_format($totalPenjualan ?? 0, 0, ',', '.') ?></div>
            </div>
            <div class="col-4 text-end">
                <div class="summary-label">Jumlah Transaksi</div>
                <div class="summary-count"><?= $jmlTransaksi ?? 0 ?></div>
            </div>
        </div>
    </div>

    <div class="card-list-history">
        <h5 class="fw-bold mb-4" style="color: #0f0c29;">Daftar Transaksi</h5>
        <?php if (empty($transaksiHariIni)) : ?>
            <div class="text-center py-5 text-muted small">Belum ada history transaksi untuk periode ini</div>
        <?php else : ?>
            <?php foreach ($transaksiHariIni as $tr) : ?>
                <div class="item-history shadow-sm">
                    <div>
                        <div class="fw-bold" style="color: #0f0c29;">TRX-<?= $tr['id_transaksi'] ?></div>
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            <?= ($filterAktif == 'harian') ? date('H:i', strtotime($tr['tgl_transaksi'])) . ' WIB' : date('d M Y, H:i', strtotime($tr['tgl_transaksi'])) ?>
                        </div>
                    </div>
                    <div class="fw-bold text-success">
                        Rp <?= number_format($tr['total_bayar'], 0, ',', '.') ?>
                    </div>
                </div>
            <?php endforeach; ?>
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