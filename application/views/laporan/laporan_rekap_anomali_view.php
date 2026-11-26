<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<i class="fa fa-shield"></i> Rekap Anomali Jaringan
		<small>Laporan historis peserta yang mengakses jalur publik / paket data atau berpindah jaringan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Laporan</li>
		<li class="active">Rekap Anomali Jaringan</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Filter Laporan</h3>
                </div>
                <div class="box-body form-horizontal">
                    <div class="row">
                        <div class="col-md-5 col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Rentang Tanggal</label>
                                <div class="col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" id="filter-rentang" class="form-control" value="<?php if(!empty($rentang_waktu)){ echo html_escape($rentang_waktu); } ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Ruang</label>
                                <div class="col-sm-9">
                                    <select id="filter-ruang" class="form-control input-sm">
                                        <option value="semua">-- Semua Ruang --</option>
                                        <?php if(!empty($ruang_list)){ foreach($ruang_list as $r){ ?>
                                            <option value="<?php echo html_escape($r); ?>"><?php echo html_escape($r); ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div style="display:flex; gap:6px;">
                                <button type="button" id="btn-tampilkan" class="btn btn-primary btn-sm" style="flex:1;">
                                    <i class="fa fa-search"></i> Tampilkan
                                </button>
                                <button type="button" id="btn-export-excel" class="btn btn-success btn-sm" style="flex:1;">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3 id="stat-total">0</h3>
                    <p>Total Catatan Siswa-Hari</p>
                </div>
                <div class="icon"><i class="fa fa-list"></i></div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3 id="stat-merah">0</h3>
                    <p>Status Akhir MERAH (Paket Data)</p>
                </div>
                <div class="icon"><i class="fa fa-warning"></i></div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3 id="stat-kuning">0</h3>
                    <p>Status Akhir KUNING (Pindah Jalur)</p>
                </div>
                <div class="icon"><i class="fa fa-exchange"></i></div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-table"></i> Daftar Anomali per Siswa per Hari</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th style="width:95px;">Tanggal</th>
                                <th>Nama &amp; NISN</th>
                                <th>Kelas &amp; Ruang</th>
                                <th>Mapel / Tes</th>
                                <th style="width:110px;">Status Akhir</th>
                                <th style="width:115px;">Pindah Jalur</th>
                                <th style="width:140px;">Jam Kejadian</th>
                                <th style="width:95px;">Rincian</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-rekap">
                            <tr>
                                <td colspan="9" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat data rekap...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Rincian Kronologi & Soal Publik -->
<div class="modal fade" id="modal-detail-anomali" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#1f2937; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-history"></i> Kronologi Lengkap Anomali Jaringan</h4>
            </div>
            <div class="modal-body" id="isi-modal-detail"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var rekapItems = [];

    function esc_html(s){
        if(s === null || s === undefined) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function muat_rekap(){
        $('#tbody-rekap').html('<tr><td colspan="9" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat rekap...</td></tr>');
        $.getJSON("<?php echo site_url($url.'/get_data'); ?>", {
            rentang: $('#filter-rentang').val(),
            ruang:   $('#filter-ruang').val(),
            _t:      new Date().getTime()
        }, function(res){
            if(!res || res.status != 1) return;
            $('#stat-total').text(res.summary.total || 0);
            $('#stat-merah').text(res.summary.merah || 0);
            $('#stat-kuning').text(res.summary.kuning || 0);

            rekapItems = res.items || [];
            if(rekapItems.length === 0){
                $('#tbody-rekap').html('<tr><td colspan="9" class="text-center text-muted" style="padding:20px;">Tidak ada catatan anomali jaringan pada rentang tanggal &amp; ruang ini.</td></tr>');
                return;
            }

            var html = '';
            for(var i = 0; i < rekapItems.length; i++){
                var it = rekapItems[i];
                var badgeStatus = (it.status_akhir === 'MERAH')
                    ? '<span class="label label-danger"><i class="fa fa-warning"></i> MERAH</span>'
                    : '<span class="label label-warning"><i class="fa fa-exchange"></i> KUNING</span>';

                var soalInfo = (it.soal_pub && it.soal_pub.length > 0)
                    ? '<br><small class="text-danger"><b>' + it.soal_pub.length + ' soal</b> via publik</small>'
                    : '';

                html += '<tr>'
                    + '<td class="text-center">' + (i + 1) + '</td>'
                    + '<td><strong>' + esc_html(it.tanggal) + '</strong></td>'
                    + '<td><strong>' + esc_html(it.nama) + '</strong><br><small class="text-muted">NISN: ' + esc_html(it.username) + '</small></td>'
                    + '<td>' + esc_html(it.kelas) + '<br><span class="label label-default">' + esc_html(it.ruang) + '</span></td>'
                    + '<td>' + esc_html(it.tes_nama) + soalInfo + '</td>'
                    + '<td>' + badgeStatus + '</td>'
                    + '<td><strong>' + (it.jml_pindah || 0) + 'x</strong> catatan log</td>'
                    + '<td><code>' + esc_html(it.jam_pertama) + '</code> &ndash; <code>' + esc_html(it.jam_terakhir) + '</code></td>'
                    + '<td class="text-center">'
                        + '<button type="button" class="btn btn-info btn-xs" onclick="lihat_detail(' + i + ')"><i class="fa fa-search-plus"></i> Rincian</button>'
                    + '</td>'
                    + '</tr>';
            }
            $('#tbody-rekap').html(html);
        });
    }

    function lihat_detail(idx){
        var it = rekapItems[idx];
        if(!it) return;

        var h = '<div class="callout ' + (it.status_akhir === 'MERAH' ? 'callout-danger' : 'callout-warning') + '">'
              + '<h4 style="margin-top:0;">' + esc_html(it.nama) + ' (' + esc_html(it.username) + ') &bull; ' + esc_html(it.tanggal) + '</h4>'
              + '<p style="margin-bottom:0;"><b>Kelas:</b> ' + esc_html(it.kelas) + ' &bull; <b>Ruang:</b> ' + esc_html(it.ruang)
              + ' &bull; <b>Mapel/Tes:</b> ' + esc_html(it.tes_nama) + ' &bull; <b>Status Akhir:</b> ' + esc_html(it.status_akhir) + '</p>'
              + '</div>';

        h += '<h5><i class="fa fa-list-ol"></i> <b>Kronologi Log Jaringan (' + (it.kronologi ? it.kronologi.length : 0) + ' catatan)</b></h5>';
        if(it.kronologi && it.kronologi.length > 0){
            h += '<table class="table table-condensed table-bordered"><thead><tr><th style="width:95px;">Jam</th><th>Kejadian</th></tr></thead><tbody>';
            for(var k = 0; k < it.kronologi.length; k++){
                var kr = it.kronologi[k];
                h += '<tr><td><code>' + esc_html(kr.jam) + '</code></td><td>' + esc_html(kr.pesan) + '</td></tr>';
            }
            h += '</tbody></table>';
        }else{
            h += '<p class="text-muted">Tidak ada baris log di <code>user_log</code> (terdeteksi dari IP pengiriman jawaban soal).</p>';
        }

        h += '<h5 style="margin-top:16px;"><i class="fa fa-file-text-o"></i> <b>Soal yang Dikirim Lewat Jalur Publik / Paket Data (' + (it.soal_pub ? it.soal_pub.length : 0) + ' soal)</b></h5>';
        if(it.soal_pub && it.soal_pub.length > 0){
            h += '<table class="table table-condensed table-bordered"><thead><tr><th style="width:90px;">No. Soal</th><th style="width:100px;">Jam</th><th>IP Publik Pengirim</th></tr></thead><tbody>';
            for(var s = 0; s < it.soal_pub.length; s++){
                var sp = it.soal_pub[s];
                h += '<tr><td><b>Soal #' + esc_html(sp.nomor) + '</b></td><td><code>' + esc_html(sp.jam) + '</code></td><td><code>' + esc_html(sp.ip) + '</code></td></tr>';
            }
            h += '</tbody></table>';
        }else{
            h += '<p class="text-muted">Tidak ada jawaban soal yang dikirim via jalur publik pada tanggal ini.</p>';
        }

        $('#isi-modal-detail').html(h);
        $('#modal-detail-anomali').modal('show');
    }

    $(function(){
        $('#filter-rentang').daterangepicker({
            format: 'YYYY-MM-DD',
            separator: ' - '
        });

        $('#btn-tampilkan').click(function(){
            muat_rekap();
        });

        $('#btn-export-excel').click(function(){
            var url = "<?php echo site_url($url.'/export'); ?>?rentang=" + encodeURIComponent($('#filter-rentang').val())
                    + "&ruang=" + encodeURIComponent($('#filter-ruang').val());
            window.open(url, '_self');
        });

        muat_rekap();
    });
</script>
