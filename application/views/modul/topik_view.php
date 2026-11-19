<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Topik (Mata Pelajaran)
		<small>Daftar mata pelajaran / paket soal pada modul ujian (tambah, import Excel, salin, ubah, dan hapus)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Topik (Mata Pelajaran)</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="box-title">Pilih Modul</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="form-group">
                            <label>Modul (Periode / Paket Ujian)</label>
                            <div id="data-kelas">
                                <select name="modul" id="modul" class="form-control input-sm">
                                    <?php if(!empty($select_modul)){ echo $select_modul; } ?>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm btn-block" onclick="buka_kelola_modul()">
                            <i class="fa fa-folder-open"></i> Kelola / Tambah Modul
                        </button>
                    </div>
                    <div class="box-footer">
                        <p class="text-muted" style="margin-bottom:0;font-size:12px;">
                            <strong>Modul</strong> = folder periode ujian (mis. <em>SAS Ganjil 2026</em>).<br />
                            <strong>Topik</strong> = mata pelajaran / paket soal di dalam modul tersebut.
                        </p>
                    </div>
                </div>
        </div>

        <div class="col-md-9">
                <div class="box">
                    <div class="box-header with-border">
    						<div class="box-title">Daftar Topik</div>
    						<div class="box-tools pull-right">
    							<button type="button" class="btn btn-primary btn-xs" onclick="tambah()"><i class="fa fa-plus"></i> Tambah Topik</button>
    							<button type="button" class="btn btn-success btn-xs" onclick="buka_import_topik()" style="margin-left:3px;"><i class="fa fa-file-excel-o"></i> Import Excel</button>
    							<button type="button" class="btn btn-info btn-xs" onclick="buka_salin_topik()" style="margin-left:3px;"><i class="fa fa-copy"></i> Salin dari Modul</button>
    						</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <?php echo form_open($url.'/hapus_daftar_topik','id="form-hapus"'); ?>
                        <input type="hidden" name="check" id="check" value="0">
                        <table id="table-topik" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class="all">Nama Topik</th>
                                    <th>Deskripsi</th>
                                    <th>Jml. Soal</th>
                                    <th>Status</th>
                                    <th class="all">Action</th>
                                    <th class="all"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
									<td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                            </tbody>
                        </table> 
                        </form>                       
                    </div>
                    <div class="box-footer">
                        <button type="button" id="btn-edit-hapus" class="btn btn-danger" title="Hapus Topik yang dipilih"><i class="fa fa-trash"></i> Hapus</button>
                        <button type="button" id="btn-edit-pilih" class="btn btn-default pull-right"><i class="fa fa-check-square-o"></i> Pilih Semua</button>
                    </div>
                </div>
        </div>
    </div>

    <div class="modal" id="modal-tambah" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <?php echo form_open($url.'/tambah','id="form-tambah"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul">Tambah Topik</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <div id="form-pesan"></div>
                            <div class="form-group">
                                <label>Nama Topik</label>
                                <input type="hidden" name="tambah-modul-id" id="tambah-modul-id">
                                <input type="text" class="form-control" id="tambah-topik" name="tambah-topik" placeholder="Nama Topik">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" class="form-control" id="tambah-deskripsi" name="tambah-deskripsi" placeholder="Deskripsi Topik" >
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" id="tambah-status" name="tambah-status" value="AKTIF" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="tambah-simpan" class="btn btn-primary">Tambah</button>
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>

    </form>
    </div>

    <div class="modal" id="modal-edit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <?php echo form_open($url.'/edit','id="form-edit"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul">Edit Topik</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <div id="form-pesan-edit"></div>
                            <div class="form-group">
                                <label>Nama Topik</label>
                                <input type="hidden" name="edit-id" id="edit-id">
								<input type="hidden" name="edit-modul-id" id="edit-modul-id">
                                <input type="hidden" name="edit-pilihan" id="edit-pilihan">
                                <input type="hidden" name="edit-topik-asli" id="edit-topik-asli">
                                <input type="text" class="form-control" id="edit-topik" name="edit-topik" placeholder="Nama Topik">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" class="form-control" id="edit-deskripsi" name="edit-deskripsi" placeholder="Deskripsi Topik" >
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" id="edit-status" name="edit-status" value="AKTIF" readonly>
                            </div>
                            <p>NB : Topik yang dihapus, maka semua bank soal akan ikut terhapus !</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="edit-hapus" class="btn btn-default pull-left">Hapus</button>
                    <button type="button" id="edit-simpan" class="btn btn-primary">Simpan</button>
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>

    </form>
    </div>

    <div class="modal" id="modal-hapus" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul">Hapus Topik</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <strong>Peringatan</strong>
                            Data Topik yang sudah dipilih akan dihapus beserta isi soal didalamnya.
                            <br /><br />
                            Apakah anda yakin untuk menghapus data Topik ?
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-hapus" class="btn btn-default pull-left">Hapus</button>
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-kelola-modul" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-folder-open"></i> Kelola Modul Ujian</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan-modul"></div>
                    <?php echo form_open($url.'/tambah_modul', 'id="form-tambah-modul" class="form-inline" style="margin-bottom:15px;"'); ?>
                        <div class="input-group" style="width:100%;">
                            <input type="text" class="form-control" id="input-modul-nama" name="modul-nama" placeholder="Nama Modul baru, misal: SAS Ganjil 2026/2027" autocomplete="off">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Modul</button>
                            </span>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-condensed" id="table-daftar-modul">
                            <thead>
                                <tr>
                                    <th style="width:40px;" class="text-center">No</th>
                                    <th>Nama Modul</th>
                                    <th style="width:90px;" class="text-center">Topik</th>
                                    <th style="width:90px;" class="text-center">Status</th>
                                    <th style="width:230px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-daftar-modul">
                                <tr><td colspan="5" class="text-center text-muted">Memuat data modul...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="help-block" style="margin-bottom:0;">
                        <i class="fa fa-shield text-success"></i> <strong>Mode 1 Modul Aktif:</strong> Hanya 1 Modul yang aktif pada satu waktu. Menambah modul baru atau klik <em>Jadikan Aktif</em> akan otomatis memindahkan modul sebelumnya ke <strong>Arsip</strong> (disembunyikan dari dropdown ujian harian tanpa menghapus soal maupun nilai lama). Modul yang masih berisi Topik dikunci dari penghapusan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-import-topik" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <form id="form-import-topik" enctype="multipart/form-data">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Import Daftar Topik dari Excel</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan-import"></div>
                    <div class="callout callout-info" style="margin-bottom:12px;padding:10px 15px;">
                        <p style="margin-bottom:6px;"><strong>Modul Tujuan:</strong> <span id="import-modul-label" class="label label-primary"></span></p>
                        <p style="margin-bottom:6px;font-size:12px;">Format kolom: <code>No</code> | <code>Mata Pelajaran</code> | <code>Kelas / Tingkat</code> | <code>Deskripsi (Opsional)</code>.<br />Otomatis digabung menjadi: <strong>Mata Pelajaran - Kelas / Tingkat</strong>.</p>
                        <a href="<?php echo site_url().'/'.$url; ?>/download_template" class="btn btn-success btn-xs"><i class="fa fa-download"></i> Download Template Excel (.xlsx)</a>
                    </div>
                    <div class="form-group">
                        <label>Pilih File Excel (.xlsx / .xls)</label>
                        <input type="hidden" name="import-modul-id" id="import-modul-id">
                        <input type="file" name="file-excel" id="file-excel-topik" class="form-control" accept=".xls,.xlsx">
                        <p class="help-block" style="margin-bottom:0;">
                            <i class="fa fa-shield text-success"></i> <strong>Anti-Duplikat Aktif:</strong> Topik yang namanya sudah ada di modul ini otomatis dilewati.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-submit-import" class="btn btn-success"><i class="fa fa-upload"></i> Mulai Import</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <div class="modal" id="modal-salin-topik" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <form id="form-salin-topik">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-copy"></i> Salin Daftar Topik dari Modul Lain</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan-salin"></div>
                    <div class="form-group">
                        <label>Modul Tujuan (Aktif / Terpilih)</label>
                        <input type="hidden" name="tujuan-modul-id" id="salin-tujuan-id">
                        <input type="text" id="salin-tujuan-nama" class="form-control input-sm" readonly>
                    </div>
                    <div class="form-group">
                        <label>Salin Nama Topik Dari Modul</label>
                        <select name="sumber-modul-id" id="salin-sumber-id" class="form-control input-sm"></select>
                        <p class="help-block" style="margin-bottom:0;">
                            Hanya menyalin <strong>nama &amp; deskripsi topik</strong> saja (jumlah soal mulai dari 0). Topik yang sudah ada di modul tujuan otomatis dilewati.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-submit-salin" class="btn btn-info"><i class="fa fa-copy"></i> Salin Daftar Topik</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</section><!-- /.content -->



<script lang="javascript">
    function refresh_table(){
        $('#table-topik').dataTable().fnReloadAjax();
    }

    function escape_html(str){
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    function buka_kelola_modul(){
        $('#form-pesan-modul').html('');
        $('#input-modul-nama').val('');
        $('#modal-kelola-modul').modal('show');
        load_daftar_modul($('#modul').val());
    }

    function buka_import_topik(){
        var modulId = $('#modul').val();
        var modulNama = $('#modul option:selected').text();
        $('#import-modul-id').val(modulId);
        $('#import-modul-label').text(modulNama);
        $('#file-excel-topik').val('');
        $('#form-pesan-import').html('');
        $('#modal-import-topik').modal('show');
    }

    function buka_salin_topik(){
        var tujuanId = $('#modul').val();
        var tujuanNama = $('#modul option:selected').text();
        $('#salin-tujuan-id').val(tujuanId);
        $('#salin-tujuan-nama').val(tujuanNama);
        $('#form-pesan-salin').html('');
        $('#salin-sumber-id').html('<option value="">Memuat daftar modul...</option>');
        $('#modal-salin-topik').modal('show');

        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_daftar_modul', function(res){
            var opts = '';
            if(res.status == 1 && res.data){
                $.each(res.data, function(idx, m){
                    if(String(m.modul_id) !== String(tujuanId)){
                        var statusTxt = (m.modul_aktif == 1) ? 'Aktif' : 'Arsip';
                        opts += '<option value="'+m.modul_id+'">'+escape_html(m.modul_nama)+' ('+m.jml_topik+' topik - '+statusTxt+')</option>';
                    }
                });
            }
            if(opts === ''){
                opts = '<option value="">Belum ada modul lain untuk disalin</option>';
            }
            $('#salin-sumber-id').html(opts);
        });
    }

    function load_daftar_modul(selectId){
        var url = '<?php echo site_url().'/'.$url; ?>/get_daftar_modul';
        if(selectId){
            url += '?selected_id=' + encodeURIComponent(selectId);
        }
        $.getJSON(url, function(res){
            if(res.status == 1){
                $('#modul').html(res.select_modul);
                var html = '';
                if(res.data && res.data.length > 0){
                    $.each(res.data, function(idx, m){
                        var badge = (m.modul_aktif == 1)
                            ? '<span class="label label-success"><i class="fa fa-check"></i> Aktif</span>'
                            : '<span class="label label-default">Arsip</span>';
                        var btnToggle = (m.modul_aktif == 1)
                            ? '<button type="button" class="btn btn-default btn-xs" disabled title="Modul ini sedang aktif digunakan"><i class="fa fa-check-circle text-success"></i> Aktif</button>'
                            : '<button type="button" class="btn btn-success btn-xs" onclick="toggle_status_modul('+m.modul_id+')" title="Jadikan modul aktif (modul lain otomatis diarsipkan)"><i class="fa fa-check-circle"></i> Jadikan Aktif</button>';
                        var btnHapus = (m.jml_topik == 0)
                            ? ' <button type="button" class="btn btn-danger btn-xs" onclick="hapus_modul_kosong('+m.modul_id+', \''+escape_html(m.modul_nama).replace(/'/g, "\\'")+'\')" title="Hapus modul kosong"><i class="fa fa-trash"></i></button>'
                            : ' <button type="button" class="btn btn-default btn-xs" disabled title="Kosongkan topik dulu atau gunakan Arsipkan"><i class="fa fa-lock"></i></button>';

                        html += '<tr>'
                            + '<td class="text-center">'+(idx+1)+'</td>'
                            + '<td><strong>'+escape_html(m.modul_nama)+'</strong></td>'
                            + '<td class="text-center"><span class="badge bg-blue">'+m.jml_topik+'</span></td>'
                            + '<td class="text-center">'+badge+'</td>'
                            + '<td class="text-center">'
                            + '<button type="button" class="btn btn-info btn-xs" onclick="ubah_nama_modul('+m.modul_id+', \''+escape_html(m.modul_nama).replace(/'/g, "\\'")+'\')" title="Ubah nama modul"><i class="fa fa-pencil"></i> Ubah</button> '
                            + btnToggle + btnHapus
                            + '</td>'
                            + '</tr>';
                    });
                }else{
                    html = '<tr><td colspan="5" class="text-center text-muted">Belum ada modul.</td></tr>';
                }
                $('#tbody-daftar-modul').html(html);
                refresh_table();
            }
        });
    }

    function ubah_nama_modul(id, namaLama){
        var namaBaru = prompt('Ubah nama Modul:', namaLama);
        if(namaBaru === null){ return; }
        namaBaru = $.trim(namaBaru);
        if(namaBaru === ''){
            $('#form-pesan-modul').html(pesan_err('Nama Modul tidak boleh kosong.'));
            return;
        }
        $.ajax({
            url: '<?php echo site_url().'/'.$url; ?>/edit_modul',
            type: 'POST',
            data: {'modul-id': id, 'modul-nama': namaBaru},
            cache: false,
            success: function(respon){
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#form-pesan-modul').html('');
                    notify_success(obj.pesan);
                    load_daftar_modul(obj.modul_id || $('#modul').val());
                }else{
                    $('#form-pesan-modul').html(pesan_err(obj.pesan));
                }
            }
        });
    }

    function toggle_status_modul(id){
        $.ajax({
            url: '<?php echo site_url().'/'.$url; ?>/toggle_modul',
            type: 'POST',
            data: {'modul-id': id},
            cache: false,
            success: function(respon){
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#form-pesan-modul').html('');
                    notify_success(obj.pesan);
                    load_daftar_modul(obj.modul_id || $('#modul').val());
                }else{
                    $('#form-pesan-modul').html(pesan_err(obj.pesan));
                }
            }
        });
    }

    function hapus_modul_kosong(id, nama){
        if(!confirm('Hapus modul kosong "' + nama + '"?')){ return; }
        $.ajax({
            url: '<?php echo site_url().'/'.$url; ?>/hapus_modul',
            type: 'POST',
            data: {'modul-id': id},
            cache: false,
            success: function(respon){
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#form-pesan-modul').html('');
                    notify_success(obj.pesan);
                    load_daftar_modul();
                }else{
                    $('#form-pesan-modul').html(pesan_err(obj.pesan));
                }
            }
        });
    }

    function tambah(){
        $('#form-pesan').html('');
        $('#tambah-topik').val('');
        $('#tambah-modul-id').val('');
        $('#tambah-deskripsi').val('');

        $("#modal-tambah").modal("show");
        $('#tambah-topik').focus();
    }

    function edit(id){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_by_id/'+id+'', function(data){
            if(data.data==1){
                $('#edit-id').val(data.id);
                $('#edit-topik').val(data.topik);
                $('#edit-topik-asli').val(data.topik);
                $('#edit-deskripsi').val(data.deskripsi);
				$('#edit-modul-id').val('');
                
                $("#modal-edit").modal("show");
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
        $('#btn-edit-pilih').click(function(event) {
            if($('#check').val()==0) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
                $('#check').val('1');
            }else{
                $(':checkbox').each(function() {
                    this.checked = false;
                });
                $('#check').val('0');
            }
        });

        $("#modul").change(function(){
            refresh_table();
        });

        $('#form-tambah-modul').submit(function(){
            $.ajax({
                url: '<?php echo site_url().'/'.$url; ?>/tambah_modul',
                type: 'POST',
                data: $('#form-tambah-modul').serialize(),
                cache: false,
                success: function(respon){
                    var obj = $.parseJSON(respon);
                    if(obj.status == 1){
                        $('#input-modul-nama').val('');
                        $('#form-pesan-modul').html('');
                        notify_success(obj.pesan);
                        load_daftar_modul(obj.modul_id);
                    }else{
                        $('#form-pesan-modul').html(pesan_err(obj.pesan));
                    }
                }
            });
            return false;
        });

        $('#form-import-topik').submit(function(){
            var fd = new FormData(this);
            $("#modal-proses").modal('show');
            $.ajax({
                url: '<?php echo site_url().'/'.$url; ?>/import_excel',
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                cache: false,
                success: function(respon){
                    $("#modal-proses").modal('hide');
                    var obj = $.parseJSON(respon);
                    if(obj.status == 1){
                        $('#form-pesan-import').html('<div class="alert alert-success">'+obj.pesan+'</div>');
                        $('#file-excel-topik').val('');
                        refresh_table();
                        notify_success('Import topik selesai.');
                    }else{
                        $('#form-pesan-import').html(pesan_err(obj.pesan));
                    }
                },
                error: function(){
                    $("#modal-proses").modal('hide');
                    $('#form-pesan-import').html(pesan_err('Gagal menghubungi server saat import Excel.'));
                }
            });
            return false;
        });

        $('#form-salin-topik').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                url: '<?php echo site_url().'/'.$url; ?>/salin_topik',
                type: 'POST',
                data: $('#form-salin-topik').serialize(),
                cache: false,
                success: function(respon){
                    $("#modal-proses").modal('hide');
                    var obj = $.parseJSON(respon);
                    if(obj.status == 1){
                        $('#form-pesan-salin').html('<div class="alert alert-success">'+obj.pesan+'</div>');
                        refresh_table();
                        notify_success('Daftar topik berhasil disalin.');
                    }else{
                        $('#form-pesan-salin').html(pesan_err(obj.pesan));
                    }
                }
            });
            return false;
        });

        $('#edit-simpan').click(function(){
            $('#edit-pilihan').val('simpan');
            $('#form-edit').submit();
        });
        $('#edit-hapus').click(function(){
            $('#edit-pilihan').val('hapus');
            $('#form-edit').submit();
        });
        $('#btn-edit-hapus').click(function(){
            $("#modal-hapus").modal('show');
        });
        $('#btn-hapus').click(function(){
            $("#form-hapus").submit();
        });

        $('#form-hapus').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/hapus_daftar_topik",
                    type:"POST",
                    data:$('#form-hapus').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $("#modal-proses").modal('hide');
                            $("#modal-hapus").modal('hide');
                            notify_success(obj.pesan);
                            $('#check').val('0');
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(obj.pesan);
                        }
                    }
            });
            return false;
        });

        $('#form-edit').submit(function(){
			$('#edit-modul-id').val($('#modul').val());
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/edit",
                    type:"POST",
                    data:$('#form-edit').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $("#modal-proses").modal('hide');
                            $("#modal-edit").modal('hide');
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan-edit').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

        $('#form-tambah').submit(function(){
            $('#tambah-modul-id').val($('#modul').val());
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/tambah",
                    type:"POST",
                    data:$('#form-tambah').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $("#modal-proses").modal('hide');
                            $("#modal-tambah").modal('hide');
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

        $('#table-topik').DataTable({
                  "paging": true,
                  "iDisplayLength":10,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": true,
                  "aoColumns": [
    					{"bSearchable": false, "bSortable": false, "sWidth":"20px"},
    					{"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false},
    					{"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false, "sWidth":"30px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"30px"}],
                  "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
                  "autoWidth": false,
                  "responsive": true,
                  "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "modul", "value": $('#modul').val()} );
                  }
         });          
    });
</script>