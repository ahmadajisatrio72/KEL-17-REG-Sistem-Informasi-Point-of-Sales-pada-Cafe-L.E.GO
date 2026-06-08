<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Laporan Keuangan - Caffe Lego' ?></title>
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
        .report-card { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .stat-card { border: none; border-radius: 20px; padding: 25px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }

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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Laporan Keuangan</h1>
                <p class="text-muted small mb-0">Rekap pendapatan bulanan</p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <small class="text-muted d-block mb-1">Total Pendapatan Hari Ini</small>
                <h3 class="fw-bold mb-0" style="color: #0f0c29;">Rp <?= number_format($pendapatanHariIni ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <small class="text-muted d-block mb-1">Total Pendapatan Bulan Ini </small>
                <h3 class="fw-bold mb-0 text-primary" id="totalPendapatanFiltered">Rp 0</h3>
            </div>
        </div>
    </div>

    <div class="report-card">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <h5 class="fw-bold mb-0">Tabel Laporan</h5>
            <div class="d-flex gap-2 justify-content-between">
                <div class="d-flex gap-2">
                    <input type="month" id="filterBulan" value="<?= date('Y-m') ?>" class="form-control border-0 bg-light shadow-none" style="border-radius:10px; min-width: 170px;">
                </div>
                <button onclick="exportExcel()" class="btn btn-success px-3" style="border-radius:10px; border:none; font-weight: 600;">
                    <i class="bi bi-file-earmark-excel me-2"></i> Export
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle" id="tabelLaporan">
                <thead class="text-muted" style="font-size: 12px;">
                    <tr>
                        <th>TANGGAL</th>
                        <th class="text-center">JUMLAH TRX</th>
                        <th class="text-end pe-4">PENDAPATAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)) : ?>
                        <tr class="empty-row"><td colspan="3" class="text-center py-5 text-muted">Belum ada data laporan.</td></tr>
                    <?php else : ?>
                        <?php foreach ($laporan as $row) : ?>
                            <tr class="data-row" 
                                data-bulan="<?= date('Y-m', strtotime($row['tanggal'])) ?>" 
                                data-revenue="<?= $row['total_pendapatan'] ?>">
                                <td class="text-muted"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                <td class="text-center fw-bold"><?= $row['jml_transaksi'] ?></td>
                                <td class="text-end pe-4 fw-bold text-success">Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr id="noDataRow" style="display: none;"><td colspan="3" class="text-center py-5 text-muted">Tidak ada data laporan pada bulan ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const filterInput = document.getElementById('filterBulan');
    const totalBox = document.getElementById('totalPendapatanFiltered');

    function jalankanFilterBulanan() {
        const bulanDipilih = filterInput.value; 
        const rows = document.querySelectorAll('.data-row');
        let totalPendapatan = 0;
        let adaData = false;

        rows.forEach(row => {
            const bulanRow = row.getAttribute('data-bulan');
            const revenueRow = parseInt(row.getAttribute('data-revenue')) || 0;

            if (bulanRow === bulanDipilih) {
                row.style.display = ""; 
                totalPendapatan += revenueRow; 
                adaData = true;
            } else {
                row.style.display = "none"; 
            }
        });

        const noDataRow = document.getElementById('noDataRow');
        if (noDataRow) {
            noDataRow.style.display = adaData ? "none" : "";
        }

        totalBox.innerText = "Rp " + totalPendapatan.toLocaleString('id-ID');
    }

    if (filterInput) {
        filterInput.addEventListener('change', jalankanFilterBulanan);
        window.addEventListener('DOMContentLoaded', jalankanFilterBulanan);
    }

    function exportExcel() {
        const bulanDipilih = filterInput.value;
        const totalText = totalBox.innerText;
        let barisHtmlClean = "";
        document.querySelectorAll('.data-row').forEach(row => {
            if (row.style.display !== "none") {
                barisHtmlClean += row.outerHTML;
            }
        });

        if (barisHtmlClean === "") {
            barisHtmlClean = `<tr><td colspan="3" style="text-align:center; padding:20px; color:#999;">Tidak ada data laporan pada bulan ini.</td></tr>`;
        }

        const footerExtra = `
            <tfoot>
                <tr style="font-weight:bold; background-color: #f8f9fa;">
                    <td colspan="2" style="border: 1px solid #cccccc; text-align: center; padding: 8px;">TOTAL KESELURUHAN BULAN INI</td>
                    <td style="border: 1px solid #cccccc; text-align: right; color: #198754; padding: 8px; padding-right: 20px;">${totalText}</td>
                </tr>
            </tfoot>`;

        const styleText = `
            <style>
                table { border-collapse: collapse; font-family: 'Segoe UI', sans-serif; }
                th { background-color: #5f4bd8; color: #ffffff; border: 1px solid #cccccc; padding: 8px; text-align: center; font-weight: bold; }
                td { border: 1px solid #cccccc; padding: 6px; text-align: left; }
                .text-end { text-align: right; }
                .text-center { text-align: center; }
            </style>`;
        
        const template = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="utf-8">
                ${styleText}
            </head>
            <body>
                <h3 style="text-align:center; font-family:'Segoe UI'; margin-bottom: 5px;">LAPORAN KEUANGAN BULANAN </h3>
                <p style="text-align:center; font-family:'Segoe UI'; font-size:12px; margin-bottom:15px;">Periode Bulan: ${bulanDipilih}</p>
                <table>
                    <thead>
                        <tr style="background-color: #5f4bd8; color: #ffffff;">
                            <th>TANGGAL</th>
                            <th>JUMLAH TRX</th>
                            <th>PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${barisHtmlClean}
                    </tbody>
                    ${footerExtra}
                </table>
            </body>
            </html>`;

        const blob = new Blob(['\ufeff' + template], { type: 'application/vnd.ms-excel;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const downloadLink = document.createElement('a');
        
        downloadLink.href = url;
        downloadLink.download = `Laporan_Bulan_${bulanDipilih}.xls`;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
</body>
</html>