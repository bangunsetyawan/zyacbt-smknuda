<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Token Ujian
		<small>Generate, salin, dan tampilkan kode token ujian hari ini</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Token</li>
	</ol>
</section>

<style>
    .token-hero {
        background: #0f172a;
        color: #fff;
        border-radius: 8px;
        padding: 22px 20px;
        text-align: center;
        margin-bottom: 16px;
        border: 2px solid #334155;
    }
    .token-hero .token-caption {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.4px;
        color: #94a3b8;
        margin-bottom: 6px;
    }
    .token-hero .token-code {
        font-family: "Courier New", Courier, monospace;
        font-size: 46px;
        font-weight: 800;
        letter-spacing: 8px;
        color: #38bdf8;
        line-height: 1.15;
        margin: 4px 0 12px 0;
        word-break: break-all;
    }
    .token-hero .token-meta {
        font-size: 12.5px;
        color: #cbd5e1;
        margin-bottom: 14px;
    }
    .token-hero .token-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-key"></i> Token Aktif Hari Ini</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-default btn-xs" onclick="manual()">
                            <i class="fa fa-pencil"></i> Buat Token Manual
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="token-hero">
                        <div class="token-caption">Kode Token Ujian (Siap Proyektor / Share Pengawas)</div>
                        <div class="token-code" id="isi-token"><?php echo !empty($latest_token) ? html_escape($latest_token->token_isi) : '------'; ?></div>
                        <div class="token-meta" id="token-meta-info">
                            <?php if(!empty($latest_token)){ ?>
                                Dibuat: <?php echo html_escape($latest_token->token_ts); ?> &bull;
                                <?php echo ((int)$latest_token->token_aktif === 1) ? 'Aktif 1 Hari' : (int)$latest_token->token_aktif.' Menit'; ?> &bull;
                                <?php echo ((int)$latest_token->token_tes_id === 0) ? 'Semua Tes (Global)' : 'Tes Spesifik'; ?>
                            <?php }else{ ?>
                                Belum ada token hari ini. Klik tombol di bawah untuk membuat Token Global.
                            <?php } ?>
                        </div>
                        <div class="token-actions">
                            <button type="button" class="btn btn-info" onclick="copyCurrentToken()">
                                <i class="fa fa-copy"></i> Copy Token
                            </button>
                            <button type="button" class="btn btn-success" onclick="generateTokenGlobal()">
                                <i class="fa fa-bolt"></i> Generate Token Global (1 Hari)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <?php echo form_open($url.'/token','id="form-token"'); ?>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-sliders"></i> Generate Token Kustom</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Masa Aktif Token</label>
                        <select class="form-control" id="token-aktif" name="token-aktif">
                            <option value="1">1 Hari (Direkomendasikan)</option>
                            <option value="5">5 Menit</option>
                            <option value="15">15 Menit</option>
                            <option value="30">30 Menit</option>
                            <option value="60">1 Jam</option>
                            <option value="120">2 Jam</option>
                            <option value="240">4 Jam</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Berlaku Untuk Tes</label>
                        <select class="form-control" id="token-tes" name="token-tes">
                            <?php if(!empty($select_tes)){ echo $select_tes; } ?>
                        </select>
                        <p class="help-block" style="margin-bottom:0;">Pilih <strong>Semua Tes (Global)</strong> agar 1 kode token dapat dipakai semua jadwal ujian hari ini yang mengaktifkan Token.</p>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="import">
                        <i class="fa fa-refresh"></i> Generate Token
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-history"></i> Daftar Token Hari Ini</h3>
                </div>
                <div class="box-body">
                    <table id="table-token" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="all" style="width:45px;">No.</th>
                                <th class="all">Token</th>
                                <th>Waktu Generate</th>
                                <th>Masa Aktif</th>
								<th>Berlaku Untuk</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="modal-token-manual" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo form_open($url.'/token_manual','id="form-token-manual"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Buat Token Manual</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan-manual"></div>
                    <div class="form-group">
                        <label>Kode Token Manual</label>
                        <input type="text" class="form-control" id="manual-token" name="manual-token" placeholder="Contoh: SMKNUDA1" style="text-transform:uppercase;">
						<span class="help-block">Pastikan kode token manual belum digunakan pada hari yang sama.</span>
                    </div>
					<div class="form-group">
                        <label>Masa Aktif</label>
                        <select class="form-control input-sm" id="manual-aktif" name="manual-aktif">
                            <option value="1">1 Hari</option>
                            <option value="5">5 Menit</option>
                            <option value="15">15 Menit</option>
                            <option value="30">30 Menit</option>
                            <option value="60">1 Jam</option>
                            <option value="120">2 Jam</option>
                            <option value="240">4 Jam</option>
						</select>
                    </div>
					<div class="form-group">
                        <label>Berlaku Untuk</label>
                        <select class="form-control input-sm" id="manual-tes" name="manual-tes">
							<?php if(!empty($select_tes)){ echo $select_tes; } ?>
						</select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="tambah-simpan" class="btn btn-primary">Simpan Token</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Tutup</a>
                </div>
            </div>
        </div>
    </form>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    function refresh_table(){
        $('#table-token').dataTable().fnReloadAjax();
    }

    function copyTokenText(txt){
        if(!txt || txt === '------'){
            notify_error('Belum ada kode token untuk disalin.');
            return;
        }
        if(navigator.clipboard && navigator.clipboard.writeText){
            navigator.clipboard.writeText(txt).then(function(){
                notify_success('Token ' + txt + ' berhasil disalin ke clipboard!');
            }, function(){
                fallbackCopy(txt);
            });
        }else{
            fallbackCopy(txt);
        }
    }

    function fallbackCopy(txt){
        var tmp = $('<input>');
        $('body').append(tmp);
        tmp.val(txt).select();
        document.execCommand('copy');
        tmp.remove();
        notify_success('Token ' + txt + ' berhasil disalin!');
    }

    function copyCurrentToken(){
        copyTokenText($.trim($('#isi-token').text()));
    }

    function generateTokenGlobal(){
        $('#token-aktif').val('1');
        $('#token-tes').val('0');
        $('#form-token').submit();
    }
	
	function manual(){
        $('#form-pesan-manual').html('');
        $('#manual-token').val('');
        $("#modal-token-manual").modal("show");
        $('#manual-token').focus();
    }

    $(function(){
        $('#form-token').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url($url.'/token'); ?>",
                type: "POST",
                data: $('#form-token').serialize(),
                cache: false,
                success: function(respon){
                    var obj = $.parseJSON(respon);
                    $("#modal-proses").modal('hide');
                    if(obj.status == 1){
                        $("#isi-token").text(obj.token);
                        var aktifText = ($('#token-aktif').val() == '1') ? 'Aktif 1 Hari' : ($('#token-aktif').val() + ' Menit');
                        var tesText = ($('#token-tes').val() == '0') ? 'Semua Tes (Global)' : $('#token-tes option:selected').text();
                        $('#token-meta-info').text('Baru saja dibuat • ' + aktifText + ' • ' + tesText);
                        notify_success(obj.pesan);
                        refresh_table();
                    }else{
                        notify_error(obj.pesan);
                    }
                }
            });
            return false;
        });

        $('#form-token-manual').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url($url.'/token_manual'); ?>",
                type: "POST",
                data: $('#form-token-manual').serialize(),
                cache: false,
                success: function(respon){
                    var obj = $.parseJSON(respon);
                    $("#modal-proses").modal('hide');
                    if(obj.status == 1){
                        var tok = $.trim($('#manual-token').val()).toUpperCase();
                        $("#isi-token").text(tok);
                        $('#token-meta-info').text('Token manual baru saja disimpan');
                        $("#modal-token-manual").modal('hide');
                        notify_success(obj.pesan);
                        refresh_table();
                    }else{
                        $('#form-pesan-manual').html(pesan_err(obj.pesan));
                    }
                }
            });
            return false;
        });

        $('#table-token').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "bProcessing": false,
            "bServerSide": true,
            "searching": true,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth": "40px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "170px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "165px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "100px"},
                {"bSearchable": false, "bSortable": false}
            ],
            "sAjaxSource": "<?php echo site_url($url.'/get_datatable'); ?>",
            "autoWidth": false,
            "responsive": true
        });
    });
</script>
