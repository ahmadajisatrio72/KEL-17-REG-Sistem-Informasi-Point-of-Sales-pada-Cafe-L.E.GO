<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            /* Gradasi disamakan dengan Admin/Login agar konsisten */
            --login-gradient: linear-gradient(135deg, #0f0c29, #302b63, #5f4bd8);
            --button-gradient: linear-gradient(135deg, #6c4cff, #8b6cff);
            --bg-body: #F4F7FE;
        }
        
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Responsive */
        .sidebar { 
            background: var(--login-gradient); 
            min-height: 100vh; 
            width: 260px;
            position: fixed;
            color: white;
            padding: 25px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.3);
            transition: 0.3s;
            z-index: 1050;
        }
        
        /* Main content adjustment */
        .main-content { margin-left: 260px; padding: 40px; transition: 0.3s; }
        
        /* Sidebar Links */
        .nav-link { 
            color: rgba(255,255,255,0.7); 
            padding: 12px 15px; 
            border-radius: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
        .nav-link.active, .nav-link:hover { 
            color: white !important; 
            background: rgba(255, 255, 255, 0.15); 
            transform: translateX(5px);
        }
        .nav-link i { font-size: 1.2rem; margin-right: 15px; }

        /* Card Statistik */
        .card-stat { 
            border: none; border-radius: 20px; padding: 25px; background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); height: 100%;
        }
        .label-gray { color: #8A92A6; font-size: 0.85rem; }
        .val-dark { font-size: 1.8rem; font-weight: 700; color: #0f0c29; margin-top: 5px; }
        .date-small { font-size: 0.65rem; color: #ADB5BD; text-align: right; }

        .logo-box {
            width: 45px; height: 45px; background: var(--button-gradient);
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .btn-logout { 
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; 
            border-radius: 10px; width: 100%; padding: 10px; margin-top: 15px;
            font-weight: 600; transition: 0.3s;
        }
        .btn-logout:hover { background: var(--button-gradient); border-color: transparent; }

        /* Mobile Responsive Breakpoints */
        @media (max-width: 992px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; padding: 20px; }
            .sidebar-overlay {
                display: none; position: fixed; top: 0; left: 0;
                width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040;
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay"></div>

<div class="sidebar d-flex flex-column" id="sidebar">
    <div class="d-flex align-items-center mb-5 ps-1">
        <div class="logo-box me-3">LC</div>
        <div>
            <h5 class="mb-0 fw-bold">LEGO Caffe</h5>
            <small style="opacity: 0.6; font-size: 10px; text-transform: uppercase; letter-spacing: 1px;"><?= $role ?></small>
        </div>
    </div>

    <nav class="nav flex-column mb-auto">
        <a class="nav-link active" href="<?= base_url('kasir/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('kasir/transaksi') ?>"><i class="bi bi-calculator"></i> Transaksi</a>
        <a class="nav-link" href="<?= base_url('kasir/histori') ?>"><i class="bi bi-clock-history"></i> Histori Hari Ini</a>
    </nav>

    <div class="profile-area" style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <div class="d-flex align-items-center">
            <i class="bi bi-person-circle fs-2 me-3 text-white"></i>
            <div>
                <div class="fw-bold fs-6 text-white"><?= $username ?></div>
                <div id="clock" style="font-size: 10px; color: rgba(255,255,255,0.6);">Memuat...</div>
            </div>
        </div>
        <a href="<?= base_url('login') ?>" class="btn btn-logout text-decoration-none d-block text-center mt-3">
            <i class="bi bi-box-arrow-right me-2"></i> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn d-lg-none p-0 text-dark" id="menu-toggle">
            <i class="bi bi-list fs-1"></i>
        </button>
        <div class="d-none d-lg-block"></div>
        <div class="d-flex gap-3 text-muted">
            <i class="bi bi-bell fs-4" style="cursor:pointer"></i>
            <i class="bi bi-arrow-clockwise fs-4" style="cursor:pointer" onclick="location.reload()"></i>
        </div>
    </div>

    <div class="mb-5">
        <h1 class="fw-bold h2" style="color: #0f0c29;">Dashboard Kasir</h1>
        <p class="text-muted small">Ringkasan transaksi anda hari ini</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Total Omzet</div>
                <div class="val-dark">Rp <?= number_format($omzet, 0, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Transaksi</div>
                <div class="val-dark">0</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Total Produk</div>
                <div class="val-dark"><?= $produk ?></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Stok Menipis</div>
                <div class="val-dark">0 <small class="fs-6 fw-normal">item</small></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card-stat" style="min-height: 250px;">
                <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-clock me-2"></i> Transaksi Terakhir</h6>
                <div class="d-flex justify-content-center align-items-center h-75">
                    <p class="text-muted small">Belum ada transaksi.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card-stat" style="min-height: 250px;">
                <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-exclamation-circle me-2"></i> Stok Menipis</h6>
                <div class="d-flex justify-content-center align-items-center h-75">
                    <p class="text-success small">Semua Stok Aman</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        document.getElementById('clock').innerText = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateTime, 1000);
    updateTime();

    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    menuToggle.onclick = () => {
        sidebar.classList.add('active');
        overlay.classList.add('show');
    };

    overlay.onclick = () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('show');
    };
</script>

</body>
</html>