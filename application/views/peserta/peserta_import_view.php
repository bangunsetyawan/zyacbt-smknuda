<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Import Data Peserta
		<small>Upload Excel peserta sekaligus membuat Kelas (Group) secara otomatis</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Import Peserta</li>
	</ol>
</section>

<style>
    .kelas-badge-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }
    .kelas-badge-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12.5px;
        border: 1px solid #d1d5db;
        background: #f9fafb;
    }
    .kelas-badge-item.is-new {
        background: #ecfdf5;
        border-color: #6ee7b7;
        color: #065f46;
    }
    .preview-table-wrap {
        max-height: 360px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }
</style>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h4><i class="fa fa-info-circle"></i> Cara Kerja Import Peserta (Auto-Kelas)</h4>
                <p style="margin-bottom: 4px;">Anda <strong>tidak perlu membuat Kelas (Group) satu per satu terlebih dahulu</strong>. Cukup isi kolom <strong>Kelas (Group)</strong> pada file Excel (contoh: <code>X TKJ 1</code>, <code>XI AKL 1</code>, <code>XII TKR 1</code>) — kelas baru akan otomatis dibuatkan saat data disimpan.</p>
                <p style="margin-bottom: 0;">Urutan kolom tetap kompatibel dengan format bawaan ZyaCBT: <code>No | Username | Password | Nama Lengkap | Email (Opsional) | Kelas (Group) | Keterangan (Ruang/Sesi, maks 25 karakter)</code>.</p>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-file-excel-o"></i> 1. Upload &amp; Analisa File Excel</h3>
					<div class="box-tools pull-right">
						<a href="<?php echo site_url($url.'/download_template'); ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-download"></i> Download Template Excel
                        </a>
                        <a href="<?php echo site_url('manager/peserta_daftar'); ?>" class="btn btn-default btn-sm">
                            <i class="fa fa-users"></i> Lihat Peserta &amp; Kelas
                        </a>
					</div>
                </div>

                <form id="form-import-page" enctype="multipart/form-data">
                    <div class="box-body">
                        <div id="pesan-import-page"></div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="userfile">Pilih File Excel (.xlsx / .xls)</label>
                            <input type="file" id="userfile" name="userfile" accept=".xlsx,.xls" class="form-control">
                            <p class="help-block" style="margin-bottom: 0;">File akan dianalisa terlebih dahulu untuk menampilkan preview kelas baru dan deteksi duplikat sebelum disimpan ke database.</p>
                        </div>
                    </div>
					<div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="btn-analisa-page">
                            <i class="fa fa-search"></i> Analisa &amp; Preview Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Box 2: Hasil Preview & Konfirmasi Simpan -->
    <div class="row" id="box-preview-page" style="display:none;">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-check-square-o"></i> 2. Preview &amp; Konfirmasi Simpan</h3>
                    <div class="box-tools pull-right" id="badges-preview-page"></div>
                </div>
                <div class="box-body">
                    <input type="hidden" id="page-import-token" value="">

                    <div style="margin-bottom: 14px; background: #f8fafc; padding: 12px; border: 1px solid #e2e8f0; border-radius: 5px;">
                        <strong><i class="fa fa-sitemap"></i> Daftar Kelas (Group) Terdeteksi di Excel:</strong>
                        <div id="groups-preview-page" class="kelas-badge-list"></div>
                    </div>

                    <div id="dup-option-page" style="display:none; margin-bottom: 14px; background: #fffbeb; padding: 12px; border: 1px solid #fde68a; border-radius: 5px;">
                        <strong style="color: #92400e;"><i class="fa fa-exclamation-circle"></i> Ditemukan Username yang Sudah Terdaftar di Database:</strong>
                        <div style="margin-top: 6px;">
                            <label style="font-weight: normal; margin-right: 18px; cursor: pointer;">
                                <input type="radio" name="page-dup-mode" value="skip" checked> <strong>Lewati (Aman)</strong> &mdash; pertahankan data lama
                            </label>
                            <label style="font-weight: normal; cursor: pointer;">
                                <input type="radio" name="page-dup-mode" value="update"> <strong>Perbarui Data</strong> &mdash; update password/nama/kelas/ruang (riwayat tes tetap aman)
                            </label>
                        </div>
                    </div>

                    <div class="preview-table-wrap">
                        <table class="table table-bordered table-condensed table-striped" style="margin-bottom:0; font-size: 13px;">
                            <thead>
                                <tr style="background:#f3f4f6;">
                                    <th style="width:55px;">Baris</th>
                                    <th style="width:140px;">Username</th>
                                    <th>Nama Lengkap</th>
                                    <th style="width:165px;">Kelas (Group)</th>
                                    <th style="width:165px;">Keterangan</th>
                                    <th style="width:185px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-preview-page"></tbody>
                        </table>
                    </div>
                    <p id="truncated-note-page" class="help-block" style="display:none; margin-top: 8px; margin-bottom: 0;"></p>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-success" id="btn-simpan-page">
                        <i class="fa fa-save"></i> Simpan ke Database
                    </button>
                    <button type="button" class="btn btn-default pull-right" id="btn-batal-page">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Box 3: Ringkasan Hasil Simpan -->
    <div class="row" id="box-hasil-page" style="display:none;">
        <div class="col-md-12">
            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-check-circle"></i> Import Selesai</h3>
                </div>
                <div class="box-body" id="body-hasil-page"></div>
                <div class="box-footer">
                    <a href="<?php echo site_url('manager/peserta_daftar'); ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-users"></i> Buka Peserta &amp; Kelas
                    </a>
                    <a href="<?php echo site_url('manager/peserta_kartu'); ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-key"></i> Lanjut Cetak Kartu Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    function escHtmlPage(str){
        if(str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    $(function(){
        $('#form-import-page').submit(function(){
            var fileInput = $('#userfile')[0];
            if(!fileInput.files || fileInput.files.length === 0){
                $('#pesan-import-page').html(pesan_err('Pilih file Excel (.xlsx / .xls) terlebih dahulu.'));
                return false;
            }
            var formData = new FormData(this);
            $('#pesan-import-page').html('');
            $('#box-hasil-page').hide();
            $('#modal-proses').modal('show');

            $.ajax({
                url: '<?php echo site_url($url."/preview"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(respon){
                    $('#modal-proses').modal('hide');
                    var obj = $.parseJSON(respon);
                    if(obj.status != 1){
                        $('#pesan-import-page').html(pesan_err(obj.pesan));
                        $('#box-preview-page').hide();
                        return;
                    }

                    $('#page-import-token').val(obj.token);
                    var st = obj.stats;
                    var badges = '<span class="label label-default" style="font-size:12px; margin-right:4px;">Total: ' + st.total_rows + '</span>' +
                                 '<span class="label label-success" style="font-size:12px; margin-right:4px;">Peserta Baru: ' + st.valid_new + '</span>' +
                                 '<span class="label label-primary" style="font-size:12px; margin-right:4px;">Kelas Baru: ' + obj.new_groups_count + '</span>';
                    if(st.existing_users > 0){
                        badges += '<span class="label label-warning" style="font-size:12px; margin-right:4px;">Sudah Ada: ' + st.existing_users + '</span>';
                    }
                    if(st.invalid_rows + st.dup_in_file > 0){
                        badges += '<span class="label label-danger" style="font-size:12px;">Bermasalah: ' + (st.invalid_rows + st.dup_in_file) + '</span>';
                    }
                    $('#badges-preview-page').html(badges);

                    var gHtml = '';
                    for(var i=0; i<obj.groups_summary.length; i++){
                        var gs = obj.groups_summary[i];
                        var isNew = gs.status === 'new';
                        gHtml += '<span class="kelas-badge-item ' + (isNew ? 'is-new' : '') + '">' +
                                    '<strong>' + escHtmlPage(gs.nama) + '</strong> (' + gs.count + ' peserta) ' +
                                    (isNew ? '<span class="label label-success" style="font-size:10px;">Kelas Baru</span>' : '<span class="label label-default" style="font-size:10px;">Sudah Ada</span>') +
                                 '</span>';
                    }
                    if(!gHtml) gHtml = '<span class="text-muted">Tidak ada kelas valid.</span>';
                    $('#groups-preview-page').html(gHtml);

                    if(st.existing_users > 0){
                        $('#dup-option-page').show();
                    }else{
                        $('#dup-option-page').hide();
                    }

                    var rHtml = '';
                    for(var j=0; j<obj.preview_rows.length; j++){
                        var r = obj.preview_rows[j];
                        var stBadge = '';
                        var trCls = '';
                        if(r.status === 'new'){
                            stBadge = '<span class="label label-success">Siap Tambah</span>';
                        }else if(r.status === 'existing'){
                            stBadge = '<span class="label label-warning">Username Sudah Ada</span>';
                            trCls = 'warning';
                        }else{
                            stBadge = '<span class="label label-danger">' + escHtmlPage(r.errors.join('; ')) + '</span>';
                            trCls = 'danger';
                        }
                        if(r.warnings && r.warnings.length > 0){
                            stBadge += ' <small class="text-warning" title="' + escHtmlPage(r.warnings.join('; ')) + '"><i class="fa fa-info-circle"></i></small>';
                        }
                        rHtml += '<tr class="' + trCls + '">' +
                                    '<td>' + r.row + '</td>' +
                                    '<td><code>' + escHtmlPage(r.username) + '</code></td>' +
                                    '<td>' + escHtmlPage(r.nama) + '</td>' +
                                    '<td>' + escHtmlPage(r.group) + (r.group_is_new ? ' <span class="label label-success" style="font-size:9px;">Baru</span>' : '') + '</td>' +
                                    '<td>' + escHtmlPage(r.detail || '-') + '</td>' +
                                    '<td>' + stBadge + '</td>' +
                                 '</tr>';
                    }
                    $('#tbody-preview-page').html(rHtml);

                    if(obj.preview_truncated){
                        $('#truncated-note-page').text('Menampilkan 100 baris pertama + semua baris bermasalah. Seluruh ' + st.total_rows + ' baris akan diproses saat klik Simpan.').show();
                    }else{
                        $('#truncated-note-page').hide();
                    }

                    $('#box-preview-page').show();
                    $('#btn-simpan-page').toggle(st.valid_new > 0 || st.existing_users > 0);
                },
                error: function(){
                    $('#modal-proses').modal('hide');
                    $('#pesan-import-page').html(pesan_err('Gagal mengupload file Excel.'));
                }
            });
            return false;
        });

        $('#btn-batal-page').click(function(){
            $('#box-preview-page').hide();
            $('#page-import-token').val('');
            $('#userfile').val('');
        });

        $('#btn-simpan-page').click(function(){
            var token = $('#page-import-token').val();
            var dupMode = $('input[name="page-dup-mode"]:checked').val() || 'skip';
            if(!token) return;

            $('#modal-proses').modal('show');
            $.post('<?php echo site_url($url."/simpan"); ?>', {
                token: token,
                duplicate_mode: dupMode
            }, function(respon){
                $('#modal-proses').modal('hide');
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#box-preview-page').hide();
                    $('#userfile').val('');
                    var detailHtml = '<p style="font-size:14px; font-weight:600;">' + escHtmlPage(obj.pesan) + '</p>';
                    if(obj.kelas_baru && obj.kelas_baru.length > 0){
                        detailHtml += '<p style="margin-bottom:0;"><strong>Kelas baru yang dibuat otomatis:</strong> ' + escHtmlPage(obj.kelas_baru.join(', ')) + '</p>';
                    }
                    $('#body-hasil-page').html(detailHtml);
                    $('#box-hasil-page').show();
                    notify_success(obj.pesan);
                }else{
                    $('#pesan-import-page').html(pesan_err(obj.pesan));
                }
            }).fail(function(){
                $('#modal-proses').modal('hide');
                $('#pesan-import-page').html(pesan_err('Gagal menghubungi server saat menyimpan data.'));
            });
        });
    });
</script>
