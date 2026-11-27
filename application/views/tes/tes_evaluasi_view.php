<style type="text/css">
    /* Kartu Ringkasan Progres Koreksi Esai */
    .ev-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 14px;
    }
    @media (max-width: 991px) {
        .ev-stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .ev-stat-grid { grid-template-columns: 1fr; }
    }
    .ev-stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #64748b;
        border-radius: 8px;
        padding: 11px 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ev-stat-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    }
    .ev-stat-card.is-active {
        background: #f8fafc;
        box-shadow: 0 0 0 2px #3b82f6;
    }
    .ev-stat-card.card-ungraded { border-left-color: #f59e0b; }
    .ev-stat-card.card-graded   { border-left-color: #10b981; }
    .ev-stat-card.card-all      { border-left-color: #4f46e5; }
    .ev-stat-card.card-empty    { border-left-color: #94a3b8; }

    .ev-stat-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.3px;
    }
    .ev-stat-num {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-top: 2px;
    }
    .ev-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Progress Bar Koreksi */
    .ev-progress-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .ev-progress-bar-outer {
        flex: 1;
        min-width: 180px;
        height: 10px;
        background: #e2e8f0;
        border-radius: 99px;
        overflow: hidden;
    }
    .ev-progress-bar-inner {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        width: 0%;
        transition: width 0.25s ease;
    }

    /* Tabel & Komponen Baris Koreksi */
    .ev-student-box {
        line-height: 1.35;
    }
    .ev-qa-wrap {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }
    .ev-question-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 3px solid #6366f1;
        border-radius: 6px;
        padding: 8px 11px;
        font-size: 13px;
        color: #334155;
    }
    .ev-q-header {
        margin-bottom: 4px;
    }
    .ev-q-body p:last-child {
        margin-bottom: 0;
    }
    .ev-q-body img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }
    .ev-key-box {
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px dashed #cbd5e1;
        color: #065f46;
        background: #ecfdf5;
        padding: 6px 9px;
        border-radius: 4px;
        font-size: 12.5px;
    }
    .ev-answer-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-top: 2px;
    }
    .ev-answer-box {
        background: #fffbeb;
        border: 1.5px solid #fde68a;
        border-left: 4px solid #f59e0b;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.55;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .ev-answer-box.is-empty {
        background: #f8fafc;
        border-color: #e2e8f0;
        border-left-color: #94a3b8;
        color: #94a3b8;
        font-weight: 400;
    }

    /* Panel Input Nilai Cepat (Kanan) */
    .ev-grade-box {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px;
        transition: border-color 0.2s, background 0.2s;
    }
    .ev-grade-box.is-graded {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .ev-grade-box.is-dirty {
        background: #eff6ff;
        border-color: #60a5fa;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
    }
    .ev-max-info {
        font-size: 11.5px;
        color: #475569;
        margin-bottom: 5px;
    }
    .ev-score-input {
        font-size: 15px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        text-align: center;
        height: 34px !important;
    }
    .btn-save-inline {
        height: 34px !important;
        font-weight: 700 !important;
    }
    .ev-preset-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        margin-bottom: 5px;
    }
    .ev-preset-btns .btn {
        flex: 1;
        padding: 2px 4px;
        font-size: 11px;
        font-weight: 700;
    }
    .ev-save-feedback {
        font-size: 11.5px;
        min-height: 16px;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Evaluasi Jawaban Esai
		<small>Koreksi cepat jawaban uraian siswa per mata pelajaran, kelas, &amp; nomor soal</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('manager'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="<?php echo site_url('manager/tes_daftar'); ?>">Daftar Tes</a></li>
		<li class="active">Evaluasi Esai</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- 4 Kartu Ringkasan Progres (Klik untuk filter status) -->
    <div class="ev-stat-grid">
        <div class="ev-stat-card card-ungraded is-active" id="card-filter-belum" onclick="pilihStatusKoreksi('belum')" title="Klik untuk menampilkan jawaban yang belum dikoreksi">
            <div>
                <div class="ev-stat-label">Perlu Dikoreksi</div>
                <div class="ev-stat-num" id="stat-belum">0</div>
            </div>
            <div class="ev-stat-icon" style="background:#fef3c7; color:#d97706;">
                <i class="fa fa-hourglass-half"></i>
            </div>
        </div>
        <div class="ev-stat-card card-graded" id="card-filter-sudah" onclick="pilihStatusKoreksi('sudah')" title="Klik untuk menampilkan jawaban yang sudah dinilai">
            <div>
                <div class="ev-stat-label">Sudah Dikoreksi</div>
                <div class="ev-stat-num" id="stat-sudah">0</div>
            </div>
            <div class="ev-stat-icon" style="background:#d1fae5; color:#059669;">
                <i class="fa fa-check-circle"></i>
            </div>
        </div>
        <div class="ev-stat-card card-all" id="card-filter-semua" onclick="pilihStatusKoreksi('semua')" title="Klik untuk menampilkan semua jawaban terisi (Belum + Sudah)">
            <div>
                <div class="ev-stat-label">Semua Jawaban Terisi</div>
                <div class="ev-stat-num" id="stat-terisi">0</div>
            </div>
            <div class="ev-stat-icon" style="background:#e0e7ff; color:#4f46e5;">
                <i class="fa fa-file-text-o"></i>
            </div>
        </div>
        <div class="ev-stat-card card-empty" id="card-filter-kosong" onclick="pilihStatusKoreksi('kosong')" title="Klik untuk menampilkan lembar esai yang dikosongkan siswa">
            <div>
                <div class="ev-stat-label">Tidak Dijawab / Kosong</div>
                <div class="ev-stat-num" id="stat-kosong">0</div>
            </div>
            <div class="ev-stat-icon" style="background:#f1f5f9; color:#64748b;">
                <i class="fa fa-minus-circle"></i>
            </div>
        </div>
    </div>

    <!-- Bar Progres Koreksi & Pintasan ke Hasil Tes -->
    <div class="ev-progress-wrap">
        <div style="font-size:13px; font-weight:700; color:#334155;">
            <i class="fa fa-tasks text-green"></i> Progres Koreksi: <span id="prog-text">0 / 0 Jawaban (0%)</span>
            <span class="text-muted" style="font-weight:normal; margin-left:6px;" id="prog-siswa-info"></span>
        </div>
        <div class="ev-progress-bar-outer">
            <div class="ev-progress-bar-inner" id="prog-bar"></div>
        </div>
        <div>
            <a href="<?php echo site_url('manager/tes_hasil'); ?>" id="btn-goto-hasil" class="btn btn-info btn-xs">
                <i class="fa fa-bar-chart"></i> Lihat Hasil Tes
            </a>
        </div>
    </div>

    <!-- Box Filter Koreksi -->
    <div class="box box-primary" style="margin-bottom:14px;">
        <div class="box-header with-border" style="padding:8px 14px;">
            <h3 class="box-title" style="font-size:14px; font-weight:700;"><i class="fa fa-filter"></i> Filter &amp; Mode Koreksi Cepat</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-primary btn-xs" onclick="cetakJawabanEsai()" title="Pratinjau &amp; Cetak / Simpan PDF (Ctrl+P)">
                    <i class="fa fa-print"></i> Cetak / PDF Esai
                </button>
                <button type="button" class="btn btn-default btn-xs" onclick="muatUlangSemua()" style="margin-left:4px;">
                    <i class="fa fa-refresh"></i> Refresh Data
                </button>
            </div>
        </div>
        <div class="box-body" style="padding:12px 14px;">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group" style="margin-bottom:8px;">
                        <label style="font-size:12px; margin-bottom:3px;">1. Pilih Jadwal Tes Esai</label>
                        <select name="pilih-tes" id="pilih-tes" class="form-control input-sm">
                            <?php if(!empty($select_tes)){ echo $select_tes; } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom:8px;">
                        <label style="font-size:12px; margin-bottom:3px;">2. Filter Kelas</label>
                        <select name="pilih-grup" id="pilih-grup" class="form-control input-sm">
                            <?php if(!empty($select_group)){ echo $select_group; } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="form-group" style="margin-bottom:8px;">
                        <label style="font-size:12px; margin-bottom:3px;">3. Status Koreksi</label>
                        <select name="pilih-status" id="pilih-status" class="form-control input-sm">
                            <option value="belum" selected>Belum Dikoreksi</option>
                            <option value="sudah">Sudah Dikoreksi</option>
                            <option value="semua">Semua Jawaban Terisi</option>
                            <option value="kosong">Jawaban Kosong</option>
                            <option value="semua_termasuk_kosong">Semua (Termasuk Kosong)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="form-group" style="margin-bottom:8px;">
                        <label style="font-size:12px; margin-bottom:3px;">4. Fokus Butir Soal</label>
                        <select name="pilih-soal" id="pilih-soal" class="form-control input-sm">
                            <option value="semua">Semua Nomor Soal</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="form-group" style="margin-bottom:8px;">
                        <label style="font-size:12px; margin-bottom:3px;">5. Urutkan</label>
                        <select name="pilih-urutkan" id="pilih-urutkan" class="form-control input-sm">
                            <option value="soal" selected>Per Nomor Soal &rarr; Kelas</option>
                            <option value="siswa">Per Nama Siswa &rarr; Soal</option>
                            <option value="kelas">Per Kelas &rarr; Soal</option>
                            <option value="nilai_tinggi">Nilai Tertinggi</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:2px;">
                <div class="col-md-5 col-sm-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="pilih-cari" class="form-control" placeholder="Cari nama siswa, username/NIS, atau kata di jawaban...">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" onclick="$('#pilih-cari').val(''); refresh_table();" title="Bersihkan Pencarian"><i class="fa fa-times"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-7 col-sm-6 text-right" style="padding-top:3px;">
                    <label style="font-weight:normal; font-size:12.5px; margin-right:12px; cursor:pointer;" title="Jika dicentang, saat berada di tab Belum Dikoreksi baris yang sudah disimpan tidak langsung hilang sampai halaman di-refresh, atau otomatis lompat fokus ke kotak nilai di bawahnya">
                        <input type="checkbox" id="chk-auto-next" checked> Tekan <b>Enter</b> / Klik Preset = Simpan &amp; Lompat ke Baris Berikutnya
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Jawaban Esai -->
	<div class="row">
        <div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title" style="font-size:15px; font-weight:700;">
                        <i class="fa fa-check-square-o"></i> Lembar Jawaban Esai Siswa
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-default btn-sm" onclick="isiSemuaPenuhHalamanIni()" title="Isi kotak nilai yang masih kosong di halaman ini dengan nilai maksimal">
                            <i class="fa fa-magic"></i> Isi Maksimal yang Kosong
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="btn-batch-save" onclick="simpanSemuaHalamanIni()" style="margin-left:6px; font-weight:700;">
                            <i class="fa fa-save"></i> Simpan Semua Nilai Terisi di Halaman Ini
                        </button>
                    </div>
				</div>

                <div class="box-body table-responsive">
					<table id="table-jawaban" class="table table-bordered table-hover" style="width:100%;">
						<thead>
                            <tr>
                                <th style="width:38px;" class="text-center">No.</th>
                                <th style="width:200px;">Peserta &amp; Kelas</th>
                                <th>Butir Soal &amp; Jawaban Siswa</th>
                                <th style="width:240px;">Penilaian Cepat</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
					</table>                        
				</div>
                <div class="box-footer clearfix">
                    <div class="pull-left text-muted" style="font-size:12.5px; padding-top:4px;">
                        <i class="fa fa-lightbulb-o text-yellow"></i> <b>Tips Cepat:</b> Ketik angka nilai lalu tekan <code>Enter</code>, atau klik tombol <code>0</code> / <code>25%</code> / <code>50%</code> / <code>75%</code> / <code>Max</code> untuk langsung menyimpan dan pindah ke siswa berikutnya.
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-success btn-sm" onclick="simpanSemuaHalamanIni()" style="font-weight:700;">
                            <i class="fa fa-save"></i> Simpan Semua Nilai Terisi di Halaman Ini
                        </button>
                    </div>
                </div>
			</div>
        </div>
    </div>
</section><!-- /.content -->

<script type="text/javascript">
    var searchTimer = null;
    var lastTesForSoalList = null;

    function cetakJawabanEsai(){
        var params = $.param({
            tes: $('#pilih-tes').val(),
            grup: $('#pilih-grup').val(),
            status_koreksi: $('#pilih-status').val(),
            soal_id: $('#pilih-soal').val(),
            urutkan: $('#pilih-urutkan').val(),
            cari: $('#pilih-cari').val()
        });
        window.open('<?php echo site_url($url."/cetak"); ?>?' + params, '_blank');
    }

    function updateHasilLink(){
        var tid = $('#pilih-tes').val();
        var base = '<?php echo site_url("manager/tes_hasil"); ?>';
        if(tid && tid !== 'semua'){
            $('#btn-goto-hasil').attr('href', base + '?tes_id=' + encodeURIComponent(tid));
        }else{
            $('#btn-goto-hasil').attr('href', base);
        }
    }

    function syncActiveStatCard(){
        var st = $('#pilih-status').val();
        $('.ev-stat-card').removeClass('is-active');
        if(st === 'belum') $('#card-filter-belum').addClass('is-active');
        else if(st === 'sudah') $('#card-filter-sudah').addClass('is-active');
        else if(st === 'semua') $('#card-filter-semua').addClass('is-active');
        else if(st === 'kosong') $('#card-filter-kosong').addClass('is-active');
    }

    function pilihStatusKoreksi(statusVal){
        $('#pilih-status').val(statusVal);
        syncActiveStatCard();
        refresh_table();
    }

    function loadSummary(rebuildSoalDropdown){
        var tes  = $('#pilih-tes').val();
        var grup = $('#pilih-grup').val();
        updateHasilLink();

        $.getJSON('<?php echo site_url($url."/get_summary"); ?>', { tes: tes, grup: grup }, function(res){
            if(!res || res.status != 1) return;
            var s = res.stat || {};
            var belum  = parseInt(s.belum_dikoreksi, 10) || 0;
            var sudah  = parseInt(s.sudah_dikoreksi, 10) || 0;
            var kosong = parseInt(s.jawaban_kosong, 10) || 0;
            var terisi = belum + sudah;
            var siswa  = parseInt(s.total_siswa, 10) || 0;

            $('#stat-belum').text(belum);
            $('#stat-sudah').text(sudah);
            $('#stat-terisi').text(terisi);
            $('#stat-kosong').text(kosong);

            var pct = terisi > 0 ? Math.round((sudah / terisi) * 100) : 0;
            $('#prog-text').text(sudah + ' / ' + terisi + ' Jawaban Terisi (' + pct + '%)');
            $('#prog-siswa-info').text(siswa > 0 ? '• ' + siswa + ' Siswa mengikuti tes' : '');
            $('#prog-bar').css('width', pct + '%');

            if(rebuildSoalDropdown || lastTesForSoalList !== tes){
                lastTesForSoalList = tes;
                var prevSoal = $('#pilih-soal').val();
                var opts = '<option value="semua">Semua Nomor Soal</option>';
                var list = res.soal_list || [];
                for(var i = 0; i < list.length; i++){
                    var item = list[i];
                    var tag = item.belum > 0 ? ' (' + item.belum + ' belum)' : ' (Selesai)';
                    opts += '<option value="' + item.soal_id + '">Soal #' + item.nomor + tag + ' — ' + $('<div>').text(item.cuplikan).html() + '</option>';
                }
                $('#pilih-soal').html(opts);
                if(prevSoal && $('#pilih-soal option[value="' + prevSoal + '"]').length > 0){
                    $('#pilih-soal').val(prevSoal);
                }
            }
        });
    }

    function refresh_table(){
        syncActiveStatCard();
        loadSummary(false);
        if($.fn.DataTable.isDataTable('#table-jawaban')){
            $('#table-jawaban').dataTable().fnReloadAjax();
        }
    }

    function muatUlangSemua(){
        loadSummary(true);
        if($.fn.DataTable.isDataTable('#table-jawaban')){
            $('#table-jawaban').dataTable().fnReloadAjax();
        }
    }

    function focusNextInput(currentId){
        var $inputs = $('.ev-score-input');
        var idx = -1;
        $inputs.each(function(i){
            if(parseInt($(this).attr('data-id'), 10) === parseInt(currentId, 10)){
                idx = i;
                return false;
            }
        });
        if(idx >= 0 && (idx + 1) < $inputs.length){
            var $next = $inputs.eq(idx + 1);
            $next.focus().select();
        }
    }

    function setPresetNilai(tessoalId, val){
        var $inp = $('#input-nilai-' + tessoalId);
        $inp.val(val);
        simpanInline(tessoalId, true);
    }

    function simpanInline(tessoalId, fromPreset){
        var $inp = $('#input-nilai-' + tessoalId);
        var $box = $('#grade-box-' + tessoalId);
        var $btn = $('#btn-save-' + tessoalId);
        var $fb  = $('#feedback-' + tessoalId);

        if($inp.length === 0) return;

        var rawVal = $.trim($inp.val());
        var minVal = parseFloat($inp.attr('data-min')) || 0;
        var maxVal = parseFloat($inp.attr('data-max')) || 100;

        if(rawVal === '' || isNaN(rawVal)){
            notify_error('Masukkan angka nilai terlebih dahulu.');
            $inp.focus();
            return;
        }
        var numVal = parseFloat(rawVal);
        if(numVal < minVal || numVal > maxVal){
            notify_error('Nilai harus di antara ' + minVal + ' sampai ' + maxVal + '!');
            $inp.focus().select();
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $fb.html('<small class="text-info"><i class="fa fa-spinner fa-spin"></i> Menyimpan...</small>');

        $.ajax({
            url: '<?php echo site_url($url."/simpan_nilai"); ?>',
            type: 'POST',
            data: {
                'evaluasi-testlog-id': tessoalId,
                'evaluasi-nilai-min': minVal,
                'evaluasi-nilai-max': maxVal,
                'evaluasi-nilai': numVal
            },
            cache: false,
            success: function(respon){
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Simpan');
                var obj = {};
                try { obj = $.parseJSON(respon); } catch(e){}

                if(obj && obj.status == 1){
                    $inp.attr('data-orig', numVal);
                    $box.removeClass('is-ungraded is-dirty').addClass('is-graded');
                    $('#badge-status-' + tessoalId)
                        .removeClass('label-warning')
                        .addClass('label-success')
                        .html('<i class="fa fa-check-circle"></i> Sudah Dikoreksi (' + numVal + ')');
                    var totalInfo = (obj.total_nilai_siswa !== null && obj.total_nilai_siswa !== undefined)
                        ? ' • Total Nilai Siswa: <b>' + obj.total_nilai_siswa + '</b>'
                        : '';
                    $fb.html('<small class="text-success"><i class="fa fa-check-circle"></i> Tersimpan (' + numVal + ')' + totalInfo + '</small>');
                    loadSummary(false);

                    if($('#chk-auto-next').is(':checked')){
                        focusNextInput(tessoalId);
                    }
                }else{
                    var errMsg = (obj && obj.pesan) ? obj.pesan : 'Gagal menyimpan nilai.';
                    $fb.html('<small class="text-danger"><i class="fa fa-exclamation-triangle"></i> ' + errMsg + '</small>');
                    notify_error(errMsg);
                }
            },
            error: function(){
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Simpan');
                $fb.html('<small class="text-danger">Gagal menghubungi server.</small>');
                notify_error('Gagal menghubungi server.');
            }
        });
    }

    function isiSemuaPenuhHalamanIni(){
        var count = 0;
        $('.ev-score-input').each(function(){
            if($.trim($(this).val()) === ''){
                var maxVal = $(this).attr('data-max');
                $(this).val(maxVal).trigger('input');
                count++;
            }
        });
        if(count > 0){
            notify_info(count + ' kotak kosong diisi nilai maksimal. Klik "Simpan Semua" untuk menyimpan.');
        }else{
            notify_info('Semua kotak nilai di halaman ini sudah terisi.');
        }
    }

    function simpanSemuaHalamanIni(){
        var items = [];
        $('.ev-score-input').each(function(){
            var val = $.trim($(this).val());
            if(val !== '' && !isNaN(val)){
                items.push({
                    tessoal_id: parseInt($(this).attr('data-id'), 10),
                    nilai: parseFloat(val),
                    min: parseFloat($(this).attr('data-min')) || 0,
                    max: parseFloat($(this).attr('data-max')) || 100
                });
            }
        });

        if(items.length === 0){
            notify_error('Belum ada angka nilai yang diisi pada halaman ini.');
            return;
        }

        $("#modal-proses").modal('show');
        $.ajax({
            url: '<?php echo site_url($url."/simpan_nilai_batch"); ?>',
            type: 'POST',
            data: { items_json: JSON.stringify(items) },
            cache: false,
            success: function(respon){
                $("#modal-proses").modal('hide');
                var obj = {};
                try { obj = $.parseJSON(respon); } catch(e){}
                if(obj && obj.status == 1){
                    notify_success(obj.pesan);
                    refresh_table();
                }else{
                    notify_error((obj && obj.pesan) ? obj.pesan : 'Gagal menyimpan nilai batch.');
                }
            },
            error: function(){
                $("#modal-proses").modal('hide');
                notify_error('Gagal menghubungi server saat menyimpan batch.');
            }
        });
    }

    $(function(){
        // Event tekan Enter pada kotak nilai -> langsung simpan & lompat ke siswa berikutnya
        $(document).on('keydown', '.ev-score-input', function(e){
            if(e.which === 13){
                e.preventDefault();
                var id = $(this).attr('data-id');
                simpanInline(id, false);
            }
        });

        // Tandai kotak biru saat nilai diubah namun belum disimpan
        $(document).on('input', '.ev-score-input', function(){
            var id = $(this).attr('data-id');
            var orig = $(this).attr('data-orig') || '';
            var curr = $.trim($(this).val());
            var $box = $('#grade-box-' + id);
            if(curr !== orig){
                $box.addClass('is-dirty');
                $('#feedback-' + id).html('<small class="text-primary"><i class="fa fa-info-circle"></i> Belum disimpan (tekan Enter)</small>');
            }else{
                $box.removeClass('is-dirty');
            }
        });

        $('#pilih-tes').on('change', function(){
            $('#pilih-soal').val('semua');
            loadSummary(true);
            if($.fn.DataTable.isDataTable('#table-jawaban')){
                $('#table-jawaban').dataTable().fnReloadAjax();
            }
        });

        $('#pilih-grup, #pilih-status, #pilih-soal, #pilih-urutkan').on('change', function(){
            refresh_table();
        });

        $('#pilih-cari').on('keyup', function(e){
            if(searchTimer) clearTimeout(searchTimer);
            if(e.which === 13){
                refresh_table();
                return;
            }
            searchTimer = setTimeout(function(){
                refresh_table();
            }, 350);
        });

        loadSummary(true);

        $('#table-jawaban').DataTable({
            "paging": true,
            "iDisplayLength": 25,
            "bProcessing": true,
            "bServerSide": true,
            "searching": false,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth": "38px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "200px"},
                {"bSearchable": false, "bSortable": false},
                {"bSearchable": false, "bSortable": false, "sWidth": "240px"}
            ],
            "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
            "autoWidth": false,
            "aLengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "tes", "value": $('#pilih-tes').val()} );
                aoData.push( { "name": "grup", "value": $('#pilih-grup').val()} );
                aoData.push( { "name": "status_koreksi", "value": $('#pilih-status').val()} );
                aoData.push( { "name": "soal_id", "value": $('#pilih-soal').val()} );
                aoData.push( { "name": "urutkan", "value": $('#pilih-urutkan').val()} );
                aoData.push( { "name": "cari", "value": $('#pilih-cari').val()} );
            }
        });

        $(document).on('keydown', function(e){
            if((e.ctrlKey || e.metaKey) && e.key && e.key.toLowerCase() === 'p'){
                e.preventDefault();
                cetakJawabanEsai();
            }
        });
    });
</script>
