<?php
/**
 * @var array $all_transaksi
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Histori Transaksi - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-body: #F4F7FE;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* LAYOUT MAIN CONTENT UTAMA */
        .main-content { margin-left: 260px; padding: 25px 40px; transition: 0.3s; }

        /* CARD & TABLE STYLE */
        .card-history { border: none; border-radius: 35px; padding: 35px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.05); }
        .table thead th { border: none; color: #8A92A6; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        .table tbody td { border-top: 1px solid #f8f9fa; padding: 20px 0; }
        
        .badge-pay { background-color: #EBF1FA; color: #5f4bd8; border-radius: 20px; padding: 6px 15px; font-size: 0.75rem; font-weight: 600; }
        .btn-view { color: #6c4cff; background: #f0ebff; border: none; border-radius: 10px; padding: 8px 12px; display: inline-block; transition: 0.3s; }
        .btn-view:hover { color: #fff; background: #6c4cff; }

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
        <button class="btn d-lg-none p-0 text-dark me-3" id="menu-toggle">
                <i class="bi bi-list fs-1"></i>
            </button>
        <div>
            <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Histori Transaksi</h1>
            <p class="text-muted small mb-0">Semua riwayat transaksi</p>
        </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
    </div>

    <div class="card-history">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 160px;">
                    <small class="text-muted d-block mb-1" style="font-size: 10px; font-weight: 700;">DARI :</small>
                    <input type="date" id="tglMulai" class="form-control border-0 shadow-none py-2" style="border-radius: 12px; background-color: #f8f9fa; color: #8A92A6; font-size: 13px;">
                </div>
                <div style="width: 160px;">
                    <small class="text-muted d-block mb-1" style="font-size: 10px; font-weight: 700;">SAMPAI :</small>
                    <input type="date" id="tglSelesai" class="form-control border-0 shadow-none py-2" style="border-radius: 12px; background-color: #f8f9fa; color: #8A92A6; font-size: 13px;">
                </div>
            </div>
            <button onclick="exportTableToExcel('tabelTransaksi', 'Laporan-Transaksi')" class="btn btn-success px-4 py-2 shadow-sm" style="border-radius: 12px; font-weight: 600; background-color: #2DCE89; border: none;">
                <i class="bi bi-box-arrow-up-right me-2"></i> Export Excel
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle" id="tabelTransaksi">
                <thead>
                    <tr>
                        <th width="150">ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($all_transaksi)) : ?>
                        <tr id="noData">
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi di database.</td>
                        </tr>
                    <?php else : ?>
                        <tr id="noDataFilter" style="display: none;">
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada transaksi pada rentang tanggal ini.</td>
                        </tr>
                        
                        <?php foreach ($all_transaksi as $tr) : ?>
                            <tr class="tr-row" data-date="<?= date('Y-m-d', strtotime($tr['tgl_transaksi'])) ?>">
                                <td class="fw-bold" style="color: #0f0c29;">TRX-<?= $tr['id_transaksi'] ?></td>
                                <td class="text-muted small"><?= date('d M Y, H.i', strtotime($tr['tgl_transaksi'])) ?></td>
                                <td class="text-muted"><?= $tr['nama_kasir'] ?? 'Kasir' ?></td>
                                <td class="fw-bold text-success">Rp <?= number_format($tr['total_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge-pay"><?= strtoupper($tr['metode_bayar'] ?? 'CASH') ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/history/detail/'.$tr['id_transaksi']) ?>" class="btn-view">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const tglMulai = document.getElementById('tglMulai');
    const tglSelesai = document.getElementById('tglSelesai');
    const rows = document.querySelectorAll('.tr-row');
    const noDataFilter = document.getElementById('noDataFilter');

    function filterByDate() {
        const start = tglMulai.value;
        const end = tglSelesai.value;
        let adaData = false;

        rows.forEach(row => {
            const rowDate = row.getAttribute('data-date');
            
            if (!start && !end) {
                row.style.display = '';
                adaData = true;
            } else if (start && !end) {
                const match = rowDate >= start;
                row.style.display = match ? '' : 'none';
                if(match) adaData = true;
            } else if (!start && end) {
                const match = rowDate <= end;
                row.style.display = match ? '' : 'none';
                if(match) adaData = true;
            } else {
                const match = rowDate >= start && rowDate <= end;
                row.style.display = match ? '' : 'none';
                if(match) adaData = true;
            }
        });

        if(noDataFilter) {
            noDataFilter.style.display = adaData ? 'none' : '';
        }
    }

    if(tglMulai) tglMulai.addEventListener('change', filterByDate);
    if(tglSelesai) tglSelesai.addEventListener('change', filterByDate);

    function exportTableToExcel(tableID, filename = ''){
    // 1. Ambil elemen tabel asli dari halaman
    const tableSelect = document.getElementById(tableID);
    if (!tableSelect) return;
    const tableClone = tableSelect.cloneNode(true);
    const rows = tableClone.querySelectorAll('tr');
    rows.forEach(row => {
        if (row.lastElementChild) {
            row.removeChild(row.lastElementChild);
        }
    });

    const tableHTML = tableClone.outerHTML;
    const metaText = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                    <head>
                    <meta charset="utf-8">
                    </x:WorksheetOptions>
                            </x:ExcelWorksheet>
                        </x:ExcelWorksheets>
                        </x:ExcelWorkbook>
                    </xml>
                    <![endif]-->
                    <style>
                        /* Gaya khusus untuk memastikan garis border hitam tipis muncul di Excel */
                        table { border-collapse: collapse; }
                        th, td { border: 1px solid #cccccc; padding: 6px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                    </style>
                    </head>
                    <body>
                        ${tableHTML}
                    </body>
                    </html>`;

    const blob = new Blob(['\ufeff' + metaText], {
        type: 'application/vnd.ms-excel;charset=utf-8;'
    });

    filename = filename ? filename + '.xls' : 'excel_data.xls';

    if (navigator.msSaveOrOpenBlob) {
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        const downloadLink = document.createElement("a");
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = filename;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
}
</script>
</body>
</html>rgb(80, 70, 70)