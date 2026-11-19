<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Peserta &amp; Kelas (Group)
		<small>Kelola kelas, daftar peserta, dan import Excel otomatis buat kelas dalam satu halaman</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Peserta &amp; Kelas</li>
	</ol>
</section>

<style>
    .kelas-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        max-height: 210px;
        overflow-y: auto;
        padding: 2px 0;
    }
    .kelas-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        border: 1px solid #d2d6de;
        border-radius: 6px;
        background: #fff;
        color: #333;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .kelas-chip:hover {
        border-color: #3c8dbc;
        background: #f0f7fc;
        color: #1f5c82;
    }
    .kelas-chip.active {
        background: #3c8dbc;
        border-color: #367fa9;
        color: #fff;
        box-shadow: 0 2px 5px rgba(60, 141, 188, 0.25);
    }
    .kelas-chip .chip-count {
        font-size: 11px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 10px;
        background: #e8edf2;
        color: #444;
    }
    .kelas-chip.active .chip-count {
        background: rgba(255,255,255,0.22);
        color: #fff;
    }
    .toolbar-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
    .import-preview-wrap {
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }
    .kelas-badge-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }
    .kelas-badge-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 4px;
        font-size: 12px;
        border: 1px solid #d1d5db;
        background: #f9fafb;
    }
    .kelas-badge-item.is-new {
        background: #ecfdf5;
        border-color: #6ee7b7;
        color: #065f46;
    }
    #table-peserta td {
        vertical-align: middle;
    }
</style>

<!-- Main content -->
<section class="content">
    <!-- Baris 1: Panel Kelas (Group) 1-Klik + Aksi Cepat -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-sitemap"></i> Daftar Kelas (Group)</h3>
                    <div class="box-tools pull-right toolbar-actions">
                        <input type="text" id="filter-kelas-input" class="form-control input-sm" style="width: 170px; display: inline-block;" placeholder="Cari kelas...">
                        <button type="button" class="btn btn-default btn-sm" onclick="bukaModalKelolaKelas()">
                            <i class="fa fa-cog"></i> Kelola Kelas
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="bukaModalImport()">
                            <i class="fa fa-file-excel-o"></i> Import Excel (Auto-Kelas)
                        </button>
                        <button type="button" class="btn btn-info btn-sm" id="btn-cetak-kartu-cepat" onclick="cetakKartuAktif()">
                            <i class="fa fa-key"></i> Cetak Kartu Login
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <input type="hidden" name="group" id="group" value="semua">
                    <div id="kelas-chip-container" class="kelas-grid">
                        <!-- Diisi otomatis via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Tabel Daftar Peserta -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-users"></i> Daftar Peserta
                        <span id="label-kelas-aktif" class="label label-primary" style="margin-left: 6px; font-size: 12px;">Semua Kelas</span>
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" onclick="tambah()">
                            <i class="fa fa-user-plus"></i> Tambah Peserta
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?php echo form_open($url.'/hapus_daftar_siswa','id="form-hapus"'); ?>
                    <input type="hidden" name="check" id="check" value="0">
                    <table id="table-peserta" class="table table-bordered table-hover table-striped" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width:45px;">No.</th>
                                <th style="width:130px;">Username</th>
                                <th class="all">Nama Lengkap</th>
                                <th style="width:140px;">Kelas (Group)</th>
                                <th style="width:160px;">Keterangan (Ruang/Sesi)</th>
                                <th class="all" style="width:80px;">Aksi</th>
                                <th class="all" style="width:36px;"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </form>
                </div>
                <div class="box-footer">
                    <button type="button" id="btn-edit-hapus" class="btn btn-danger btn-sm" title="Hapus Peserta yang dipilih">
                        <i class="fa fa-trash"></i> Hapus Terpilih
                    </button>
                    <button type="button" id="btn-edit-pilih" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-check-square-o"></i> Pilih Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Kelas (CRUD Group + Proteksi DB Cascade) -->
    <div style="overflow-y:auto;" class="modal fade" id="modal-kelola-kelas" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-sitemap"></i> Kelola Kelas (Group)</h4>
                </div>
                <div class="modal-body">
                    <div id="pesan-kelola-kelas"></div>

                    <!-- Tambah Kelas Cepat -->
                    <form id="form-group-tambah" class="form-inline" style="margin-bottom: 15px; background: #f8fafc; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <div class="form-group" style="width: 72%; margin-right: 6px;">
                            <input type="text" class="form-control" style="width: 100%;" id="input-group-baru" name="tambah-group" placeholder="Ketik nama kelas baru (contoh: X TKJ 1, XI AKL 2, XII TKR 1)...">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kelas</button>
                    </form>

                    <!-- Tabel Daftar Kelas -->
                    <div style="max-height: 330px; overflow-y: auto;">
                        <table class="table table-bordered table-condensed table-hover" id="table-kelola-kelas">
                            <thead>
                                <tr style="background: #f4f4f4;">
                                    <th style="width:45px;">No</th>
                                    <th>Nama Kelas (Group)</th>
                                    <th style="width:95px;" class="text-center">Peserta</th>
                                    <th style="width:95px;" class="text-center">Jadwal Tes</th>
                                    <th style="width:155px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-kelola-kelas">
                                <tr><td colspan="5" class="text-center text-muted">Memuat data kelas...</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="help-block" style="margin-top: 8px; margin-bottom: 0;">
                        <i class="fa fa-shield text-blue"></i> <strong>Keamanan Database:</strong> Kelas yang masih dipakai jadwal tes tidak dapat dihapus. Kelas yang masih berisi peserta memerlukan konfirmasi khusus karena menghapus kelas akan ikut menghapus peserta di dalamnya.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Kelas (Dengan Peringatan Cascade) -->
    <div style="overflow-y:auto;" class="modal fade" id="modal-hapus-kelas" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dd4b39; color: #fff;">
                    <button class="close" type="button" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-warning"></i> Konfirmasi Hapus Kelas</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hapus-grup-id">
                    <p id="hapus-kelas-teks"></p>
                    <div id="hapus-kelas-cascade-box" class="callout callout-danger" style="display:none; margin-bottom: 0;">
                        <h4 style="margin-top:0;"><i class="fa fa-exclamation-triangle"></i> Peringatan Keras!</h4>
                        <p id="hapus-kelas-cascade-msg"></p>
                        <label style="font-weight: 600; cursor: pointer; margin-top: 6px;">
                            <input type="checkbox" id="chk-confirm-cascade"> Saya paham dan yakin menghapus kelas ini beserta seluruh peserta &amp; hasil tesnya.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" id="btn-konfirm-hapus-kelas">Hapus Kelas</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel Peserta (Auto-Create Kelas + Preview) -->
    <div style="overflow-y:auto;" class="modal fade" id="modal-import-peserta" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #00a65a; color: #fff;">
                    <button class="close" type="button" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Import Data Peserta Excel (Auto-Buat Kelas)</h4>
                </div>
                <div class="modal-body">
                    <div id="import-pesan"></div>

                    <div class="callout callout-info" style="margin-bottom: 14px;">
                        <p style="margin:0;">
                            <strong>Sekali Upload, Kelas Otomatis Terbentuk:</strong> Isi kolom <code>Kelas (Group)</code> pada file Excel (contoh: <code>X TKJ 1</code>, <code>XI AKL 1</code>). Kelas yang belum terdaftar akan <strong>otomatis dibuatkan</strong> saat disimpan.
                            <a href="<?php echo site_url($url.'/download_template'); ?>" class="btn btn-xs btn-primary pull-right" style="text-decoration:none;">
                                <i class="fa fa-download"></i> Download Template Excel
                            </a>
                        </p>
                    </div>

                    <form id="form-import-preview" enctype="multipart/form-data" style="margin-bottom: 12px;">
                        <div class="row">
                            <div class="col-sm-8">
                                <input type="file" name="file-excel" id="import-file-excel" accept=".xlsx,.xls" class="form-control input-sm">
                                <p class="help-block" style="margin-bottom:0;">Format didukung: <code>.xlsx</code> atau <code>.xls</code> (kolom standar ZyaCBT: No, Username, Password, Nama, Email, Kelas/Group, Keterangan).</p>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-block btn-sm" id="btn-import-analisa">
                                    <i class="fa fa-search"></i> Analisa &amp; Preview
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Area Hasil Preview -->
                    <div id="import-preview-area" style="display:none; border-top: 1px solid #e5e7eb; padding-top: 12px;">
                        <input type="hidden" id="import-token" value="">

                        <div style="margin-bottom: 10px;" id="import-badges"></div>

                        <div style="margin-bottom: 12px; background: #f8fafc; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 5px;">
                            <strong style="font-size: 12.5px;"><i class="fa fa-sitemap"></i> Daftar Kelas Terdeteksi di Excel:</strong>
                            <div id="import-groups-list" class="kelas-badge-list"></div>
                        </div>

                        <div id="import-dup-option" style="display:none; margin-bottom: 12px; background: #fffbeb; padding: 10px 12px; border: 1px solid #fde68a; border-radius: 5px;">
                            <strong style="font-size: 12.5px; color: #92400e;"><i class="fa fa-exclamation-circle"></i> Jika Username Sudah Terdaftar di Database:</strong>
                            <div style="margin-top: 6px;">
                                <label style="font-weight: normal; margin-right: 16px; cursor: pointer;">
                                    <input type="radio" name="import-dup-mode" value="skip" checked> <strong>Lewati (Aman)</strong> &mdash; pertahankan data lama
                                </label>
                                <label style="font-weight: normal; cursor: pointer;">
                                    <input type="radio" name="import-dup-mode" value="update"> <strong>Perbarui Data</strong> &mdash; update password/nama/kelas/ruang (riwayat tes tetap aman)
                                </label>
                            </div>
                        </div>

                        <div class="import-preview-wrap">
                            <table class="table table-bordered table-condensed table-striped" style="margin-bottom: 0; font-size: 12.5px;">
                                <thead>
                                    <tr style="background:#f3f4f6;">
                                        <th style="width:45px;">Baris</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Kelas (Group)</th>
                                        <th>Keterangan</th>
                                        <th style="width:155px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="import-preview-tbody"></tbody>
                            </table>
                        </div>
                        <p id="import-truncated-note" class="help-block" style="display:none; margin-top: 6px; margin-bottom: 0;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success pull-left" id="btn-import-simpan" style="display:none;" onclick="simpanImportPeserta()">
                        <i class="fa fa-save"></i> Simpan ke Database
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Peserta Terpilih -->
    <div style="overflow-y:auto;" class="modal fade" id="modal-hapus" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Hapus Peserta Terpilih</h4>
                </div>
                <div class="modal-body">
                    <div class="callout callout-danger" style="margin-bottom:0;">
                        <h4>Peringatan</h4>
                        <p>Data peserta yang dipilih akan dihapus permanen beserta seluruh riwayat hasil tesnya. Lanjutkan?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-hapus" class="btn btn-danger pull-left">Hapus Permanen</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Batal</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Peserta -->
    <div style="max-height: 100%;overflow-y:auto;" class="modal fade" id="modal-tambah" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo form_open($url.'/tambah','id="form-tambah"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah Peserta</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan"></div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="tambah-username" name="tambah-username" placeholder="Username / NIS Peserta">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" class="form-control" id="tambah-password" name="tambah-password" placeholder="Password login peserta">
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" id="tambah-nama" name="tambah-nama" placeholder="Nama Lengkap Peserta">
                    </div>
                    <div class="form-group">
                        <label>Kelas (Group)</label>
                        <select name="tambah-group" id="tambah-group" class="form-control">
                            <?php if(!empty($select_group)){ echo $select_group; } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan (Ruang / Sesi) <small class="text-muted">(Maks 25 karakter)</small></label>
                        <input type="text" class="form-control" id="tambah-detail" name="tambah-detail" maxlength="25" placeholder="Contoh: Ruang 1, Sesi 1">
                    </div>
                    <div class="form-group">
                        <label>Email <small class="text-muted">(Opsional)</small></label>
                        <input type="text" class="form-control" id="tambah-email" name="tambah-email" placeholder="Boleh dikosongkan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="tambah-simpan" class="btn btn-primary">Simpan</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Tutup</a>
                </div>
            </div>
        </div>
    </form>
    </div>

    <!-- Modal Edit Peserta -->
    <div style="max-height: 100%;overflow-y:auto;" class="modal fade" id="modal-edit" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo form_open($url.'/edit','id="form-edit"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Peserta</h4>
                </div>
                <div class="modal-body">
                    <div id="form-pesan-edit"></div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="hidden" name="edit-id" id="edit-id">
                        <input type="hidden" name="edit-pilihan" id="edit-pilihan">
                        <input type="text" class="form-control" id="edit-username" name="edit-username" readonly>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" id="edit-password" name="edit-password" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-flat" id="btn-show-password" title="Tampilkan Password" type="button"><i id="icon-show-password" class="fa fa-eye"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit-nama" name="edit-nama" placeholder="Nama Lengkap Peserta">
                    </div>
                    <div class="form-group">
                        <label>Kelas (Group)</label>
                        <select name="edit-group" id="edit-group" class="form-control">
                            <?php if(!empty($select_group)){ echo $select_group; } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan (Ruang / Sesi) <small class="text-muted">(Maks 25 karakter)</small></label>
                        <input type="text" class="form-control" id="edit-detail" name="edit-detail" maxlength="25" placeholder="Contoh: Ruang 1, Sesi 1">
                    </div>
                    <div class="form-group">
                        <label>Email <small class="text-muted">(Opsional)</small></label>
                        <input type="text" class="form-control" id="edit-email" name="edit-email" placeholder="Boleh dikosongkan">
                    </div>
                    <p class="text-danger" style="font-size:12px; margin-bottom:0;"><i class="fa fa-info-circle"></i> Menghapus peserta akan ikut menghapus seluruh hasil tes peserta tersebut.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="edit-hapus" class="btn btn-danger pull-left">Hapus</button>
                    <button type="button" id="edit-simpan" class="btn btn-primary">Simpan</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Tutup</a>
                </div>
            </div>
        </div>
    </form>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    var allGroups = <?php echo json_encode(!empty($groups) ? $groups : array()); ?>;
    var totalPesertaAll = <?php echo (int)(isset($total_peserta) ? $total_peserta : 0); ?>;
    var activeGroupId = 'semua';

    function escHtml(str){
        if(str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function refresh_table(){
        $('#table-peserta').dataTable().fnReloadAjax();
    }

    function reloadGroups(callback){
        $.getJSON('<?php echo site_url($url."/group_list"); ?>', function(res){
            if(res && res.status == 1){
                allGroups = res.groups || [];
                totalPesertaAll = res.total_peserta || 0;

                // Pastikan activeGroupId masih ada; jika kelas baru dihapus, kembali ke 'semua'
                if(activeGroupId !== 'semua'){
                    var found = false;
                    for(var i=0; i<allGroups.length; i++){
                        if(String(allGroups[i].grup_id) === String(activeGroupId)){
                            found = true; break;
                        }
                    }
                    if(!found){
                        activeGroupId = 'semua';
                        $('#group').val('semua');
                    }
                }

                renderGroupChips();
                renderGroupSelects();
                renderKelolaKelasTable();
                updateActiveClassLabel();
            }
            if(typeof callback === 'function') callback();
        });
    }

    function renderGroupChips(){
        var kw = $.trim($('#filter-kelas-input').val() || '').toLowerCase();
        var html = '';

        // Chip Semua Kelas
        var allCls = (activeGroupId === 'semua') ? 'kelas-chip active' : 'kelas-chip';
        html += '<div class="' + allCls + '" onclick="pilihKelas(\'semua\')">' +
                    '<i class="fa fa-th-large"></i> <span>Semua Kelas</span>' +
                    '<span class="chip-count">' + totalPesertaAll + '</span>' +
                '</div>';

        var shown = 0;
        for(var i = 0; i < allGroups.length; i++){
            var g = allGroups[i];
            if(kw !== '' && g.grup_nama.toLowerCase().indexOf(kw) === -1){
                continue;
            }
            var cls = (String(activeGroupId) === String(g.grup_id)) ? 'kelas-chip active' : 'kelas-chip';
            html += '<div class="' + cls + '" onclick="pilihKelas(\'' + g.grup_id + '\')">' +
                        '<span>' + escHtml(g.grup_nama) + '</span>' +
                        '<span class="chip-count">' + g.jml_peserta + '</span>' +
                    '</div>';
            shown++;
        }

        if(allGroups.length === 0){
            html += '<span class="text-muted" style="align-self:center; font-size:12.5px; margin-left:6px;">Belum ada kelas. Klik <strong>Kelola Kelas</strong> atau <strong>Import Excel</strong>.</span>';
        }else if(kw !== '' && shown === 0){
            html += '<span class="text-muted" style="align-self:center; font-size:12.5px; margin-left:6px;">Tidak ada kelas yang cocok dengan "' + escHtml(kw) + '".</span>';
        }

        $('#kelas-chip-container').html(html);
    }

    function renderGroupSelects(){
        var prevTambah = $('#tambah-group').val();
        var prevEdit = $('#edit-group').val();
        var opts = '';
        if(allGroups.length > 0){
            for(var i=0; i<allGroups.length; i++){
                opts += '<option value="' + allGroups[i].grup_id + '">' + escHtml(allGroups[i].grup_nama) + '</option>';
            }
        }else{
            opts = '<option value="">Belum ada kelas</option>';
        }
        $('#tambah-group').html(opts);
        $('#edit-group').html(opts);
        if(prevTambah) $('#tambah-group').val(prevTambah);
        if(prevEdit) $('#edit-group').val(prevEdit);
    }

    function updateActiveClassLabel(){
        if(activeGroupId === 'semua'){
            $('#label-kelas-aktif').text('Semua Kelas (' + totalPesertaAll + ' peserta)');
            $('#btn-cetak-kartu-cepat').html('<i class="fa fa-key"></i> Cetak Kartu Login');
        }else{
            var nama = '';
            var cnt = 0;
            for(var i=0; i<allGroups.length; i++){
                if(String(allGroups[i].grup_id) === String(activeGroupId)){
                    nama = allGroups[i].grup_nama;
                    cnt = allGroups[i].jml_peserta;
                    break;
                }
            }
            $('#label-kelas-aktif').text(nama + ' (' + cnt + ' peserta)');
            $('#btn-cetak-kartu-cepat').html('<i class="fa fa-key"></i> Kartu Login ' + escHtml(nama));
        }
    }

    function pilihKelas(gid){
        activeGroupId = String(gid);
        $('#group').val(activeGroupId);
        renderGroupChips();
        updateActiveClassLabel();
        $('#check').val('0');
        refresh_table();
    }

    function cetakKartuAktif(){
        var target = (activeGroupId === 'semua') ? 'semua' : activeGroupId;
        window.open('<?php echo site_url("manager/peserta_kartu/cetak_kartu"); ?>/' + target, '_blank');
    }

    // === Kelola Kelas Modal ===
    function bukaModalKelolaKelas(){
        $('#pesan-kelola-kelas').html('');
        $('#input-group-baru').val('');
        renderKelolaKelasTable();
        $('#modal-kelola-kelas').modal('show');
    }

    function renderKelolaKelasTable(){
        var html = '';
        if(allGroups.length === 0){
            html = '<tr><td colspan="5" class="text-center text-muted">Belum ada kelas terdaftar.</td></tr>';
        }else{
            for(var i=0; i<allGroups.length; i++){
                var g = allGroups[i];
                var btnHapus = '';
                if(g.jml_tes > 0){
                    btnHapus = '<button type="button" class="btn btn-default btn-xs" disabled title="Dipakai oleh ' + g.jml_tes + ' jadwal Tes"><i class="fa fa-lock"></i> Dipakai Tes</button>';
                }else{
                    btnHapus = '<button type="button" class="btn btn-danger btn-xs" onclick="konfirmasiHapusKelas(' + g.grup_id + ')"><i class="fa fa-trash"></i> Hapus</button>';
                }
                html += '<tr>' +
                    '<td class="text-center">' + (i+1) + '</td>' +
                    '<td>' +
                        '<div class="input-group input-group-sm">' +
                            '<input type="text" class="form-control" id="rename-grup-' + g.grup_id + '" value="' + escHtml(g.grup_nama) + '">' +
                            '<span class="input-group-btn">' +
                                '<button type="button" class="btn btn-primary btn-flat" onclick="simpanRenameKelas(' + g.grup_id + ')" title="Simpan nama baru"><i class="fa fa-check"></i></button>' +
                            '</span>' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center"><span class="badge bg-blue">' + g.jml_peserta + '</span></td>' +
                    '<td class="text-center">' + (g.jml_tes > 0 ? '<span class="badge bg-yellow">' + g.jml_tes + '</span>' : '<span class="text-muted">0</span>') + '</td>' +
                    '<td class="text-center">' + btnHapus + '</td>' +
                '</tr>';
            }
        }
        $('#tbody-kelola-kelas').html(html);
    }

    function simpanRenameKelas(gid){
        var nama = $.trim($('#rename-grup-' + gid).val());
        if(!nama){
            $('#pesan-kelola-kelas').html(pesan_err('Nama kelas tidak boleh kosong.'));
            return;
        }
        $('#modal-proses').modal('show');
        $.post('<?php echo site_url($url."/group_edit"); ?>', {
            'edit-grup-id': gid,
            'edit-grup-nama': nama
        }, function(respon){
            $('#modal-proses').modal('hide');
            var obj = $.parseJSON(respon);
            if(obj.status == 1){
                $('#pesan-kelola-kelas').html('');
                notify_success(obj.pesan);
                reloadGroups(function(){ refresh_table(); });
            }else{
                $('#pesan-kelola-kelas').html(pesan_err(obj.pesan));
            }
        });
    }

    function konfirmasiHapusKelas(gid){
        var target = null;
        for(var i=0; i<allGroups.length; i++){
            if(allGroups[i].grup_id == gid){ target = allGroups[i]; break; }
        }
        if(!target) return;

        $('#hapus-grup-id').val(target.grup_id);
        $('#chk-confirm-cascade').prop('checked', false);

        if(target.jml_peserta > 0){
            $('#hapus-kelas-teks').html('Anda akan menghapus kelas <strong>' + escHtml(target.grup_nama) + '</strong>.');
            $('#hapus-kelas-cascade-msg').html('Kelas ini masih memiliki <strong>' + target.jml_peserta + ' peserta</strong>. Karena relasi database, menghapus kelas akan ikut menghapus seluruh peserta tersebut beserta riwayat hasil tesnya!');
            $('#hapus-kelas-cascade-box').show();
            $('#btn-konfirm-hapus-kelas').prop('disabled', true);
        }else{
            $('#hapus-kelas-teks').html('Hapus kelas kosong <strong>' + escHtml(target.grup_nama) + '</strong>?');
            $('#hapus-kelas-cascade-box').hide();
            $('#btn-konfirm-hapus-kelas').prop('disabled', false);
        }

        $('#modal-hapus-kelas').modal('show');
    }

    // === Import Excel Modal ===
    function bukaModalImport(){
        $('#import-pesan').html('');
        $('#import-file-excel').val('');
        $('#import-token').val('');
        $('#import-preview-area').hide();
        $('#btn-import-simpan').hide();
        $('#modal-import-peserta').modal('show');
    }

    function simpanImportPeserta(){
        var token = $('#import-token').val();
        var dupMode = $('input[name="import-dup-mode"]:checked').val() || 'skip';
        if(!token) return;

        $('#modal-proses').modal('show');
        $.post('<?php echo site_url($url."/import_simpan"); ?>', {
            token: token,
            duplicate_mode: dupMode
        }, function(respon){
            $('#modal-proses').modal('hide');
            var obj = $.parseJSON(respon);
            if(obj.status == 1){
                $('#modal-import-peserta').modal('hide');
                notify_success(obj.pesan);
                reloadGroups(function(){ refresh_table(); });
            }else{
                $('#import-pesan').html(pesan_err(obj.pesan));
            }
        }).fail(function(){
            $('#modal-proses').modal('hide');
            $('#import-pesan').html(pesan_err('Gagal menghubungi server saat menyimpan data import.'));
        });
    }

    function showpassword(){
		var x = document.getElementById("edit-password");
		if (x.type === "password") {
			x.type = "text";
			$("#icon-show-password").removeClass("fa-eye").addClass("fa-eye-slash");
		} else {
			x.type = "password";
			$("#icon-show-password").removeClass("fa-eye-slash").addClass("fa-eye");
		}
    }

    function tambah(){
        $('#form-pesan').html('');
        $('#tambah-username').val('');
        $('#tambah-password').val('');
        $('#tambah-nama').val('');
        $('#tambah-email').val('');
		$('#tambah-detail').val('');
        if(activeGroupId !== 'semua'){
            $('#tambah-group').val(activeGroupId);
        }
        $("#modal-tambah").modal("show");
        $('#tambah-username').focus();
    }

    function edit(id){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_by_id/'+id+'', function(data){
            if(data.data==1){
                $('#form-pesan-edit').html('');
                $('#edit-id').val(data.id);
                $('#edit-username').val(data.username);
                $('#edit-password').val(data.password).attr('type', 'password');
                $('#icon-show-password').removeClass('fa-eye-slash').addClass('fa-eye');
                $('#edit-nama').val(data.nama);
                $('#edit-email').val(data.email);
				$('#edit-detail').val(data.detail);
                $('#edit-group').val(data.group);
                $("#modal-edit").modal("show");
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
        renderGroupChips();
        updateActiveClassLabel();

        $('#filter-kelas-input').on('keyup input', function(){
            renderGroupChips();
        });

		$("#btn-show-password").click(function(){
			showpassword();
		});

        $('#chk-confirm-cascade').change(function(){
            $('#btn-konfirm-hapus-kelas').prop('disabled', !this.checked);
        });

        $('#btn-konfirm-hapus-kelas').click(function(){
            var gid = $('#hapus-grup-id').val();
            var confirmCascade = $('#chk-confirm-cascade').is(':checked') ? '1' : '0';
            $('#modal-proses').modal('show');
            $.post('<?php echo site_url($url."/group_hapus"); ?>', {
                'hapus-grup-id': gid,
                'confirm-cascade': confirmCascade
            }, function(respon){
                $('#modal-proses').modal('hide');
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#modal-hapus-kelas').modal('hide');
                    notify_success(obj.pesan);
                    reloadGroups(function(){ refresh_table(); });
                }else{
                    $('#modal-hapus-kelas').modal('hide');
                    $('#pesan-kelola-kelas').html(pesan_err(obj.pesan));
                }
            });
        });

        $('#form-group-tambah').submit(function(){
            var nama = $.trim($('#input-group-baru').val());
            if(!nama){
                $('#pesan-kelola-kelas').html(pesan_err('Ketik nama kelas baru terlebih dahulu.'));
                return false;
            }
            $('#modal-proses').modal('show');
            $.post('<?php echo site_url($url."/group_tambah"); ?>', {
                'tambah-group': nama
            }, function(respon){
                $('#modal-proses').modal('hide');
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    $('#input-group-baru').val('');
                    $('#pesan-kelola-kelas').html('');
                    notify_success(obj.pesan);
                    reloadGroups();
                }else{
                    $('#pesan-kelola-kelas').html(pesan_err(obj.pesan));
                }
            });
            return false;
        });

        // Preview Import Excel
        $('#form-import-preview').submit(function(){
            var fileInput = $('#import-file-excel')[0];
            if(!fileInput.files || fileInput.files.length === 0){
                $('#import-pesan').html(pesan_err('Pilih file Excel (.xlsx / .xls) terlebih dahulu.'));
                return false;
            }
            var formData = new FormData(this);
            $('#import-pesan').html('');
            $('#modal-proses').modal('show');
            $.ajax({
                url: '<?php echo site_url($url."/import_preview"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(respon){
                    $('#modal-proses').modal('hide');
                    var obj = $.parseJSON(respon);
                    if(obj.status != 1){
                        $('#import-pesan').html(pesan_err(obj.pesan));
                        $('#import-preview-area').hide();
                        $('#btn-import-simpan').hide();
                        return;
                    }

                    $('#import-token').val(obj.token);
                    var st = obj.stats;
                    var badges = '<span class="label label-default" style="font-size:12px; margin-right:5px;">Total: ' + st.total_rows + ' baris</span>' +
                                 '<span class="label label-success" style="font-size:12px; margin-right:5px;">Peserta Baru: ' + st.valid_new + '</span>' +
                                 '<span class="label label-primary" style="font-size:12px; margin-right:5px;">Kelas Baru Otomatis: ' + obj.new_groups_count + '</span>';
                    if(st.existing_users > 0){
                        badges += '<span class="label label-warning" style="font-size:12px; margin-right:5px;">Sudah Terdaftar: ' + st.existing_users + '</span>';
                    }
                    if(st.invalid_rows + st.dup_in_file > 0){
                        badges += '<span class="label label-danger" style="font-size:12px;">Bermasalah: ' + (st.invalid_rows + st.dup_in_file) + '</span>';
                    }
                    $('#import-badges').html(badges);

                    // Ringkasan kelas
                    var gHtml = '';
                    for(var i=0; i<obj.groups_summary.length; i++){
                        var gs = obj.groups_summary[i];
                        var isNew = gs.status === 'new';
                        gHtml += '<span class="kelas-badge-item ' + (isNew ? 'is-new' : '') + '">' +
                                    '<strong>' + escHtml(gs.nama) + '</strong> (' + gs.count + ') ' +
                                    (isNew ? '<span class="label label-success" style="font-size:10px;">Baru</span>' : '<span class="label label-default" style="font-size:10px;">Ada</span>') +
                                 '</span>';
                    }
                    if(!gHtml) gHtml = '<span class="text-muted">Tidak ada kelas valid.</span>';
                    $('#import-groups-list').html(gHtml);

                    if(st.existing_users > 0){
                        $('#import-dup-option').show();
                    }else{
                        $('#import-dup-option').hide();
                    }

                    // Preview tabel
                    var rHtml = '';
                    for(var j=0; j<obj.preview_rows.length; j++){
                        var r = obj.preview_rows[j];
                        var stBadge = '';
                        var trCls = '';
                        if(r.status === 'new'){
                            stBadge = '<span class="label label-success">Siap Tambah</span>';
                        }else if(r.status === 'existing'){
                            stBadge = '<span class="label label-warning">Username Sudah Ada</span>';
                            trCls = 'warning';
                        }else{
                            stBadge = '<span class="label label-danger">' + escHtml(r.errors.join('; ')) + '</span>';
                            trCls = 'danger';
                        }
                        if(r.warnings && r.warnings.length > 0){
                            stBadge += ' <small class="text-warning" title="' + escHtml(r.warnings.join('; ')) + '"><i class="fa fa-info-circle"></i></small>';
                        }
                        rHtml += '<tr class="' + trCls + '">' +
                                    '<td>' + r.row + '</td>' +
                                    '<td><code>' + escHtml(r.username) + '</code></td>' +
                                    '<td>' + escHtml(r.nama) + '</td>' +
                                    '<td>' + escHtml(r.group) + (r.group_is_new ? ' <span class="label label-success" style="font-size:9px;">Baru</span>' : '') + '</td>' +
                                    '<td>' + escHtml(r.detail || '-') + '</td>' +
                                    '<td>' + stBadge + '</td>' +
                                 '</tr>';
                    }
                    $('#import-preview-tbody').html(rHtml);

                    if(obj.preview_truncated){
                        $('#import-truncated-note').text('Menampilkan 100 baris pertama + semua baris bermasalah. Seluruh ' + st.total_rows + ' baris akan diproses saat klik Simpan.').show();
                    }else{
                        $('#import-truncated-note').hide();
                    }

                    $('#import-preview-area').show();
                    if(st.valid_new > 0 || st.existing_users > 0){
                        $('#btn-import-simpan').show();
                    }else{
                        $('#btn-import-simpan').hide();
                    }
                },
                error: function(){
                    $('#modal-proses').modal('hide');
                    $('#import-pesan').html(pesan_err('Gagal mengupload file Excel untuk preview.'));
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
        $('#btn-edit-pilih').click(function(event) {
            if($('#check').val()==0) {
                $('.chk-peserta').prop('checked', true);
                $('#check').val('1');
            }else{
                $('.chk-peserta').prop('checked', false);
                $('#check').val('0');
            }
        });
        $('#btn-edit-hapus').click(function(){
            if($('.chk-peserta:checked').length === 0){
                notify_error('Centang minimal satu peserta yang akan dihapus.');
                return;
            }
            $("#modal-hapus").modal('show');
        });
        $('#btn-hapus').click(function(){
            $("#form-hapus").submit();
        });

        $('#form-hapus').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/hapus_daftar_siswa",
                    type:"POST",
                    data:$('#form-hapus').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            $("#modal-hapus").modal('hide');
                            notify_success(obj.pesan);
                            $('#check').val('0');
                            reloadGroups(function(){ refresh_table(); });
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(obj.pesan);
                        }
                    }
            });
            return false;
        });

        $('#form-edit').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/edit",
                    type:"POST",
                    data:$('#form-edit').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            $("#modal-edit").modal('hide');
                            notify_success(obj.pesan);
                            reloadGroups(function(){ refresh_table(); });
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan-edit').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

        $('#form-tambah').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/tambah",
                    type:"POST",
                    data:$('#form-tambah').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            $("#modal-tambah").modal('hide');
                            notify_success(obj.pesan);
                            reloadGroups(function(){ refresh_table(); });
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

        $('#table-peserta').DataTable({
            "paging": true,
            "iDisplayLength": 25,
            "aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "bProcessing": false,
            "bServerSide": true,
            "searching": true,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth": "40px"},
                {"bSearchable": false, "bSortable": false},
                {"bSearchable": false, "bSortable": false},
                {"bSearchable": false, "bSortable": false, "sWidth": "140px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "155px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "75px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "30px"}
            ],
            "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
            "autoWidth": false,
            "responsive": true,
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "group", "value": $('#group').val() } );
            }
        });
    });
</script>
