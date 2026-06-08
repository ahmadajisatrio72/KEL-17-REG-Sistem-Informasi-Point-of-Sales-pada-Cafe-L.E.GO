<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Status Pesanan - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --bg-body: #F4F7FE;
            --sidebar-gradient: linear-gradient(135deg, #0f0c29, #302b63, #5f4bd8);
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { padding: 25px 40px; transition: 0.3s; }
        .card-order { border: none; border-radius: 20px; padding: 20px 25px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 15px; transition: 0.3s; }
        .card-order:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
        .badge-status { font-size: 0.65rem; padding: 4px 10px; border-radius: 8px; font-weight: 700; text-transform: uppercase; }
        .status-menunggu { background: #FFF4E5; color: #FFB020; }
        .status-dimasak { background: #EBF1FA; color: #5f4bd8; }
        .status-selesai { background: #E6F9F1; color: #00C853; }
        .trx-id { font-weight: 700; color: #0f0c29; font-size: 0.85rem; }
        .cust-info { font-size: 0.8rem; color: #8A92A6; }
        .menu-name { font-weight: 700; color: #0f0c29; font-size: 1.1rem; margin-top: 5px; }
        .price-text { font-size: 1.5rem; font-weight: 700; color: #5f4bd8; text-align: right; }
        .time-text { font-size: 0.8rem; color: #8A92A6; text-align: right; }
        .btn-reprint {
            background: #EBF1FA;
            color: #5f4bd8;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-reprint:hover {
            background: #5f4bd8;
            color: white;
        }
        /* 📱 Tombol Cetak Struk Android */
        .btn-android {
            background: #E6F9F1;
            color: #00C853;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-android:hover {
            background: #00C853;
            color: white;
        }

        @media (max-width: 992px) { .main-content { padding: 20px; } }
    </style>
</head>
<body>

<?= view('sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn d-lg-none p-0 text-dark me-3" id="menu-toggle">
                <i class="bi bi-list fs-1"></i>
            </button>
            <div>
                <h2 class="fw-bold mb-0" style="color: #0f0c29;">Pesanan</h2>
                <p class="text-muted small mb-0">Pantau progres pesanan secara real-time</p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-3 text-dark" style="cursor:pointer" onclick="location.reload()" title="Refresh"></i>
    </div>

    <div class="d-flex flex-column align-items-end gap-3 mb-4">
        <select id="statusFilter" class="form-select form-select-sm border-1 shadow-sm" style="border-radius: 10px; width: 160px; cursor:pointer; padding: 15px;">
            <option value="semua">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="sedang dibuat">Sedang Dibuat</option>
            <option value="selesai">Selesai</option>
        </select>
    </div>

    <div id="order-list">
        <?php if (empty($orders)) : ?>
            <div class="text-center py-5">
                <i class="bi bi-clipboard-x display-1 text-muted"></i>
                <p class="mt-3 text-muted">Belum ada pesanan masuk hari ini</p>
            </div>
        <?php else : ?>
            <?php foreach ($orders as $row) : ?>
                <div class="card-order shadow-sm searchable-item" data-status="<?= $row['status'] ?>">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <div class="d-flex align-items-center gap-2">
                                <span class="trx-id">#TRX-<?= $row['id_transaksi'] ?></span>
                                <?php 
                                    $st = strtolower($row['status'] ?? 'menunggu');
                                    $badgeClass = "status-" . $st;
                                ?>
                                <span class="badge-status <?= $badgeClass ?>"><?= $row['status'] ?? 'MENUNGGU' ?></span>
                            </div>
                            <div class="cust-info"><?= $row['nama_pelanggan'] ?> | <?= $row['metode_bayar'] ?? 'Tunai' ?></div>
                            <div class="menu-name"><?= $row['qty'] ?>x <?= $row['nama_menu'] ?></div>
                        </div>
                        <div class="col-5 d-flex flex-column align-items-end justify-content-between" style="min-height: 75px;">
                            <div class="time-text mb-1"><?= date('d M Y, H:i', strtotime($row['tgl_transaksi'])) ?></div>
                            <div class="price-text mb-2">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></div>
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-android" onclick="printAndroid(<?= $row['id_transaksi'] ?>)">
                                    <i class="bi bi-phone me-1"></i> HP
                                </button>
                                <button type="button" class="btn-reprint" onclick="reprintStruk(<?= $row['id_transaksi'] ?>)">
                                    <i class="bi bi-printer-fill me-1"></i> Windows
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' };
            const clockEl = document.getElementById('clock');
            if(clockEl) clockEl.innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        const statusFilter = document.getElementById('statusFilter');

        function filterOrders() {
            let statusTerm = statusFilter.value.toLowerCase();
            let cards = document.querySelectorAll('.searchable-item');

            cards.forEach(card => {
                let cardStatus = card.getAttribute('data-status').toLowerCase();

                // Sinkronisasi value 'sedang dibuat' dari dropdown dengan status di database/kartu
                const matchesStatus = (statusTerm === 'semua' || cardStatus === statusTerm);

                if (matchesStatus) {
                    card.style.setProperty('display', 'block', 'important');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            });
        }

        // Pasang event listener ke dropdown saja
        if (statusFilter) {
            statusFilter.addEventListener('change', filterOrders);
        }
    });

    setInterval(() => { 
        location.reload(); 
    }, 30000);

    function reprintStruk(idTransaksi) {
        let baseUrl = '<?= base_url("kasir/cetak_struk") ?>';
        let urlCetak = baseUrl.replace(/\/+$/, '') + '/' + idTransaksi;
        window.open(urlCetak, '_blank');
    }

    function printAndroid(idTransaksi) {
        Swal.fire({ title: 'Menyiapkan Struk...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        let urlJson = '<?= site_url("kasir/get_transaksi_json") ?>/' + idTransaksi;

        fetch(urlJson)
            .then(res => {
                if (!res.ok) throw new Error('Eror Server: ' + res.status);
                return res.json();
            })
            .then(transaksi => {
                if (transaksi.status === 'error') throw new Error(transaksi.message);

                const namaCafe   = transaksi.nama_cafe || 'CAFFE LEGO';
                const alamatCafe = transaksi.alamat || 'Subang, Jawa Barat';
                const pesanStruk = transaksi.pesan_struk || 'Terima Kasih!';
                const kasir      = transaksi.username || 'Kasir';
                const metodeStr  = (transaksi.metode_pembayaran || transaksi.metode_bayar || 'CASH').toUpperCase();
                
                let waktuTransaksi = transaksi.tgl_transaksi || '';
                if (waktuTransaksi) {
                    try {
                        let tglObj = new Date(waktuTransaksi.replace(/-/g, "/"));
                        waktuTransaksi = `${String(tglObj.getDate()).padStart(2, '0')}/${String(tglObj.getMonth() + 1).padStart(2, '0')}/${tglObj.getFullYear()} ${String(tglObj.getHours()).padStart(2, '0')}:${String(tglObj.getMinutes()).padStart(2, '0')}`;
                    } catch (e) {}
                }

                let subtotalMurni = parseInt(transaksi.subtotal || 0);
                let persenPajak   = parseInt(transaksi.pajak || 0);
                let nilaiPajak    = parseInt(transaksi.nilai_pajak || 0);
                let totalBayar    = parseInt(transaksi.total_bayar || 0);
                let uangBayar     = parseInt(transaksi.uang_bayar || 0);
                let kembali       = uangBayar - totalBayar;

                if (metodeStr === 'QRIS') { uangBayar = totalBayar; kembali = 0; }

                const buatTengah = (teks) => {
                    if (!teks) return "";
                    let str = teks.toString().trim();
                    if (str.length <= 32) return " ".repeat(Math.floor((32 - str.length) / 2)) + str;
                    let kata = str.split(" "), barisSkarang = "", hasilAkhir = [];
                    kata.forEach(k => {
                        if ((barisSkarang + k).length < 32) barisSkarang += (barisSkarang === "" ? "" : " ") + k;
                        else { hasilAkhir.push(barisSkarang); barisSkarang = k; }
                    });
                    if (barisSkarang !== "") hasilAkhir.push(barisSkarang);
                    return hasilAkhir.map(b => " ".repeat(Math.max(0, Math.floor((32 - b.length) / 2))) + b).join("\n");
                };

                const buatRataKiriKanan = (kiri, kanan) => kiri + " ".repeat(Math.max(1, 32 - kiri.length - kanan.length)) + kanan;

                let strukText = `${buatTengah(namaCafe.toUpperCase())}\n${buatTengah(alamatCafe)}\n${buatTengah(waktuTransaksi)}\n--------------------------------\n${buatRataKiriKanan(`No    : #${transaksi.id_transaksi || idTransaksi}`, "")}\n${buatRataKiriKanan(`Plgn  : ${transaksi.nama_pelanggan || transaksi.pelanggan}`, "")}\n${buatRataKiriKanan(`Kasir : ${kasir}`, "")}\n--------------------------------\n`;

                let items = typeof transaksi.items === 'string' ? JSON.parse(transaksi.items || '[]') : (transaksi.items || []);
                items.forEach(item => {
                    strukText += (item.nama_menu || item.nama).toUpperCase() + "\n" + buatRataKiriKanan(`${item.qty} x ${parseInt(item.harga).toLocaleString('id-ID')}`, parseInt(item.total || (item.qty * item.harga)).toLocaleString('id-ID')) + "\n";
                });

                strukText += `--------------------------------\n${buatRataKiriKanan("Subtotal:", subtotalMurni.toLocaleString('id-ID'))}\n`;
                if (persenPajak > 0) strukText += buatRataKiriKanan(`Pajak (${persenPajak}%):`, nilaiPajak.toLocaleString('id-ID')) + "\n";
                strukText += `${buatRataKiriKanan("TOTAL:", "Rp " + totalBayar.toLocaleString('id-ID'))}\n--------------------------------\n${buatRataKiriKanan("Metode:", metodeStr)}\n${buatRataKiriKanan("Bayar:", uangBayar.toLocaleString('id-ID'))}\n${buatRataKiriKanan("Kembali:", kembali.toLocaleString('id-ID'))}\n`; 
                
                if (transaksi.deskripsi && transaksi.deskripsi.trim() !== "") strukText += `--------------------------------\nKet: ${transaksi.deskripsi}\n`;
                strukText += `--------------------------------\n${buatTengah(pesanStruk)}\n\n\n`; 

                Swal.close();

                Swal.fire({
                    title: 'Pratinjau Struk',
                    html: `<pre style="text-align: left; background: #f4f6f9; padding: 15px; font-family: monospace; font-size: 13px; border-radius: 5px; white-space: pre-wrap;">${strukText}</pre>`,
                    icon: 'success', showCancelButton: true, confirmButtonText: '🔗 Bagikan Struk', cancelButtonText: 'Selesai', confirmButtonColor: '#28a745', allowOutsideClick: false
                }).then((shareResult) => {
                    if (shareResult.isConfirmed && navigator.share) navigator.share({ title: `Struk #${transaksi.id_transaksi || idTransaksi}`, text: strukText }).catch(console.error);
                    else if (shareResult.isConfirmed) alert("Browser tidak mendukung share. Gunakan Chrome HP!");
                });
            })
            .catch(err => { console.error(err); Swal.fire('Gagal Ambil Data', 'Eror: ' + err.message, 'error'); });
    }
</script>
</body>
</html>