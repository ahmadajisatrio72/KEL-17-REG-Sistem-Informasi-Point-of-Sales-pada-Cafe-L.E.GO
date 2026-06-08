<?php
/**
 * @var array $transaksi
 * @var array $detail
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Detail Transaksi - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-body: #F4F7FE;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { margin-left: 260px; padding: 25px 40px; transition: 0.3s; }
        .card-history { border: none; border-radius: 35px; padding: 35px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.05); }
        .table thead th { border: none; color: #8A92A6; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        .table tbody td { border-top: 1px solid #f8f9fa; padding: 20px 0; }
        
        .badge-pay { background-color: #EBF1FA; color: #5f4bd8; border-radius: 20px; padding: 6px 15px; font-size: 0.75rem; font-weight: 600; }
        
        .summary-box { background-color: #f8f9fa; border-radius: 20px; padding: 25px; }

        @media (max-width: 992px) { 
            .main-content { margin-left: 0; padding: 20px; } 
        }
    </style>
</head>
<body>

<?= view('sidebar') ?>

<div class="main-content">
   
     <div class="d-flex justify-content-between align-items-center mb-5 bg-white p-3 rounded-4 shadow-sm">
    <div class="d-flex align-items-center">
        <div>
            <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Rincian Transaksi</h1>
            <p class="text-muted small mb-0">Detail data pesanan #TRX-<?= $transaksi['id_transaksi'] ?></p>
        </div>
        <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
    </div>

    <div class="card-history">
        <div class="row mb-5 align-items-start">
            <div class="col-md-6">
                <p class="text-muted small mb-1">DIPROSES OLEH</p>
                <h5 class="fw-bold" style="color: #0f0c29;"><?= $transaksi['nama_kasir'] ?? 'Staff Caffe Lego' ?></h5>
                <p class="text-muted small mt-3 mb-1">WAKTU TRANSAKSI</p>
                <p class="fw-bold m-0"><?= date('d M Y, H:i', strtotime($transaksi['tgl_transaksi'])) ?></p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <p class="text-muted small mb-2">METODE PEMBAYARAN</p>
                <span class="badge-pay px-4 py-2" style="font-size: 0.9rem;">
                    <i class="bi bi-wallet2 me-2"></i><?= strtoupper($transaksi['metode_bayar'] ?? 'CASH') ?>
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th width="350">Nama Menu</th>
                        <th class="text-center">Harga Satuan</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail as $item) : ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: #0f0c29;"><?= $item['nama_menu'] ?></div>
                            <small class="text-muted">ID Menu: #<?= $item['id_menu'] ?></small>
                        </td>
                        <td class="text-center text-muted">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                        <td class="text-center fw-bold"><?= $item['qty'] ?></td>
                        <td class="text-end fw-bold" style="color: #0f0c29;">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-5 justify-content-end">
            <div class="col-md-5">
                <div class="summary-box">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Belanja</span>
                        <span class="fw-bold">Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Uang Bayar</span>
                        <span class="text-dark">Rp <?= number_format($transaksi['uang_bayar'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <hr style="border-top: 2px dashed #dee2e6;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold" style="color: #0f0c29;">Kembalian</span>
                        <span class="fw-bold fs-4" style="color: #2DCE89;">
                            Rp <?= number_format(($transaksi['uang_bayar'] ?? 0) - $transaksi['total_bayar'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>