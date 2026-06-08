
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Kitchen - Caffe Lego' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-body: #F4F7FE;
        }
        
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .main-content { padding: 25px 40px; transition: 0.3s; }
        .card-stat { border: none; border-radius: 25px; padding: 30px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05); height: 160px; position: relative; display: flex; flex-direction: column; justify-content: center; transition: 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .label-gray { color: #8A92A6; font-size: 0.75rem; font-weight: 500; }
        .val-dark { font-size: 1.8rem; font-weight: 700; color: #0f0c29; margin-top: 5px; }
        .date-small { font-size: 0.65rem; color: #ADB5BD; position: absolute; top: 25px; right: 25px; }
        .card-icon { position: absolute; bottom: 25px; right: 25px; font-size: 2rem; color: #5f4bd8; opacity: 0.2; }

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
                <h1 class="fw-bold h3 mb-0" style="color: #0f0c29;">Dashboard Kitchen</h1>
                <p class="text-muted small mb-0">Ringkasan orderan dapur hari ini</p>
            </div>
        </div>
        <i class="bi bi-arrow-clockwise fs-3 text-dark" style="cursor:pointer" onclick="location.reload()" title="Refresh"></i>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Pesanan Aktif</div>
                <div class="val-dark"><?= $total_aktif ?? '0' ?></div>
                <i class="bi bi-graph-up card-icon"></i>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Pesanan yang sedang dibuat</div>
                <div class="val-dark"><?= $total_proses ?? '0' ?></div>
                <i class="bi bi-cart3 card-icon"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6"> 
            <div class="card-stat">
                <div class="date-small">Hari ini</div>
                <div class="label-gray">Total Pesanan yang belum dibuat</div>
                <div class="val-dark"><?= $total_menunggu ?? '0' ?></div>
                <i class="bi bi-book card-icon"></i>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>