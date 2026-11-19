<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Topik
		<small>Daftar topik, penambahan topik, pengubahan topik, dan penghapusan topik berdasarkan Modul</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Topik</li>
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
    							<div class="dropdown pull-right">
    								<a style="cursor: pointer;" onclick="tambah()">Tambah Topik</a>
    							</div>
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
                        <button type="button" id="btn-edit-hapus" class="btn btn-primary" title="Hapus Siswa yang dipilih">Hapus</button>
                        <button type="button" id="btn-edit-pilih" class="btn btn-default pull-right">Pilih Semua</button>
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