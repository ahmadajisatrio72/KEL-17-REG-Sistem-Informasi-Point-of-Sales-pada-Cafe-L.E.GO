<style>
    :root {
        --sidebar-gradient: linear-gradient(135deg, #0f0c29, #302b63, #5f4bd8);
        --bg-body: #F4F7FE;
    }
    body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
    
    /* SIDEBAR STYLE (Konsisten dengan halaman lain) */
    /* SIDEBAR STYLE */
/* SIDEBAR STYLE (Versi Tahan Banting di HP) */
.sidebar { 
    background: var(--sidebar-gradient); 
    height: 100dvh; /* Kuncinya di sini, ganti 100vh jadi 100dvh! */
    width: 260px; 
    position: fixed; 
    color: white; 
    padding: 25px 25px 40px 25px; /* Gua tambahin padding bawah 40px biar aman dari tombol navigasi HP */
    box-shadow: 4px 0 15px rgba(0,0,0,0.3); 
    transition: 0.3s; 
    z-index: 1050; 
    display: flex; 
    flex-direction: column; 
    left: 0; 
    top: 0; 
    overflow-y: auto; 
    overflow-x: hidden; 
}
    .sidebar-overlay.show { display: block; }
    .main-content { margin-left: 260px; padding: 25px 40px; transition: 0.3s; }

    .nav-link { color: rgba(255,255,255,0.7); padding: 12px 15px; border-radius: 12px; margin-bottom: 5px; display: flex; align-items: center; transition: 0.3s; text-decoration: none; }
    .nav-link.active, .nav-link:hover { color: white !important; background: rgba(255, 255, 255, 0.15); transform: translateX(5px); }
    .nav-link i { font-size: 1.2rem; margin-right: 15px; }

    #menu-toggle-sidebar { display: none; cursor: pointer; font-size: 2.2rem; color: white; width: fit-content; margin-bottom: 20px; }

    .profile-area { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; }
    .btn-logout { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 12px; width: 100%; padding: 12px; margin-top: 15px; font-weight: 600; transition: 0.3s; display: flex; align-items: center; justify-content: center; text-decoration: none; }

    /* CARD & TABLE STYLE */
    .card-history { border: none; border-radius: 35px; padding: 35px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.05); }
    .table thead th { border: none; color: #8A92A6; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
    .table tbody td { border-top: 1px solid #f8f9fa; padding: 20px 0; }
    
    .badge-pay { background-color: #EBF1FA; color: #5f4bd8; border-radius: 20px; padding: 6px 15px; font-size: 0.75rem; font-weight: 600; }
    
    .summary-box { background-color: #f8f9fa; border-radius: 20px; padding: 25px; }

    @media (max-width: 992px) { 
    .sidebar { left: -260px; } 
    .sidebar.active { left: 0; } 
    .main-content { margin-left: 0; padding: 20px; } 
    #menu-toggle-sidebar { display: block; }
    
    /* 🔴 SELIPIN BARIS INI BIAR TOMBOLNYA DIPAKSA MUNCUL DI HP */
    #menu-toggle { display: block !important; } 
}
</style>

<div class="sidebar d-flex flex-column" id="sidebar">
    <div class="mb-3 ps-1">
    <div class="d-flex align-items-center">
        <div class="logo-box me-3 text-white overflow-hidden d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 10px;">
            <?php if (!empty($pengaturan['logo_cafe']) && file_exists(FCPATH . 'img/' . $pengaturan['logo_cafe'])) : ?>
                <img src="<?= base_url('img/' . $pengaturan['logo_cafe'] . '?v=' . time()) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else : ?>
                <span class="fw-bold">LC</span>
            <?php endif; ?>
        </div>
        
        <div>
            <h5 class="mb-0 fw-bold text-white"><?= $pengaturan['nama_cafe'] ?? 'LEGO Caffe' ?></h5>
            <small style="opacity: 0.6; font-size: 10px; text-transform: uppercase; color: white;">
                <?= session()->get('role') ?? 'GUEST' ?>
            </small>
        </div>
    </div>
    <i class="bi bi-list" id="menu-toggle-sidebar"></i>
</div>

    <nav class="nav flex-column mb-auto">
        <a class="nav-link <?= (url_is('*dashboard*')) ? 'active' : '' ?>" href="<?= base_url(strtolower(session()->get('role')) . '/dashboard') ?>">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <?php if (session()->get('role') === 'ADMIN') : ?>
            <a class="nav-link <?= (url_is('*menu*')) ? 'active' : '' ?>" href="<?= base_url('admin/menu') ?>">
                <i class="bi bi-cup-hot"></i> Kelola Menu
            </a>
            <a class="nav-link <?= (url_is('*kategori*')) ? 'active' : '' ?>" href="<?= base_url('admin/kategori') ?>">
                <i class="bi bi-list-task"></i> Kategori
            </a>
            <a class="nav-link <?= (url_is('*history*')) ? 'active' : '' ?>" href="<?= base_url('admin/history') ?>">
                <i class="bi bi-clock-history"></i> History
            </a>
            <a class="nav-link <?= (url_is('*laporan_keuangan*')) ? 'active' : '' ?>" href="<?= base_url('admin/laporan_keuangan') ?>">
                <i class="bi bi-journal-text"></i> Laporan Keuangan
            </a>
            <a class="nav-link <?= (url_is('*kelola_akun*')) ? 'active' : '' ?>" href="<?= base_url('admin/kelola_akun') ?>">
                <i class="bi bi-person-gear"></i> Kelola User
            </a>
            <a class="nav-link <?= (url_is('*profil*')) ? 'active' : '' ?>" href="<?= base_url('admin/profil') ?>">
                <i class="bi bi-gear-wide-connected"></i> Edit profile saya
            </a>
            <a class="nav-link <?= (url_is('*pengaturan*')) ? 'active' : '' ?>" href="<?= base_url('admin/pengaturan') ?>">
                <i class="bi bi-gear-wide-connected"></i> Pengaturan Cafe
            </a>
        <?php endif; ?>

        <?php if (session()->get('role') === 'KASIR') : ?>
            <a class="nav-link <?= (url_is('*kasir/transaksi*')) ? 'active' : '' ?>" href="<?= base_url('kasir/transaksi') ?>">
                <i class="bi bi-cart3"></i> Transaksi
            </a>
            <a class="nav-link <?= (url_is('*kasir/histori_transaksi*')) ? 'active' : '' ?>" href="<?= base_url('kasir/histori_transaksi') ?>">
                <i class="bi bi-check2-square"></i> history Transaksi
            </a>
            <a class="nav-link <?= (url_is('*kasir/pesanan*')) ? 'active' : '' ?>" href="<?= base_url('kasir/pesanan') ?>">
                <i class="bi bi-clipboard-data"></i> Pesanan
            </a>
            <a class="nav-link <?= (url_is('*profil*')) ? 'active' : '' ?>" href="<?= base_url('kasir/profil') ?>">
                <i class="bi bi-gear-wide-connected"></i> Edit profile saya </a>
        <?php endif; ?>

        <?php if (session()->get('role') === 'KITCHEN') : ?>
            <a class="nav-link <?= (url_is('*menu_status*')) ? 'active' : '' ?>" href="<?= base_url('kitchen/menu_status') ?>">
                <i class="bi bi-egg-fried"></i> Kelola Menu
            </a>
            <a class="nav-link <?= (url_is('*pesanan_status*')) ? 'active' : '' ?>" href="<?= base_url('kitchen/pesanan_status') ?>">
                <i class="bi bi-clock-history"></i> Status Pesanan 
            </a>
            <a class="nav-link <?= (url_is('*profil*')) ? 'active' : '' ?>" href="<?= base_url('kitchen/profil') ?>">
                <i class="bi bi-gear-wide-connected"></i> Edit profile saya </a>
        <?php endif; ?>
    </nav>

    <div class="profile-area" style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 45px; height: 45px; font-size: 1.2rem; overflow: hidden; flex-shrink: 0;">
                <?php if (!empty(session()->get('foto_user')) && file_exists(FCPATH . 'img/profile/' . session()->get('foto_user'))) : ?>
                    <img src="<?= base_url('img/profile/' . session()->get('foto_user') . '?v=' . time()) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else : ?>
                    <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'U', 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div style="overflow: hidden;">
                <div class="fw-bold fs-6 text-white text-truncate" style="max-width: 140px;">
                    <?= session()->get('nama_lengkap') ?? 'User' ?>
                </div>
                <div id="clock" style="font-size: 10px; color: rgba(255,255,255,0.6);">Memuat...</div>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="btn btn-logout text-decoration-none mt-3">
            <i class="bi bi-box-arrow-right me-2"></i> Keluar
        </a>
    </div>
</div>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<script>
    // 1. UPDATE WAKTU REALTIME (Bawaan lu)
    function updateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' };
        const clockEl = document.getElementById('clock');
        if(clockEl) clockEl.innerText = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
    }
    setInterval(updateTime, 1000); updateTime();

    // 2. TOGGLE SIDEBAR DENGAN EVENT DELEGATION GLOBAL (Anti-Freeze & Anti-Eror)
    document.addEventListener('click', function(event) {
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        
        if (!sidebarEl || !overlayEl) return;

        // A. Jika mengklik tombol garis 3 (id="menu-toggle") di halaman mana pun
        if (event.target.closest('#menu-toggle')) {
            sidebarEl.classList.add('active');
            overlayEl.classList.add('show');
        }

        // B. Jika mengklik tombol panah/list di DALAM sidebar (id="menu-toggle-sidebar")
        if (event.target.closest('#menu-toggle-sidebar')) {
            sidebarEl.classList.remove('active');
            overlayEl.classList.remove('show');
        }

        // C. Jika mengklik area transparan gelap (overlay) di luar sidebar
        if (event.target === overlayEl) {
            sidebarEl.classList.remove('active');
            overlayEl.classList.remove('show');
        }
    });
</script>