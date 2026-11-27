<style>
.rapot-header {
	border-bottom: 2px solid #3c8dbc;
	padding-bottom: 10px;
	margin-bottom: 14px;
}
.rapot-header h3 {
	margin: 0 0 4px 0;
	font-weight: 700;
	font-size: 18px;
}
.rapot-meta {
	font-size: 13px;
	color: #444;
}
.rapot-meta span {
	margin-right: 18px;
	display: inline-block;
}
.table-analisis th, .table-analisis td {
	text-align: center;
	vertical-align: middle !important;
	font-size: 12px;
	padding: 5px 6px !important;
}
.table-analisis td.text-left-col, .table-analisis th.text-left-col {
	text-align: left;
	white-space: nowrap;
}
.cell-benar {
	background-color: #eafaf1 !important;
	color: #1e8449;
	font-weight: 600;
}
.cell-salah {
	background-color: #fdf2f2 !important;
	color: #922b21;
}
.summary-row th, .summary-row td {
	background-color: #f8f9fa !important;
	font-weight: 600;
	border-top: 2px solid #bbb !important;
}
.badge-kat {
	display: inline-block;
	padding: 2px 5px;
	border-radius: 3px;
	font-size: 10px;
	font-weight: 700;
	margin-top: 2px;
	white-space: nowrap;
}
.kat-success { background-color: #00a65a !important; color: #fff !important; }
.kat-info    { background-color: #00c0ef !important; color: #fff !important; }
.kat-warning { background-color: #f39c12 !important; color: #fff !important; }
.kat-danger  { background-color: #dd4b39 !important; color: #fff !important; }

@media print {
	@page {
		size: landscape;
		margin: 6mm;
	}
	body {
		background: #fff !important;
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
	}
	.main-header, .main-sidebar, .left-side, .main-footer,
	.content-header, .callout, .no-print {
		display: none !important;
	}
	.content-wrapper, .right-side, .content {
		margin-left: 0 !important;
		padding: 0 !important;
		background: #fff !important;
	}
	#preview-box {
		border: none !important;
		box-shadow: none !important;
		margin-bottom: 0 !important;
	}
	#print-area {
		padding: 0 !important;
	}
	.rapot-header {
		padding-bottom: 5px;
		margin-bottom: 8px;
	}
	.rapot-header h3 {
		font-size: 13px;
		margin-bottom: 2px;
	}
	.rapot-meta {
		font-size: 10px;
	}
	.table-responsive {
		overflow: visible !important;
		border: none !important;
		margin-bottom: 4px !important;
	}
	.table-analisis th, .table-analisis td {
		font-size: 8px;
		line-height: 1.15;
		padding: 1px 2px !important;
	}
	.badge-kat {
		font-size: 7.5px;
		padding: 1px 3px;
		margin-top: 1px;
	}
	.legend-print {
		font-size: 8.5px !important;
		margin-top: 4px !important;
	}
}
</style>

<!-- Content Header (Page header) -->
<section class="content-header no-print">
	<h1>
		Laporan Analisis Butir Soal
		<small>Analisis Butir Soal sesuai dengan grup dan tes yang dipilih</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Analisis Butir Soal</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row no-print">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h4>Informasi</h4>
                <p>Pilih Grup peserta dan Tes untuk melihat pratinjau, mencetak/menyimpan PDF, atau Export Excel analisis butir soal (khusus soal Pilihan Ganda).</p>
                <p>Urutan soal yang dianalisis sesuai dengan urutan soal saat dilakukan input soal seperti pada Daftar Soal pada menu Modul.</p>
            </div>
        </div>
    </div>
	<?php echo form_open($url.'/export','id="form-export" class="no-print"'); ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="box">
				<div class="box-header with-border">
					<div class="box-title">Pilih Grup dan Tes</div>
				</div><!-- /.box-header -->
					
				<div class="box-body">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Grup</label>
							<input type="hidden" id="nama-grup" name="nama-grup">
							<select name="pilih-grup" id="pilih-grup" class="form-control input-sm">
								<?php if(!empty($select_group)){ echo $select_group; } ?>
							</select>
							<span class="help-block">Pilih Grup yang akan dianalisis soalnya</span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Tes</label>
							<input type="hidden" id="nama-tes" name="nama-tes">
							<select name="pilih-tes" id="pilih-tes" class="form-control input-sm">
								<?php if(!empty($select_tes)){ echo $select_tes; } ?>
							</select>
							<span class="help-block">Pilih Nama Tes yang akan dianalisis soalnya</span>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<button type="button" class="btn btn-primary" id="btn-preview"><i class="fa fa-eye"></i> Tampilkan Pratinjau</button>
						<button type="submit" class="btn btn-success" id="btn-export"><i class="fa fa-file-excel-o"></i> Export Excel</button>
					</div>
				</div>
			</div>
		</div>
    </div>
	</form>

	<!-- Area Pratinjau & Cetak -->
	<div class="row" id="preview-wrapper" style="display:none;">
		<div class="col-sm-12">
			<div class="box box-primary" id="preview-box">
				<div class="box-header with-border no-print">
					<h3 class="box-title"><i class="fa fa-table"></i> Pratinjau Analisis Butir Soal</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-info btn-sm" id="btn-print"><i class="fa fa-print"></i> Cetak / Simpan PDF</button>
					</div>
				</div>
				<div class="box-body" id="print-area">
					<div class="rapot-header">
						<h3>LAPORAN ANALISIS BUTIR SOAL (PILIHAN GANDA)</h3>
						<div class="rapot-meta">
							<span><strong>Grup:</strong> <span id="pv-grup">-</span></span>
							<span><strong>Nama Tes:</strong> <span id="pv-tes">-</span></span>
							<span><strong>Jumlah Peserta:</strong> <span id="pv-jml-siswa">0</span></span>
							<span><strong>Jumlah Soal PG:</strong> <span id="pv-jml-soal">0</span></span>
						</div>
					</div>
					<div id="pv-loading" class="text-center" style="display:none; padding:20px;">
						<i class="fa fa-spinner fa-spin fa-2x"></i>
						<p class="text-muted">Menghitung analisis butir soal...</p>
					</div>
					<div id="pv-empty" class="alert alert-warning" style="display:none;">
						Belum ada data peserta atau soal Pilihan Ganda untuk kombinasi grup dan tes ini.
					</div>
					<div class="table-responsive" id="pv-table-wrap">
						<table class="table table-bordered table-condensed table-analisis" id="table-preview-analisis">
							<thead id="pv-thead"></thead>
							<tbody id="pv-tbody"></tbody>
							<tfoot id="pv-tfoot"></tfoot>
						</table>
					</div>
					<div class="small text-muted legend-print" style="margin-top:8px;">
						<strong>Keterangan Matriks:</strong>
						<span class="badge-kat cell-benar" style="border:1px solid #b7e4c7;">1 = Benar</span>
						<span class="badge-kat cell-salah" style="border:1px solid #f5c6cb;">0 = Salah</span>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<strong>Tingkat Kesulitan:</strong>
						<span class="badge-kat kat-danger">0–30% Sukar</span>
						<span class="badge-kat kat-warning">31–70% Sedang</span>
						<span class="badge-kat kat-success">71–100% Mudah</span>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<strong>Daya Beda:</strong>
						<span class="badge-kat kat-success">&ge;0,40 Baik</span>
						<span class="badge-kat kat-info">0,20–0,39 Cukup</span>
						<span class="badge-kat kat-warning">0,00–0,19 Buruk</span>
						<span class="badge-kat kat-danger">&lt;0 Soal bermasalah</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section><!-- /.content -->

<script lang="javascript">
	function escapeHtml(str) {
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function syncHiddenLabels() {
		$('#nama-grup').val($('#pilih-grup option:selected').text());
		$('#nama-tes').val($('#pilih-tes option:selected').text());
	}

	$('#btn-export').click(function(e){
		syncHiddenLabels();
	});

	$('#btn-print').click(function(){
		window.print();
	});

	$('#btn-preview').click(function(){
		syncHiddenLabels();
		$('#preview-wrapper').show();
		$('#pv-loading').show();
		$('#pv-empty').hide();
		$('#pv-table-wrap').hide();

		$.ajax({
			url: '<?php echo site_url($url."/preview"); ?>',
			type: 'POST',
			dataType: 'json',
			data: $('#form-export').serialize(),
			success: function(res){
				$('#pv-loading').hide();
				if(!res || res.status != 1){
					$('#pv-empty').html(res && res.pesan ? res.pesan : 'Gagal memuat pratinjau.').show();
					return;
				}
				$('#pv-grup').text(res.nama_grup);
				$('#pv-tes').text(res.nama_tes);
				$('#pv-jml-siswa').text(res.jumlah_siswa);
				$('#pv-jml-soal').text(res.jumlah_soal);

				if(!res.students || res.students.length === 0 || res.jumlah_soal === 0){
					$('#pv-empty').text('Belum ada data peserta atau soal Pilihan Ganda untuk kombinasi grup dan tes ini.').show();
					return;
				}

				var thead = '<tr><th style="width:36px;">No</th><th class="text-left-col">Username</th><th class="text-left-col">Nama Siswa</th>';
				for(var s = 1; s <= res.jumlah_soal; s++){
					thead += '<th>' + s + '</th>';
				}
				thead += '</tr>';
				$('#pv-thead').html(thead);

				var tbody = '';
				for(var i = 0; i < res.students.length; i++){
					var st = res.students[i];
					tbody += '<tr>';
					tbody += '<td>' + st.no + '</td>';
					tbody += '<td class="text-left-col">' + escapeHtml(st.username) + '</td>';
					tbody += '<td class="text-left-col">' + escapeHtml(st.nama) + '</td>';
					for(var j = 0; j < st.jawaban.length; j++){
						var val = st.jawaban[j];
						var cls = (val == 1) ? 'cell-benar' : 'cell-salah';
						tbody += '<td class="' + cls + '">' + val + '</td>';
					}
					tbody += '</tr>';
				}
				$('#pv-tbody').html(tbody);

				var rowKesulitan = '<tr class="summary-row"><th colspan="3" class="text-left-col">Tingkat Kesulitan</th>';
				for(var k = 0; k < res.kesulitan.length; k++){
					var itemK = res.kesulitan[k];
					rowKesulitan += '<td><div>' + itemK.nilai.toFixed(1) + '%</div><span class="badge-kat kat-' + escapeHtml(itemK.badge) + '">' + escapeHtml(itemK.kategori) + '</span></td>';
				}
				rowKesulitan += '</tr>';

				var rowDayaBeda = '<tr class="summary-row"><th colspan="3" class="text-left-col">Daya Beda</th>';
				for(var d = 0; d < res.daya_beda.length; d++){
					var itemD = res.daya_beda[d];
					rowDayaBeda += '<td><div>' + itemD.nilai.toFixed(2) + '</div><span class="badge-kat kat-' + escapeHtml(itemD.badge) + '">' + escapeHtml(itemD.kategori) + '</span></td>';
				}
				rowDayaBeda += '</tr>';

				$('#pv-tfoot').html(rowKesulitan + rowDayaBeda);
				$('#pv-table-wrap').show();
			},
			error: function(){
				$('#pv-loading').hide();
				$('#pv-empty').text('Terjadi kesalahan saat memuat pratinjau.').show();
			}
		});
	});
</script>