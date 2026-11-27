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
.table-rekap th, .table-rekap td {
	vertical-align: middle !important;
	font-size: 13px;
	padding: 6px 8px !important;
}
.table-rekap th.num-col, .table-rekap td.num-col {
	text-align: center;
}
.col-rata {
	background-color: #f0f7ff !important;
	font-weight: 700;
	color: #1f4e78;
}
.nilai-na {
	color: #999;
	font-style: italic;
}

@media print {
	@page {
		size: landscape;
		margin: 8mm;
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
	.table-rekap th, .table-rekap td {
		font-size: 9px;
		line-height: 1.2;
		padding: 2px 4px !important;
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
		Rekapitulasi Hasil Tes
		<small>Pratinjau, Cetak PDF, dan Export Rekapitulasi Hasil Tes</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Rekap Hasil Tes</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row no-print">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h4>Informasi</h4>
                <p>Pilih Grup peserta dan rentang waktu Tes untuk melihat pratinjau rapot, mencetak/menyimpan PDF, atau Export Excel.</p>
                <p>Siswa yang tidak mengikuti suatu tes akan tertulis <strong>N/A</strong> dan tidak dihitung dalam pembagi kolom <strong>Rata-Rata</strong>.</p>
            </div>
        </div>
    </div>
	<?php echo form_open($url.'/export','id="form-export" class="no-print"'); ?>
	<div class="row">
        <div class="col-md-4">
            <div class="box">
				<div class="box-header with-border">
					<div class="box-title">Pilih Grup</div>
    			</div><!-- /.box-header -->
				
				<div class="box-body">
					<div class="form-group">
						<label>Nama Grup</label>
						<input type="hidden" id="nama-grup" name="nama-grup">
                        <select name="pilih-grup" id="pilih-grup" class="form-control input-sm">
							<?php if(!empty($select_group)){ echo $select_group; } ?>
						</select>
						<span class="help-block">Pilih Grup yang akan direkap</span>
					</div>
                </div>
			</div>
        </div>
		
		<div class="col-md-8">
            <div class="box">
				<div class="box-header with-border">
					<div class="box-title">Rekapitulasi Hasil Tes</div>
    			</div><!-- /.box-header -->
				
				<div class="box-body form-horizontal">
						<div class="form-group">
                            <label class="col-sm-3 control-label">Waktu Tes</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" name="pilih-rentang-waktu" id="pilih-rentang-waktu" class="form-control input-sm" value="<?php if(!empty($rentang_waktu)){ echo $rentang_waktu; } ?>" readonly />
                                </div>
								<span class="help-block">Pilih Rentang Tanggal dimana Tes dilakukan. Sesuai Waktu Mulai pada Daftar Tes.</span>
                            </div>
                        </div>
                </div>
				<div class="box-footer">
					<div class="pull-right">
						<button type="button" class="btn btn-primary" id="btn-preview"><i class="fa fa-eye"></i> Tampilkan Pratinjau</button>
						<button type="button" class="btn btn-success" id="btn-export"><i class="fa fa-file-excel-o"></i> Export Excel</button>
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
					<h3 class="box-title"><i class="fa fa-table"></i> Pratinjau Rekap Hasil Tes</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-info btn-sm" id="btn-print"><i class="fa fa-print"></i> Cetak / Simpan PDF</button>
					</div>
				</div>
				<div class="box-body" id="print-area">
					<div class="rapot-header">
						<h3>REKAPITULASI HASIL TES PESERTA</h3>
						<div class="rapot-meta">
							<span><strong>Grup:</strong> <span id="pv-grup">-</span></span>
							<span><strong>Rentang Waktu:</strong> <span id="pv-periode">-</span></span>
							<span><strong>Jumlah Tes:</strong> <span id="pv-jml-tes">0</span></span>
							<span><strong>Jumlah Siswa:</strong> <span id="pv-jml-siswa">0</span></span>
						</div>
					</div>
					<div id="pv-loading" class="text-center" style="display:none; padding:20px;">
						<i class="fa fa-spinner fa-spin fa-2x"></i>
						<p class="text-muted">Memuat rekap hasil tes...</p>
					</div>
					<div id="pv-empty" class="alert alert-warning" style="display:none;">
						Belum ada data peserta atau tes pada rentang waktu yang dipilih.
					</div>
					<div class="table-responsive" id="pv-table-wrap">
						<table class="table table-bordered table-striped table-condensed table-rekap">
							<thead id="pv-thead"></thead>
							<tbody id="pv-tbody"></tbody>
						</table>
					</div>
					<div class="small text-muted legend-print" style="margin-top:8px;">
						<strong>Keterangan:</strong> <strong>N/A</strong> = Peserta tidak mengikuti tes pada jadwal tersebut (tidak dihitung dalam pembagi kolom <strong>Rata-Rata</strong>).
					</div>
				</div>
			</div>
		</div>
	</div>
</section><!-- /.content -->

<script lang="javascript">
    $(function(){
		$('#pilih-rentang-waktu').daterangepicker({timePicker: false, timePickerIncrement: 30, format: 'YYYY-MM-DD'});  
    });

	function escapeHtml(str) {
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

    $('#btn-export').click(function(){
    	$('#nama-grup').val($('#pilih-grup option:selected').text());
    	$('#form-export').submit();
    });

	$('#btn-print').click(function(){
		window.print();
	});

	$('#btn-preview').click(function(){
		$('#nama-grup').val($('#pilih-grup option:selected').text());
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
				$('#pv-periode').text(res.periode);
				$('#pv-jml-tes').text(res.jumlah_tes);
				$('#pv-jml-siswa').text(res.jumlah_siswa);

				if(!res.students || res.students.length === 0 || !res.tests || res.tests.length === 0){
					$('#pv-empty').text('Belum ada data peserta atau tes pada rentang waktu yang dipilih.').show();
					return;
				}

				var thead = '<tr><th class="num-col" style="width:40px;">No</th><th>Nama Siswa</th><th>Grup</th><th class="num-col col-rata">Rata-Rata</th>';
				for(var t = 0; t < res.tests.length; t++){
					thead += '<th class="num-col">' + escapeHtml(res.tests[t].tes_nama) + '</th>';
				}
				thead += '</tr>';
				$('#pv-thead').html(thead);

				var tbody = '';
				for(var i = 0; i < res.students.length; i++){
					var st = res.students[i];
					var rataCls = (st.rata_rata === 'N/A') ? 'num-col col-rata nilai-na' : 'num-col col-rata';
					tbody += '<tr>';
					tbody += '<td class="num-col">' + st.no + '</td>';
					tbody += '<td>' + escapeHtml(st.nama) + '</td>';
					tbody += '<td>' + escapeHtml(st.grup) + '</td>';
					tbody += '<td class="' + rataCls + '">' + escapeHtml(st.rata_rata) + '</td>';
					for(var j = 0; j < st.nilai_tes.length; j++){
						var val = st.nilai_tes[j];
						var cls = (val === 'N/A') ? 'num-col nilai-na' : 'num-col';
						tbody += '<td class="' + cls + '">' + escapeHtml(val) + '</td>';
					}
					tbody += '</tr>';
				}
				$('#pv-tbody').html(tbody);
				$('#pv-table-wrap').show();
			},
			error: function(){
				$('#pv-loading').hide();
				$('#pv-empty').text('Terjadi kesalahan saat memuat pratinjau.').show();
			}
		});
	});
</script>