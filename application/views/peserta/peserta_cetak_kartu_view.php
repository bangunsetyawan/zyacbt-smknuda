<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>Kartu Login Ujian | <?php echo html_escape($cbt_nama); ?></title>
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>favicon-32x32.png?v=2.0" />
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>favicon-16x16.png?v=2.0" />
	<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico?v=2.0" type="image/x-icon" />
	<link rel="apple-touch-icon" href="<?php echo base_url(); ?>apple-touch-icon.png?v=2.0" />
	<style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            background: #e5e7eb;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
        }

        /* Toolbar layar (otomatis hilang saat print) */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #1f2937;
            color: #fff;
            padding: 9px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .print-toolbar .info {
            font-size: 13px;
        }
        .print-toolbar .info strong {
            color: #93c5fd;
        }
        .print-toolbar .actions {
            display: flex;
            gap: 8px;
        }
        .btn-tool {
            border: none;
            border-radius: 4px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-print {
            background: #10b981;
            color: #fff;
        }
        .btn-print:hover { background: #059669; }
        .btn-close {
            background: #4b5563;
            color: #fff;
        }
        .btn-close:hover { background: #374151; }

        /* Lembar kertas */
        .sheet-wrap {
            width: <?php echo ($kertas === 'f4') ? '215mm' : '210mm'; ?>;
            margin: 14px auto 28px auto;
            background: #fff;
            padding: 6mm;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        }

        /*
         * Ukuran tiap kartu: 92mm x 64mm (besar, mudah dibaca)
         * Grid 2 kolom x 4 baris = 8 kartu/lembar A4 (lebar 2*92 + 1*6 = 190mm <= 198mm,
         * tinggi 4*64 + 3*6 = 274mm <= 285mm). F4 juga 8 kartu/lembar (2x4).
         * Hitam-putih saja.
         */
        .slip-grid {
            display: grid;
            grid-template-columns: repeat(2, 92mm);
            justify-content: center;
            gap: 6mm 6mm;
        }

        .slip-cut {
            width: 92mm;
            height: 64mm;
            border: 1px dashed #666;
            padding: 0;
            break-inside: avoid;
            page-break-inside: avoid;
            background: #fff;
        }

        .slip-box {
            width: 100%;
            height: 100%;
            border: 1.5px solid #000;
            border-radius: 2mm;
            padding: 3mm 3.5mm;
            background: #fff;
            color: #000;
            position: relative;
            overflow: hidden;
        }

        .slip-head {
            border-bottom: 1px solid #000;
            padding-bottom: 1.5mm;
            margin-bottom: 2mm;
            text-align: center;
            line-height: 1.2;
        }
        .slip-title {
            font-size: 11pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .slip-sub {
            font-size: 8pt;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .slip-nama {
            font-size: 13pt;
            font-weight: 700;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Baris label : nilai — titik dua sejajar lurus */
        .slip-cred {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
            font-size: 9pt;
        }
        .slip-cred td {
            padding: 1.2mm 0 0 0;
            line-height: 1.2;
            vertical-align: baseline;
        }
        .slip-cred td.lbl {
            width: 22mm;
            white-space: nowrap;
        }
        .slip-cred td.sep {
            width: 4mm;
        }
        .cred-mono {
            font-family: "Courier New", Courier, monospace;
            font-weight: 700;
            font-size: 14pt;
            letter-spacing: 1px;
        }

        .slip-qr {
            position: absolute;
            right: 3.5mm;
            bottom: 7mm;
            width: 16mm;
            text-align: center;
        }
        .slip-qr img {
            width: 16mm;
            height: 16mm;
        }
        .slip-qr .cap {
            font-size: 6.5pt;
            margin-top: 0.3mm;
        }

        .slip-url {
            position: absolute;
            left: 3.5mm;
            right: 3.5mm;
            bottom: 2mm;
            border-top: 1px solid #000;
            padding-top: 1.2mm;
            text-align: center;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.2px;
            line-height: 1.3;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #444;
        }

        @page {
            size: <?php echo ($kertas === 'f4') ? '215mm 330mm' : 'A4 portrait'; ?>;
            margin: 6mm;
        }

        @media print {
            body {
                background: #fff;
            }
            .print-toolbar {
                display: none !important;
            }
            .sheet-wrap {
                width: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
	</style>
</head>
<body>
    <?php
        $sub_header = !empty($cbt_keterangan) ? $cbt_keterangan : $cbt_nama;
        // Jika tombol cepat dari Peserta & Kelas (tanpa ?judul=), pakai nama sekolah agar pendek & rapi
        if($this->input->get('judul', TRUE) === NULL){
            $sub_header = $cbt_nama;
        }
    ?>
    <div class="print-toolbar">
        <div class="info">
            Kartu Login Ujian &mdash; <strong><?php echo $label_filter; ?></strong>
            &bull; Total: <strong><?php echo count($peserta_list); ?> kartu</strong>
            &bull; Ukuran: <strong>92 &times; 64 mm</strong>
            &bull; <strong>8 kartu/A4 (2&times;4)</strong>
        </div>
        <div class="actions">
            <?php if(!empty($peserta_list)){ ?>
                <button type="button" class="btn-tool btn-print" onclick="window.print()">&#128424; Cetak / Simpan PDF</button>
            <?php } ?>
            <button type="button" class="btn-tool btn-close" onclick="window.close()">Tutup</button>
        </div>
    </div>

    <div class="sheet-wrap">
        <?php if(empty($peserta_list)){ ?>
            <div class="empty-state">
                <h3>Data Peserta Kosong</h3>
                <p>Tidak ditemukan peserta untuk filter yang dipilih.</p>
            </div>
        <?php }else{ ?>
            <div class="slip-grid">
                <?php foreach($peserta_list as $p){
                    $kelas_info = html_escape($p->grup_nama ? $p->grup_nama : '-');
                    if(!empty($p->user_detail)){
                        $kelas_info .= ' &bull; '.html_escape($p->user_detail);
                    }
                ?>
                    <div class="slip-cut">
                        <div class="slip-box">
                            <div class="slip-head">
                                <div class="slip-title"><?php echo html_escape($sub_header); ?></div>
                                <div class="slip-sub">Kartu Login Ujian</div>
                            </div>
                            <div class="slip-nama"><?php echo html_escape(stripslashes($p->user_firstname)); ?></div>
                            <table class="slip-cred">
                                <tr>
                                    <td class="lbl">Kelas</td>
                                    <td class="sep">:</td>
                                    <td><?php echo $kelas_info; ?></td>
                                </tr>
                                <tr>
                                    <td class="lbl">Username</td>
                                    <td class="sep">:</td>
                                    <td><span class="cred-mono"><?php echo html_escape($p->user_name); ?></span></td>
                                </tr>
                                <tr>
                                    <td class="lbl">Password</td>
                                    <td class="sep">:</td>
                                    <td><span class="cred-mono"><?php echo html_escape($p->user_password); ?></span></td>
                                </tr>
                            </table>
                            <div class="slip-qr">
                                <img src="<?php echo base_url('public/images/qr-login.png'); ?>" alt="QR Login">
                                <div class="cap">Scan untuk login</div>
                            </div>
                            <div class="slip-url">Akses: siakanuda.com atau 10.10.10.10 &bull; wifi sekolah</div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>
