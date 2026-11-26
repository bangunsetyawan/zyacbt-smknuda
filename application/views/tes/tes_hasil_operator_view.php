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
    .btn-segarkan-besar {
        font-size: 15px;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.12);
    }

    /* Grid Kartu Ruang 1-Tap (Mobile-First) */
    .ruang-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    @media (min-width: 768px) {
        .ruang-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }
    .ruang-card-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 12px 6px;
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.12s ease;
        min-height: 64px;
        user-select: none;
    }
    .ruang-card-btn:active, .ruang-card-btn:hover {
        border-color: #16a34a;
        background: #f0fdf4;
    }
    .ruang-card-btn.is-active {
        background: #16a34a;
        border-color: #15803d;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.35);
    }
    .ruang-card-title {
        font-size: 14px;
        font-weight: 800;
        line-height: 1.2;
    }
    .ruang-card-count {
        font-size: 11.5px;
        font-weight: 600;
        opacity: 0.82;
        margin-top: 3px;
    }
    .ruang-card-btn.is-admin-all {
        grid-column: 1 / -1;
        flex-direction: row;
        gap: 8px;
        min-height: 46px;
        padding: 10px 12px;
        background: #fef2f2;
        border-color: #fca5a5;
        color: #991b1b;
    }
    .ruang-card-btn.is-admin-all.is-active {
        background: #dc2626;
        border-color: #b91c1c;
        color: #ffffff;
    }
    .ruang-aktif-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        background: #f0fdf4;
        border: 1.5px solid #86efac;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 10px;
    }
    .ruang-aktif-label {
        font-size: 14px;
        font-weight: 700;
        color: #14532d;
    }

    .pengawas-summary-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
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
        font-size: 15.5px;
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
    .badge-anomali {
        display: inline-block;
        margin-top: 4px;
        padding: 3px 8px;
        font-size: 11.5px;
        font-weight: 700;
        border-radius: 4px;
        background-color: #1f2937;
        color: #fff;
        white-space: nowrap;
    }

    /* Kotak Sorotan Jam Kejadian & Aksi Terakhir Besar (Bagian D #1) */
    .siswa-waktu-highlight {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 6px 12px;
        margin-top: 10px;
        padding: 8px 12px;
        border-radius: 6px;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
    }
    .siswa-card.warna-merah .siswa-waktu-highlight {
        background: #fff5f5;
        border-color: #fca5a5;
    }
    .siswa-card.warna-kuning .siswa-waktu-highlight {
        background: #fffdf0;
        border-color: #fcd34d;
    }
    .waktu-jam-besar {
        font-size: 19px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #111827;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    .siswa-card.warna-merah .waktu-jam-besar { color: #b91c1c; }
    .siswa-card.warna-kuning .waktu-jam-besar { color: #b45309; }
    .waktu-aksi-label {
        font-size: 13.5px;
        font-weight: 600;
        color: #1f2937;
    }

    .siswa-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 16px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed rgba(0,0,0,0.12);
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
            font-size: 15px;
        }
        .waktu-jam-besar {
            font-size: 18px;
        }
    }
</style>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success" style="margin-bottom: 12px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-map-marker"></i> Pilih Ruang Ujian (1-Tap)</h3>
                    <div class="box-tools pull-right">
                        <span id="indikator-live" class="label label-default" style="font-size:11.5px; padding:4px 8px;">
                            <i class="fa fa-circle-o"></i> Pilih Ruang (<span id="jam-update">-</span>)
                        </span>
                    </div>
                </div>
                <div class="box-body">
                    <!-- Bar Ruang Aktif (tampil setelah ruang dipilih agar kartu siswa langsung terlihat tanpa scroll) -->
                    <div id="bar-ruang-aktif" class="ruang-aktif-bar" style="display:none;">
                        <div class="ruang-aktif-label">
                            <i class="fa fa-map-marker text-success"></i> <span id="teks-ruang-aktif">-</span>
                        </div>
                        <button type="button" id="btn-ganti-ruang" class="btn btn-sm btn-default" onclick="tampilkan_grid_ruang(true)">
                            <i class="fa fa-th-large"></i> Ganti Ruang
                        </button>
                    </div>

                    <!-- Grid Kartu Ruang (Ketuk 1x langsung memantau ruang tersebut) -->
                    <div id="wadah-grid-ruang" class="ruang-grid" style="margin-bottom: 10px;">
                        <?php if(!empty($is_admin)): ?>
                        <button type="button" class="ruang-card-btn is-admin-all" data-ruang="semua_masalah" data-label="Semua Ruang (Merah & Kuning)" onclick="pilih_ruang(this)">
                            <span class="ruang-card-title"><i class="fa fa-globe"></i> Semua Ruang (Khusus Merah &amp; Kuning)</span>
                        </button>
                        <?php endif; ?>
                        <?php if(!empty($ruang_list)): foreach($ruang_list as $rl): ?>
                        <button type="button" class="ruang-card-btn" data-ruang="<?php echo html_escape($rl['kode']); ?>" data-label="<?php echo html_escape($rl['label']).' ('.(int)$rl['jumlah'].' siswa)'; ?>" onclick="pilih_ruang(this)">
                            <span class="ruang-card-title"><?php echo html_escape($rl['label']); ?></span>
                            <span class="ruang-card-count"><?php echo (int)$rl['jumlah']; ?> siswa</span>
                        </button>
                        <?php endforeach; else: ?>
                        <div class="text-muted" style="grid-column: 1 / -1; padding:8px 0;">Belum ada daftar ruang terisi di data peserta.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Segarkan Besar & Jelas (Muncul atau aktif saat ruang dipilih) -->
                    <div class="row" id="wadah-tombol-segarkan" style="display:none;">
                        <div class="col-xs-12">
                            <button type="button" id="btn-refresh-manual" class="btn btn-success btn-block btn-segarkan-besar">
                                <i class="fa fa-refresh" id="ikon-segarkan"></i> <span id="teks-segarkan">Segarkan Data Sekarang</span>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" id="filter-ruang" value="">
                    <input type="hidden" id="filter-warna" value="masalah">
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan 4 Kartu Warna (Tersembunyi sebelum pilih ruang; Hijau & Belum cukup angka hitungan) -->
    <div id="wadah-ringkasan" style="display:none;">
        <div class="pengawas-summary-grid">
            <div class="pengawas-stat-card stat-merah" onclick="set_filter_warna('merah')" id="card-stat-merah" title="Klik untuk menampilkan siswa Paket Data / Publik saja">
                <div class="pengawas-stat-num text-danger" id="jml-merah">0</div>
                <div class="pengawas-stat-label"><i class="fa fa-warning text-danger"></i> MERAH (Paket Data)</div>
            </div>
            <div class="pengawas-stat-card stat-kuning" onclick="set_filter_warna('kuning')" id="card-stat-kuning" title="Klik untuk menampilkan siswa yang pernah pindah jaringan">
                <div class="pengawas-stat-num text-yellow" id="jml-kuning">0</div>
                <div class="pengawas-stat-label"><i class="fa fa-exchange text-yellow"></i> KUNING (Pindah IP)</div>
            </div>
            <div class="pengawas-stat-card stat-hijau" onclick="set_filter_warna('hijau')" id="card-stat-hijau" title="Angka siswa aman di LAN sekolah (klik bila ingin melihat daftar)">
                <div class="pengawas-stat-num text-success" id="jml-hijau">0</div>
                <div class="pengawas-stat-label"><i class="fa fa-check-circle text-success"></i> HIJAU (LAN Aman)</div>
            </div>
            <div class="pengawas-stat-card stat-belum" onclick="set_filter_warna('belum')" id="card-stat-belum" title="Angka siswa yang belum login / mulai ujian di ruang ini">
                <div class="pengawas-stat-num text-muted" id="jml-belum">0</div>
                <div class="pengawas-stat-label"><i class="fa fa-clock-o text-muted"></i> BELUM LOGIN (<span id="jml-total">0</span> Total)</div>
            </div>
        </div>
        <div id="bar-mode-pantau" style="margin-bottom:10px; font-size:12.5px; color:#374151; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px;">
            <span id="teks-mode-pantau"><i class="fa fa-eye text-danger"></i> Menampilkan <b>Kuning &amp; Merah saja</b> (fokus pelanggaran).</span>
            <button type="button" id="btn-reset-mode" class="btn btn-xs btn-default" onclick="set_filter_warna('masalah')" style="display:none;">
                <i class="fa fa-undo"></i> Kembali ke Merah &amp; Kuning
            </button>
        </div>
    </div>

    <!-- Daftar Kartu Siswa (Kosong sebelum pilih ruang) -->
    <div class="row">
        <div class="col-xs-12">
            <div id="wadah-daftar-siswa"></div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var timerPantau = null;
    var sedangMemuat = false;
    var retryAwalDone = false;

    function esc_html(str){
        if(str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function tampilkan_grid_ruang(tampil){
        if(tampil){
            $('#wadah-grid-ruang').show();
            $('#bar-ruang-aktif').hide();
        }else{
            $('#wadah-grid-ruang').hide();
            $('#bar-ruang-aktif').show();
        }
    }

    function pilih_ruang(btnEl){
        var $btn = $(btnEl);
        var ruang = $btn.data('ruang') || '';
        var label = $btn.data('label') || ruang;

        $('.ruang-card-btn').removeClass('is-active');
        $btn.addClass('is-active');

        $('#filter-ruang').val(ruang);
        $('#teks-ruang-aktif').text(label);
        $('#wadah-tombol-segarkan').show();
        tampilkan_grid_ruang(false);

        retryAwalDone = false;
        $('#filter-warna').val('masalah');
        update_mode_banner();
        muat_data_pantau(true);
    }

    function update_mode_banner(){
        var aktif = $('#filter-warna').val();
        $('.pengawas-stat-card').removeClass('active-filter');
        if(aktif === 'masalah'){
            $('#teks-mode-pantau').html('<i class="fa fa-eye text-danger"></i> Menampilkan <b>Kuning &amp; Merah saja</b> (Hijau &amp; Belum Login ditampilkan sebagai angka ringkasan).');
            $('#btn-reset-mode').hide();
        }else if(aktif === 'merah'){
            $('#card-stat-merah').addClass('active-filter');
            $('#teks-mode-pantau').html('<i class="fa fa-filter text-danger"></i> Filter aktif: <b>MERAH saja</b>.');
            $('#btn-reset-mode').show();
        }else if(aktif === 'kuning'){
            $('#card-stat-kuning').addClass('active-filter');
            $('#teks-mode-pantau').html('<i class="fa fa-filter text-yellow"></i> Filter aktif: <b>KUNING saja</b>.');
            $('#btn-reset-mode').show();
        }else if(aktif === 'hijau'){
            $('#card-stat-hijau').addClass('active-filter');
            $('#teks-mode-pantau').html('<i class="fa fa-filter text-success"></i> Filter aktif: <b>HIJAU saja</b>.');
            $('#btn-reset-mode').show();
        }else if(aktif === 'belum'){
            $('#card-stat-belum').addClass('active-filter');
            $('#teks-mode-pantau').html('<i class="fa fa-filter text-muted"></i> Filter aktif: <b>BELUM LOGIN / MULAI saja</b>.');
            $('#btn-reset-mode').show();
        }
    }

    function set_filter_warna(warna){
        var ruang = $('#filter-ruang').val();
        if(ruang === ''){
            return;
        }
        if(ruang === 'semua_masalah' && (warna === 'hijau' || warna === 'belum')){
            if(typeof notify_info === 'function'){
                notify_info('Mode "Semua Ruang" hanya menampilkan kartu Merah & Kuning agar ringan. Ketuk Ganti Ruang untuk melihat Hijau/Belum per ruang.');
            }
            return;
        }
        var cur = $('#filter-warna').val();
        if(cur === warna && warna !== 'masalah'){
            $('#filter-warna').val('masalah');
        }else{
            $('#filter-warna').val(warna);
        }
        update_mode_banner();
        muat_data_pantau(true);
    }

    function set_tombol_loading(isLoading){
        if(isLoading){
            $('#ikon-segarkan').addClass('fa-spin');
            $('#teks-segarkan').text('Menyegarkan Data...');
        }else{
            $('#ikon-segarkan').removeClass('fa-spin');
            $('#teks-segarkan').text('Segarkan Data Sekarang');
        }
    }

    function reset_tampilan_kosong(){
        $('#wadah-ringkasan').hide();
        $('#wadah-tombol-segarkan').hide();
        $('#bar-ruang-aktif').hide();
        $('#wadah-grid-ruang').show();
        $('#wadah-daftar-siswa').html('');
        $('#indikator-live')
            .removeClass('label-success')
            .addClass('label-default')
            .html('<i class="fa fa-circle-o"></i> Pilih Ruang (<span id="jam-update">-</span>)');
    }

    function muat_data_pantau(isManual){
        var ruang = $('#filter-ruang').val();
        if(!ruang || ruang === ''){
            reset_tampilan_kosong();
            return;
        }
        if(sedangMemuat) return;
        sedangMemuat = true;
        if(isManual){
            set_tombol_loading(true);
        }

        $.ajax({
            url: "<?php echo site_url().'/'.$url; ?>/get_pantau_data",
            type: "GET",
            dataType: "json",
            cache: false,
            timeout: 8000,
            data: {
                ruang: ruang,
                warna: $('#filter-warna').val(),
                _t:    new Date().getTime()
            },
            success: function(res){
                sedangMemuat = false;
                set_tombol_loading(false);
                if(typeof res === 'string'){
                    try { res = $.parseJSON(res); } catch(e) { res = null; }
                }
                if(!res || res.status != 1) return;

                if(res.empty_room){
                    reset_tampilan_kosong();
                    return;
                }

                $('#wadah-ringkasan').show();
                $('#indikator-live')
                    .removeClass('label-default')
                    .addClass('label-success')
                    .html('<i class="fa fa-circle"></i> Live 10d (<span id="jam-update">' + esc_html(res.waktu_server || '-') + '</span>)');

                $('#jml-merah').text(res.summary.merah || 0);
                $('#jml-kuning').text(res.summary.kuning || 0);
                $('#jml-hijau').text(res.summary.hijau || 0);
                $('#jml-belum').text(res.summary.belum || 0);
                $('#jml-total').text(res.summary.total || 0);

                var items = res.items || [];
                var modeWarna = $('#filter-warna').val();

                if(items.length === 0){
                    if(modeWarna === 'masalah'){
                        $('#wadah-daftar-siswa').html(
                            '<div class="callout callout-success" style="margin-bottom:0;">' +
                            '<i class="fa fa-check-circle"></i> <b>Ruang Aman (0 Merah, 0 Kuning):</b> Tidak ada pelanggaran jaringan di ruang ini. ' +
                            'Tercatat <b>' + (res.summary.hijau || 0) + ' siswa LAN aman</b> dan <b>' + (res.summary.belum || 0) + ' belum login/mulai</b>.' +
                            '</div>'
                        );
                    }else{
                        $('#wadah-daftar-siswa').html(
                            '<div class="box box-solid"><div class="box-body text-center text-muted" style="padding:20px;">' +
                            '<i class="fa fa-info-circle"></i> Tidak ada peserta dengan status <b>' + esc_html(modeWarna.toUpperCase()) + '</b> di ruang ini.' +
                            '</div></div>'
                        );
                    }
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
                            + '<div style="text-align:right;">'
                                + '<span class="badge-net ' + badgeClass + '"><i class="fa ' + badgeIcon + '"></i> ' + esc_html(it.badge_label) + '</span>'
                                + (it.anomali_count > 0 ? '<br><span class="badge-anomali"><i class="fa fa-exchange"></i> ' + esc_html(it.anomali_count) + 'x Pindah/Keluar Jalur</span>' : '')
                            + '</div>'
                        + '</div>';

                    // Jam & Aksi Terakhir Besar agar mudah membedakan pelanggaran vs human error / selesai
                    html += '<div class="siswa-waktu-highlight">'
                        + '<div class="waktu-jam-besar"><i class="fa fa-clock-o"></i> ' + esc_html(it.jam_kejadian || it.waktu_aktivitas || '-') + ' WIB</div>'
                        + '<div class="waktu-aksi-label">' + esc_html(it.aksi_terakhir || '-') + '</div>'
                        + '</div>';

                    html += '<div class="siswa-meta-row">'
                            + '<div><i class="fa fa-tasks text-muted"></i> <b>Status:</b> ' + statusBadge + ' (' + esc_html(it.progres) + ')</div>'
                            + '<div><i class="fa fa-book text-muted"></i> <b>Tes:</b> ' + esc_html(it.tes_nama) + '</div>'
                            + '<div><i class="fa fa-wifi text-muted"></i> <b>IP Asal:</b> <code>' + esc_html(it.ip) + '</code> (' + esc_html(it.jalur) + ')</div>'
                            + '<div><i class="fa fa-history text-muted"></i> <b>Mulai:</b> ' + esc_html(it.waktu_mulai) + '</div>'
                        + '</div>';

                    if(it.catatan_log && it.catatan_log !== ''){
                        var prefixAnomali = (it.anomali_summary && it.anomali_summary !== '') ? ' (' + esc_html(it.anomali_summary) + ')' : '';
                        html += '<div class="siswa-log-alert"><i class="fa fa-bell"></i> <b>Bukti Log Jaringan' + prefixAnomali + ':</b> ' + esc_html(it.catatan_log) + '</div>';
                    }

                    html += '</div>';
                }

                $('#wadah-daftar-siswa').html(html);
            },
            error: function(){
                sedangMemuat = false;
                set_tombol_loading(false);
                if(!retryAwalDone && $('#filter-ruang').val() !== ''){
                    retryAwalDone = true;
                    setTimeout(function(){
                        muat_data_pantau(false);
                    }, 1200);
                }
            }
        });
    }

    $(function(){
        update_mode_banner();
        reset_tampilan_kosong();

        $('#btn-refresh-manual').click(function(){
            muat_data_pantau(true);
        });

        // Auto-refresh tiap 10 detik HANYA bila ruang sudah dipilih
        timerPantau = setInterval(function(){
            if($('#filter-ruang').val() !== ''){
                muat_data_pantau(false);
            }
        }, 10000);
    });
</script>
