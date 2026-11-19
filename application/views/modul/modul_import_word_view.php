<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Mengimport Soal dari Word
		<small>Melakukan Import Soal pilihan ganda berdasarkan modul dan topik</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Import Soal Word</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab-batch-upload" data-toggle="tab">
					<i class="fa fa-cloud-upload"></i> Batch Upload (.docx / .doc / .zip)
					<span class="label label-success" style="margin-left: 4px;">Multi-File</span>
				</a>
			</li>
			<li>
				<a href="#tab-copy-paste" data-toggle="tab">
					<i class="fa fa-clipboard"></i> Import Copy-Paste (Manual)
				</a>
			</li>
			<li class="pull-right" style="padding: 8px 12px 0 0;">
				<a href="<?php echo base_url(); ?>public/form/form-soal-ganda.docx" class="btn btn-default btn-xs" style="padding: 4px 10px; border: 1px solid #d2d6de;">
					<i class="fa fa-download"></i> Template Word (.docx)
				</a>
			</li>
		</ul>

		<div class="tab-content" style="background: #ecf0f5; padding: 15px;">
			<!-- TAB 1: BATCH UPLOAD .DOCX / .ZIP -->
			<div class="tab-pane active" id="tab-batch-upload">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-files-o"></i> Upload Banyak File Soal Word (.docx / .doc / .zip) Sekaligus</h3>
								<div class="box-tools pull-right">
									<?php if(!empty($active_modul_nama)): ?>
										<span class="label label-primary" style="font-size: 12px;">Modul Aktif: <?php echo htmlspecialchars($active_modul_nama, ENT_QUOTES, 'UTF-8'); ?></span>
									<?php else: ?>
										<span class="label label-danger" style="font-size: 12px;">Belum Ada Modul Aktif</span>
									<?php endif; ?>
								</div>
							</div>
							<?php echo form_open_multipart($url.'/batch_preview', 'id="form-batch-upload"'); ?>
							<div class="box-body">
								<div id="batch-pesan"></div>
								<div class="callout callout-info" style="margin-bottom: 15px;">
									<p style="margin: 0;">
										<strong>Cara Pakai:</strong> Pilih beberapa file <code>.docx</code> atau <code>.doc</code> (ekspor HTML/Gemini Canvas) sesuai template ZyaCBT (tabel <code>No | Jenis | Isi | Jawaban</code>) sekaligus, atau masukkan puluhan file ke dalam satu arsip <code>.zip</code> lalu upload di sini. Tabel PG &amp; Uraian/Esai serta gambar di dalamnya otomatis terbaca. Nama file otomatis dicocokkan dengan Topik di Modul Aktif.
									</p>
								</div>
								<div class="form-group">
									<label for="batch-files">Pilih File Soal (.docx, .doc, atau .zip)</label>
									<input type="file" id="batch-files" name="files[]" multiple accept=".docx,.doc,.zip" class="form-control" style="height: auto; padding: 8px;" />
									<p class="help-block">
										Batas server saat ini: maks. <strong><?php echo !empty($php_max_file_uploads) ? intval($php_max_file_uploads) : 20; ?> file</strong> per upload langsung (maks. <strong><?php echo !empty($php_upload_max) ? htmlspecialchars($php_upload_max, ENT_QUOTES, 'UTF-8') : '64M'; ?></strong>). Untuk lebih dari <?php echo !empty($php_max_file_uploads) ? intval($php_max_file_uploads) : 20; ?> mapel sekaligus, kompres semua file <code>.docx</code> / <code>.doc</code> menjadi <strong>1 file .zip</strong> lalu upload.
									</p>
								</div>
							</div>
							<div class="box-footer">
								<button type="submit" class="btn btn-primary" id="btn-batch-analisis">
									<i class="fa fa-search"></i> Upload &amp; Analisis File
								</button>
								<button type="button" class="btn btn-default hide" id="btn-batch-reset" style="margin-left: 6px;">
									<i class="fa fa-refresh"></i> Reset
								</button>
							</div>
							</form>
						</div>
					</div>
				</div>

				<!-- AREA PREVIEW TAHAP 2 -->
				<div class="row hide" id="box-batch-preview">
					<div class="col-md-12">
						<?php echo form_open($url.'/batch_simpan', 'id="form-batch-simpan"'); ?>
						<input type="hidden" name="batch_token" id="batch-token" value="" />
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-list-alt"></i> Preview &amp; Pemetaan Topik</h3>
								<div class="box-tools pull-right" id="batch-summary-badges"></div>
							</div>
							<div class="box-body">
								<div id="batch-preview-pesan"></div>
								<div class="well well-sm" style="margin-bottom: 12px; background: #f8fafc; border-color: #dce4ec;">
									<strong style="margin-right: 12px;"><i class="fa fa-sliders"></i> Mode Import Soal:</strong>
									<label class="radio-inline" style="margin-right: 16px;">
										<input type="radio" name="import_mode" class="batch-import-mode" value="pg_esai" checked />
										<strong>Sertakan Esai (Topik Terpisah)</strong>
										<small class="text-muted">— Esai otomatis jadi topik baru berawalan <code>Esai ...</code></small>
									</label>
									<label class="radio-inline">
										<input type="radio" name="import_mode" class="batch-import-mode" value="pg_only" />
										<strong>Pilihan Ganda Saja</strong>
										<small class="text-muted">— Hanya import PG, abaikan tabel Esai</small>
									</label>
								</div>
								<style>
									#table-batch-preview {
										table-layout: fixed;
										width: 100%;
										margin-bottom: 0;
									}
									#table-batch-preview th,
									#table-batch-preview td {
										word-wrap: break-word;
										overflow-wrap: anywhere;
									}
									#table-batch-preview .form-control {
										width: 100%;
										box-sizing: border-box;
									}
								</style>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover" id="table-batch-preview">
										<thead>
											<tr>
												<th style="width: 36px; text-align: center;">
													<input type="checkbox" id="batch-check-all" checked />
												</th>
												<th style="width: 29%;">Nama File (.docx / .doc)</th>
												<th style="width: 115px;">Hasil Baca</th>
												<th style="width: 135px;">Status</th>
												<th>Target Topik (Modul Aktif)</th>
											</tr>
										</thead>
										<tbody id="batch-preview-tbody"></tbody>
									</table>
								</div>
							</div>
							<div class="box-footer">
								<button type="button" class="btn btn-default" id="btn-batch-batal">
									<i class="fa fa-times"></i> Batal / Upload Ulang
								</button>
								<button type="submit" class="btn btn-success pull-right" id="btn-batch-simpan">
									<i class="fa fa-save"></i> Simpan Semua ke Bank Soal
								</button>
							</div>
						</div>
						</form>
					</div>
				</div>

				<!-- AREA HASIL IMPORT BATCH -->
				<div class="row hide" id="box-batch-hasil">
					<div class="col-md-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-check-circle"></i> Laporan Hasil Batch Import</h3>
							</div>
							<div class="box-body">
								<div id="batch-hasil-ringkasan"></div>
								<div class="table-responsive">
									<table class="table table-bordered table-condensed">
										<thead>
											<tr>
												<th>Nama File</th>
												<th>Topik Tujuan</th>
												<th>Soal</th>
												<th>Opsi</th>
												<th>Gambar</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody id="batch-hasil-tbody"></tbody>
									</table>
								</div>
							</div>
							<div class="box-footer">
								<button type="button" class="btn btn-primary" id="btn-batch-lagi">
									<i class="fa fa-cloud-upload"></i> Upload Batch Lagi
								</button>
								<a href="<?php echo site_url('manager/modul_daftar'); ?>" class="btn btn-default pull-right">
									<i class="fa fa-list"></i> Lihat Daftar Soal
								</a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.tab-pane batch -->

			<!-- TAB 2: IMPORT COPY-PASTE (ORIGINAL) -->
			<div class="tab-pane" id="tab-copy-paste">
    <div class="row" id="box-awal">
		<?php echo form_open($url.'/import','id="form-importsoal"'); ?>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-title">Pilih Topik</div>
                </div><!-- /.box-header -->

                <div class="box-body">
					<div class="form-group">
                        <label>Pilih Topik</label>
						<select name="topik" id="topik" class="form-control input-sm">
							<?php if(!empty($select_topik)){ echo $select_topik; } ?>
                        </select>
					</div>
                </div>
                <div class="box-footer">
                    <p>Pilih terlebih dahulu Topik yang akan digunakan sebelum melakukan import soal</p>
                </div>
            </div>
        </div>
		<div class="col-md-8">
			<div class="box">
                <div class="box-header with-border">
                    <div class="box-title">Import Soal dari Word</div>
					<div class="box-tools pull-right">
						<div class="dropdown pull-right">
							<a href="<?php echo base_url(); ?>public/form/form-soal-ganda.docx">Form Word Soal Pilihan Ganda</a>
    					</div>
    				</div>
                </div><!-- /.box-header -->

                <div class="box-body">
					<span id="form-pesan"></span>
                    <div class="form-group">
                        <label>Data Soal sesuai Format Pilihan Ganda</label>
                        <input type="hidden" name="import-soal" id="import-soal" >
                        <textarea class="textarea" id="import_soal" name="import_soal" style="width: 100%; height: 150px; font-size: 13px; line-height: 25px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        <p class="help-block">
							Paste Tabel pada file Word sesuai format yang telah ditentukan untuk proses import.<br/>
							Jangan sertakan baris kosong dalam format import soal yang akan dicopy, karena akan mengganggu hasil import soal.<br />
							Buat tampilan menjadi Fullscreen untuk mempermudah dalam melakukan paste ke layar.
						</p>
					</div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right" id="import">Import</button>
                </div>
            </div>
        </div>
		</form>
    </div>
	
	<div class="row hide" id="box-konfirmasi">
		<div class="col-md-12">
			<?php echo form_open($url.'/konfirmasi','id="form-konfirmasi"'); ?>
			<div class="box">
                <div class="box-header with-border">
                    <div class="box-title">Konfirmasi Daftar Soal <span id="judul-daftar-soal"></span></div>
                </div><!-- /.box-header -->

                <div class="box-body">
					<div class="callout callout-info">
						<h4>Konfirmasi</h4>
						<p>Silahkan cek soal pilihan ganda yang telah dikirim. Jika soal sudah sesuai, silahkan klik tombol Simpan dibawah ini.</p>
						<input type="hidden" name="konfirmasi-import-soal" id="konfirmasi-import-soal" >
						<input type="hidden" name="konfirmasi-topik" id="konfirmasi-topik" >
					</div>
					<span id="form-pesan-konfirmasi"></span>
                    <div id="daftar-soal" style="overflow-y: auto;height: 500px;">
					
					</div>
                </div>
                <div class="box-footer">
					<button type="button" class="btn btn-default" id="batal">Batal</button>
                    <button type="submit" class="btn btn-primary pull-right" id="btn-konfirmasi">Simpan</button>
                </div>
            </div>
			</form>
		</div>
	</div>
			</div><!-- /.tab-pane copy-paste -->
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->
</section><!-- /.content -->

<div class="modal" id="modal-sukses" data-backdrop="static" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Informasi Import Soal</h4>
            </div>
            <div class="modal-body">
                <div class="callout callout-info">
                    <p>
						Import Soal pilihan ganda berhasil.<br />
						Berikut informasi soal yang berhasil di import :<br/>
						Jumlah Soal : <span id="info-jml-soal"></span><br/>
						Jumlah Jawaban : <span id="info-jml-jawaban"></span><br/>
					</p>
					
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="refresh">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

	<div class="modal" id="modal-image" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog" style="width: 950px">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul">Insert Image</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <?php echo form_open_multipart($url.'/upload_file','id="form-upload-image" class="form-horizontal"'); ?>
                                        <div class="box">
                                            <div class="box-header with-border">
                                                <div class="box-title">Upload File</div>
                                            </div><!-- /.box-header -->

                                            <div class="box-body">
                                                <div class="row-fluid">
                                                    <div class="box-body">
                                                        <div id="form-pesan-upload-image"></div>
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">File</label>
                                                            <div class="col-sm-10">
                                                                <input type="hidden" id="image-topik-id" name="image-topik-id" >
                                                                <input type="file" id="image-file" name="image-file" >
                                                                <p class="help-block">File yang didukung adalah jpg, jpeg, png</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="image-upload" class="btn btn-primary">Upload File</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-xs-6">
                                        <div class="box hide" id="box-preview" >
                                            <div class="box-body">
                                                <div class="row-fluid">
                                                    <div class="box-body" style="height: 132px;">
                                                        <input type="hidden" name="image-isi" id="image-isi">
                                                        <div id="image-preview" style="text-align: center;vertical-align: middle;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="btn-image-insert" class="btn btn-primary">Masukkan Gambar</button>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-body" style="max-height: 230px;overflow: auto;">
                                            <table id="table-image" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama File</th>
                                                        <th>Preview</th>
                                                        <th>Tanggal</th>
                                                        <th> </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> </td>
                                                        <td> </td>
                                                        <td> </td>
                                                        <td> </td>
                                                        <td> </td>
                                                    </tr>
                                                </tbody>
                                            </table>                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script lang="javascript">

    function batal_tambah(){
        $("#form-pesan").html('');
    }
	
	/**
     * Fungsi untuk upload image dari editor text
     */
    function imageUpload(){
        $('#box-preview').addClass('hide');
        $('#image-preview').html('');
        $('#form-pesan-upload-image').html('');
        $('#image-isi').val('');
        $('#image-file').val('');

        refresh_table_image();

        $("#modal-image").modal("show");
    }
	
	function refresh_table_image(){
        $('#table-image').dataTable().fnReloadAjax();
    }

    function image_preview(posisi, image){
        $('#image-preview').html('<img src="<?php echo base_url(); ?>'+posisi+'/'+image+'" style="max-height: 110px;" />');
        $('#image-isi').val('<img src="<?php echo base_url(); ?>'+posisi+'/'+image+'" style="max-width: 600px;" />');
        $('#box-preview').removeClass('hide');
    }

    $(function(){
        $('#topik').select2();
		
		$('#batal').click(function(){
			location.reload(); 
		});
		
		$('#refresh').click(function(){
			location.reload(); 
		});

        /**
         * Submit form import soal
         */
        $('#form-importsoal').submit(function(){
			$('#import-soal').val(CKEDITOR.instances.import_soal.getData());
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/import",
                    type:"POST",
                    timeout: 300000,
                    data:$('#form-importsoal').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            batal_tambah();
							$('#konfirmasi-import-soal').val($('#import-soal').val());
							$('#konfirmasi-topik').val($('#topik').val());
							$('#daftar-soal').html(obj.soal);
                            $('#box-awal').addClass('hide');
							$('#box-konfirmasi').removeClass('hide');
							
							var judul = $('#topik option:selected').text();
							$('#judul-daftar-soal').html(" : "+judul);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    },
                    statusCode: {
                        500: function(respon) {
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err('Terjadi kesalahan saat import soal. Silahkan cek terlebih dahulu format yang di upload.'));
                        }
                    },
                    error: function(xmlhttprequest, textstatus, message) {
                        if(textstatus==="timeout") {
                            $("#modal-proses").modal('hide');
                            notify_error("Gagal mengimport Soal, Silahkan Refresh Halaman");
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(message);
                        }
                    }
            });
            return false;
        });
		
		/**
         * Submit form import soal
         */
        $('#form-konfirmasi').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/konfirmasi",
                    type:"POST",
                    timeout: 300000,
                    data:$('#form-konfirmasi').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            // Menampilkan modal konfirmasi berhasil import soal
							$("#info-jml-soal").html(obj.counter_soal)
							$("#info-jml-jawaban").html(obj.counter_jawaban)
							$("#modal-sukses").modal('show');
							// Tombol informasi akan me refresh halaman 
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    },
                    statusCode: {
                        500: function(respon) {
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err('Terjadi kesalahan saat import soal. Silahkan cek terlebih dahulu format yang di upload.'));
                        }
                    },
                    error: function(xmlhttprequest, textstatus, message) {
                        if(textstatus==="timeout") {
                            $("#modal-proses").modal('hide');
                            notify_error("Gagal mengimport Soal, Silahkan Refresh Halaman");
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(textstatus);
                        }
                    }
            });
            return false;
        });
		
		
		$('#btn-image-insert').click(function(){
            var image = $('#image-isi').val();
            CKEDITOR.instances.import_soal.insertHtml(image);
            $("#modal-image").modal("hide");
        });
		/**
         * Submit form upload pada image browser
         */
        $('#form-upload-image').submit(function(){
            $('#image-topik-id').val($('#topik').val());
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/upload_file",
                    type:"POST",
                    data:new FormData(this),
                    mimeType: "multipart/form-data",
                    contentType:false,
                    cache: false,
                    processData: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $('#image-preview').html(obj.image);
                            $('#image-isi').val(obj.image_isi);
                            $('#box-preview').removeClass('hide');
                            $("#modal-proses").modal('hide');
                            $("#form-pesan-upload-image").html('');
                            $('#image-file').val('');
                            refresh_table_image();
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan-upload-image').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });
		
		$('#table-image').DataTable({
                  "bPaginate": false,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": false,
                  "aoColumns": [
                        {"bSearchable": false, "bSortable": false, "sWidth":"20px"},
                        {"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false, "sWidth":"100px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"90px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"50px"}],
                  "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable_image/",
                  "autoWidth": false,
                  "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "topik", "value": $('#topik').val()} );
                  }
            });
		 
		$( document ).ready(function() {
            CKEDITOR.replace('import_soal');
		});

        /**
         * Batch Upload (.docx / .zip) Handlers
         */
        function escapeHtml(str) {
            return String(str || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function resetBatchUI() {
            $('#batch-files').val('');
            $('#batch-token').val('');
            $('#batch-pesan').html('');
            $('#batch-preview-pesan').html('');
            $('#batch-preview-tbody').html('');
            $('#batch-summary-badges').html('');
            $('#box-batch-preview').addClass('hide');
            $('#box-batch-hasil').addClass('hide');
            $('#btn-batch-reset').addClass('hide');
        }

        $('#btn-batch-reset, #btn-batch-batal, #btn-batch-lagi').click(function() {
            resetBatchUI();
        });

        var BATCH_TOPICS_BY_ID = {};
        var BATCH_TOPICS_BY_LOWER = {};

        function buildEsaiTopicName(baseName) {
            var name = $.trim(String(baseName || ''));
            if (name === '') name = 'Soal';
            if (/^esai\b/i.test(name)) {
                return name;
            }
            return 'Esai ' + name;
        }

        function refreshRowTargetHints(idx) {
            var $sel = $('.batch-target-select[data-idx="' + idx + '"]');
            if ($sel.length === 0) return;

            var val = $sel.val();
            var $newWrap = $('#batch-new-wrap-' + idx);
            var $existWarn = $('#batch-exist-warn-' + idx);
            var baseName = '';

            if (val === '__NEW__') {
                $newWrap.removeClass('hide');
                baseName = $.trim($('#batch-new-input-' + idx).val()) || $sel.data('clean-name') || '';
                $existWarn.addClass('hide').html('');
            } else {
                $newWrap.addClass('hide');
                var t = BATCH_TOPICS_BY_ID[String(val)];
                baseName = t ? t.nama : '';
                if (t && parseInt(t.jml_soal, 10) > 0) {
                    $existWarn.removeClass('hide').html(
                        '<i class="fa fa-info-circle"></i> Topik sudah berisi <strong>' + t.jml_soal + ' soal</strong> (akan <strong>menambah</strong> soal, tidak menimpa).'
                    );
                } else {
                    $existWarn.addClass('hide').html('');
                }
            }

            var $esaiPreview = $('#batch-esai-target-' + idx);
            if ($esaiPreview.length > 0) {
                var jmlEsai = parseInt($esaiPreview.data('jml-esai'), 10) || 0;
                var mode = $('input[name="import_mode"]:checked').val() || 'pg_esai';
                if (mode === 'pg_only') {
                    $esaiPreview.html(
                        '<span class="text-muted" style="text-decoration:line-through;"><i class="fa fa-ban"></i> ' + jmlEsai + ' Esai diabaikan (mode Pilihan Ganda Saja)</span>'
                    );
                } else {
                    var esaiName = buildEsaiTopicName(baseName);
                    var existingEsai = BATCH_TOPICS_BY_LOWER[esaiName.toLowerCase()];
                    var esaiBadge = existingEsai
                        ? ' <span class="label label-warning" style="font-size:10px;">Sudah ada (' + existingEsai.jml_soal + ' soal)</span>'
                        : ' <span class="label" style="background:#4f46e5; font-size:10px;">Topik Baru</span>';
                    $esaiPreview.html(
                        '<i class="fa fa-level-up fa-rotate-90 text-purple"></i> <strong>Topik Esai (' + jmlEsai + ' soal):</strong> ' +
                        '<span style="color:#4f46e5; font-weight:600;">' + escapeHtml(esaiName) + '</span>' + esaiBadge
                    );
                }
            }
        }

        $(document).on('change', '#batch-check-all', function() {
            var checked = $(this).is(':checked');
            $('.batch-file-check:not(:disabled)').prop('checked', checked);
        });

        $(document).on('change', '.batch-target-select', function() {
            refreshRowTargetHints($(this).data('idx'));
        });

        $(document).on('input', '.batch-new-topik-input', function() {
            refreshRowTargetHints($(this).data('idx'));
        });

        $(document).on('change', '.batch-import-mode', function() {
            $('.batch-target-select').each(function() {
                refreshRowTargetHints($(this).data('idx'));
            });
        });

        $('#form-batch-upload').submit(function(e) {
            e.preventDefault();
            var fileInput = document.getElementById('batch-files');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                $('#batch-pesan').html(pesan_err('Pilih minimal satu file .docx, .doc, atau .zip terlebih dahulu.'));
                return false;
            }

            $('#batch-pesan').html('');
            $('#box-batch-hasil').addClass('hide');
            $("#modal-proses").modal('show');

            $.ajax({
                url: "<?php echo site_url().'/'.$url; ?>/batch_preview",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                timeout: 300000,
                success: function(respon) {
                    $("#modal-proses").modal('hide');
                    var obj;
                    try {
                        obj = $.parseJSON(respon);
                    } catch (err) {
                        $('#batch-pesan').html(pesan_err('Respon server tidak valid. Cek ukuran file atau format yang diupload.'));
                        return;
                    }

                    if (obj.status == 1) {
                        $('#batch-token').val(obj.batch_token);
                        $('#btn-batch-reset').removeClass('hide');
                        $('input[name="import_mode"][value="pg_esai"]').prop('checked', true);

                        var sum = obj.summary || {};
                        $('#batch-summary-badges').html(
                            '<span class="label label-primary" style="margin-right:4px;">' + (sum.total_siap || 0) + ' / ' + (sum.total_file || 0) + ' File Siap</span>' +
                            ((sum.total_duplikat || 0) > 0 ? '<span class="label label-warning" style="margin-right:4px;">' + sum.total_duplikat + ' Duplikat Dilewati</span>' : '') +
                            '<span class="label label-success" style="margin-right:4px;">' + (sum.total_soal || 0) + ' PG</span>' +
                            ((sum.total_esai || 0) > 0 ? '<span class="label" style="background:#4f46e5; margin-right:4px;">' + sum.total_esai + ' Esai (' + (sum.total_file_ada_esai || 0) + ' topik terpisah)</span>' : '') +
                            '<span class="label label-info">' + (sum.total_gambar || 0) + ' Gambar</span>'
                        );

                        var alertHtml = '';
                        if (obj.skipped_notes && obj.skipped_notes.length > 0) {
                            alertHtml += '<div class="alert alert-warning" style="margin-bottom:10px;"><strong>File dilewati:</strong> ' +
                                escapeHtml(obj.skipped_notes.join('; ')) + '</div>';
                        }
                        if ((sum.total_duplikat || 0) > 0) {
                            alertHtml += '<div class="alert alert-warning" style="margin-bottom:10px;"><i class="fa fa-copy"></i> <strong>' + sum.total_duplikat + ' file duplikat identik</strong> (isi 100% sama) terdeteksi di dalam batch dan <strong>otomatis tidak dicentang</strong> agar soal tidak masuk ganda.</div>';
                        }
                        $('#batch-preview-pesan').html(alertHtml);

                        var topics = obj.topics || [];
                        BATCH_TOPICS_BY_ID = {};
                        BATCH_TOPICS_BY_LOWER = {};
                        $.each(topics, function(__, t) {
                            BATCH_TOPICS_BY_ID[String(t.id)] = t;
                            BATCH_TOPICS_BY_LOWER[String(t.nama || '').toLowerCase()] = t;
                        });

                        var canCreate = !!obj.can_create_topik;
                        var tbodyHtml = '';
                        var allChecked = true;

                        $.each(obj.files || [], function(_, f) {
                            var isError = (f.status === 'error');
                            var isDup = !!f.is_duplicate_content;
                            var isChecked = !isError && (f.default_checked !== false);
                            if (!isError && !isChecked) allChecked = false;

                            var statusBadge = '';
                            if (isError) {
                                statusBadge = '<span class="label label-danger"><i class="fa fa-times"></i> Gagal Baca</span>';
                            } else if (isDup) {
                                statusBadge = '<span class="label label-warning"><i class="fa fa-copy"></i> Duplikat Identik</span>';
                            } else if (f.status === 'warning') {
                                statusBadge = '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Peringatan</span>';
                            } else {
                                statusBadge = '<span class="label label-success"><i class="fa fa-check"></i> Siap Import</span>';
                            }

                            var warnHtml = '';
                            if (f.warnings && f.warnings.length > 0) {
                                warnHtml = '<div class="text-muted" style="font-size:11px; margin-top:4px;">' +
                                    escapeHtml(f.warnings.join(' ')) + '</div>';
                            }

                            var matchNote = '';
                            if (f.name_disambiguated) {
                                matchNote += ' <span class="label label-warning" style="font-size:10px;" title="Nama file sama dengan file lain, otomatis dibedakan dengan nama folder">Nama Dibedakan</span>';
                            }
                            if (f.match_type === 'exact') {
                                matchNote += ' <span class="label label-info" style="font-size:10px;">Auto-Match</span>';
                            } else if (f.match_type === 'partial') {
                                matchNote += ' <span class="label label-default" style="font-size:10px;">Mirip</span>';
                            }

                            var selectHtml = '<select name="target_topik[' + f.index + ']" class="form-control input-sm batch-target-select" data-idx="' + f.index + '" data-clean-name="' + escapeHtml(f.clean_name) + '"' + (isError ? ' disabled' : '') + '>';
                            if (canCreate) {
                                selectHtml += '<option value="__NEW__"' + (f.suggested_topik === '__NEW__' ? ' selected' : '') + '>+ Buat Topik Baru (di Modul Aktif)</option>';
                            }
                            $.each(topics, function(__, t) {
                                var sel = (String(f.suggested_topik) === String(t.id)) ? ' selected' : '';
                                selectHtml += '<option value="' + t.id + '"' + sel + '>' + escapeHtml(t.nama) + ' [' + t.jml_soal + ' soal]</option>';
                            });
                            selectHtml += '</select>';

                            var newWrapClass = (f.suggested_topik === '__NEW__' && canCreate && !isError) ? '' : ' hide';
                            selectHtml += '<div id="batch-new-wrap-' + f.index + '" class="' + newWrapClass + '" style="margin-top:5px;">' +
                                '<input type="text" id="batch-new-input-' + f.index + '" name="new_topik_name[' + f.index + ']" data-idx="' + f.index + '" class="form-control input-sm batch-new-topik-input" value="' + escapeHtml(f.clean_name) + '" placeholder="Nama topik PG baru..." />' +
                                '</div>';

                            selectHtml += '<div id="batch-exist-warn-' + f.index + '" class="text-warning hide" style="font-size:11px; margin-top:4px; color:#b45309;"></div>';

                            if ((f.jumlah_esai || 0) > 0 && !isError) {
                                selectHtml += '<div id="batch-esai-target-' + f.index + '" data-jml-esai="' + f.jumlah_esai + '" style="font-size:11px; margin-top:5px; padding:4px 7px; background:#f5f3ff; border-left:3px solid #4f46e5; border-radius:2px;"></div>';
                            }

                            var rowCls = isError ? ' class="danger"' : ((isDup || f.status === 'warning') ? ' class="warning"' : '');
                            tbodyHtml += '<tr' + rowCls + '>' +
                                '<td style="text-align:center; vertical-align:middle;">' +
                                    '<input type="checkbox" class="batch-file-check" name="include_idx[]" value="' + f.index + '"' + (isError ? ' disabled' : (isChecked ? ' checked' : '')) + ' />' +
                                '</td>' +
                                '<td style="vertical-align:middle;"><strong>' + escapeHtml(f.filename) + '</strong> <span class="label label-default" style="font-size:10px;">' + escapeHtml(f.format_label || '') + '</span>' + matchNote + '</td>' +
                                '<td style="vertical-align:middle;">' +
                                    '<strong>' + f.jumlah_soal + '</strong> PG, ' + f.jumlah_jawaban + ' Opsi' +
                                    ((f.jumlah_esai || 0) > 0 ? '<br/><span class="label" style="background:#4f46e5; font-size:10px;">+ ' + f.jumlah_esai + ' Esai</span>' : '') +
                                    (f.jumlah_gambar > 0 ? '<br/><span class="text-info"><i class="fa fa-image"></i> ' + f.jumlah_gambar + ' gambar</span>' : '') +
                                '</td>' +
                                '<td style="vertical-align:middle;">' + statusBadge + warnHtml + '</td>' +
                                '<td style="vertical-align:middle;">' + selectHtml + '</td>' +
                            '</tr>';
                        });

                        $('#batch-preview-tbody').html(tbodyHtml);
                        $.each(obj.files || [], function(_, f) {
                            refreshRowTargetHints(f.index);
                        });
                        $('#batch-check-all').prop('checked', allChecked);
                        $('#box-batch-preview').removeClass('hide');
                        notify_success(obj.pesan);
                    } else {
                        $('#batch-pesan').html(pesan_err(obj.pesan));
                    }
                },
                error: function(xhr, textstatus, message) {
                    $("#modal-proses").modal('hide');
                    $('#batch-pesan').html(pesan_err('Gagal mengupload file (' + escapeHtml(textstatus || message) + ').'));
                }
            });
            return false;
        });

        $('#form-batch-simpan').submit(function(e) {
            e.preventDefault();
            if ($('.batch-file-check:checked').length === 0) {
                $('#batch-preview-pesan').html(pesan_err('Centang minimal satu file untuk disimpan ke Bank Soal.'));
                return false;
            }

            $('#batch-preview-pesan').html('');
            $("#modal-proses").modal('show');

            $.ajax({
                url: "<?php echo site_url().'/'.$url; ?>/batch_simpan",
                type: "POST",
                data: $(this).serialize(),
                cache: false,
                timeout: 300000,
                success: function(respon) {
                    $("#modal-proses").modal('hide');
                    var obj;
                    try {
                        obj = $.parseJSON(respon);
                    } catch (err) {
                        $('#batch-preview-pesan').html(pesan_err('Respon server tidak valid saat menyimpan batch.'));
                        return;
                    }

                    var sum = obj.summary || {};
                    var alertCls = (obj.status == 1) ? 'callout-success' : 'callout-danger';
                    $('#batch-hasil-ringkasan').html(
                        '<div class="callout ' + alertCls + '">' +
                            '<h4>' + (obj.status == 1 ? 'Batch Import Selesai' : 'Batch Import Gagal') + '</h4>' +
                            '<p>' + escapeHtml(obj.pesan) +
                            (sum.total_gambar > 0 ? ' (' + sum.total_gambar + ' gambar diekstrak)' : '') +
                            (sum.total_topik_baru > 0 ? ' (' + sum.total_topik_baru + ' topik baru dibuat)' : '') +
                            '</p>' +
                        '</div>'
                    );

                    var rowsHtml = '';
                    $.each(obj.reports || [], function(_, r) {
                        var isOk = (r.status === 'ok');
                        var badge = isOk
                            ? '<span class="label label-success">Berhasil</span>'
                            : '<span class="label label-danger">Gagal</span>';
                        var topikLabel = '-';
                        if (isOk) {
                            var parts = [];
                            if (r.jumlah_soal > 0 && r.topik_nama) {
                                parts.push('<strong>PG:</strong> ' + escapeHtml(r.topik_nama) + (r.created_new_topic ? ' <span class="label label-info">Baru</span>' : ''));
                            }
                            if (r.jumlah_esai > 0 && r.esai_topik_nama) {
                                parts.push('<span style="color:#4f46e5;"><strong>Esai:</strong> ' + escapeHtml(r.esai_topik_nama) + '</span>' + (r.created_esai_topic ? ' <span class="label" style="background:#4f46e5;">Baru</span>' : ''));
                            }
                            topikLabel = parts.length > 0 ? parts.join('<br/>') : escapeHtml(r.topik_nama || '-');
                        }
                        var soalInfo = isOk
                            ? ((r.jumlah_soal > 0 ? r.jumlah_soal + ' PG' : '') + (r.jumlah_soal > 0 && r.jumlah_esai > 0 ? '<br/>' : '') + (r.jumlah_esai > 0 ? '<span style="color:#4f46e5;">' + r.jumlah_esai + ' Esai</span>' : ''))
                            : '-';
                        rowsHtml += '<tr>' +
                            '<td>' + escapeHtml(r.filename) + '</td>' +
                            '<td>' + topikLabel + '</td>' +
                            '<td>' + soalInfo + '</td>' +
                            '<td>' + (isOk ? r.jumlah_jawaban : '-') + '</td>' +
                            '<td>' + (isOk ? r.jumlah_gambar : '-') + '</td>' +
                            '<td>' + badge + (isOk ? '' : ' <small class="text-danger">' + escapeHtml(r.pesan) + '</small>') + '</td>' +
                        '</tr>';
                    });
                    $('#batch-hasil-tbody').html(rowsHtml);

                    $('#box-batch-preview').addClass('hide');
                    $('#box-batch-hasil').removeClass('hide');
                    $('#batch-token').val('');
                    if (obj.status == 1) {
                        notify_success(obj.pesan);
                    } else {
                        notify_error(obj.pesan);
                    }
                },
                error: function(xhr, textstatus, message) {
                    $("#modal-proses").modal('hide');
                    $('#batch-preview-pesan').html(pesan_err('Gagal menyimpan batch (' + escapeHtml(textstatus || message) + ').'));
                }
            });
            return false;
        });
    });
</script>