<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<i class="fa fa-shield"></i> Pantau Ruang Pengawas
		<small>Pemantauan jaringan &amp; progres peserta ujian secara real-time (Read-Only)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pantau Ruang Pengawas</li>
	</ol>
</section>

<style type="text/css">
    .pengawas-summary-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }
    .pengawas-stat-card {
        flex: 1 1 calc(25% - 8px);
        min-width: 130px;
        background: #fff;
        border-radius: 6px;
        padding: 10px 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border-left: 5px solid #ccc;
        cursor: pointer;
        transition: transform 0.1s ease, box-shadow 0.1s ease;
    }
    .pengawas-stat-card:hover, .pengawas-stat-card.active-filter {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    .pengawas-stat-card.stat-merah { border-left-color: #dc2626; background: #fef2f2; }
    .pengawas-stat-card.stat-kuning { border-left-color: #d97706; background: #fffbeb; }
    .pengawas-stat-card.stat-hijau { border-left-color: #16a34a; background: #f0fdf4; }
    .pengawas-stat-card.stat-belum { border-left-color: #6b7280; background: #f9fafb; }
    .pengawas-stat-num {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.1;
    }
    .pengawas-stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-top: 2px;
    }

    .siswa-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border-left: 6px solid #9ca3af;
        transition: background-color 0.2s ease;
    }
    .siswa-card.warna-merah {
        border-left-color: #dc2626;
        background: #fef2f2;
        border-top: 1px solid #fecaca;
        border-right: 1px solid #fecaca;
        border-bottom: 1px solid #fecaca;
    }
    .siswa-card.warna-kuning {
        border-left-color: #d97706;
        background: #fffbeb;
        border-top: 1px solid #fde68a;
        border-right: 1px solid #fde68a;
        border-bottom: 1px solid #fde68a;
    }
    .siswa-card.warna-hijau {
        border-left-color: #16a34a;
        background: #ffffff;
        border-top: 1px solid #bbf7d0;
        border-right: 1px solid #bbf7d0;
        border-bottom: 1px solid #bbf7d0;
    }
    .siswa-card.warna-belum {
        border-left-color: #9ca3af;
        background: #f9fafb;
    }
    .siswa-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
        flex-wrap: wrap;
    }
    .siswa-nama {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .siswa-identitas {
        font-size: 12.5px;
        color: #4b5563;
        margin-top: 2px;
    }
    .badge-net {
        display: inline-block;
        padding: 4px 9px;
        font-size: 11.5px;
        font-weight: 700;
        border-radius: 4px;
        color: #fff;
        white-space: nowrap;
    }
    .badge-net.net-merah { background-color: #dc2626; }
    .badge-net.net-kuning { background-color: #d97706; }
    .badge-net.net-hijau { background-color: #16a34a; }
    .badge-net.net-belum { background-color: #6b7280; }

    .siswa-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 16px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed rgba(0,0,0,0.1);
        font-size: 12.5px;
        color: #1f2937;
    }
    .siswa-log-alert {
        margin-top: 8px;
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(220, 38, 38, 0.1);
        color: #991b1b;
    }
    .siswa-card.warna-kuning .siswa-log-alert {
        background: rgba(217, 119, 6, 0.14);
        color: #92400e;
    }
    @media (max-width: 576px) {
        .pengawas-stat-card {
            flex: 1 1 calc(50% - 8px);
        }
        .siswa-nama {
            font-size: 14.5px;
        }
    }
</style>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success" style="margin-bottom: 12px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Filter Ruang &amp; Kelas</h3>
                    <div class="box-tools pull-right">
                        <span id="indikator-live" class="label label-success" style="font-size:11.5px; padding:4px 8px;">
                            <i class="fa fa-circle"></i> Live 10d (<span id="jam-update">-</span>)
                        </span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12" style="margin-bottom:8px;">
                            <label style="font-size:12px; margin-bottom:3px;"><i class="fa fa-map-marker"></i> Pilih Ruang Ujian</label>
                            <select id="filter-ruang" class="form-control input-sm">
                                <?php if(!empty($select_ruang)){ echo $select_ruang; } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-xs-12" style="margin-bottom:8px;">
                            <label style="font-size:12px; margin-bottom:3px;"><i class="fa fa-users"></i> Pilih Kelas / Group</label>
                            <select id="filter-group" class="form-control input-sm">
                                <?php if(!empty($select_group)){ echo $select_group; } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-xs-12" style="margin-bottom:8px;">
                            <label style="font-size:12px; margin-bottom:3px;"><i class="fa fa-book"></i> Filter Mata Pelajaran / Tes</label>
                            <select id="filter-tes" class="form-control input-sm">
                                <?php if(!empty($select_tes)){ echo $select_tes; } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-xs-12" style="margin-bottom:8px;">
                            <label style="font-size:12px; margin-bottom:3px;"><i class="fa fa-search"></i> Cari Nama / NISN / Status</label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="filter-q" class="form-control" placeholder="Ketik nama / NISN...">
                                <span class="input-group-btn">
                                    <button type="button" id="btn-refresh-manual" class="btn btn-success btn-flat" title="Segarkan Sekarang">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="filter-warna" value="semua">
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan 4 Kartu Warna (Klik untuk filter cepat) -->
    <div class="pengawas-summary-grid">
        <div class="pengawas-stat-card stat-merah" onclick="set_filter_warna('merah')" id="card-stat-merah" title="Klik untuk menampilkan siswa Paket Data / Publik saja">
            <div class="pengawas-stat-num text-danger" id="jml-merah">0</div>
            <div class="pengawas-stat-label"><i class="fa fa-warning text-danger"></i> MERAH (Paket Data / Publik)</div>
        </div>
        <div class="pengawas-stat-card stat-kuning" onclick="set_filter_warna('kuning')" id="card-stat-kuning" title="Klik untuk menampilkan siswa yang pernah pindah jaringan">
            <div class="pengawas-stat-num text-yellow" id="jml-kuning">0</div>
            <div class="pengawas-stat-label"><i class="fa fa-exchange text-yellow"></i> KUNING (Pernah Pindah IP)</div>
        </div>
        <div class="pengawas-stat-card stat-hijau" onclick="set_filter_warna('hijau')" id="card-stat-hijau" title="Klik untuk menampilkan siswa LAN Lokal yang aman">
            <div class="pengawas-stat-num text-success" id="jml-hijau">0</div>
            <div class="pengawas-stat-label"><i class="fa fa-check-circle text-success"></i> HIJAU (LAN Sekolah Aman)</div>
        </div>
        <div class="pengawas-stat-card stat-belum" onclick="set_filter_warna('semua')" id="card-stat-semua" title="Klik untuk menampilkan semua peserta">
            <div class="pengawas-stat-num" id="jml-total">0</div>
            <div class="pengawas-stat-label"><i class="fa fa-list"></i> SEMUA (<span id="jml-belum">0</span> Belum Mulai)</div>
        </div>
    </div>

    <!-- Daftar Kartu Siswa (Mobile-First, Merah & Kuning otomatis paling atas) -->
    <div class="row">
        <div class="col-xs-12">
            <div id="wadah-daftar-siswa">
                <div class="callout callout-info">
                    <i class="fa fa-spinner fa-spin"></i> Memuat data pemantauan ruang...
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var timerPantau = null;
    var sedangMemuat = false;

    function esc_html(str){
        if(str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function set_filter_warna(warna){
        var cur = $('#filter-warna').val();
        if(cur === warna && warna !== 'semua'){
            $('#filter-warna').val('semua');
        }else{
            $('#filter-warna').val(warna);
        }
        $('.pengawas-stat-card').removeClass('active-filter');
        var aktif = $('#filter-warna').val();
        $('#card-stat-' + aktif).addClass('active-filter');
        muat_data_pantau();
    }

    function muat_data_pantau(){
        if(sedangMemuat) return;
        sedangMemuat = true;

        $.ajax({
            url: "<?php echo site_url().'/'.$url; ?>/get_pantau_data",
            type: "GET",
            dataType: "json",
            cache: false,
            data: {
                ruang: $('#filter-ruang').val(),
                group: $('#filter-group').val(),
                tes:   $('#filter-tes').val(),
                warna: $('#filter-warna').val(),
                q:     $('#filter-q').val()
            },
            success: function(res){
                sedangMemuat = false;
                if(!res || res.status != 1) return;

                $('#jam-update').text(res.waktu_server || '-');
                $('#jml-merah').text(res.summary.merah || 0);
                $('#jml-kuning').text(res.summary.kuning || 0);
                $('#jml-hijau').text(res.summary.hijau || 0);
                $('#jml-belum').text(res.summary.belum || 0);
                $('#jml-total').text(res.summary.total || 0);

                var items = res.items || [];
                if(items.length === 0){
                    $('#wadah-daftar-siswa').html(
                        '<div class="box box-solid"><div class="box-body text-center text-muted" style="padding:24px;">' +
                        '<i class="fa fa-info-circle fa-2x"></i><p style="margin-top:8px; margin-bottom:0;">Tidak ada peserta yang cocok dengan filter ruang/kelas ini.</p>' +
                        '</div></div>'
                    );
                    return;
                }

                var html = '';
                for(var i = 0; i < items.length; i++){
                    var it = items[i];
                    var badgeClass = 'net-belum';
                    var badgeIcon = 'fa-clock-o';
                    if(it.warna === 'merah'){
                        badgeClass = 'net-merah';
                        badgeIcon = 'fa-exclamation-triangle';
                    }else if(it.warna === 'kuning'){
                        badgeClass = 'net-kuning';
                        badgeIcon = 'fa-exchange';
                    }else if(it.warna === 'hijau'){
                        badgeClass = 'net-hijau';
                        badgeIcon = 'fa-check-circle';
                    }

                    var statusBadge = '<span class="label label-default">' + esc_html(it.status_ujian) + '</span>';
                    if(it.status_kode === 'berjalan'){
                        statusBadge = '<span class="label label-warning"><i class="fa fa-clock-o"></i> ' + esc_html(it.status_ujian) + '</span>';
                    }else if(it.status_kode === 'selesai'){
                        statusBadge = '<span class="label label-success"><i class="fa fa-check"></i> ' + esc_html(it.status_ujian) + '</span>';
                    }

                    html += '<div class="siswa-card warna-' + esc_html(it.warna) + '">'
                        + '<div class="siswa-card-header">'
                            + '<div>'
                                + '<div class="siswa-nama">' + (i + 1) + '. ' + esc_html(it.nama) + '</div>'
                                + '<div class="siswa-identitas">'
                                    + '<b>NISN/User:</b> ' + esc_html(it.username)
                                    + ' &bull; <b>Kelas:</b> ' + esc_html(it.kelas)
                                    + ' &bull; <b>Ruang:</b> ' + esc_html(it.ruang)
                                + '</div>'
                            + '</div>'
                            + '<div>'
                                + '<span class="badge-net ' + badgeClass + '"><i class="fa ' + badgeIcon + '"></i> ' + esc_html(it.badge_label) + '</span>'
                            + '</div>'
                        + '</div>'
                        + '<div class="siswa-meta-row">'
                            + '<div><i class="fa fa-tasks text-muted"></i> <b>Status:</b> ' + statusBadge + ' (' + esc_html(it.progres) + ')</div>'
                            + '<div><i class="fa fa-book text-muted"></i> <b>Tes:</b> ' + esc_html(it.tes_nama) + '</div>'
                            + '<div><i class="fa fa-wifi text-muted"></i> <b>IP Asal:</b> <code>' + esc_html(it.ip) + '</code> (' + esc_html(it.jalur) + ')</div>'
                            + '<div><i class="fa fa-history text-muted"></i> <b>Mulai:</b> ' + esc_html(it.waktu_mulai) + ' &bull; <b>Aktivitas Terakhir:</b> ' + esc_html(it.waktu_aktivitas) + '</div>'
                        + '</div>';

                    if(it.catatan_log && it.catatan_log !== ''){
                        html += '<div class="siswa-log-alert"><i class="fa fa-bell"></i> <b>Bukti Log Jaringan:</b> ' + esc_html(it.catatan_log) + '</div>';
                    }

                    html += '</div>';
                }

                $('#wadah-daftar-siswa').html(html);
            },
            error: function(){
                sedangMemuat = false;
            }
        });
    }

    $(function(){
        $('#filter-ruang, #filter-group, #filter-tes').change(function(){
            muat_data_pantau();
        });

        var searchTimeout = null;
        $('#filter-q').on('keyup input', function(){
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function(){
                muat_data_pantau();
            }, 350);
        });

        $('#btn-refresh-manual').click(function(){
            muat_data_pantau();
        });

        // Muat pertama kali & jalankan auto-refresh tiap 10 detik
        muat_data_pantau();
        timerPantau = setInterval(function(){
            muat_data_pantau();
        }, 10000);
    });
</script>
