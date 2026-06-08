<?php 
if (service('toolbar')) {
    service('toolbar')->respond(); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $transaksi['id_transaksi'] ?></title>
<style>
    <?php 
    $lebar_kertas = $pengaturan['lebar_kertas'] ?? '58'; 

    if ($lebar_kertas == '80') {
        $width_body = '72mm';   
        $font_size  = '10pt';   
        $font_item  = '9.5pt';
    } else {
        $width_body = '40mm';   
        $font_size  = '8.5pt';  
        $font_item  = '8pt';  
    }
    ?>

    html, body {
        margin: 0;
        padding: 0;
        background: #fff;
        color: #000 !important;
        font-family: 'Arial Narrow', 'Segoe UI Condensed', 'Liberation Sans Narrow', sans-serif;
        font-weight: 500; 
        -webkit-font-smoothing: antialiased;
    }

    body { 
        width: <?= $width_body ?>; 
        margin: 0 auto; 
        font-size: <?= $font_size ?>; 
        line-height: 1.30; 
        letter-spacing: 0.5px; 
        box-sizing: border-box;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .fw-bold { font-weight: 800 !important; } 
    .line { 
        border-bottom: 1px dashed #000; 
        margin: 4px 0; 
        width: 100%;
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: fixed; 
    }
    td { 
        vertical-align: top; 
        padding: 1.5px 0; 
        word-wrap: break-word; 
    }

    .item-name { font-size: <?= $font_size ?>; text-transform: uppercase; }
    .item-detail { font-size: <?= $font_item ?>; }

    @media print {
        @page { 
            margin: 0; 
            size: auto; 
        }
        body { 
            width: <?= $width_body ?>;
            margin: 0 auto;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        #debug-icon, #debug-bar, .display-errors { display: none !important; }
    }
</style>
</head>
<body onload="window.print(); setTimeout(window.close, 1500);">

    <div class="text-center">
        <strong class="fw-bold" style="font-size: 11pt; letter-spacing: 0.5px;"><?= strtoupper($pengaturan['nama_cafe'] ?? 'CAFFE LEGO') ?></strong><br>
        <span style="font-size: 7.5pt;"><?= $pengaturan['alamat'] ?? 'Subang, Jawa Barat' ?></span><br>
        <small style="font-size: 7.5pt;"><?= date('d/m/Y H:i', strtotime($transaksi['tgl_transaksi'])) ?></small>
    </div>
    
    <div class="line"></div>
    
    <div style="font-size: 8.5pt;">
        No    : #<?= $transaksi['id_transaksi'] ?><br>
        Plgn  : <?= $transaksi['nama_pelanggan'] ?><br>
        Kasir : <?= $username ?>
    </div>
    
    <div class="line"></div>
    
    <table>
        <?php 
        $subtotal_murni = 0; 
        foreach($detail as $d): 
            $subtotal_murni += $d['subtotal'];
        ?>
        <tr>
            <td colspan="2" class="item-name"><?= strtoupper($d['nama_menu']) ?></td>
        </tr>
        <tr>
            <td class="item-detail"><?= $d['qty'] ?> x <?= number_format($d['harga_satuan'], 0, '', '.') ?></td>
            <td class="item-detail text-right"><?= number_format($d['subtotal'], 0, '', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="line"></div>
    
    <table>
        <?php 
            $persen_pajak = $pengaturan['pajak'] ?? 0;
            $nilai_pajak = ($subtotal_murni * $persen_pajak) / 100;
            $total_akhir = $subtotal_murni + $nilai_pajak;
        ?>
        <tr>
            <td style="font-size: 8.5pt;">Subtotal</td>
            <td class="text-right" style="font-size: 8.5pt;"><?= number_format($subtotal_murni, 0, '', '.') ?></td>
        </tr>

        <tr>
            <td style="font-size: 8.5pt;">Pajak (<?= $persen_pajak ?>%)</td>
            <td class="text-right" style="font-size: 8.5pt;"><?= number_format($nilai_pajak, 0, '', '.') ?></td>
        </tr>

        <tr style="border-top: 1px solid #000;">
            <td class="fw-bold" style="padding-top: 4px; font-size: 9.5pt;">TOTAL</td>
            <td class="text-right fw-bold" style="font-size: 10.5pt; padding-top: 4px;">Rp <?= number_format($total_akhir, 0, '', '.') ?></td>
        </tr>
        
        <tr>
            <td style="font-size: 8.5pt; padding-top: 4px;">Metode</td>
            <td class="text-right" style="font-size: 8.5pt; padding-top: 4px;"><?= strtoupper($transaksi['metode_bayar'] ?? 'CASH') ?></td>
        </tr>
        <tr>
            <td style="font-size: 8.5pt;">Bayar</td>
            <td class="text-right" style="font-size: 8.5pt;"><?= number_format($transaksi['uang_bayar'], 0, '', '.') ?></td>
        </tr>
        <tr>
            <td style="font-size: 8.5pt;">Kembali</td>
            <td class="text-right" style="font-size: 8.5pt;">
                <?= number_format($transaksi['uang_bayar'] - $total_akhir, 0, '', '.') ?>
            </td>
        </tr>
        <?php if (!empty($transaksi['deskripsi'])): ?>
        <tr style="border-top: 1px dashed #000;">
            <td colspan="2" style="font-size: 7.5pt; padding-top: 4px; word-wrap: break-word; white-space: normal;">
                <span style="font-style: italic;">Ket: <?= $transaksi['deskripsi'] ?></span>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div class="line"></div>
    
    <div class="text-center" style="margin-top: 8px; font-size: 8.5pt;">
        <strong class="fw-bold"><?= $pengaturan['pesan_struk'] ?? 'Terima Kasih!' ?></strong><br>
    </div>

</body>
</html>