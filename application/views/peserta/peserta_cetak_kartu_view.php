<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>Kartu Login Ujian | <?php echo html_escape($cbt_nama); ?></title>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon" />
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
         * Ukuran tiap kartu: 64mm x 37mm (pas masuk wadah ID Card B2 / B3 tanpa dilipat)
         * Grid 3 kolom x 7 baris = 21 kartu/lembar A4 (lebar 3*64 + 2*2 = 196mm <= 198mm, tinggi 7*37 + 6*1.8 = 269.8mm <= 285mm)
         * Pada F4 otomatis muat 3 kolom x 8 baris = 24 kartu/lembar (tinggi 8*37 + 7*1.8 = 308.6mm <= 318mm)
         */
        .slip-grid {
            display: grid;
            grid-template-columns: repeat(3, 64mm);
            justify-content: center;
            gap: 1.8mm 2mm;
        }

        .slip-cut {
            width: 64mm;
            height: 37mm;
            border: 1px dashed #666;
            padding: 1.3mm;
            break-inside: avoid;
            page-break-inside: avoid;
            background: #fff;
        }

        .slip-box {
            width: 100%;
            height: 100%;
            border: 1.2px solid #000;
            padding: 1.6mm 2.2mm;
            background: #fff;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .slip-head {
            border-bottom: 1px solid #000;
            padding-bottom: 0.8mm;
            text-align: center;
            line-height: 1.15;
        }
        .slip-title {
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .slip-sub {
            font-size: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .slip-identitas {
            line-height: 1.22;
            margin-top: 0.6mm;
        }
        .slip-nama {
            font-size: 9.8px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .slip-kelas {
            font-size: 8.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .slip-cred {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px dotted #000;
            font-size: 9.5px;
        }
        .slip-cred td {
            padding: 0.6mm 0 0 0;
            line-height: 1.18;
            vertical-align: middle;
        }
        .slip-cred td.lbl {
            width: 34px;
            white-space: nowrap;
        }
        .slip-cred td.sep {
            width: 7px;
        }
        .cred-mono {
            font-family: "Courier New", Courier, monospace;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.4px;
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
            &bull; Ukuran: <strong>64 &times; 37 mm (B2/B3)</strong>
            &bull; <strong><?php echo ($kertas === 'f4') ? '24 kartu/F4 (3&times;8)' : '21 kartu/A4 (3&times;7)'; ?></strong>
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
                                <div class="slip-title">KARTU LOGIN UJIAN</div>
                                <?php if($sub_header !== ''){ ?>
                                    <div class="slip-sub"><?php echo html_escape($sub_header); ?></div>
                                <?php } ?>
                            </div>
                            <div class="slip-identitas">
                                <div class="slip-nama"><?php echo html_escape(stripslashes($p->user_firstname)); ?></div>
                                <div class="slip-kelas">Kelas: <?php echo $kelas_info; ?></div>
                            </div>
                            <table class="slip-cred">
                                <tr>
                                    <td class="lbl">User</td>
                                    <td class="sep">:</td>
                                    <td><span class="cred-mono"><?php echo html_escape($p->user_name); ?></span></td>
                                </tr>
                                <tr>
                                    <td class="lbl">Pass</td>
                                    <td class="sep">:</td>
                                    <td><span class="cred-mono"><?php echo html_escape($p->user_password); ?></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>
