<?php
/** 
 * @var array  $kategori
 *  @var array  $menu
 */
?>
<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Transaksi - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    :root {
        --button-gradient: linear-gradient(135deg, #6c4cff, #8b6cff);
        --bg-body: #F4F7FE;
    }
    body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
    .main-content { padding: 25px 40px; transition: 0.3s; }
    .header-section { margin-bottom: 30px; }
    .header-section h2 { font-weight: 700; color: #0f0c29; margin-bottom: 0; }
    .header-section p { color: #8A92A6; font-size: 0.9rem; }
    .search-container { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
    .card-menu { 
        border: none; 
        border-radius: 15px; 
        padding: 12px; 
        background: #EBF1FA; 
        transition: 0.3s; 
        cursor: pointer; 
        height: 100px; 
        display: flex; 
        flex-direction: row; 
        align-items: center; 
        gap: 12px; 
    }
    .menu-img {
        width: 76px;
        height: 76px;
        object-fit: cover;
        border-radius: 10px;
        background-color: #cbd5e0;
    }
    .card-menu:hover { background: #DEE7F5; transform: translateY(-5px); }
    .card-menu h6 { font-weight: 700; color: #0f0c29; margin-bottom: 2px; font-size: 0.95rem; }
    .card-menu .price { color: #4a5568; font-size: 0.85rem; font-weight: 600; }
    .card-cart { border: none; border-radius: 30px; padding: 25px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.05); position: sticky; top: 25px; }
    .cart-header { font-weight: 700; color: #0f0c29; font-size: 1.6rem; text-align: center; margin-bottom: 25px; }
    .cart-item-box { background: #F4F7FE; border-radius: 15px; padding: 12px 15px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
    .cart-item-info h6 { font-size: 0.85rem; font-weight: 700; margin: 0; color: #0f0c29; }
    .cart-item-info small { color: #8A92A6; font-size: 0.75rem; }
    .qty-control-box { display: flex; align-items: center; background: white; border-radius: 8px; padding: 2px 8px; gap: 10px; border: 1px solid #E2E8F0; }
    .btn-qty-action { border: none; background: none; font-weight: bold; color: #8A92A6; font-size: 1rem; padding: 0 5px; }
    .btn-trash-action { color: #ef4444; margin-left: 10px; cursor: pointer; font-size: 1.1rem; }
    .total-display-box { border-top: 1px solid #F4F7FE; padding-top: 15px; margin-top: 15px; font-weight: 700; color: #0f0c29; }
    .btn-proses { background: #6c4cff; color: white; border: none; border-radius: 12px; padding: 15px; width: 100%; font-weight: 700; transition: 0.3s; margin-top: 10px; }
    .btn-proses:hover { background: #5f4bd8; transform: translateY(-2px); }
    .btn-proses:disabled { background: #cbd5e0; cursor: not-allowed; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: #8A92A6; margin-bottom: 5px; margin-top: 12px; }
    .form-control, .form-select { border-radius: 10px; border: 1px solid #E2E8F0; padding: 10px; font-size: 0.9rem; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    @media (max-width: 992px) { .main-content { padding: 20px; } }
    </style>
    </head>
<body>

<?= view('sidebar') ?>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5 bg-white p-3 rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn d-lg-none p-0 text-dark me-3" id="menu-toggle">
                <i class="bi bi-list fs-1"></i>
            </button>
            <div>
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Transaksi</h1>
                <p class="text-muted small mb-0">Buat transaksi baru</p>
            </div>
        </div>
        <div class="d-flex gap-3 text-muted align-items-center pt-2">
            <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
        </div>
    </div> 
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="search-container">
                <div class="row g-3">
                    <div class="col-md-9">
                        <div class="input-group border rounded-pill px-3 py-1 bg-light">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchBox" class="form-control bg-transparent border-0 shadow-none" placeholder="Cari Produk...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select rounded-pill border shadow-none" id="filterKat">
                            <option value="semua">Semua</option>
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k['nama_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-4" id="menuGrid">
                    <?php foreach($menu as $m): ?>
                        <div class="col-md-4 menu-item" data-nama="<?= strtolower($m['nama_menu']) ?>" data-kat="<?= $m['nama_kategori'] ?>">
                            <div class="card-menu" onclick="addToCart(<?= $m['id_menu'] ?>, '<?= $m['nama_menu'] ?>', <?= $m['harga'] ?>)">
                                <?php 
                                $nama_foto = $m['foto_menu'] ?? $m['foto'] ?? '';
                                $path_foto = (!empty($nama_foto)) ? base_url('img/menu/' . $nama_foto) : base_url('img/menu/default-food.png');?>
                                <img src="<?= $path_foto ?>" class="menu-img" alt="<?= $m['nama_menu'] ?>" onerror="this.src='https://placehold.co/100x100?text=L.E.GO'">
                                <div class="flex-grow-1">
                                    <h6><?= $m['nama_menu'] ?></h6>
                                    <div class="price">Rp <?= number_format($m['harga'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        <div class="col-lg-4">
            <div class="card-cart">
                <div class="cart-header">Keranjang</div>
        
                <div id="cartItems" class="mb-2">
                    <div class="text-center text-muted small py-4">Keranjang Kosong</div>
                </div>

                <div class="total-display-box">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="color: #8A92A6; font-size: 0.85rem;">Subtotal</span>
                        <span id="subtotalDisplay" style="font-weight: 600;">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #8A92A6; font-size: 0.85rem;">Pajak (<?= $pengaturan['pajak'] ?? 0 ?>%)</span>
                        <span id="pajakDisplay" style="font-weight: 600;">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-2">
                        <span style="color: #0f0c29; font-weight: 700;">TOTAL</span>
                        <span id="totalDisplay" class="fs-5" style="color: #6c4cff; font-weight: 800;">Rp 0</span>
                    </div>
                </div>
                <form id="formTransaksi" action="<?= base_url('kasir/save_transaksi') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="cart_data" id="cartInput">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control shadow-none" placeholder="Masukkan nama..." oninput="cekValidasi()" required>
                <label class="form-label">Deskripsi</label>
                <input type="text" name="deskripsi" class="form-control shadow-none" placeholder="Contoh: Pedas / Tanpa Es">
                <label class="form-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metodeBayar" class="form-select shadow-none" onchange="cekValidasi()">
                    <option value="Cash">Cash</option>
                    <option value="QRIS">QRIS Manual</option>
                </select>

                <div id="sectionCash">
                    <label class="form-label">Uang Diterima</label>
                    <input type="number" id="uangBayar" name="uang_bayar" class="form-control shadow-none" placeholder="0" oninput="cekValidasi()">
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">Kembalian:</small>
                        <small id="textKembalian" class="fw-bold text-success">Rp 0</small>
                    </div>
                </div>

                <button type="submit" class="btn-proses" id="btnSubmit" disabled>Proses Transaksi</button>
            </form> 
        </div>
    </div>
</div>
</div>

<script>
    let cart = [];
    let totalBelanja = 0;
    
    function addToCart(id, nama, harga) {
        const idx = cart.findIndex(i => i.id === id);
        if(idx > -1) cart[idx].qty++;
        else cart.push({ id, nama, price: harga, qty: 1 });
        render();
    }
    
    function changeQty(id, delta) {
        const idx = cart.findIndex(i => i.id === id);
        if (idx > -1) {
            cart[idx].qty += delta;
            if (cart[idx].qty <= 0) cart = cart.filter(i => i.id !== id);
        }
        render();
    }
    
    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        render();
    }

    function render() {
        const list = document.getElementById('cartItems');
        const display = document.getElementById('totalDisplay');
        const subDisplay = document.getElementById('subtotalDisplay'); 
        const pajakDisplay = document.getElementById('pajakDisplay'); 
        const inputJSON = document.getElementById('cartInput');
        const persenPajak = <?= $pengaturan['pajak'] ?? 0 ?>;

        if(cart.length === 0) {
            list.innerHTML = '<div class="text-center text-muted small py-4">Keranjang Kosong</div>';
            display.innerText = 'Rp 0';
            if(subDisplay) subDisplay.innerText = 'Rp 0';
            if(pajakDisplay) pajakDisplay.innerText = 'Rp 0';
            totalBelanja = 0;
            cekValidasi();
            return;
        }

        let subtotal = 0;
        let html = '';
        cart.forEach((item) => {
            subtotal += item.price * item.qty;
            html += `
                <div class="cart-item-box">
                    <div class="cart-item-info">
                        <h6>${item.nama}</h6>
                        <small>Rp ${item.price.toLocaleString()} x ${item.qty}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="qty-control-box">
                            <button type="button" class="btn-qty-action" onclick="changeQty(${item.id}, -1)">-</button>
                            <span class="small fw-bold">${item.qty}</span>
                            <button type="button" class="btn-qty-action" onclick="changeQty(${item.id}, 1)">+</button>
                        </div>
                        <i class="bi bi-trash-fill btn-trash-action" onclick="removeFromCart(${item.id})"></i>
                    </div>
                </div>`;
        });
        
        let nilaiPajak = (subtotal * persenPajak) / 100;
        totalBelanja = subtotal + nilaiPajak;
        list.innerHTML = html;
        
        if(subDisplay) subDisplay.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        if(pajakDisplay) pajakDisplay.innerText = 'Rp ' + nilaiPajak.toLocaleString('id-ID');
        display.innerText = 'Rp ' + totalBelanja.toLocaleString('id-ID');
        
        inputJSON.value = JSON.stringify(cart);
        cekValidasi();
    }
    
    function cekValidasi() {
        const metode = document.getElementById('metodeBayar').value;
        const inputUang = document.getElementById('uangBayar');
        const sectionCash = document.getElementById('sectionCash');
        const btn = document.getElementById('btnSubmit');
        const nama = document.getElementById('nama_pelanggan').value.trim();
        const textKembalian = document.getElementById('textKembalian');
        if (cart.length === 0 || nama === "") {
            btn.disabled = true;
            return;
        }
        if (metode === 'Cash') {
            sectionCash.style.display = 'block'; 
            const bayar = parseInt(inputUang.value) || 0;
            const kembalian = bayar - totalBelanja;
            if (bayar >= totalBelanja && totalBelanja > 0) {
                textKembalian.innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
                btn.disabled = false;
            } else {
                textKembalian.innerText = 'Uang Kurang';
                btn.disabled = true;
            }
        } else {
            sectionCash.style.display = 'none'; 
            inputUang.value = totalBelanja; 
            btn.disabled = false; 
        }
    }
    document.getElementById('formTransaksi').onsubmit = function(e) {
        e.preventDefault();
        const form = this;

        const kirim = () => {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch(form.action, { method: 'POST', body: new FormData(form) })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        title: 'Transaksi Sukses!',
                        text: 'Pilih metode cetak:',
                        icon: 'success',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Android',           
                        denyButtonText: 'Standar (Windows)',     
                        cancelButtonText: 'Tutup',               
                        confirmButtonColor: '#6f42c1',           
                        denyButtonColor: '#dc3545'               
                    }).then((result) => {
                        
                        if (result.isConfirmed) {
                            Swal.fire({ title: 'Menyiapkan Struk...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

let urlJson = '<?= site_url("kasir/get_transaksi_json") ?>/' + data.id_transaksi;

                        fetch(urlJson)
                            .then(res => {
                                if (!res.ok) throw new Error('Eror Server: ' + res.status);
                                return res.json();
                            })
                            .then(transaksi => {
                                if (transaksi.status === 'error') {
                                    throw new Error(transaksi.message);
                                }

                                const namaCafe   = transaksi.nama_cafe || 'CAFFE LEGO';
                                const alamatCafe = transaksi.alamat || 'Subang, Jawa Barat';
                                const pesanStruk = transaksi.pesan_struk || 'Terima Kasih!';
                                const kasir      = transaksi.username || 'Kasir';
                                const metodeStr  = (transaksi.metode_pembayaran || 'CASH').toUpperCase();
                                
                                let waktuTransaksi = transaksi.tgl_transaksi || '';
                                if (waktuTransaksi) {
                                    try {
                                        let tglObj = new Date(waktuTransaksi.replace(/-/g, "/"));
                                        let dd = String(tglObj.getDate()).padStart(2, '0');
                                        let mm = String(tglObj.getMonth() + 1).padStart(2, '0');
                                        let yyyy = tglObj.getFullYear();
                                        let hh = String(tglObj.getHours()).padStart(2, '0');
                                        let min = String(tglObj.getMinutes()).padStart(2, '0');
                                        waktuTransaksi = `${dd}/${mm}/${yyyy} ${hh}:${min}`;
                                    } catch (e) {
                                        console.log("Gagal format tanggal");
                                    }
                                }

                                let subtotalMurni = parseInt(transaksi.subtotal || 0);
                                let persenPajak   = parseInt(transaksi.pajak || 0);
                                let nilaiPajak    = parseInt(transaksi.nilai_pajak || 0);
                                let totalBayar    = parseInt(transaksi.total_bayar || 0);

                                let uangBayar    = parseInt(transaksi.uang_bayar || 0);
                                let kembali      = uangBayar - totalBayar;

                                if (metodeStr === 'QRIS') {
                                    uangBayar = totalBayar;
                                    kembali   = 0;
                                }

                                const buatTengah = (teks) => {
                                    if (!teks) return "";
                                    let str = teks.toString().trim();
                                    if (str.length <= 32) {
                                        let sisa = 32 - str.length;
                                        let spasiKiri = Math.floor(sisa / 2);
                                        return " ".repeat(spasiKiri) + str;
                                    }

                                    let kata = str.split(" ");
                                    let barisSkarang = "";
                                    let hasilAkhir = [];

                                    kata.forEach(k => {
                                        if ((barisSkarang + k).length < 32) {
                                            barisSkarang += (barisSkarang === "" ? "" : " ") + k;
                                        } else {
                                            hasilAkhir.push(barisSkarang);
                                            barisSkarang = k;
                                        }
                                    });
                                    if (barisSkarang !== "") hasilAkhir.push(barisSkarang);

                                    let teksTengah = "";
                                    hasilAkhir.forEach(b => {
                                        let sisa = 32 - b.length;
                                        let spasiKiri = sisa > 0 ? Math.floor(sisa / 2) : 0;
                                        teksTengah += " ".repeat(spasiKiri) + b + "\n";
                                    });
                                    return teksTengah.replace(/\n$/, "");
                                };

                                const buatRataKiriKanan = (kiri, kanan) => {
                                    let sisaSpasi = 32 - kiri.length - kanan.length;
                                    if (sisaSpasi < 1) sisaSpasi = 1;
                                    return kiri + " ".repeat(sisaSpasi) + kanan;
                                };

                                let strukText = "";
                                strukText += buatTengah(namaCafe.toUpperCase()) + "\n"; 
                                strukText += buatTengah(alamatCafe) + "\n";           
                                strukText += buatTengah(waktuTransaksi) + "\n";       
                                strukText += "--------------------------------\n";
                                strukText += buatRataKiriKanan(`No    : #${transaksi.id}`, "") + "\n";
                                strukText += buatRataKiriKanan(`Plgn  : ${transaksi.pelanggan}`, "") + "\n";
                                strukText += buatRataKiriKanan(`Kasir : ${kasir}`, "") + "\n";
                                strukText += "--------------------------------\n";

                                transaksi.items.forEach(item => {
                                    strukText += item.nama.toUpperCase() + "\n"; // 👈 Nama menu aman di sebelah kiri
                                    let qtyHarga = `${item.qty} x ${parseInt(item.harga).toLocaleString('id-ID')}`;
                                    let totalItem = parseInt(item.total).toLocaleString('id-ID');
                                    strukText += buatRataKiriKanan(qtyHarga, totalItem) + "\n";
                                });

                                strukText += "--------------------------------\n";
                                strukText += buatRataKiriKanan("Subtotal:", subtotalMurni.toLocaleString('id-ID')) + "\n";

                                if (persenPajak > 0) {
                                    strukText += buatRataKiriKanan(`Pajak (${persenPajak}%):`, nilaiPajak.toLocaleString('id-ID')) + "\n";
                                }

                                strukText += buatRataKiriKanan("TOTAL:", "Rp " + totalBayar.toLocaleString('id-ID')) + "\n";
                                strukText += "--------------------------------\n";
                                
                                strukText += buatRataKiriKanan("Metode:", metodeStr) + "\n";
                                strukText += buatRataKiriKanan("Bayar:", uangBayar.toLocaleString('id-ID')) + "\n";
                                strukText += buatRataKiriKanan("Kembali:", kembali.toLocaleString('id-ID')) + "\n"; 
                                
                                if (transaksi.deskripsi && transaksi.deskripsi.trim() !== "") {
                                    strukText += "--------------------------------\n";
                                    strukText += `Ket: ${transaksi.deskripsi}\n`;
                                }
                                
                                strukText += "--------------------------------\n";
                                strukText += buatTengah(pesanStruk) + "\n";
                                strukText += "\n\n\n"; 

                                Swal.close();

                                Swal.fire({
                                    title: 'Pratinjau Struk',
                                    html: `<pre style="text-align: left; background: #f4f6f9; padding: 15px; font-family: monospace; font-size: 13px; border-radius: 5px; white-space: pre-wrap;">${strukText}</pre>`,
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: '🔗 Bagikan Struk',
                                    cancelButtonText: 'Selesai',
                                    confirmButtonColor: '#28a745',
                                    allowOutsideClick: false
                                }).then((shareResult) => {
                                    if (shareResult.isConfirmed) {
                                        if (navigator.share) {
                                            navigator.share({ title: `Struk #${transaksi.id}`, text: strukText })
                                            .then(() => { location.reload(); })
                                            .catch(() => { location.reload(); });
                                        } else {
                                            alert("Browser tidak mendukung share. Gunakan Chrome HP!");
                                            location.reload();
                                        }
                                    } else {
                                        location.reload();
                                    }
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Gagal Ambil Data', 'Eror: ' + err.message, 'error');
                            });

                        } else if (result.isDenied) {
                            let baseUrl = '<?= base_url("kasir/cetak_struk") ?>';
                            let urlCetak = baseUrl.replace(/\/+$/, '') + '/' + data.id_transaksi;
                            window.open(urlCetak, '_blank');
                            location.reload();
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Gagal terhubung ke server', 'error'));
        };

        if (document.getElementById('metodeBayar').value === 'QRIS') {
            Swal.fire({ title: 'QRIS', text: 'Konfirmasi pembayaran?', icon: 'warning', showCancelButton: true }).then(res => { if(res.isConfirmed) kirim(); });
        } else { 
            kirim(); 
        }
    };

    function filterProduk() {
        const q = document.getElementById('searchBox').value.toLowerCase();
        const kat = document.getElementById('filterKat').value;
        document.querySelectorAll('.menu-item').forEach(el => {
            const matchNama = el.dataset.nama.includes(q);
            const matchKat = (kat === 'semua' || el.dataset.kat === kat);
            if (matchNama && matchKat) el.style.display = 'block'; else el.style.display = 'none';
        });
    }

    document.getElementById('searchBox').addEventListener('input', filterProduk);
    document.getElementById('filterKat').addEventListener('change', filterProduk);
</script>
</body>
</html>