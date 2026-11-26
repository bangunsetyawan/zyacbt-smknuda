<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Backup Data
		<small>Backup data Aplikasi Ujian Online meliputi database dan file yang diupload.</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Backup Data</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
            <div class="callout callout-info">
                <h4>Informasi</h4>
                <p>Lakukan Backup Data secara berkala. Lakukan Backup pada Database dan Data yang telah di upload untuk keamanan.</p>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Backup Database</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <span id="form-pesan-database"></span>
                        <p>Klik tombol <b>Backup Database</b> untuk melakukan Backup database ZYA CBT. Simpan backup database pada tempat yang aman.</p>
                        <p>Database akan disimpan dalam archive. Extract terlebih dahulu sebelum melakukan restore database.</p>
                        <p>Restore database dapat menggunakan PHPMyAdmin atau software yang lain.</p>
                    </div>
					
					<div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="backup-database">Backup Database</button>
                    </div>
                </div>
        </div>
        <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Backup Data Upload</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <span id="form-pesan-data-upload"></span>
                        <p>Klik tombol <b>Backup Data Upload</b> untuk melakukan Backup Data yang di Upload (gambar, audio) pada ZYA CBT. Simpan backup data pada tempat yang aman.</p>
                        <p>Data file yang di upload akan disimpan dalam bentuk archive.</p>
                        <p>Restore Data Upload dilakukan dengan meng-copy data ke folder uploads pada folder utama ZYA CBT</p>
                    </div>
					
					<div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="backup-data-upload">Backup Data Upload</button>
                    </div>
                </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Clear Sessions</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <span id="form-pesan-database"></span>
                        <p>Klik tombol <b>Clear Sessions</b> untuk membersihkan sessions peserta tes yang disimpan di tabel cbt_sessions.</p>
                        <p>Setelah ZYACBT digunakan dalam waktu yang lama dan menjalan banyak TES, maka session harus dibersikan.</p>
                        <p>Pastikan tidak ada peserta yang sedang melakukan TES saat session dibersihkan.</p>
						<p>Anda akan Logout secara otomatis, silahkan Login kembali.</p>
                    </div>
					
					<div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="backup-session">Clear Sessions</button>
                    </div>
                </div>
        </div>
        <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
    					<div class="box-title"><i class="fa fa-broom"></i> Pemeliharaan Media &amp; Reset Data Dummy</div>
                    </div>

                    <div class="box-body">
                        <div id="form-pesan-dummy"></div>
                        <p><strong>1. Bersihkan Folder Media Yatim (<code>uploads/topik_X</code>):</strong> Menghapus folder gambar/audio dari topik yang sudah dihapus agar penyimpanan server tetap bersih.</p>
                        <button type="button" class="btn btn-info btn-sm" id="btn-clean-orphan"><i class="fa fa-folder-open"></i> Bersihkan Folder Media Yatim</button>
                        <hr style="margin:12px 0;">
                        <p><strong>2. Reset Data Dummy / Uji Coba (Menjelang Ujian Real):</strong></p>
                        <div class="form-group" style="margin-bottom:8px;">
                            <select id="mode-reset-dummy" class="form-control input-sm">
                                <option value="peserta_999">Hapus Peserta Dummy (NISN 999...) &amp; Hasil Tesnya Saja</option>
                                <option value="full_clean">Reset Total Semua Data Dummy (Peserta, Kelas, Jadwal, Hasil &amp; Soal Uji Coba)</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" id="input-konfirmasi-dummy" class="form-control" placeholder='Ketik: RESET DUMMY untuk konfirmasi' autocomplete="off">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-danger" id="btn-reset-dummy"><i class="fa fa-trash"></i> Jalankan Reset</button>
                            </span>
                        </div>
                    </div>
                </div>
        </div>
	</div>
</section><!-- /.content -->



<script lang="javascript">
    $(function(){
        $('#backup-database').click(function(){
            window.open("<?php echo site_url().'/'.$url; ?>/database");
        });

        $('#backup-data-upload').click(function(){
            window.open("<?php echo site_url().'/'.$url; ?>/data_upload");
        });
		
		$('#backup-session').click(function(){
			$("#modal-proses").modal('show');
			$.getJSON('<?php echo site_url().'/'.$url; ?>/clear_session', function(data){
				if(data.status==1){
					window.location.reload(true);
				}
				$("#modal-proses").modal('hide');
			});
        });

        $('#btn-clean-orphan').click(function(){
            $("#modal-proses").modal('show');
            $.getJSON('<?php echo site_url().'/'.$url; ?>/clean_orphan_uploads', function(res){
                $("#modal-proses").modal('hide');
                if(res.status == 1){
                    $('#form-pesan-dummy').html('<div class="alert alert-success" style="padding:8px 12px;margin-bottom:10px;">'+res.pesan+'</div>');
                }else{
                    $('#form-pesan-dummy').html('<div class="alert alert-danger" style="padding:8px 12px;margin-bottom:10px;">'+res.pesan+'</div>');
                }
            });
        });

        $('#btn-reset-dummy').click(function(){
            var konf = $.trim($('#input-konfirmasi-dummy').val());
            var mode = $('#mode-reset-dummy').val();
            if(konf.toUpperCase() !== 'RESET DUMMY'){
                $('#form-pesan-dummy').html('<div class="alert alert-warning" style="padding:8px 12px;margin-bottom:10px;">Silahkan ketik <b>RESET DUMMY</b> terlebih dahulu pada kotak konfirmasi.</div>');
                return;
            }
            var labelMode = $('#mode-reset-dummy option:selected').text();
            if(!confirm('Yakin ingin menjalankan: ' + labelMode + '?\n\nPastikan Anda sudah melakukan Backup Database bila diperlukan.')){
                return;
            }
            $("#modal-proses").modal('show');
            $.ajax({
                url: '<?php echo site_url().'/'.$url; ?>/reset_data_dummy',
                type: 'POST',
                data: {konfirmasi: konf, mode: mode},
                dataType: 'json',
                success: function(res){
                    $("#modal-proses").modal('hide');
                    if(res.status == 1){
                        $('#input-konfirmasi-dummy').val('');
                        $('#form-pesan-dummy').html('<div class="alert alert-success" style="padding:8px 12px;margin-bottom:10px;">'+res.pesan+'</div>');
                    }else{
                        $('#form-pesan-dummy').html('<div class="alert alert-danger" style="padding:8px 12px;margin-bottom:10px;">'+res.pesan+'</div>');
                    }
                },
                error: function(){
                    $("#modal-proses").modal('hide');
                    $('#form-pesan-dummy').html('<div class="alert alert-danger" style="padding:8px 12px;margin-bottom:10px;">Terjadi kesalahan saat menghubungi server.</div>');
                }
            });
        });
    });
</script>