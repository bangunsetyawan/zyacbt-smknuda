<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cetak Jawaban Esai - <?php echo html_escape($nama_tes); ?></title>
	<style>
		* {
			box-sizing: border-box;
		}
		body {
			margin: 0;
			padding: 0;
			background: #eef2f6;
			color: #1e293b;
			font-family: "Helvetica Neue", Arial, sans-serif;
			font-size: 12px;
			line-height: 1.4;
		}
		.toolbar {
			position: sticky;
			top: 0;
			z-index: 100;
			background: #1f4e78;
			color: #fff;
			padding: 10px 20px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			box-shadow: 0 2px 6px rgba(0,0,0,0.18);
		}
		.toolbar-title {
			font-weight: 700;
			font-size: 14px;
		}
		.toolbar-actions {
			display: flex;
			gap: 8px;
		}
		.btn {
			border: none;
			border-radius: 4px;
			padding: 7px 14px;
			font-size: 13px;
			font-weight: 600;
			cursor: pointer;
		}
		.btn-print {
			background: #00a65a;
			color: #fff;
		}
		.btn-print:hover {
			background: #008d4c;
		}
		.btn-close {
			background: rgba(255,255,255,0.18);
			color: #fff;
		}
		.btn-close:hover {
			background: rgba(255,255,255,0.28);
		}
		.sheet {
			max-width: 210mm;
			margin: 18px auto;
			background: #fff;
			padding: 14mm 12mm;
			box-shadow: 0 2px 12px rgba(0,0,0,0.1);
		}
		.report-header {
			border-bottom: 2px solid #1f4e78;
			padding-bottom: 8px;
			margin-bottom: 12px;
		}
		.report-header h1 {
			margin: 0 0 6px 0;
			font-size: 16px;
			color: #1f4e78;
			letter-spacing: 0.4px;
		}
		.report-meta {
			background: #f4f7fb;
			border: 1px solid #d5e0ec;
			border-radius: 3px;
			padding: 6px 10px;
			font-size: 11px;
			display: flex;
			flex-wrap: wrap;
			gap: 4px 18px;
		}
		.soal-block {
			margin-bottom: 16px;
		}
		.soal-box {
			background: #f8fafc;
			border: 1px solid #cbd5e1;
			border-left: 4px solid #1f4e78;
			padding: 8px 10px;
			margin-bottom: 6px;
			font-size: 11.5px;
		}
		.soal-box-label {
			font-weight: 700;
			color: #1f4e78;
			margin-bottom: 4px;
			font-size: 11.5px;
		}
		.soal-box img {
			max-width: 100%;
			height: auto;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 4px;
		}
		thead {
			display: table-header-group;
		}
		tr {
			page-break-inside: avoid;
			break-inside: avoid;
		}
		th, td {
			border: 1px solid #cbd5e1;
			padding: 5px 6px;
			vertical-align: top;
			font-size: 10.5px;
		}
		th {
			background: #1f4e78;
			color: #fff;
			font-weight: 700;
			text-align: left;
		}
		th.center, td.center {
			text-align: center;
		}
		.jawaban-text {
			white-space: pre-wrap;
			word-break: break-word;
		}
		.jawaban-kosong {
			color: #94a3b8;
			font-style: italic;
		}
		.empty-state {
			text-align: center;
			padding: 30px;
			color: #64748b;
			border: 1px dashed #cbd5e1;
			border-radius: 4px;
		}

		@media print {
			@page {
				size: A4 portrait;
				margin: 10mm;
			}
			body {
				background: #fff !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
			}
			.no-print {
				display: none !important;
			}
			.sheet {
				max-width: none;
				margin: 0;
				padding: 0;
				box-shadow: none;
			}
			.report-header h1 {
				font-size: 14px;
			}
			.report-meta {
				font-size: 9.5px;
				padding: 4px 8px;
			}
			.soal-box {
				font-size: 10px;
				padding: 6px 8px;
				page-break-inside: avoid;
				break-inside: avoid;
			}
			th, td {
				font-size: 9.5px;
				padding: 3.5px 5px;
			}
		}
	</style>
</head>
<body>
	<div class="toolbar no-print">
		<div class="toolbar-title">Pratinjau Cetak / PDF Jawaban Esai (<?php echo (int)$jumlah_jawaban; ?> Jawaban)</div>
		<div class="toolbar-actions">
			<button type="button" class="btn btn-print" onclick="window.print()">Cetak / Simpan PDF (Ctrl+P)</button>
			<button type="button" class="btn btn-close" onclick="window.close()">Tutup</button>
		</div>
	</div>

	<div class="sheet">
		<div class="report-header">
			<h1>LAPORAN JAWABAN ESAI PESERTA</h1>
			<div class="report-meta">
				<span><strong>Tes / Mapel:</strong> <?php echo html_escape($nama_tes); ?></span>
				<span><strong>Kelas:</strong> <?php echo html_escape($nama_grup); ?></span>
				<span><strong>Filter Status:</strong> <?php echo html_escape($status_label); ?></span>
				<span><strong>Peserta:</strong> <?php echo (int)$jumlah_siswa; ?></span>
				<span><strong>Total Jawaban:</strong> <?php echo (int)$jumlah_jawaban; ?></span>
				<span><strong>Dicetak:</strong> <?php echo date('d/m/Y H:i'); ?></span>
			</div>
		</div>

		<?php if(empty($soal_groups)){ ?>
			<div class="empty-state">
				Tidak ada data jawaban esai untuk filter yang dipilih.
			</div>
		<?php } else { ?>
			<?php $no_soal = 1; foreach($soal_groups as $grp){ ?>
				<div class="soal-block">
					<div class="soal-box">
						<div class="soal-box-label">
							Soal Esai #<?php echo $no_soal++; ?>
							<?php if(!empty($show_tes_col)){ echo ' &mdash; '.html_escape($grp['tes_nama']); } ?>
						</div>
						<div><?php echo $grp['soal_detail']; ?></div>
					</div>
					<table>
						<thead>
							<tr>
								<th class="center" style="width:30px;">No</th>
								<th style="width:92px;">Username</th>
								<th style="width:140px;">Nama Siswa</th>
								<th style="width:72px;">Kelas</th>
								<th>Jawaban Esai Siswa</th>
								<th class="center" style="width:52px;">Nilai</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; foreach($grp['items'] as $item){
								$jwb = trim((string)$item->tessoal_jawaban_text);
								$nilai_str = ($item->tessoal_comment !== null) ? rtrim(rtrim(number_format((float)$item->tessoal_nilai, 2, '.', ''), '0'), '.') : '';
							?>
								<tr>
									<td class="center"><?php echo $no++; ?></td>
									<td><?php echo html_escape($item->user_name); ?></td>
									<td><?php echo html_escape(stripslashes((string)$item->user_firstname)); ?></td>
									<td><?php echo html_escape($item->grup_nama ? $item->grup_nama : '-'); ?></td>
									<td>
										<?php if($jwb !== ''){ ?>
											<div class="jawaban-text"><?php echo html_escape($jwb); ?></div>
										<?php } else { ?>
											<span class="jawaban-kosong">(Tidak dijawab)</span>
										<?php } ?>
									</td>
									<td class="center"><?php echo html_escape($nilai_str); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</body>
</html>
