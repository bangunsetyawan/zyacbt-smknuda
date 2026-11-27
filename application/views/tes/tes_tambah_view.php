<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?php echo !empty($is_edit) ? 'Edit Jadwal Tes' : 'Buat Jadwal Tes'; ?>
		<small><?php echo !empty($is_edit) ? 'Ubah pengaturan jadwal, kelas, dan paket soal tes' : 'Cari mapel, atur jam & kelas, lalu simpan dalam 1 halaman ringkas'; ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="<?php echo site_url('manager/tes_daftar'); ?>">Daftar Tes</a></li>
		<li class="active"><?php echo !empty($is_edit) ? 'Edit Tes' : 'Buat Jadwal Tes'; ?></li>
	</ol>
</section>

<style>
    .modul-active-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 12px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
        vertical-align: middle;
    }
    .section-step {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        border-bottom: 1.5px solid #e5e7eb;
        padding-bottom: 6px;
        margin: 14px 0 12px 0;
    }
    .section-step:first-child { margin-top: 0; }
    .section-step .step-left {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .section-step .step-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #059669;
        color: #fff;
        width: 21px;
        height: 21px;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 700;
    }
    .filter-mini-group {
        display: inline-flex;
        gap: 4px;
        text-transform: none;
        letter-spacing: 0;
    }
    .filter-mini-btn {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        border-radius: 4px;
        padding: 2px 9px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
    }
    .filter-mini-btn.active {
        background: #1f2937;
        border-color: #1f2937;
        color: #fff;
    }
    .topik-summary-strip {
        margin-top: 8px;
        padding: 7px 11px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 12.5px;
        color: #334155;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }
    .pair-esai-card {
        background: #f5f3ff;
        border: 1.5px solid #c4b5fd;
        border-radius: 6px;
        padding: 11px 14px;
        margin-top: 10px;
    }
    .pair-mode-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: 8px 0 10px 0;
    }
    .pair-mode-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border: 1.5px solid #c4b5fd;
        border-radius: 5px;
        background: #fff;
        color: #4c1d95;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        margin: 0;
    }
    .pair-mode-pill.active {
        background: #5b21b6;
        border-color: #5b21b6;
        color: #fff;
    }
    .pair-mode-pill input[type="radio"] {
        margin: 0;
    }
    .preset-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 6px;
        align-items: center;
    }
    .kelas-check-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 8px;
    }
    .kelas-check-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border: 1px solid #d2d6de;
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
        font-weight: 500;
        font-size: 13px;
        user-select: none;
        margin: 0;
        transition: all 0.12s ease;
    }
    .kelas-check-item:hover {
        border-color: #059669;
        background: #ecfdf5;
    }
    .kelas-check-item.checked {
        background: #d1fae5;
        border-color: #059669;
        color: #065f46;
        font-weight: 600;
    }
    .kelas-check-item input[type="checkbox"] {
        margin: 0;
    }
    .advanced-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 14px;
        padding: 6px 11px;
        border-radius: 5px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #374151;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
    }
    .advanced-toggle:hover, .advanced-toggle:focus {
        background: #f3f4f6;
        color: #111827;
        text-decoration: none;
    }
    .max-score-pill {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 4px;
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
        font-weight: 700;
        font-size: 11.5px;
    }
    .session-created-box {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 6px;
        padding: 10px 14px;
        margin-bottom: 14px;
    }
    .session-created-box ul {
        margin: 6px 0 0 0;
        padding-left: 18px;
        font-size: 12.5px;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        border-color: #d2d6de;
        border-radius: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        font-size: 14px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .jam-picker-btn {
        cursor: pointer;
        background: #f9fafb;
        color: #374151;
    }
    .jam-picker-btn:hover {
        background: #e5e7eb;
    }
    #jam-24-floating-menu {
        position: fixed;
        z-index: 9999;
        width: 105px;
        max-height: 220px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.14);
        display: none;
        padding: 3px 0;
    }
    #jam-24-floating-menu .jam-opt {
        padding: 5px 10px;
        font-size: 12.5px;
        cursor: pointer;
        text-align: center;
        color: #1f2937;
    }
    #jam-24-floating-menu .jam-opt:hover,
    #jam-24-floating-menu .jam-opt.selected {
        background: #059669;
        color: #fff;
        font-weight: 600;
    }
</style>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
            <?php if(!empty($is_edit) && !empty($has_participants)){ ?>
                <div class="callout callout-warning">
                    <h4><i class="fa fa-lock"></i> Tes Sudah Dikerjakan (<?php echo (int)$has_participants; ?> peserta)</h4>
                    <p style="margin:0;">Anda tetap dapat mengubah <strong>Rentang Waktu (Buka–Tutup)</strong>, <strong>Durasi</strong>, dan status <strong>Token</strong>. Pengaturan paket soal &amp; bobot skor dikunci otomatis demi menjaga integritas nilai peserta.</p>
                </div>
            <?php } ?>

            <!-- Ringkasan jadwal yang baru dibuat dalam sesi input ini -->
            <div id="session-created-box" class="session-created-box" style="display:none;">
                <strong style="color:#166534;"><i class="fa fa-check-circle"></i> Jadwal baru dibuat di sesi ini (<span id="session-created-count">0</span>):</strong>
                <ul id="session-created-list"></ul>
            </div>

            <div class="box box-success">
                <?php echo form_open($url.(!empty($is_edit) ? '/tambah_tes' : '/simpan_cepat'), 'id="form-tambah-tes"'); ?>
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-calendar-plus-o"></i>
                        <?php echo !empty($is_edit) ? 'Pengaturan Jadwal Tes' : 'Buat Jadwal Tes'; ?>
                        <?php if(empty($is_edit)){ ?>
                            <span class="modul-active-badge" title="Otomatis mengikuti Modul Aktif di menu Data Modul">
                                <i class="fa fa-database"></i> Modul Aktif: <?php echo html_escape($active_modul_nama); ?>
                            </span>
                        <?php } ?>
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo site_url('manager/tes_daftar'); ?>" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Daftar Tes
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div id="form-pesan-tes"></div>
                    <input type="hidden" name="tambah-id" id="tambah-id" value="<?php echo !empty($edit_tes_id) ? (int)$edit_tes_id : ''; ?>" />
                    <input type="hidden" name="tambah-nama-lama" id="tambah-nama-lama" value="" />
                    <input type="hidden" name="tambah-rentang-waktu" id="tambah-rentang-waktu" value="<?php echo html_escape($rentang_waktu); ?>" />

                    <?php if(empty($is_edit)){ ?>
                    <input type="hidden" id="smart-modul" name="soal-modul" value="<?php echo (int)$active_modul_id; ?>" />

                    <!-- STEP 1: CARI & PILIH TOPIK / MAPEL -->
                    <div class="section-step">
                        <span class="step-left"><span class="step-num">1</span> Pilih Topik / Mata Pelajaran</span>
                        <div class="filter-mini-group" id="topik-type-filter">
                            <button type="button" class="filter-mini-btn active" data-filter="all">Semua</button>
                            <button type="button" class="filter-mini-btn" data-filter="pg">PG</button>
                            <button type="button" class="filter-mini-btn" data-filter="esai">Esai</button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:8px;">
                        <select class="form-control" id="smart-topik" name="soal-topik" style="width:100%;">
                            <option value="">-- Ketik untuk mencari topik / mata pelajaran... --</option>
                        </select>
                        <div id="smart-topik-summary" class="topik-summary-strip" style="display:none;"></div>
                    </div>

                    <!-- Auto-Pair Esai Card (muncul otomatis jika topik PG punya pasangan Esai) -->
                    <div id="box-pair-esai" class="pair-esai-card" style="display:none;">
                        <input type="hidden" name="pair-esai-topik-id" id="pair-esai-topik-id" value="0">
                        <input type="hidden" name="pair-esai-jml" id="pair-esai-jml" value="5">
                        <input type="hidden" name="pair-esai-poin" id="pair-esai-poin" value="20">

                        <div style="font-size:13px; font-weight:700; color:#4c1d95;">
                            <i class="fa fa-magic"></i> Pasangan Soal Esai Terdeteksi: <span id="pair-esai-topic-label"></span>
                        </div>

                        <div class="pair-mode-pills" id="pair-mode-pills">
                            <label class="pair-mode-pill active">
                                <input type="radio" name="pair-mode" value="pisah" checked> Pisah 2 Tes (PG + Esai)
                            </label>
                            <label class="pair-mode-pill">
                                <input type="radio" name="pair-mode" value="gabung"> Gabung 1 Tes (PG + Esai)
                            </label>
                            <label class="pair-mode-pill">
                                <input type="radio" name="pair-mode" value="utama_saja"> PG Saja
                            </label>
                        </div>

                        <div id="pair-esai-pisah-fields" class="row">
                            <div class="col-md-5">
                                <div class="form-group" style="margin-bottom:4px;">
                                    <label style="font-size:12px;">Nama Kartu Tes Esai</label>
                                    <input type="text" class="form-control input-sm" name="pair-esai-nama" id="pair-esai-nama">
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group" style="margin-bottom:4px;">
                                    <label style="font-size:12px;">Durasi Esai (Menit)</label>
                                    <input type="number" min="5" class="form-control input-sm" name="pair-esai-durasi" id="pair-esai-durasi" value="30">
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-6">
                                <div class="form-group" style="margin-bottom:4px;">
                                    <label style="font-size:12px;">Jendela Waktu Esai</label>
                                    <select class="form-control input-sm" name="pair-esai-jadwal" id="pair-esai-jadwal">
                                        <option value="sama">Sama dengan jadwal PG</option>
                                        <option value="menyusul">Menyusul setelah durasi PG</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="pair-esai-gabung-info" style="display:none; font-size:12.5px; color:#5b21b6;">
                            <i class="fa fa-info-circle"></i> Soal PG dan Esai akan digabung ke dalam <strong>1 kartu tes</strong> yang sama dengan durasi di bawah.
                        </div>
                    </div>
                    <?php } ?>

                    <!-- STEP 2: NAMA TES & JADWAL WAKTU -->
                    <div class="section-step">
                        <span class="step-left"><span class="step-num"><?php echo empty($is_edit) ? '2' : '1'; ?></span> Nama Tes &amp; Jadwal Waktu</span>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Nama Tes (tampil di kartu siswa) <span class="text-danger">*</span></label>
                                <input type="text" name="tambah-nama" id="tambah-nama" class="form-control" placeholder="Otomatis terisi saat memilih topik, bisa diedit..." required />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Durasi Pengerjaan (Menit) <span class="text-danger">*</span></label>
                                <div style="display:flex; gap:6px; align-items:center;">
                                    <input type="number" min="5" name="tambah-waktu" id="tambah-waktu" class="form-control" value="60" style="width:95px;" required />
                                    <div class="preset-bar" style="margin-top:0;">
                                        <button type="button" class="btn btn-default btn-xs" onclick="$('#tambah-waktu').val(30)">30m</button>
                                        <button type="button" class="btn btn-default btn-xs" onclick="$('#tambah-waktu').val(45)">45m</button>
                                        <button type="button" class="btn btn-default btn-xs" onclick="$('#tambah-waktu').val(60)">60m</button>
                                        <button type="button" class="btn btn-default btn-xs" onclick="$('#tambah-waktu').val(90)">90m</button>
                                        <button type="button" class="btn btn-default btn-xs" onclick="$('#tambah-waktu').val(120)">120m</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group" style="margin-bottom:6px;">
                                <label>Tanggal Ujian</label>
                                <input type="date" name="tgl-mulai" id="tgl-mulai" class="form-control input-sm" value="<?php echo html_escape($default_tgl_mulai); ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group" style="margin-bottom:6px;">
                                <label>Jam Buka Sesi (24 Jam)</label>
                                <div class="input-group input-group-sm jam-picker-wrap">
                                    <input type="text" name="jam-mulai" id="jam-mulai" class="form-control jam-24" maxlength="5" inputmode="numeric" pattern="^([01]\d|2[0-3]):[0-5]\d$" placeholder="07:30" title="Format 24 jam, contoh: 07:30 atau 13:45" value="<?php echo html_escape($default_jam_mulai); ?>" required>
                                    <span class="input-group-addon">WIB</span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default jam-picker-btn" title="Pilih jam (24 jam)"><i class="fa fa-caret-down"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group" style="margin-bottom:6px;">
                                <label>Jam Tutup Sesi (24 Jam)</label>
                                <div class="input-group input-group-sm jam-picker-wrap">
                                    <input type="text" name="jam-selesai" id="jam-selesai" class="form-control jam-24" maxlength="5" inputmode="numeric" pattern="^([01]\d|2[0-3]):[0-5]\d$" placeholder="09:30" title="Format 24 jam, contoh: 09:30 atau 15:00" value="<?php echo html_escape($default_jam_selesai); ?>" required>
                                    <span class="input-group-addon">WIB</span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default jam-picker-btn" title="Pilih jam (24 jam)"><i class="fa fa-caret-down"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="wrap-tgl-selesai" style="display:none;">
                            <div class="form-group" style="margin-bottom:6px;">
                                <label>Tanggal Tutup (Multi-hari)</label>
                                <input type="date" name="tgl-selesai" id="tgl-selesai" class="form-control input-sm" value="<?php echo html_escape($default_tgl_selesai); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="preset-bar">
                        <button type="button" class="btn btn-default btn-xs" onclick="setPresetSesi('07:30','09:30')">Sesi 1 (07:30–09:30)</button>
                        <button type="button" class="btn btn-default btn-xs" onclick="setPresetSesi('09:45','11:45')">Sesi 2 (09:45–11:45)</button>
                        <button type="button" class="btn btn-default btn-xs" onclick="setPresetSesi('12:30','14:30')">Sesi 3 (12:30–14:30)</button>
                        <button type="button" class="btn btn-default btn-xs" onclick="setPresetSesi('07:00','15:00')">Seharian (07:00–15:00)</button>
                        <label style="font-weight:normal; font-size:12px; margin:0 0 0 8px; cursor:pointer;">
                            <input type="checkbox" name="multi-hari" id="chk-multi-hari" value="1"> Multi-hari (Susulan)
                        </label>
                    </div>

                    <!-- STEP 3: PILIH KELAS PESERTA -->
                    <div class="section-step">
                        <span class="step-left">
                            <span class="step-num"><?php echo empty($is_edit) ? '3' : '2'; ?></span>
                            Pilih Kelas Peserta
                            <span id="badge-kelas-count" class="label label-success" style="text-transform:none;">0 kelas dipilih</span>
                        </span>
                        <div class="preset-bar" style="margin-top:0; text-transform:none;">
                            <button type="button" class="btn btn-default btn-xs" onclick="pilihKelasTingkat('X')">Kelas X</button>
                            <button type="button" class="btn btn-default btn-xs" onclick="pilihKelasTingkat('XI')">Kelas XI</button>
                            <button type="button" class="btn btn-default btn-xs" onclick="pilihKelasTingkat('XII')">Kelas XII</button>
                            <button type="button" class="btn btn-info btn-xs" onclick="pilihSemuaKelas(true)">Pilih Semua</button>
                            <button type="button" class="btn btn-default btn-xs" onclick="pilihSemuaKelas(false)">Reset</button>
                        </div>
                    </div>

                    <div class="kelas-check-grid" id="kelas-check-grid">
                        <?php if(!empty($groups_list)){ foreach($groups_list as $g){
                            $checked = in_array((int)$g->grup_id, $selected_groups, true);
                        ?>
                            <label class="kelas-check-item <?php echo $checked ? 'checked' : ''; ?>" data-nama="<?php echo html_escape($g->grup_nama); ?>">
                                <input type="checkbox" name="tambah-group[]" class="chk-kelas-tes" value="<?php echo (int)$g->grup_id; ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                <span><?php echo html_escape($g->grup_nama); ?></span>
                                <small class="text-muted">(<?php echo (int)$g->jml_peserta; ?>)</small>
                            </label>
                        <?php } } else { ?>
                            <span class="text-danger">Belum ada kelas terdaftar. Tambahkan kelas di menu Peserta &amp; Kelas terlebih dahulu.</span>
                        <?php } ?>
                    </div>

                    <!-- PENGATURAN LANJUTAN (TERTUTUP SECARA DEFAULT) -->
                    <div>
                        <a data-toggle="collapse" href="#collapse-lanjutan" class="advanced-toggle">
                            <i class="fa fa-sliders"></i> Pengaturan Lanjutan (Opsional: Jumlah Soal, Acak, Poin, Token &amp; Hasil)
                        </a>

                        <div id="collapse-lanjutan" class="collapse" style="margin-top:10px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:12px 14px;">
                            <?php if(empty($is_edit)){ ?>
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Tipe Soal Utama</label>
                                        <select class="form-control input-sm" id="smart-tipe" name="soal-tipe">
                                            <option value="1">Pilihan Ganda</option>
                                            <option value="2">Esai / Uraian</option>
                                            <option value="3">Jawaban Singkat</option>
                                            <option value="0">Semua Tipe</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Jumlah Soal</label>
                                        <input type="number" min="1" class="form-control input-sm" id="smart-jml" name="soal-jml" value="40">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6" id="wrap-smart-opsi">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Jml Opsi PG</label>
                                        <input type="number" min="1" max="10" class="form-control input-sm" id="smart-opsi" name="soal-jml-jawaban" value="5">
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-6">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Pengacakan Soal</label>
                                        <div style="padding-top:4px;">
                                            <label style="font-weight:normal; font-size:12.5px; margin-right:12px; cursor:pointer;">
                                                <input type="checkbox" name="soal-acak-soal" id="smart-acak-soal" value="1" checked> Acak Soal
                                            </label>
                                            <label style="font-weight:normal; font-size:12.5px; margin-right:12px; cursor:pointer;" id="lbl-smart-acak-jwb">
                                                <input type="checkbox" name="soal-acak-jawaban" id="smart-acak-jawaban" value="1" checked> Acak Opsi
                                            </label>
                                            <label style="font-weight:normal; font-size:12.5px; cursor:pointer;">
                                                <input type="checkbox" name="pair-esai-acak" id="pair-esai-acak" value="1"> Acak Esai
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="margin:6px 0 12px 0;">
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Poin Dasar per Soal <span id="badge-max-score" class="max-score-pill pull-right">Max: 100</span></label>
                                        <input type="number" step="0.01" name="tambah-poin" id="tambah-poin" class="form-control input-sm" value="2.50" required />
                                        <div class="preset-bar" id="pg-auto-skala-info" style="font-size:11.5px; color:#065f46; font-weight:600;">
                                            <i class="fa fa-check-circle text-success"></i> PG Otomatis Skala 100 (Nilai = Benar &divide; Jumlah Soal &times; 100)
                                        </div>
                                        <div class="preset-bar" id="non-pg-preset-bar" style="display:none;">
                                            <button type="button" class="btn btn-success btn-xs" onclick="hitungPoinSkala100()">Skala 100</button>
                                            <button type="button" class="btn btn-default btn-xs" onclick="setPoinMentah()">Skor Mentah (1.00)</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Poin Salah</label>
                                        <input type="number" step="0.01" name="tambah-poin-salah" id="tambah-poin-salah" class="form-control input-sm" value="0.00">
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Poin Kosong</label>
                                        <input type="number" step="0.01" name="tambah-poin-kosong" id="tambah-poin-kosong" class="form-control input-sm" value="0.00">
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-4">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Kesulitan</label>
                                        <select class="form-control input-sm" id="smart-kesulitan" name="soal-kesulitan">
                                            <option value="1" selected>1 (Standar)</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label style="font-size:12px;">Deskripsi</label>
                                        <input type="text" name="tambah-deskripsi" id="tambah-deskripsi" class="form-control input-sm" placeholder="Otomatis">
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; flex-wrap:wrap; gap:16px; margin-top:2px;">
                                <label style="font-weight:600; font-size:12.5px; cursor:pointer; margin:0;">
                                    <input type="checkbox" name="tambah-token" id="tambah-token" value="1"> Wajib Token saat Mulai Tes
                                </label>
                                <label style="font-weight:normal; font-size:12.5px; cursor:pointer; margin:0;">
                                    <input type="checkbox" name="tambah-tunjukkan-hasil" id="tambah-tunjukkan-hasil" value="1"> Tunjukkan Nilai ke Siswa
                                </label>
                                <label style="font-weight:normal; font-size:12.5px; cursor:pointer; margin:0;">
                                    <input type="checkbox" name="tambah-detail-hasil" id="tambah-detail-hasil" value="1"> Tunjukkan Detail Jawaban
                                </label>
                                <label style="font-weight:600; font-size:12.5px; cursor:pointer; margin:0;">
                                    <input type="checkbox" name="tambah-wajib-jawab" id="tambah-wajib-jawab" value="1"> Wajib Jawab Semua Soal
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <?php if(empty($is_edit)){ ?>
                        <input type="hidden" id="after-save-action" value="lagi">
                        <button type="submit" id="btn-simpan-lagi" class="btn btn-success" onclick="$('#after-save-action').val('lagi')">
                            <i class="fa fa-plus-circle"></i> Simpan &amp; Tambah Lagi
                        </button>
                        <button type="submit" id="btn-simpan-daftar" class="btn btn-primary" style="margin-left:6px;" onclick="$('#after-save-action').val('daftar')">
                            <i class="fa fa-save"></i> Simpan &amp; Kembali ke Daftar Tes
                        </button>
                    <?php }else{ ?>
                        <button type="submit" id="btn-tambah-simpan" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Perubahan Jadwal
                        </button>
                    <?php } ?>
                    <a href="<?php echo site_url('manager/tes_daftar'); ?>" class="btn btn-default pull-right">Batal</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PANEL KELOLA PAKET SOAL (UNTUK MODE EDIT) -->
    <div class="row <?php echo empty($is_edit) ? 'hide' : ''; ?>" id="kolom-soal">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-list-alt"></i> Paket Soal Terpasang <span id="judul-tambah-soal"></span>
                        <span class="modul-active-badge" style="font-size:11.5px;"><i class="fa fa-database"></i> Modul Aktif: <?php echo html_escape($active_modul_nama); ?></span>
                    </h3>
                    <div class="box-tools pull-right">
                        <a data-toggle="collapse" href="#collapse-tambah-paket-soal" class="btn btn-info btn-xs">
                            <i class="fa fa-plus"></i> Tambah Paket Soal Lain (Opsional)
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <!-- Daftar paket soal yang benar-benar terpasang di tes ini -->
                    <table id="table-soal" class="table table-bordered table-hover table-condensed" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Topik &amp; Konfigurasi Aktif</th>
                                <th style="width:85px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Form opsional untuk menambah paket soal tambahan (tertutup secara default) -->
                    <div id="collapse-tambah-paket-soal" class="collapse" style="margin-top:14px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:6px; padding:12px 14px;">
                        <p style="font-size:12.5px; font-weight:700; color:#334155; margin-bottom:10px;">
                            <i class="fa fa-plus-circle text-info"></i> Tambah Paket Soal Tambahan ke Tes Ini
                        </p>
                        <?php echo form_open($url.'/tambah_soal','id="form-tambah-soal" class="form-horizontal"'); ?>
                        <div id="form-pesan-soal"></div>
                        <input type="hidden" name="soal-tes-id" id="soal-tes-id" value="<?php echo !empty($edit_tes_id) ? (int)$edit_tes_id : ''; ?>">
                        <input type="hidden" id="soal-modul" name="soal-modul" value="<?php echo (int)$active_modul_id; ?>">
                        <input type="hidden" id="soal-kesulitan" name="soal-kesulitan" value="1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Pilih Topik</label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm" id="soal-topik" name="soal-topik" style="width:100%;">
                                    <option value="kosong">-- Cari topik / mapel... --</option>
                                </select>
                            </div>
                        </div>

                        <div id="wrap-edit-soal-detail" style="display:none;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tipe Soal</label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm" id="soal-tipe" name="soal-tipe">
                                        <option value="1">Pilihan Ganda</option>
                                        <option value="2">Esai</option>
                                        <option value="3">Jawaban Singkat</option>
                                        <option value="0">Semua</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jml Soal</label>
                                <div class="col-sm-3">
                                    <input type="number" min="1" name="soal-jml" id="soal-jml" class="form-control input-sm" value="">
                                </div>
                            </div>
                            <div class="form-group" id="row-edit-soal-opsi">
                                <label class="col-sm-3 control-label">Jml Opsi</label>
                                <div class="col-sm-3">
                                    <input type="number" min="1" name="soal-jml-jawaban" id="soal-jml-jawaban" class="form-control input-sm" value="5">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pengacakan</label>
                                <div class="col-sm-7" style="padding-top:5px;">
                                    <label style="font-weight:normal; margin-right:12px; cursor:pointer;"><input type="checkbox" name="soal-acak-soal" id="soal-acak-soal" value="1"> Acak Soal</label>
                                    <label id="lbl-edit-acak-opsi" style="font-weight:normal; cursor:pointer;"><input type="checkbox" name="soal-acak-jawaban" id="soal-acak-jawaban" value="1"> Acak Opsi</label>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <div class="col-sm-offset-3 col-sm-7">
                                    <button type="submit" id="btn-tambah-soal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Pasang Paket Soal Ini</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating 24-hour time picker menu (bebas pilih semua jam 06:00-23:45 tanpa terfilter) -->
    <div id="jam-24-floating-menu">
        <?php for($h = 6; $h <= 23; $h++){ foreach(array('00','15','30','45') as $m){ ?>
            <div class="jam-opt" data-val="<?php echo sprintf('%02d:%s', $h, $m); ?>"><?php echo sprintf('%02d:%s', $h, $m); ?></div>
        <?php } } ?>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    var isEditMode = <?php echo !empty($is_edit) ? 'true' : 'false'; ?>;
    var smartTopiksData = [];
    var activeTypeFilter = 'all';
    var sessionCreatedCount = 0;

    function normalizeJam24(val){
        var s = $.trim(String(val || '')).replace(/\./g, ':');
        var m;
        if(/^\d{3,4}$/.test(s)){
            if(s.length === 3) s = '0' + s;
            s = s.substr(0, 2) + ':' + s.substr(2, 2);
        }
        m = s.match(/^(\d{1,2}):(\d{1,2})$/);
        if(!m) return s;
        var hh = parseInt(m[1], 10);
        var mm = parseInt(m[2], 10);
        if(hh < 0 || hh > 23 || mm < 0 || mm > 59) return s;
        return (hh < 10 ? '0' : '') + hh + ':' + (mm < 10 ? '0' : '') + mm;
    }

    function escHtml(s){
        if(s === null || s === undefined) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function setPresetSesi(mulai, selesai){
        $('#jam-mulai').val(mulai);
        $('#jam-selesai').val(selesai);
    }

    function updateKelasCounter(){
        var n = $('.chk-kelas-tes:checked').length;
        $('#badge-kelas-count').text(n + ' kelas dipilih');
        $('.kelas-check-item').each(function(){
            var cb = $(this).find('.chk-kelas-tes');
            $(this).toggleClass('checked', cb.is(':checked'));
        });
    }

    function pilihSemuaKelas(state){
        $('.chk-kelas-tes').prop('checked', state);
        updateKelasCounter();
    }

    function pilihKelasTingkat(tingkat){
        var re = new RegExp('^' + tingkat + '(\\s|\\-|$)', 'i');
        $('.kelas-check-item').each(function(){
            var nama = String($(this).attr('data-nama') || '').trim();
            if(re.test(nama)){
                $(this).find('.chk-kelas-tes').prop('checked', true);
            }
        });
        updateKelasCounter();
    }

    function syncPgAutoScoringUI(){
        if(isEditMode){
            $('#pg-auto-skala-info').hide();
            $('#non-pg-preset-bar').show();
            return;
        }
        var tipe = parseInt($('#smart-tipe').val(), 10) || 1;
        var mode = $('input[name="pair-mode"]:checked').val() || 'utama_saja';
        var isGabung = ($('#box-pair-esai').is(':visible') && mode === 'gabung');
        if(tipe === 1 && !isGabung){
            $('#tambah-poin').prop('readonly', true);
            $('#tambah-poin-salah').val('0.00').prop('readonly', true);
            $('#tambah-poin-kosong').val('0.00').prop('readonly', true);
            $('#pg-auto-skala-info').show();
            $('#non-pg-preset-bar').hide();
            hitungPoinSkala100();
            $('#badge-max-score').text('Max: 100');
        }else{
            $('#tambah-poin').prop('readonly', false);
            $('#tambah-poin-salah').prop('readonly', false);
            $('#tambah-poin-kosong').prop('readonly', false);
            $('#pg-auto-skala-info').hide();
            $('#non-pg-preset-bar').show();
            updateMaxScorePreview();
        }
    }

    function updateMaxScorePreview(){
        var jml = parseInt($('#smart-jml').val(), 10) || 0;
        var poin = parseFloat($('#tambah-poin').val()) || 0;
        if(isEditMode){
            $('#badge-max-score').text('Poin/Soal: ' + poin.toFixed(2));
        }else{
            var tipe = parseInt($('#smart-tipe').val(), 10) || 1;
            var mode = $('input[name="pair-mode"]:checked').val() || 'utama_saja';
            var isGabung = ($('#box-pair-esai').is(':visible') && mode === 'gabung');
            if(tipe === 1 && !isGabung){
                $('#badge-max-score').text('Max: 100');
                return;
            }
            if(isGabung){
                jml += (parseInt($('#pair-esai-jml').val(), 10) || 0);
            }
            var maxs = (jml * poin);
            $('#badge-max-score').text('Max: ' + maxs.toFixed(2).replace(/\.00$/, ''));
        }
    }

    function hitungPoinSkala100(){
        var jml = parseInt($('#smart-jml').val(), 10) || 0;
        var mode = $('input[name="pair-mode"]:checked').val();
        if(!isEditMode && $('#box-pair-esai').is(':visible') && mode === 'gabung'){
            jml += (parseInt($('#pair-esai-jml').val(), 10) || 0);
        }
        if(jml > 0){
            $('#tambah-poin').val((100 / jml).toFixed(2));
        }
        updateMaxScorePreview();
    }

    function setPoinMentah(){
        $('#tambah-poin').val('1.00');
        updateMaxScorePreview();
    }

    function updatePairModeUI(){
        var mode = $('input[name="pair-mode"]:checked').val() || 'utama_saja';
        $('#pair-mode-pills .pair-mode-pill').each(function(){
            var r = $(this).find('input[type="radio"]');
            $(this).toggleClass('active', r.is(':checked'));
        });
        if(mode === 'pisah'){
            $('#pair-esai-pisah-fields').show();
            $('#pair-esai-gabung-info').hide();
        }else if(mode === 'gabung'){
            $('#pair-esai-pisah-fields').hide();
            $('#pair-esai-gabung-info').show();
        }else{
            $('#pair-esai-pisah-fields').hide();
            $('#pair-esai-gabung-info').hide();
        }
        hitungPoinSkala100();
    }

    function loadSmartTopiks(modulId){
        if(!modulId || modulId <= 0){
            $('#smart-topik').html('<option value="">Belum ada Modul Aktif di Data Modul</option>');
            return;
        }
        $('#smart-topik').html('<option value="">Memuat daftar topik...</option>');
        $.getJSON('<?php echo site_url($url."/get_topik_detail_by_modul"); ?>/' + modulId, function(res){
            if(res && res.status == 1){
                smartTopiksData = res.topiks || [];
                renderSmartTopikOptions();
            }
        });
    }

    function renderSmartTopikOptions(){
        var prevVal = $('#smart-topik').val();
        var html = '<option value="">-- Ketik untuk mencari topik / mata pelajaran... --</option>';
        var count = 0;

        for(var i = 0; i < smartTopiksData.length; i++){
            var t = smartTopiksData[i];
            if(t.jml_total <= 0) continue;

            if(activeTypeFilter === 'pg' && t.jml_pg <= 0) continue;
            if(activeTypeFilter === 'esai' && t.jml_esai <= 0) continue;

            var tag = '';
            if(t.jml_pg > 0 && t.paired_esai){
                tag = '[' + t.jml_pg + ' PG + ' + t.paired_esai.jml_esai + ' Esai]';
            }else if(t.jml_pg > 0 && t.jml_esai > 0){
                tag = '[' + t.jml_pg + ' PG + ' + t.jml_esai + ' Esai]';
            }else if(t.jml_pg > 0){
                tag = '[' + t.jml_pg + ' PG]';
            }else if(t.jml_esai > 0){
                tag = '[' + t.jml_esai + ' Esai]';
            }else{
                tag = '[' + t.jml_total + ' Soal]';
            }
            html += '<option value="' + t.topik_id + '">' + escHtml(t.topik_nama) + ' ' + tag + '</option>';
            count++;
        }

        if(count === 0){
            html = '<option value="">Tidak ada topik berisi soal pada filter ini</option>';
        }

        if($('#smart-topik').hasClass('select2-hidden-accessible')){
            $('#smart-topik').select2('destroy');
        }
        $('#smart-topik').html(html);
        if(prevVal) $('#smart-topik').val(prevVal);

        $('#smart-topik').select2({
            placeholder: 'Ketik untuk mencari topik / mata pelajaran...',
            allowClear: true,
            width: '100%'
        });
    }

    function onSelectSmartTopik(){
        var tid = parseInt($('#smart-topik').val(), 10) || 0;
        var found = null;
        for(var i = 0; i < smartTopiksData.length; i++){
            if(smartTopiksData[i].topik_id === tid){ found = smartTopiksData[i]; break; }
        }
        if(!found){
            $('#smart-topik-summary').hide().html('');
            $('#box-pair-esai').hide();
            return;
        }

        // Auto-isi konfigurasi soal di Pengaturan Lanjutan
        $('#smart-tipe').val(found.default_tipe);
        $('#smart-jml').val(found.default_jml);
        $('#smart-opsi').val(found.default_opsi);

        var ringkasMode = '';
        if(found.default_tipe === 2 || found.default_tipe === 3){
            $('#smart-acak-soal').prop('checked', false);
            $('#smart-acak-jawaban').prop('checked', false);
            $('#wrap-smart-opsi').hide();
            $('#lbl-smart-acak-jwb').hide();
            $('#tambah-waktu').val(30);
            ringkasMode = found.default_jml + ' Esai &bull; Urut &bull; Skala 100';
        }else{
            $('#smart-acak-soal').prop('checked', true);
            $('#smart-acak-jawaban').prop('checked', true);
            $('#wrap-smart-opsi').show();
            $('#lbl-smart-acak-jwb').show();
            $('#tambah-waktu').val(60);
            ringkasMode = found.default_jml + ' PG (' + found.default_opsi + ' opsi) &bull; Acak Soal &amp; Opsi &bull; Skala 100';
        }

        // Auto-isi nama tes
        $('#tambah-nama').val(found.topik_nama);

        var badges = [];
        if(found.jml_pg > 0) badges.push('<span class="label label-success">' + found.jml_pg + ' PG</span>');
        if(found.jml_esai > 0) badges.push('<span class="label" style="background:#4f46e5;">' + found.jml_esai + ' Esai</span>');
        if(found.jml_singkat > 0) badges.push('<span class="label label-warning">' + found.jml_singkat + ' Singkat</span>');

        $('#smart-topik-summary')
            .html(badges.join(' ') + ' <span class="text-muted">Otomatis: ' + ringkasMode + ' (bisa diubah di Pengaturan Lanjutan)</span>')
            .show();

        // Deteksi Pasangan Esai (Default: Pisah 2 Tes)
        if(found.paired_esai){
            var pe = found.paired_esai;
            $('#pair-esai-topik-id').val(pe.topik_id);
            $('#pair-esai-topic-label').text(pe.topik_nama + ' (' + pe.jml_esai + ' soal)');
            $('#pair-esai-nama').val(pe.topik_nama);
            $('#pair-esai-jml').val(pe.jml_esai);
            $('#pair-esai-durasi').val(30);
            $('#pair-esai-poin').val(pe.jml_esai > 0 ? (100 / pe.jml_esai).toFixed(2) : '20.00');
            $('input[name="pair-mode"][value="pisah"]').prop('checked', true);
            $('#box-pair-esai').slideDown(140);
            updatePairModeUI();
        }else{
            $('#pair-esai-topik-id').val(0);
            $('input[name="pair-mode"][value="utama_saja"]').prop('checked', true);
            $('#box-pair-esai').hide();
            syncPgAutoScoringUI();
        }
    }

    function appendSessionCreated(summary){
        if(!summary) return;
        sessionCreatedCount++;
        $('#session-created-count').text(sessionCreatedCount);
        var label = '<strong>' + escHtml(summary.nama) + '</strong>';
        if(summary.mode === 'pisah' && summary.pair_nama){
            label += ' + <strong>' + escHtml(summary.pair_nama) + '</strong> <span class="label" style="background:#5b21b6;">2 Tes (PG &amp; Esai)</span>';
        }else if(summary.mode === 'gabung'){
            label += ' <span class="label label-warning">Gabung PG + Esai</span>';
        }
        var li = '<li>' + label + ' &mdash; ' + escHtml(summary.waktu) + ' (' + summary.jml_kelas + ' kelas)</li>';
        $('#session-created-list').prepend(li);
        $('#session-created-box').slideDown(150);
    }

    function refresh_table(){
        if($.fn.DataTable.isDataTable('#table-soal')){
            $('#table-soal').dataTable().fnReloadAjax();
        }
    }

    var editTopiksData = [];

    function applyEditSoalTypeUI(tipe, keepCheckboxes){
        tipe = parseInt(tipe, 10);
        if(tipe === 2 || tipe === 3){
            $('#row-edit-soal-opsi').hide();
            $('#soal-jml-jawaban').val(1);
            $('#lbl-edit-acak-opsi').hide();
            $('#soal-acak-jawaban').prop('checked', false);
            if(!keepCheckboxes){
                $('#soal-acak-soal').prop('checked', false);
            }
        }else{
            $('#row-edit-soal-opsi').show();
            if((parseInt($('#soal-jml-jawaban').val(), 10) || 0) < 2){
                $('#soal-jml-jawaban').val(5);
            }
            $('#lbl-edit-acak-opsi').show();
            if(!keepCheckboxes){
                $('#soal-acak-soal').prop('checked', true);
                $('#soal-acak-jawaban').prop('checked', true);
            }
        }
    }

    function onSelectEditTopik(){
        var tid = parseInt($('#soal-topik').val(), 10) || 0;
        var found = null;
        for(var i = 0; i < editTopiksData.length; i++){
            if(editTopiksData[i].topik_id === tid){ found = editTopiksData[i]; break; }
        }
        if(!found){
            $('#wrap-edit-soal-detail').hide();
            return;
        }
        $('#soal-tipe').val(found.default_tipe);
        $('#soal-jml').val(found.default_jml);
        $('#soal-jml-jawaban').val(found.default_opsi);
        applyEditSoalTypeUI(found.default_tipe, false);
        $('#wrap-edit-soal-detail').slideDown(120);
    }

    function refresh_topik(){
        var modul = $('#soal-modul').val();
        if(!modul) return;
        $.getJSON('<?php echo site_url($url."/get_topik_detail_by_modul"); ?>/' + modul, function(res){
            if(res && res.status == 1){
                editTopiksData = res.topiks || [];
                var html = '<option value="kosong">-- Cari topik / mapel... --</option>';
                for(var i = 0; i < editTopiksData.length; i++){
                    var t = editTopiksData[i];
                    if(t.jml_total <= 0) continue;
                    var tag = (t.jml_pg > 0 && t.jml_esai > 0) ? '[' + t.jml_pg + ' PG + ' + t.jml_esai + ' Esai]'
                            : (t.jml_pg > 0 ? '[' + t.jml_pg + ' PG]' : (t.jml_esai > 0 ? '[' + t.jml_esai + ' Esai]' : '[' + t.jml_total + ']'));
                    html += '<option value="' + t.topik_id + '">' + escHtml(t.topik_nama) + ' ' + tag + '</option>';
                }
                if($('#soal-topik').hasClass('select2-hidden-accessible')){
                    $('#soal-topik').select2('destroy');
                }
                $('#soal-topik').html(html).select2({
                    placeholder: 'Cari topik / mapel...',
                    width: '100%'
                });
            }
        });
    }

    function edit(id){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url($url."/get_by_id"); ?>/' + id, function(data){
            if(data.data == 1){
                $('#tambah-id').val(data.id);
                $('#soal-tes-id').val(data.id);
                $('#tambah-nama').val(data.nama);
                $('#tambah-nama-lama').val(data.nama);
                $('#tambah-deskripsi').val(data.deskripsi);
                $('#tambah-waktu').val(data.waktu);
                $('#tambah-poin').val(data.poin);
                $('#tambah-poin-kosong').val(data.poin_kosong);
                $('#tambah-poin-salah').val(data.poin_salah);
                $('#tambah-rentang-waktu').val(data.rentang_waktu);
                $('#tgl-mulai').val(data.tgl_mulai);
                $('#jam-mulai').val(data.jam_mulai);
                $('#tgl-selesai').val(data.tgl_selesai);
                $('#jam-selesai').val(data.jam_selesai);

                if(data.tgl_mulai !== data.tgl_selesai){
                    $('#chk-multi-hari').prop('checked', true);
                    $('#wrap-tgl-selesai').show();
                }

                $('#tambah-tunjukkan-hasil').prop('checked', data.tunjukkan_hasil == 1);
                $('#tambah-detail-hasil').prop('checked', data.detail_hasil == 1);
                $('#tambah-token').prop('checked', data.token == 1);
                $('#tambah-wajib-jawab').prop('checked', data.wajib_jawab == 1);

                updateMaxScorePreview();
                refresh_topik();
                refresh_table();
                $('#kolom-soal').removeClass('hide');
            }
            $("#modal-proses").modal('hide');
        });
    }

    function hapus_soal(id){
        if(!confirm('Hapus paket soal ini dari Tes?')) return;
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url($url."/hapus_soal_by_id"); ?>/' + id, function(data){
            if(data.data == 1){
                notify_success(data.pesan);
                refresh_table();
            }else{
                notify_error(data.pesan);
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
        updateKelasCounter();

        $(document).on('change', '.chk-kelas-tes', function(){
            updateKelasCounter();
        });

        $(document).on('blur change', '.jam-24', function(){
            $(this).val(normalizeJam24($(this).val()));
        });

        $(document).on('focus', '.jam-24', function(){
            this.select();
        });

        var activeJamInput = null;
        $(document).on('click', '.jam-picker-btn', function(e){
            e.stopPropagation();
            var inp = $(this).closest('.jam-picker-wrap').find('input.jam-24');
            var menu = $('#jam-24-floating-menu');
            if(menu.is(':visible') && activeJamInput && activeJamInput[0] === inp[0]){
                menu.hide();
                activeJamInput = null;
                return;
            }
            activeJamInput = inp;
            var rect = inp[0].getBoundingClientRect();
            var curVal = normalizeJam24(inp.val());
            menu.find('.jam-opt').removeClass('selected');
            var selOpt = menu.find('.jam-opt[data-val="' + curVal + '"]').addClass('selected');
            var topPos = rect.bottom + 3;
            if(topPos + 220 > window.innerHeight){
                topPos = Math.max(8, rect.top - 224);
            }
            menu.css({ left: rect.left + 'px', top: topPos + 'px' }).show();
            if(selOpt.length){
                menu.scrollTop(selOpt[0].offsetTop - 70);
            }
        });

        $(document).on('click', '#jam-24-floating-menu .jam-opt', function(e){
            e.stopPropagation();
            if(activeJamInput){
                activeJamInput.val($(this).data('val')).trigger('change');
            }
            $('#jam-24-floating-menu').hide();
            activeJamInput = null;
        });

        $(document).on('click scroll', function(){
            $('#jam-24-floating-menu').hide();
            activeJamInput = null;
        });

        $('#chk-multi-hari').change(function(){
            if(this.checked){
                if(!$('#tgl-selesai').val()) $('#tgl-selesai').val($('#tgl-mulai').val());
                $('#wrap-tgl-selesai').show();
            }else{
                $('#tgl-selesai').val($('#tgl-mulai').val());
                $('#wrap-tgl-selesai').hide();
            }
        });

        $('#tgl-mulai').change(function(){
            if(!$('#chk-multi-hari').is(':checked')){
                $('#tgl-selesai').val($(this).val());
            }
        });

        $('#smart-jml, #tambah-poin').on('keyup input change', function(){
            syncPgAutoScoringUI();
        });

        $(document).on('change', 'input[name="pair-mode"]', function(){
            updatePairModeUI();
            syncPgAutoScoringUI();
        });

        $('#smart-tipe').change(function(){
            var v = parseInt($(this).val(), 10);
            if(v === 2 || v === 3){
                $('#wrap-smart-opsi').hide();
                $('#lbl-smart-acak-jwb').hide();
                $('#smart-acak-jawaban').prop('checked', false);
            }else{
                $('#wrap-smart-opsi').show();
                $('#lbl-smart-acak-jwb').show();
            }
            syncPgAutoScoringUI();
        });
        syncPgAutoScoringUI();

        if(!isEditMode){
            loadSmartTopiks($('#smart-modul').val());

            $('#topik-type-filter .filter-mini-btn').click(function(){
                $('#topik-type-filter .filter-mini-btn').removeClass('active');
                $(this).addClass('active');
                activeTypeFilter = $(this).data('filter');
                renderSmartTopikOptions();
            });

            $('#smart-topik').on('change', function(){
                onSelectSmartTopik();
            });
        }else{
            $('#soal-topik').on('change', function(){
                onSelectEditTopik();
            });
            $('#soal-tipe').on('change', function(){
                applyEditSoalTypeUI($(this).val(), false);
            });
        }

        $('#form-tambah-tes').submit(function(){
            if(!isEditMode && !$('#smart-topik').val()){
                $('#form-pesan-tes').html(pesan_err('Pilih Topik / Mata Pelajaran terlebih dahulu!'));
                return false;
            }
            if($('.chk-kelas-tes:checked').length === 0){
                $('#form-pesan-tes').html(pesan_err('Pilih minimal satu Kelas peserta terlebih dahulu!'));
                return false;
            }
            $("#modal-proses").modal('show');
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                cache: false,
                success: function(respon){
                    $("#modal-proses").modal('hide');
                    var obj = $.parseJSON(respon);
                    if(obj.status == 1){
                        $('#form-pesan-tes').html('');
                        notify_success(obj.pesan);
                        if(!isEditMode){
                            var act = $('#after-save-action').val();
                            if(act === 'lagi'){
                                appendSessionCreated(obj.summary);
                                // Reset topik & nama saja — tanggal, jam, durasi & kelas tetap dipertahankan agar ngebut!
                                $('#smart-topik').val('').trigger('change');
                                $('#tambah-nama').val('');
                                $('#tambah-deskripsi').val('');
                                $('#smart-topik-summary').hide().html('');
                                $('#box-pair-esai').hide();
                                $('html, body').animate({scrollTop: 0}, 180);
                            }else{
                                window.location.href = '<?php echo site_url("manager/tes_daftar"); ?>';
                            }
                        }else{
                            $('#tambah-nama-lama').val(obj.tes_nama);
                            window.location.href = '<?php echo site_url("manager/tes_daftar"); ?>';
                        }
                    }else{
                        $('#form-pesan-tes').html(pesan_err(obj.pesan));
                        $('html, body').animate({scrollTop: 0}, 180);
                    }
                },
                error: function(){
                    $("#modal-proses").modal('hide');
                    $('#form-pesan-tes').html(pesan_err('Gagal menghubungi server.'));
                }
            });
            return false;
        });

        $('#form-tambah-soal').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                url: '<?php echo site_url($url."/tambah_soal"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                cache: false,
                success: function(respon){
                    $("#modal-proses").modal('hide');
                    var obj = $.parseJSON(respon);
                    if(obj.status == 1){
                        refresh_table();
                        $('#form-pesan-soal').html('');
                        notify_success(obj.pesan);
                    }else{
                        $('#form-pesan-soal').html(pesan_err(obj.pesan));
                    }
                }
            });
            return false;
        });

        $('#table-soal').DataTable({
            "paging": false,
            "bProcessing": false,
            "bServerSide": true,
            "searching": false,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth": "35px"},
                {"bSearchable": false, "bSortable": false},
                {"bSearchable": false, "bSortable": false, "sWidth": "75px"}
            ],
            "sAjaxSource": "<?php echo site_url($url.'/get_datatable_soal'); ?>",
            "autoWidth": false,
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "tes-id", "value": $('#soal-tes-id').val() } );
            }
        });

        <?php if(!empty($data_tes)){ echo $data_tes; } ?>
    });
</script>
