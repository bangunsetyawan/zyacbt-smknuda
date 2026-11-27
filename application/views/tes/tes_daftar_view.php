<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Daftar Tes &amp; Jadwal Ujian
		<small>Kelola jadwal tes, ubah cepat jam/durasi, duplikat jadwal, dan pantau hasil ujian</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Tes</li>
	</ol>
</section>

<style>
    .status-pill-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border: 1px solid #d2d6de;
        border-radius: 5px;
        background: #fff;
        color: #333;
        font-size: 12.5px;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
        transition: all 0.12s ease;
    }
    .status-pill:hover {
        border-color: #3c8dbc;
        background: #f0f7fc;
    }
    .status-pill.active {
        background: #3c8dbc;
        border-color: #367fa9;
        color: #fff;
    }
    .status-pill .sp-cnt {
        font-size: 11px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        background: #e8edf2;
        color: #333;
    }
    .status-pill.active .sp-cnt {
        background: rgba(255,255,255,0.22);
        color: #fff;
    }
    #table-tes td {
        vertical-align: middle;
    }
    .tes-nav-tabs {
        display: flex;
        gap: 6px;
        margin-bottom: 14px;
        border-bottom: 2px solid #d2d6de;
        padding-bottom: 0;
    }
    .tes-nav-tab {
        padding: 9px 16px;
        font-size: 13.5px;
        font-weight: 600;
        color: #4b5563;
        background: #f3f4f6;
        border: 1px solid #d2d6de;
        border-bottom: none;
        border-radius: 6px 6px 0 0;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: -2px;
    }
    .tes-nav-tab:hover {
        background: #e5e7eb;
        color: #111827;
        text-decoration: none;
    }
    .tes-nav-tab.active {
        background: #fff;
        color: #059669;
        border-color: #d2d6de;
        border-top: 3px solid #059669;
        border-bottom: 2px solid #fff;
    }
    .master-day-card {
        margin-bottom: 18px;
        border: 1.5px solid #cbd5e1;
        border-radius: 7px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(15,23,42,0.05);
        background: #fff;
    }
    .master-day-head {
        background: #eff6ff;
        border-left: 5px solid #2563eb;
        border-bottom: 1px solid #dbeafe;
        padding: 9px 14px;
        font-weight: 700;
        color: #1e3a8a;
        font-size: 13.5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .master-day-head.day-tone-1 {
        background: #f5f3ff;
        border-left-color: #7c3aed;
        border-bottom-color: #ddd6fe;
        color: #4c1d95;
    }
    .master-day-head.day-tone-2 {
        background: #fffbeb;
        border-left-color: #d97706;
        border-bottom-color: #fde68a;
        color: #92400e;
    }
    .master-day-head.day-tone-3 {
        background: #ecfeff;
        border-left-color: #0891b2;
        border-bottom-color: #a5f3fc;
        color: #155e75;
    }
    .master-day-head.is-today {
        background: #ecfdf5;
        border-left-color: #059669;
        border-bottom-color: #a7f3d0;
        color: #065f46;
    }
    #table-master-plan th {
        font-size: 12px;
        background: #f8fafc;
        white-space: nowrap;
    }
    #table-master-plan td {
        vertical-align: top;
        padding: 7px 6px;
    }
    .mini-preset-row {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        margin-top: 4px;
    }
    .mini-preset-row .btn-xs {
        padding: 1px 5px;
        font-size: 10.5px;
    }
    .modul-pill-mini {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 9px;
        border-radius: 10px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        font-size: 11.5px;
        font-weight: 600;
        margin-left: 8px;
    }
    .jam-picker-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .jam-picker-wrap input.jam-24 {
        width: 58px;
        padding: 4px 5px;
        text-align: center;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .jam-picker-btn {
        height: 30px;
        padding: 0 5px;
        border: 1px solid #d2d6de;
        border-left: none;
        background: #f9fafb;
        color: #4b5563;
        border-radius: 0 3px 3px 0;
        cursor: pointer;
    }
    .jam-picker-btn:hover {
        background: #e5e7eb;
    }
    #jam-24-floating-menu {
        position: fixed;
        z-index: 9999;
        width: 92px;
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
    <!-- Tab Navigasi: Master Jadwal (Pertama) & Daftar Tes (Kedua) -->
    <div class="tes-nav-tabs" id="tes-nav-tabs">
        <a href="javascript:void(0)" class="tes-nav-tab active" data-pane="pane-master" onclick="switchTesTab('pane-master')">
            <i class="fa fa-calendar-check-o"></i> Master Jadwal &amp; Input Massal
        </a>
        <a href="javascript:void(0)" class="tes-nav-tab" data-pane="pane-daftar" onclick="switchTesTab('pane-daftar')">
            <i class="fa fa-list-ul"></i> Daftar Tes
        </a>
    </div>

    <div id="pane-daftar" style="display:none;">
    <!-- Bar Filter Cepat -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary" style="margin-bottom: 14px;">
                <div class="box-body" style="padding: 12px 15px;">
                    <div class="row">
                        <div class="col-md-7" style="margin-bottom: 8px;">
                            <div class="status-pill-bar" id="status-pill-bar">
                                <div class="status-pill active" data-status="semua" onclick="setStatusFilter('semua')">
                                    Semua <span class="sp-cnt" id="cnt-semua"><?php echo (int)$counts['semua']; ?></span>
                                </div>
                                <div class="status-pill" data-status="berlangsung" onclick="setStatusFilter('berlangsung')">
                                    <i class="fa fa-circle text-green" style="font-size:9px;"></i> Berlangsung <span class="sp-cnt" id="cnt-berlangsung"><?php echo (int)$counts['berlangsung']; ?></span>
                                </div>
                                <div class="status-pill" data-status="hari_ini" onclick="setStatusFilter('hari_ini')">
                                    Hari Ini <span class="sp-cnt" id="cnt-hari_ini"><?php echo (int)$counts['hari_ini']; ?></span>
                                </div>
                                <div class="status-pill" data-status="akan_datang" onclick="setStatusFilter('akan_datang')">
                                    Akan Datang <span class="sp-cnt" id="cnt-akan_datang"><?php echo (int)$counts['akan_datang']; ?></span>
                                </div>
                                <div class="status-pill" data-status="berakhir" onclick="setStatusFilter('berakhir')">
                                    Berakhir <span class="sp-cnt" id="cnt-berakhir"><?php echo (int)$counts['berakhir']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div style="display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end; align-items:center;">
                                <select id="filter-group" class="form-control input-sm" style="width:145px;">
                                    <option value="semua">Semua Kelas</option>
                                    <?php if(!empty($groups)){ foreach($groups as $g){ ?>
                                        <option value="<?php echo (int)$g->grup_id; ?>"><?php echo html_escape($g->grup_nama); ?></option>
                                    <?php } } ?>
                                </select>
                                <select id="filter-jenis" class="form-control input-sm" style="width:125px;">
                                    <option value="semua">Semua Jenis</option>
                                    <option value="pg">PG Saja</option>
                                    <option value="esai">Esai Saja</option>
                                    <option value="campuran">Campuran</option>
                                </select>
                                <a href="<?php echo site_url('manager/tes_tambah'); ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus-circle"></i> Buat Jadwal Tes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Tes -->
	<div class="row">
        <div class="col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Jadwal Tes</h3>
					<div class="box-tools pull-right">
                        <button type="button" class="btn btn-default btn-xs" onclick="refreshAll()" title="Muat ulang tabel">
                            <i class="fa fa-refresh"></i> Refresh
                        </button>
					</div>
                </div>

                <div class="box-body">
                    <?php echo form_open($url.'/hapus_daftar_tes','id="form-hapus-pilih"'); ?>
                    <input type="hidden" name="check" id="check" value="0">
                    <input type="hidden" name="centang" id="centang" value="0">
                    <div id="form-pesan"><?php if(!empty($pesan_hapus)){ echo $pesan_hapus; } ?></div>
                    <table id="table-tes" class="table table-bordered table-hover table-striped" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width:38px;">No.</th>
                                <th class="all">Nama Tes &amp; Paket Soal</th>
                                <th style="width:145px;">Jadwal (Buka &ndash; Tutup)</th>
                                <th style="width:115px;">Durasi &amp; Skor</th>
                                <th style="width:155px;">Kelas Peserta</th>
                                <th class="all" style="width:245px;">Aksi Cepat</th>
                                <th class="all" style="width:28px;"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </form>
                </div>
                <div class="box-footer">
                    <button type="button" id="btn-edit-hapus" class="btn btn-danger btn-sm" title="Hapus Tes yang dipilih">
                        <i class="fa fa-trash"></i> Hapus Terpilih
                    </button>
                    <button type="button" id="btn-edit-pilih" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-check-square-o"></i> Pilih Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div><!-- /#pane-daftar -->

    <!-- PANE 1: MASTER JADWAL (REKAP PER HARI + SUSUN & TERBITKAN MASSAL) -->
    <div id="pane-master">
        <!-- Card 1: Rekap Master Jadwal yang Sudah Terbit -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-calendar"></i> Master Jadwal Terbit (Rekap per Hari)
                            <span id="master-total-badge" class="label label-info" style="margin-left:6px;">0 jadwal</span>
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-default btn-xs" onclick="cetakMasterJadwal()" title="Cetak rekap master jadwal (A4)">
                                <i class="fa fa-print"></i> Cetak Jadwal
                            </button>
                            <button type="button" class="btn btn-default btn-xs" onclick="loadMasterSummary()" title="Muat ulang rekap">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="box-body" id="master-summary-container" style="padding:12px 15px;">
                        <p class="text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat master jadwal...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Susun Rencana & Terbitkan Jadwal Massal -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-magic"></i> Susun Rencana &amp; Terbitkan Jadwal Massal
                            <span class="modul-pill-mini"><i class="fa fa-database"></i> Modul Aktif: <?php echo html_escape($active_modul_nama); ?></span>
                        </h3>
                        <div class="box-tools pull-right">
                            <span class="text-muted" style="font-size:11.5px; margin-right:8px;"><i class="fa fa-floppy-o"></i> Draf tersimpan otomatis di browser</span>
                            <button type="button" class="btn btn-success btn-xs" onclick="addMasterPlanRow()">
                                <i class="fa fa-plus"></i> Tambah Baris
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="padding:10px 12px;">
                        <div id="master-plan-pesan"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed" id="table-master-plan" style="margin-bottom:0; min-width:960px;">
                                <thead>
                                    <tr>
                                        <th style="width:30px;">#</th>
                                        <th style="width:130px;">Tanggal</th>
                                        <th style="width:185px;">Jam 24j (WIB)</th>
                                        <th style="width:72px;">Durasi</th>
                                        <th style="width:270px;">Topik / Mapel &amp; Nama Tes</th>
                                        <th style="width:130px;">Mode PG/Esai</th>
                                        <th>Kelas Peserta</th>
                                        <th style="width:68px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="master-plan-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default btn-sm" onclick="addMasterPlanRow()">
                            <i class="fa fa-plus"></i> Tambah Baris
                        </button>
                        <button type="button" class="btn btn-default btn-sm" style="margin-left:5px;" onclick="clearMasterDraft()">
                            <i class="fa fa-eraser"></i> Bersihkan Draf
                        </button>
                        <label style="font-weight:600; font-size:12.5px; margin-left:14px; cursor:pointer; display:inline-block;">
                            <input type="checkbox" id="master-wajib-jawab" value="1"> Wajib Jawab Semua Soal
                        </label>
                        <button type="button" id="btn-publish-master" class="btn btn-success pull-right" onclick="publishMasterPlan()">
                            <i class="fa fa-paper-plane"></i> Terbitkan Semua ke Daftar Tes (<span id="master-row-count">0</span> baris)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /#pane-master -->

    <!-- Modal Ubah Jam / Durasi / Token Cepat -->
    <div class="modal fade" id="modal-quick-schedule" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-quick-schedule">
                    <div class="modal-header" style="background:#3c8dbc; color:#fff;">
                        <button class="close" type="button" data-dismiss="modal" style="color:#fff;">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-clock-o"></i> Ubah Cepat Jadwal &amp; Durasi</h4>
                    </div>
                    <div class="modal-body">
                        <div id="pesan-qs"></div>
                        <input type="hidden" name="qs-id" id="qs-id">
                        <div class="form-group">
                            <label>Nama Tes</label>
                            <input type="text" class="form-control input-sm" id="qs-nama" readonly>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Tanggal Buka</label>
                                    <input type="date" class="form-control input-sm" name="qs-tgl-mulai" id="qs-tgl-mulai" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Jam Buka (24 Jam)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control jam-24" list="datalist-jam-24" maxlength="5" inputmode="numeric" pattern="^([01]\d|2[0-3]):[0-5]\d$" placeholder="07:30" name="qs-jam-mulai" id="qs-jam-mulai" required>
                                        <span class="input-group-addon">WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Tanggal Tutup</label>
                                    <input type="date" class="form-control input-sm" name="qs-tgl-selesai" id="qs-tgl-selesai" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Jam Tutup (24 Jam)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control jam-24" list="datalist-jam-24" maxlength="5" inputmode="numeric" pattern="^([01]\d|2[0-3]):[0-5]\d$" placeholder="09:30" name="qs-jam-selesai" id="qs-jam-selesai" required>
                                        <span class="input-group-addon">WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Durasi Pengerjaan (Menit)</label>
                                    <input type="number" min="5" class="form-control input-sm" name="qs-durasi" id="qs-durasi" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" style="padding-top:24px;">
                                    <label style="cursor:pointer; font-weight:600;">
                                        <input type="checkbox" name="qs-token" id="qs-token" value="1"> Wajib Token
                                    </label>
                                </div>
                            </div>
                        </div>
                        <p class="help-block" style="margin-bottom:0;">
                            <i class="fa fa-info-circle text-info"></i> Cocok untuk perpanjangan jam ujian susulan atau mengubah durasi/token dengan cepat tanpa merusak hasil siswa yang sudah masuk.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Duplikat Tes -->
    <div class="modal fade" id="modal-duplikat" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-duplikat">
                    <div class="modal-header" style="background:#00a65a; color:#fff;">
                        <button class="close" type="button" data-dismiss="modal" style="color:#fff;">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-copy"></i> Duplikat Jadwal Tes</h4>
                    </div>
                    <div class="modal-body">
                        <div id="pesan-dup"></div>
                        <input type="hidden" name="dup-src-id" id="dup-src-id">
                        <p class="text-muted" style="font-size:12.5px;">Seluruh paket soal, bobot skor, dan kelas peserta akan disalin ke jadwal tes baru.</p>
                        <div class="form-group">
                            <label>Nama Tes Baru <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="dup-nama" id="dup-nama" required>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Tanggal Buka</label>
                                    <input type="date" class="form-control input-sm" name="dup-tgl-mulai" id="dup-tgl-mulai" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Jam Buka (24 Jam)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control jam-24" list="datalist-jam-24" maxlength="5" inputmode="numeric" pattern="^([01]\d|2[0-3]):[0-5]\d$" placeholder="07:30" name="dup-jam-mulai" id="dup-jam-mulai" required>
                                        <span class="input-group-addon">WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Tanggal Tutup</label>
                                    <input type="date" class="form-control input-sm" name="dup-tgl-selesai" id="dup-tgl-selesai" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Jam Tutup (24 Jam)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control jam-24" list="datalist-jam-24" maxlength="5" inputmode="numeric" pattern="^([01]\d|2[0-3]):[0-5]\d$" placeholder="09:30" name="dup-jam-selesai" id="dup-jam-selesai" required>
                                        <span class="input-group-addon">WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-copy"></i> Duplikat Sekarang</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus 1 Tes -->
    <div class="modal fade" id="modal-hapus" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo form_open($url.'/hapus_tes','id="form-hapus"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#dd4b39; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Hapus Tes</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan-hapus"></div>
                    <div class="form-group">
                        <label>Nama Tes</label>
                        <input type="hidden" name="hapus-id" id="hapus-id">
                        <input type="text" class="form-control" id="hapus-nama" name="hapus-nama" readonly>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" id="hapus-deskripsi" name="hapus-deskripsi" readonly>
                    </div>
                    <div class="callout callout-danger" style="margin-bottom:0;">
                        <p style="margin:0;"><strong>Peringatan:</strong> Menghapus Tes ini akan ikut menghapus seluruh riwayat jawaban &amp; nilai peserta di dalamnya.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-hapus" class="btn btn-danger pull-left">Hapus Permanen</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Batal</a>
                </div>
            </div>
        </div>
    </form>
    </div>

    <!-- Modal Hapus Banyak Tes -->
    <div class="modal fade" id="modal-hapus-pilih" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#dd4b39; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-warning"></i> Hapus Tes Terpilih</h4>
                </div>
                <div class="modal-body">
                    <p>Seluruh jadwal Tes yang dicentang akan dihapus permanen beserta hasil pengerjaan peserta di dalamnya.</p>
                    <div class="checkbox">
                        <label style="font-weight:600;">
                            <input type="checkbox" id="edit-hapus-centang" name="edit-hapus-centang" value="1"> Ya, saya yakin menghapus Tes yang telah dipilih.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-hapus-pilih" class="btn btn-danger pull-left">Hapus Permanen</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Batal</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating 24-hour time picker menu (bebas pilih semua jam tanpa terfilter) -->
    <div id="jam-24-floating-menu">
        <?php for($h = 6; $h <= 23; $h++){ foreach(array('00','15','30','45') as $m){ ?>
            <div class="jam-opt" data-val="<?php echo sprintf('%02d:%s', $h, $m); ?>"><?php echo sprintf('%02d:%s', $h, $m); ?></div>
        <?php } } ?>
    </div>

    <!-- Daftar pilihan jam 24-jam Indonesia -->
    <datalist id="datalist-jam-24">
        <?php for($h = 6; $h <= 21; $h++){ foreach(array('00','15','30','45') as $m){ if($h === 21 && $m !== '00') continue; ?>
            <option value="<?php echo sprintf('%02d:%s', $h, $m); ?>"></option>
        <?php } } ?>
    </datalist>
</section><!-- /.content -->

<script lang="javascript">
    var activeStatusFilter = 'semua';

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

    function refresh_table(){
        $('#table-tes').dataTable().fnReloadAjax();
    }

    function refreshCounts(){
        $.getJSON('<?php echo site_url($url."/status_counts"); ?>', function(res){
            if(res && res.status == 1 && res.counts){
                $('#cnt-semua').text(res.counts.semua);
                $('#cnt-berlangsung').text(res.counts.berlangsung);
                $('#cnt-hari_ini').text(res.counts.hari_ini);
                $('#cnt-akan_datang').text(res.counts.akan_datang);
                $('#cnt-berakhir').text(res.counts.berakhir);
            }
        });
    }

    function refreshAll(){
        refreshCounts();
        refresh_table();
        if($('#pane-master').is(':visible')){
            loadMasterSummary();
        }
    }

    // ==================== MASTER JADWAL & INPUT MASSAL ====================
    var masterActiveModulId = <?php echo (int)$active_modul_id; ?>;
    var masterGroupsData = <?php echo json_encode(array_map(function($g){ return array('id'=>(int)$g->grup_id, 'nama'=>$g->grup_nama); }, !empty($groups) ? $groups : array())); ?>;
    var masterTopiksData = [];
    var masterSummaryCache = [];
    var masterTopiksLoaded = false;
    var MASTER_DRAFT_KEY = 'zyacbt_master_jadwal_draft_v1';

    function escHtmlM(s){
        if(s === null || s === undefined) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function switchTesTab(paneId){
        $('#tes-nav-tabs .tes-nav-tab').removeClass('active');
        $('#tes-nav-tabs .tes-nav-tab[data-pane="' + paneId + '"]').addClass('active');
        if(paneId === 'pane-master'){
            $('#pane-daftar').hide();
            $('#pane-master').show();
            loadMasterSummary();
            ensureMasterTopiksLoaded();
        }else{
            $('#pane-master').hide();
            $('#pane-daftar').show();
            refreshAll();
        }
    }

    function loadMasterSummary(){
        $('#master-summary-container').html('<p class="text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat master jadwal...</p>');
        $.getJSON('<?php echo site_url($url."/master_summary"); ?>', function(res){
            if(!res || res.status != 1){
                $('#master-summary-container').html('<p class="text-danger">Gagal memuat master jadwal.</p>');
                return;
            }
            masterSummaryCache = res.days || [];
            $('#master-total-badge').text((res.total_tes || 0) + ' jadwal');

            if(masterSummaryCache.length === 0){
                $('#master-summary-container').html('<div class="text-muted" style="padding:10px 0;"><i class="fa fa-info-circle"></i> Belum ada jadwal tes yang diterbitkan. Susun rencana jadwal di tabel bawah lalu klik <strong>Terbitkan Semua</strong>.</div>');
                return;
            }

            var html = '';
            for(var i = 0; i < masterSummaryCache.length; i++){
                var d = masterSummaryCache[i];
                var toneCls = d.is_today ? 'is-today' : ('day-tone-' + (i % 4));
                html += '<div class="master-day-card">';
                html += '<div class="master-day-head ' + toneCls + '">';
                html += '<span><i class="fa fa-calendar"></i> ' + escHtmlM(d.date_label)
                      + (d.is_today ? ' <span class="label label-success" style="margin-left:6px;">HARI INI</span>' : ' <span class="label label-default" style="margin-left:6px; background:rgba(0,0,0,0.08); color:inherit;">Hari ke-' + (i + 1) + '</span>')
                      + '</span>';
                html += '<span style="font-size:12px; font-weight:600;">' + d.items.length + ' jadwal</span>';
                html += '</div>';
                html += '<table class="table table-condensed table-hover" style="margin-bottom:0; font-size:12.5px;">';
                html += '<thead><tr style="background:#fafafa;"><th style="width:145px;">Jam (WIB)</th><th>Mata Pelajaran &amp; Kelas Peserta</th><th style="width:125px;">Durasi &amp; Soal</th><th style="width:95px;">Status</th><th style="width:125px;">Aksi</th></tr></thead><tbody>';
                for(var j = 0; j < d.items.length; j++){
                    var it = d.items[j];
                    var jBadge = it.jenis === 'PG' ? '<span class="label" style="background:#059669;">PG</span>'
                               : (it.jenis === 'ESAI' ? '<span class="label" style="background:#4f46e5;">ESAI</span>'
                               : (it.jenis === 'CAMPURAN' ? '<span class="label label-warning">CAMPURAN</span>' : '<span class="label label-danger">KOSONG</span>'));
                    var sBadge = it.status === 'berlangsung' ? '<span class="label label-success">Berlangsung</span>'
                               : (it.status === 'hari_ini' ? '<span class="label label-info">Hari Ini</span>'
                               : (it.status === 'akan_datang' ? '<span class="label label-primary">Akan Datang</span>' : '<span class="label label-default">Berakhir</span>'));

                    var kelasBadges = '';
                    if(it.is_all_kelas){
                        kelasBadges = '<span class="label label-info" style="font-weight:500;"><i class="fa fa-users"></i> ' + escHtmlM(it.kelas_ringkas) + '</span>';
                    }else if(Array.isArray(it.kelas_list) && it.kelas_list.length > 0){
                        var kb = [];
                        for(var k = 0; k < it.kelas_list.length; k++){
                            kb.push('<span class="label label-default" style="display:inline-block; margin:2px 3px 0 0; font-weight:500; background:#e2e8f0; color:#1e293b;">' + escHtmlM(it.kelas_list[k]) + '</span>');
                        }
                        kelasBadges = kb.join('');
                    }else{
                        kelasBadges = '<span class="text-danger" style="font-size:11.5px;">Belum ada kelas</span>';
                    }

                    html += '<tr>';
                    html += '<td style="vertical-align:middle;"><strong>' + escHtmlM(it.waktu) + '</strong></td>';
                    html += '<td style="vertical-align:middle;">'
                          +   '<div><strong>' + escHtmlM(it.tes_nama) + '</strong> ' + jBadge + (it.token == 1 ? ' <span class="label label-warning"><i class="fa fa-key"></i></span>' : '') + '</div>'
                          +   '<div style="margin-top:3px;">' + kelasBadges + '</div>'
                          + '</td>';
                    html += '<td style="vertical-align:middle;">' + it.durasi + ' mnt &bull; ' + it.total_soal + ' soal</td>';
                    html += '<td style="vertical-align:middle;">' + sBadge + '</td>';
                    html += '<td style="vertical-align:middle; white-space:nowrap;">'
                          + '<button type="button" class="btn btn-primary btn-xs" onclick="edit(' + it.tes_id + ')" title="Edit Lengkap (Nama, Kelas, Jadwal, Soal)"><i class="fa fa-pencil"></i></button> '
                          + '<button type="button" class="btn btn-default btn-xs" onclick="bukaQuickSchedule(' + it.tes_id + ')" title="Ubah Cepat Jam/Durasi"><i class="fa fa-clock-o"></i></button> '
                          + '<button type="button" class="btn btn-danger btn-xs" onclick="hapus(' + it.tes_id + ')" title="Hapus Tes"><i class="fa fa-trash"></i></button>'
                          + '</td>';
                    html += '</tr>';
                }
                html += '</tbody></table></div>';
            }
            $('#master-summary-container').html(html);
        });
    }

    function cetakMasterJadwal(){
        if(!masterSummaryCache || masterSummaryCache.length === 0){
            notify_error('Belum ada jadwal terbit untuk dicetak.');
            return;
        }
        var w = window.open('', '_blank', 'width=900,height=700');
        var h = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Master Jadwal Ujian - SMK NU Darussalam</title>'
              + '<style>body{font-family:Arial,sans-serif;font-size:12px;color:#111;margin:20px;}'
              + 'h2{margin:0 0 4px 0;font-size:16px;text-align:center;}p.sub{margin:0 0 16px 0;text-align:center;color:#444;font-size:12px;}'
              + 'h3{margin:14px 0 5px 0;font-size:13px;background:#eef2f7;padding:5px 8px;border-left:4px solid #111;}'
              + 'table{width:100%;border-collapse:collapse;margin-bottom:8px;}th,td{border:1px solid #888;padding:5px 7px;text-align:left;}'
              + 'th{background:#f5f5f5;}@media print{.no-print{display:none;}}</style></head><body>'
              + '<div class="no-print" style="text-align:right;margin-bottom:10px;"><button onclick="window.print()">Cetak Sekarang</button></div>'
              + '<h2>MASTER JADWAL UJIAN CBT</h2><p class="sub">SMK NU Darussalam Srono</p>';
        for(var i = 0; i < masterSummaryCache.length; i++){
            var d = masterSummaryCache[i];
            h += '<h3>' + escHtmlM(d.date_label) + '</h3>';
            h += '<table><thead><tr><th style="width:28px;">No</th><th style="width:130px;">Waktu (WIB)</th><th>Mata Pelajaran &amp; Kelas Peserta</th><th style="width:65px;">Jenis</th><th style="width:70px;">Durasi</th></tr></thead><tbody>';
            for(var j = 0; j < d.items.length; j++){
                var it = d.items[j];
                h += '<tr><td>' + (j+1) + '</td><td>' + escHtmlM(it.waktu) + '</td><td><strong>' + escHtmlM(it.tes_nama) + '</strong><div style="font-size:11px;color:#444;margin-top:2px;">Kelas: ' + escHtmlM(it.kelas_ringkas) + '</div></td><td>' + escHtmlM(it.jenis) + '</td><td>' + it.durasi + ' mnt</td></tr>';
            }
            h += '</tbody></table>';
        }
        h += '</body></html>';
        w.document.open();
        w.document.write(h);
        w.document.close();
    }

    function ensureMasterTopiksLoaded(){
        if(masterTopiksLoaded){
            if($('#master-plan-tbody tr').length === 0){
                restoreMasterDraft();
            }
            return;
        }
        $.getJSON('<?php echo site_url("manager/tes_tambah/get_topik_detail_by_modul"); ?>/' + masterActiveModulId, function(res){
            masterTopiksLoaded = true;
            masterTopiksData = (res && res.status == 1 && res.topiks) ? res.topiks : [];
            restoreMasterDraft();
        });
    }

    function buildTopikOptionsHtml(selectedId){
        var html = '<option value="">-- Cari topik / mapel... --</option>';
        for(var i = 0; i < masterTopiksData.length; i++){
            var t = masterTopiksData[i];
            if(t.jml_total <= 0) continue;
            var tag = (t.jml_pg > 0 && t.paired_esai)
                ? '[' + t.jml_pg + ' PG + ' + t.paired_esai.jml_esai + ' Esai]'
                : (t.jml_pg > 0 ? '[' + t.jml_pg + ' PG]' : (t.jml_esai > 0 ? '[' + t.jml_esai + ' Esai]' : '[' + t.jml_total + ']'));
            var sel = (String(selectedId) === String(t.topik_id)) ? ' selected' : '';
            html += '<option value="' + t.topik_id + '"' + sel + '>' + escHtmlM(t.topik_nama) + ' ' + tag + '</option>';
        }
        return html;
    }

    function buildGroupOptionsHtml(selectedIds){
        var map = {};
        if(Array.isArray(selectedIds)){
            for(var k = 0; k < selectedIds.length; k++) map[String(selectedIds[k])] = true;
        }
        var html = '';
        for(var i = 0; i < masterGroupsData.length; i++){
            var g = masterGroupsData[i];
            var sel = map[String(g.id)] ? ' selected' : '';
            html += '<option value="' + g.id + '"' + sel + '>' + escHtmlM(g.nama) + '</option>';
        }
        return html;
    }

    function findMasterTopik(tid){
        tid = parseInt(tid, 10) || 0;
        for(var i = 0; i < masterTopiksData.length; i++){
            if(masterTopiksData[i].topik_id === tid) return masterTopiksData[i];
        }
        return null;
    }

    function renumberMasterRows(){
        $('#master-plan-tbody tr.mp-row').each(function(idx){
            $(this).find('.mp-no').text(idx + 1);
        });
        $('#master-row-count').text($('#master-plan-tbody tr.mp-row').length);
    }

    function saveMasterDraft(){
        try {
            var rows = collectMasterPlanRows();
            localStorage.setItem(MASTER_DRAFT_KEY, JSON.stringify(rows));
        } catch(e){}
    }

    function restoreMasterDraft(){
        $('#master-plan-tbody').empty();
        var saved = null;
        try {
            var raw = localStorage.getItem(MASTER_DRAFT_KEY);
            if(raw) saved = JSON.parse(raw);
        } catch(e){}
        if(Array.isArray(saved) && saved.length > 0){
            for(var i = 0; i < saved.length; i++){
                addMasterPlanRow(saved[i], true);
            }
        }else{
            addMasterPlanRow(null, true);
        }
        renumberMasterRows();
    }

    function clearMasterDraft(){
        if(!confirm('Bersihkan semua baris rencana di tabel ini?')) return;
        try { localStorage.removeItem(MASTER_DRAFT_KEY); } catch(e){}
        $('#master-plan-tbody').empty();
        addMasterPlanRow(null, true);
        renumberMasterRows();
        $('#master-plan-pesan').html('');
    }

    function addMasterPlanRow(preset, skipSave){
        var today = '<?php echo date("Y-m-d"); ?>';
        var p = preset || {};
        var tgl = p.tgl || today;
        var jm  = p.jam_mulai || '07:30';
        var js  = p.jam_selesai || '09:30';
        var dur = p.durasi || 60;
        var tid = p.topik_id || '';
        var nama = p.nama || '';
        var mode = p.pair_mode || 'pisah';
        var grps = Array.isArray(p.groups) ? p.groups : [];

        var tr = $('<tr class="mp-row">'
            + '<td class="mp-no text-center" style="font-weight:700; padding-top:10px;"></td>'
            + '<td><input type="date" class="form-control input-sm mp-tgl" value="' + escHtmlM(tgl) + '"></td>'
            + '<td>'
            +   '<div style="display:flex; gap:4px; align-items:center;">'
            +     '<div class="jam-picker-wrap">'
            +       '<input type="text" class="form-control input-sm jam-24 mp-jm" maxlength="5" inputmode="numeric" value="' + escHtmlM(jm) + '" placeholder="07:30">'
            +       '<button type="button" class="jam-picker-btn" title="Pilih jam (24 jam)"><i class="fa fa-caret-down"></i></button>'
            +     '</div>'
            +     '<span>–</span>'
            +     '<div class="jam-picker-wrap">'
            +       '<input type="text" class="form-control input-sm jam-24 mp-js" maxlength="5" inputmode="numeric" value="' + escHtmlM(js) + '" placeholder="09:30">'
            +       '<button type="button" class="jam-picker-btn" title="Pilih jam (24 jam)"><i class="fa fa-caret-down"></i></button>'
            +     '</div>'
            +   '</div>'
            + '</td>'
            + '<td><input type="number" min="5" class="form-control input-sm mp-dur" value="' + escHtmlM(dur) + '"></td>'
            + '<td>'
            +   '<select class="form-control input-sm mp-topik" style="width:100%;">' + buildTopikOptionsHtml(tid) + '</select>'
            +   '<input type="text" class="form-control input-sm mp-nama" style="margin-top:4px;" placeholder="Nama Tes (otomatis)" value="' + escHtmlM(nama) + '">'
            +   '<input type="hidden" class="mp-pair-tid" value="' + escHtmlM(p.pair_topik_id || 0) + '">'
            +   '<input type="hidden" class="mp-pair-nama" value="' + escHtmlM(p.pair_nama || '') + '">'
            + '</td>'
            + '<td>'
            +   '<select class="form-control input-sm mp-mode">'
            +     '<option value="pisah"' + (mode === 'pisah' ? ' selected' : '') + '>Pisah 2 Tes</option>'
            +     '<option value="gabung"' + (mode === 'gabung' ? ' selected' : '') + '>Gabung 1 Tes</option>'
            +     '<option value="utama_saja"' + (mode === 'utama_saja' ? ' selected' : '') + '>Tunggal</option>'
            +   '</select>'
            +   '<div class="mp-pair-hint text-muted" style="font-size:11px; margin-top:3px;"></div>'
            + '</td>'
            + '<td>'
            +   '<select class="form-control input-sm mp-groups" multiple="multiple" style="width:100%;">' + buildGroupOptionsHtml(grps) + '</select>'
            +   '<div class="mini-preset-row">'
            +     '<button type="button" class="btn btn-info btn-xs mp-grp-all">Semua</button>'
            +     '<button type="button" class="btn btn-default btn-xs mp-grp-tk" data-tk="X">X</button>'
            +     '<button type="button" class="btn btn-default btn-xs mp-grp-tk" data-tk="XI">XI</button>'
            +     '<button type="button" class="btn btn-default btn-xs mp-grp-tk" data-tk="XII">XII</button>'
            +     '<button type="button" class="btn btn-default btn-xs mp-grp-clr">Reset</button>'
            +   '</div>'
            + '</td>'
            + '<td class="text-center">'
            +   '<button type="button" class="btn btn-default btn-xs mp-dup" title="Duplikat baris ini"><i class="fa fa-copy"></i></button> '
            +   '<button type="button" class="btn btn-danger btn-xs mp-del" title="Hapus baris"><i class="fa fa-trash"></i></button>'
            + '</td>'
            + '</tr>');

        $('#master-plan-tbody').append(tr);

        tr.find('.mp-topik').select2({ placeholder: 'Cari topik / mapel...', allowClear: true, width: '100%' });
        tr.find('.mp-groups').select2({ placeholder: 'Pilih kelas...', width: '100%' });

        updateMasterRowPairHint(tr, !!p.nama);
        renumberMasterRows();
        if(!skipSave) saveMasterDraft();
    }

    function updateMasterRowPairHint(tr, keepNama){
        var tid = tr.find('.mp-topik').val();
        var t = findMasterTopik(tid);
        if(!t){
            tr.find('.mp-pair-tid').val(0);
            tr.find('.mp-pair-nama').val('');
            tr.find('.mp-pair-hint').html('');
            return;
        }
        if(!keepNama){
            tr.find('.mp-nama').val(t.topik_nama);
            tr.find('.mp-dur').val(t.default_tipe === 2 ? 30 : 60);
        }
        if(t.paired_esai){
            tr.find('.mp-pair-tid').val(t.paired_esai.topik_id);
            tr.find('.mp-pair-nama').val(t.paired_esai.topik_nama);
            tr.find('.mp-mode').prop('disabled', false);
            tr.find('.mp-pair-hint').html('<span style="color:#5b21b6;"><i class="fa fa-magic"></i> + ' + t.paired_esai.jml_esai + ' Esai</span>');
        }else{
            tr.find('.mp-pair-tid').val(0);
            tr.find('.mp-pair-nama').val('');
            tr.find('.mp-mode').val('utama_saja').prop('disabled', true);
            tr.find('.mp-pair-hint').html('<span>Tanpa pasangan Esai</span>');
        }
    }

    function collectMasterPlanRows(){
        var out = [];
        $('#master-plan-tbody tr.mp-row').each(function(){
            var tr = $(this);
            var grps = tr.find('.mp-groups').val() || [];
            out.push({
                tgl: tr.find('.mp-tgl').val(),
                jam_mulai: normalizeJam24(tr.find('.mp-jm').val()),
                jam_selesai: normalizeJam24(tr.find('.mp-js').val()),
                durasi: parseInt(tr.find('.mp-dur').val(), 10) || 60,
                topik_id: parseInt(tr.find('.mp-topik').val(), 10) || 0,
                nama: $.trim(tr.find('.mp-nama').val() || ''),
                pair_mode: tr.find('.mp-mode').val() || 'utama_saja',
                pair_topik_id: parseInt(tr.find('.mp-pair-tid').val(), 10) || 0,
                pair_nama: $.trim(tr.find('.mp-pair-nama').val() || ''),
                pair_durasi: 30,
                groups: grps.map(function(x){ return parseInt(x, 10); })
            });
        });
        return out;
    }

    function publishMasterPlan(){
        var rows = collectMasterPlanRows();
        if(rows.length === 0){
            $('#master-plan-pesan').html(pesan_err('Tambahkan minimal 1 baris rencana jadwal terlebih dahulu.'));
            return;
        }
        $('#master-plan-pesan').html('');
        $('#modal-proses').modal('show');
        $.post('<?php echo site_url($url."/batch_create"); ?>', {
            rows_json: JSON.stringify(rows),
            wajib_jawab: $('#master-wajib-jawab').is(':checked') ? 1 : 0
        }, function(respon){
            $('#modal-proses').modal('hide');
            var obj = $.parseJSON(respon);
            if(obj.status == 1){
                notify_success(obj.pesan);
                try { localStorage.removeItem(MASTER_DRAFT_KEY); } catch(e){}
                $('#master-plan-tbody').empty();
                addMasterPlanRow(null, true);
                renumberMasterRows();
                loadMasterSummary();
                refreshCounts();
                refresh_table();
            }else{
                $('#master-plan-pesan').html(pesan_err(obj.pesan));
            }
        }).fail(function(){
            $('#modal-proses').modal('hide');
            $('#master-plan-pesan').html(pesan_err('Gagal menghubungi server.'));
        });
    }

    $(document).on('change', '.mp-topik', function(){
        var tr = $(this).closest('tr');
        tr.find('.mp-mode').val('pisah');
        updateMasterRowPairHint(tr, false);
        saveMasterDraft();
    });
    $(document).on('change blur', '.mp-tgl, .mp-jm, .mp-js, .mp-dur, .mp-nama, .mp-mode, .mp-groups', function(){
        if($(this).hasClass('mp-nama')){
            var tr = $(this).closest('tr');
            if(parseInt(tr.find('.mp-pair-tid').val(), 10) > 0){
                var nm = $.trim($(this).val() || '');
                if(nm !== ''){
                    tr.find('.mp-pair-nama').val('Esai ' + nm);
                }
            }
        }
        saveMasterDraft();
    });
    $(document).on('click', '.mp-sesi', function(){
        var tr = $(this).closest('tr');
        tr.find('.mp-jm').val($(this).data('jm'));
        tr.find('.mp-js').val($(this).data('js'));
        saveMasterDraft();
    });
    $(document).on('click', '.mp-grp-all', function(){
        var tr = $(this).closest('tr');
        var allIds = masterGroupsData.map(function(g){ return String(g.id); });
        tr.find('.mp-groups').val(allIds).trigger('change');
        saveMasterDraft();
    });
    $(document).on('click', '.mp-grp-tk', function(){
        var tr = $(this).closest('tr');
        var tk = String($(this).data('tk'));
        var re = new RegExp('^' + tk + '(\\s|\\-|$)', 'i');
        var cur = tr.find('.mp-groups').val() || [];
        for(var i = 0; i < masterGroupsData.length; i++){
            if(re.test(masterGroupsData[i].nama) && cur.indexOf(String(masterGroupsData[i].id)) === -1){
                cur.push(String(masterGroupsData[i].id));
            }
        }
        tr.find('.mp-groups').val(cur).trigger('change');
        saveMasterDraft();
    });
    $(document).on('click', '.mp-grp-clr', function(){
        var tr = $(this).closest('tr');
        tr.find('.mp-groups').val([]).trigger('change');
        saveMasterDraft();
    });
    $(document).on('click', '.mp-dup', function(){
        var tr = $(this).closest('tr');
        addMasterPlanRow({
            tgl: tr.find('.mp-tgl').val(),
            jam_mulai: tr.find('.mp-jm').val(),
            jam_selesai: tr.find('.mp-js').val(),
            durasi: parseInt(tr.find('.mp-dur').val(), 10) || 60,
            groups: tr.find('.mp-groups').val() || []
        });
    });
    $(document).on('click', '.mp-del', function(){
        $(this).closest('tr').remove();
        if($('#master-plan-tbody tr.mp-row').length === 0){
            addMasterPlanRow(null, true);
        }
        renumberMasterRows();
        saveMasterDraft();
    });

    function setStatusFilter(st){
        activeStatusFilter = st;
        $('#status-pill-bar .status-pill').removeClass('active');
        $('#status-pill-bar .status-pill[data-status="' + st + '"]').addClass('active');
        $('#check').val('0');
        refresh_table();
    }

    function edit(id){
        window.location.href = "<?php echo site_url('manager/tes_tambah/index'); ?>/" + id;
    }

    function bukaQuickSchedule(id){
        $('#pesan-qs').html('');
        $('#modal-proses').modal('show');
        $.getJSON('<?php echo site_url($url."/get_by_id"); ?>/' + id, function(data){
            $('#modal-proses').modal('hide');
            if(data.data == 1){
                $('#qs-id').val(data.id);
                $('#qs-nama').val(data.nama);
                $('#qs-tgl-mulai').val(data.tgl_mulai);
                $('#qs-jam-mulai').val(data.jam_mulai);
                $('#qs-tgl-selesai').val(data.tgl_selesai);
                $('#qs-jam-selesai').val(data.jam_selesai);
                $('#qs-durasi').val(data.waktu);
                $('#qs-token').prop('checked', data.token == 1);
                $('#modal-quick-schedule').modal('show');
            }
        });
    }

    function bukaDuplikat(id){
        $('#pesan-dup').html('');
        $('#modal-proses').modal('show');
        $.getJSON('<?php echo site_url($url."/get_by_id"); ?>/' + id, function(data){
            $('#modal-proses').modal('hide');
            if(data.data == 1){
                $('#dup-src-id').val(data.id);
                $('#dup-nama').val(data.nama + ' (Susulan)');
                $('#dup-tgl-mulai').val(data.tgl_mulai);
                $('#dup-jam-mulai').val(data.jam_mulai);
                $('#dup-tgl-selesai').val(data.tgl_selesai);
                $('#dup-jam-selesai').val(data.jam_selesai);
                $('#modal-duplikat').modal('show');
            }
        });
    }

    function hapus(id){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url($url."/get_by_id"); ?>/' + id, function(data){
            if(data.data == 1){
                $('#form-pesan-hapus').html('');
                $('#hapus-id').val(data.id);
                $('#hapus-nama').val(data.nama);
                $('#hapus-deskripsi').val(data.deskripsi);
                $("#modal-hapus").modal("show");
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
        // Tab default adalah Master Jadwal: langsung muat rekap & topik modul aktif
        loadMasterSummary();
        ensureMasterTopiksLoaded();

        $('#filter-group, #filter-jenis').change(function(){
            refresh_table();
        });

        $('#form-quick-schedule').submit(function(){
            $('#modal-proses').modal('show');
            $.post('<?php echo site_url($url."/quick_schedule"); ?>', $(this).serialize(), function(respon){
                $('#modal-proses').modal('hide');
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#modal-quick-schedule').modal('hide');
                    notify_success(obj.pesan);
                    refreshAll();
                }else{
                    $('#pesan-qs').html(pesan_err(obj.pesan));
                }
            });
            return false;
        });

        $('#form-duplikat').submit(function(){
            $('#modal-proses').modal('show');
            $.post('<?php echo site_url($url."/duplikat_tes"); ?>', $(this).serialize(), function(respon){
                $('#modal-proses').modal('hide');
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#modal-duplikat').modal('hide');
                    notify_success(obj.pesan);
                    refreshAll();
                }else{
                    $('#pesan-dup').html(pesan_err(obj.pesan));
                }
            });
            return false;
        });

        $('#btn-edit-pilih').click(function() {
            if($('#check').val() == 0) {
                $('.chk-tes-item').prop('checked', true);
                $('#check').val('1');
            }else{
                $('.chk-tes-item').prop('checked', false);
                $('#check').val('0');
            }
        });

        $('#btn-edit-hapus').click(function(){
            if($('.chk-tes-item:checked').length === 0){
                notify_error('Centang minimal satu jadwal Tes terlebih dahulu.');
                return;
            }
            $('#centang').val('0');
            $('#edit-hapus-centang').prop("checked", false);
            $("#modal-hapus-pilih").modal('show');
        });

        $('#btn-hapus-pilih').click(function(){
            $("#modal-proses").modal('show');
            $("#form-hapus-pilih").submit();
        });

        $("#edit-hapus-centang").change(function() {
            $('#centang').val(this.checked ? '1' : '0');
        });

        $('#form-hapus').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url($url.'/hapus_tes'); ?>",
                type: "POST",
                data: $('#form-hapus').serialize(),
                cache: false,
                success: function(respon){
                    var obj = $.parseJSON(respon);
                    $("#modal-proses").modal('hide');
                    if(obj.status == 1){
                        $("#modal-hapus").modal('hide');
                        notify_success(obj.pesan);
                        refreshAll();
                    }else{
                        $('#form-pesan-hapus').html(pesan_err(obj.pesan));
                    }
                }
            });
            return false;
        });

        $('#table-tes').DataTable({
            "paging": true,
            "iDisplayLength": 25,
            "aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "bProcessing": false,
            "bServerSide": true,
            "searching": true,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth": "35px"},
                {"bSearchable": false, "bSortable": false},
                {"bSearchable": false, "bSortable": false, "sWidth": "140px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "110px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "155px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "240px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "25px"}
            ],
            "sAjaxSource": "<?php echo site_url($url.'/get_datatable'); ?>",
            "autoWidth": false,
            "responsive": true,
            "fnServerParams": function ( aoData ) {
                aoData.push({ "name": "status_filter", "value": activeStatusFilter });
                aoData.push({ "name": "group_filter", "value": $('#filter-group').val() });
                aoData.push({ "name": "jenis_filter", "value": $('#filter-jenis').val() });
            }
        });
    });
</script>
