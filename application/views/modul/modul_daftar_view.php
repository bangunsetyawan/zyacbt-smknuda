<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Daftar Soal
		<small>Daftar soal dan jawaban berdasarkan Modul dan Topik (Mata Pelajaran)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Soal</li>
	</ol>
</section>

<style>
    .topik-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }
    .topik-toolbar .form-control {
        height: 30px;
        padding: 4px 8px;
        font-size: 12px;
    }
    .topik-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 8px;
        max-height: 270px;
        overflow-y: auto;
        padding: 2px;
    }
    .topik-chip {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: left;
        background: #f9fafc;
        border: 1px solid #d2d6de;
        border-radius: 4px;
        padding: 8px 10px;
        cursor: pointer;
        transition: all 0.12s ease;
        width: 100%;
    }
    .topik-chip:hover {
        border-color: #3c8dbc;
        background: #eef6fb;
    }
    .topik-chip.is-active {
        background: #3c8dbc;
        border-color: #367fa9;
        color: #ffffff;
        box-shadow: 0 2px 5px rgba(60, 141, 188, 0.3);
    }
    .topik-chip-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
    }
    .topik-chip-title {
        font-weight: 600;
        font-size: 13px;
        line-height: 1.3;
        word-break: break-word;
    }
    .topik-chip-count {
        flex-shrink: 0;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 10px;
        background: #e4e9f0;
        color: #333;
    }
    .topik-chip.is-active .topik-chip-count {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }
    .topik-chip-meta {
        margin-top: 4px;
        font-size: 11px;
        color: #777;
        display: flex;
        justify-content: space-between;
        gap: 6px;
    }
    .topik-chip.is-active .topik-chip-meta {
        color: rgba(255, 255, 255, 0.88);
    }
    .topik-empty {
        padding: 18px;
        text-align: center;
        color: #777;
        background: #fafafa;
        border: 1px dashed #d2d6de;
        border-radius: 4px;
    }
    .soal-preview-content img {
        max-width: 100%;
        height: auto;
        border-radius: 3px;
    }
    .soal-detail-table > tbody > tr > td {
        vertical-align: top;
    }
    .daftar-soal-actions {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }
</style>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-th-list"></i> Pilih Topik (Mata Pelajaran)
                        <span id="ringkasan-modul" class="label label-info" style="margin-left:6px; font-size:11px;"></span>
                    </h3>
                    <div class="box-tools pull-right topik-toolbar" style="position:static; margin-top:-2px;">
                        <select id="filter-modul" class="form-control" style="width:auto; min-width:210px;" title="Pilih Modul">
                            <?php if(!empty($modul_list)){ ?>
                                <?php foreach($modul_list as $m){ ?>
                                    <option value="<?php echo $m['modul_id']; ?>" <?php echo ($m['modul_id'] == $active_modul_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($m['modul_nama'], ENT_QUOTES, 'UTF-8'); ?><?php echo ($m['modul_aktif'] == 1) ? ' (Aktif)' : ''; ?> [<?php echo $m['jml_topik']; ?> mapel]
                                    </option>
                                <?php } ?>
                                <?php if(count($modul_list) > 1){ ?>
                                    <option value="all">-- Semua Modul (<?php echo count($topik_list); ?> mapel) --</option>
                                <?php } ?>
                            <?php }else{ ?>
                                <option value="all">Tidak Ada Modul</option>
                            <?php } ?>
                        </select>
                        <input type="text" id="cari-topik" class="form-control" style="width:185px;" placeholder="Cari mata pelajaran...">
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <input type="hidden" name="topik" id="topik" value="kosong">
                    <div id="grid-topik" class="topik-grid"></div>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
					<h3 class="box-title">
                        Daftar Soal: <strong id="judul-daftar-soal">-</strong>
                        <span id="meta-daftar-soal" style="margin-left:6px; font-size:12px;"></span>
                    </h3>
                    <div class="box-tools pull-right daftar-soal-actions" style="position:static;">
                        <button type="button" id="btn-prev-topik" class="btn btn-xs btn-default" title="Pindah ke mata pelajaran sebelumnya">
                            <i class="fa fa-chevron-left"></i> Mapel Sebelumnya
                        </button>
                        <button type="button" id="btn-next-topik" class="btn btn-xs btn-default" title="Pindah ke mata pelajaran berikutnya">
                            Mapel Berikutnya <i class="fa fa-chevron-right"></i>
                        </button>
                        <button type="button" id="btn-cetak-soal" class="btn btn-xs btn-primary" onclick="cetak_soal()">
                            <i class="fa fa-print"></i> Cetak Daftar Soal
                        </button>
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <table id="table-soal" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tipe Soal</th>
                                <th>Soal &amp; Kunci Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> </td>
								<td> </td>
                                <td> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    var ALL_TOPIK = <?php echo json_encode(!empty($topik_list) ? $topik_list : array()); ?>;
    var VISIBLE_TOPIK = [];
    var ACTIVE_TOPIK_ID = null;
    var SOAL_TABLE = null;

    function escapeHtml(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatKomposisi(t) {
        var parts = [];
        if (t.jml_pg > 0) parts.push(t.jml_pg + ' PG');
        if (t.jml_esai > 0) parts.push(t.jml_esai + ' Esai');
        if (t.jml_singkat > 0) parts.push(t.jml_singkat + ' Singkat');
        return parts.length > 0 ? parts.join(' &middot; ') : 'Belum ada soal';
    }

    function refresh_table() {
        if (!SOAL_TABLE) return;
        var oSettings = SOAL_TABLE.fnSettings();
        oSettings._iDisplayStart = 0;
        SOAL_TABLE.fnDraw(false);
    }

    function cetak_soal() {
        var topik_id = $('#topik').val();
        if (!topik_id || topik_id === 'kosong') {
            return;
        }
        window.open('<?php echo site_url().'/'.$url; ?>/cetak_soal/' + topik_id, '_blank');
    }

    function updateNavButtons() {
        var idx = -1;
        for (var i = 0; i < VISIBLE_TOPIK.length; i++) {
            if (String(VISIBLE_TOPIK[i].topik_id) === String(ACTIVE_TOPIK_ID)) {
                idx = i;
                break;
            }
        }
        $('#btn-prev-topik').prop('disabled', idx <= 0);
        $('#btn-next-topik').prop('disabled', idx === -1 || idx >= VISIBLE_TOPIK.length - 1);
        $('#btn-cetak-soal').prop('disabled', !ACTIVE_TOPIK_ID || ACTIVE_TOPIK_ID === 'kosong');
    }

    function selectTopik(topikId, reloadTable) {
        var found = null;
        for (var i = 0; i < ALL_TOPIK.length; i++) {
            if (String(ALL_TOPIK[i].topik_id) === String(topikId)) {
                found = ALL_TOPIK[i];
                break;
            }
        }

        if (!found) {
            ACTIVE_TOPIK_ID = null;
            $('#topik').val('kosong');
            $('#judul-daftar-soal').text('Belum ada topik dipilih');
            $('#meta-daftar-soal').html('');
        } else {
            ACTIVE_TOPIK_ID = found.topik_id;
            $('#topik').val(found.topik_id);
            $('#judul-daftar-soal').text(found.topik_nama);
            $('#meta-daftar-soal').html(
                '<span class="label label-default">' + escapeHtml(found.modul_nama) + '</span> ' +
                '<span class="label label-success">' + found.jml_soal + ' soal</span> ' +
                '<span class="text-muted" style="font-size:12px;">(' + formatKomposisi(found) + ')</span>'
            );
        }

        $('#grid-topik .topik-chip').removeClass('is-active');
        if (ACTIVE_TOPIK_ID !== null) {
            $('#grid-topik .topik-chip[data-topik-id="' + ACTIVE_TOPIK_ID + '"]').addClass('is-active');
        }

        updateNavButtons();
        if (reloadTable) {
            refresh_table();
        }
    }

    function renderTopikGrid(autoSelectFirstIfMissing) {
        var modulVal = $('#filter-modul').val();
        var keyword = $.trim($('#cari-topik').val()).toLowerCase();
        var showModulBadge = (modulVal === 'all');

        VISIBLE_TOPIK = [];
        var totalSoalFiltered = 0;

        for (var i = 0; i < ALL_TOPIK.length; i++) {
            var t = ALL_TOPIK[i];
            if (modulVal !== 'all' && String(t.modul_id) !== String(modulVal)) {
                continue;
            }
            if (keyword !== '' && t.topik_nama.toLowerCase().indexOf(keyword) === -1 && t.modul_nama.toLowerCase().indexOf(keyword) === -1) {
                continue;
            }
            VISIBLE_TOPIK.push(t);
            totalSoalFiltered += parseInt(t.jml_soal, 10) || 0;
        }

        $('#ringkasan-modul').text(VISIBLE_TOPIK.length + ' Mapel · ' + totalSoalFiltered.toLocaleString('id-ID') + ' Soal');

        var $grid = $('#grid-topik');
        if (VISIBLE_TOPIK.length === 0) {
            $grid.removeClass('topik-grid').html(
                '<div class="topik-empty"><i class="fa fa-info-circle"></i> Tidak ada topik (mata pelajaran) yang cocok pada modul ini.</div>'
            );
            if (autoSelectFirstIfMissing) {
                selectTopik(null, true);
            } else {
                updateNavButtons();
            }
            return;
        }

        $grid.addClass('topik-grid');
        var html = '';
        var currentStillVisible = false;

        for (var j = 0; j < VISIBLE_TOPIK.length; j++) {
            var item = VISIBLE_TOPIK[j];
            var isAct = (String(item.topik_id) === String(ACTIVE_TOPIK_ID));
            if (isAct) currentStillVisible = true;

            html += '<button type="button" class="topik-chip' + (isAct ? ' is-active' : '') + '" data-topik-id="' + item.topik_id + '">';
            html += '  <div class="topik-chip-head">';
            html += '    <span class="topik-chip-title">' + escapeHtml(item.topik_nama) + '</span>';
            html += '    <span class="topik-chip-count">' + item.jml_soal + '</span>';
            html += '  </div>';
            html += '  <div class="topik-chip-meta">';
            html += '    <span>' + formatKomposisi(item) + '</span>';
            if (showModulBadge) {
                html += '    <span>' + escapeHtml(item.modul_nama) + '</span>';
            }
            html += '  </div>';
            html += '</button>';
        }
        $grid.html(html);

        if (autoSelectFirstIfMissing && !currentStillVisible) {
            selectTopik(VISIBLE_TOPIK[0].topik_id, SOAL_TABLE !== null);
        } else {
            updateNavButtons();
        }
    }

    $(function(){
        // Render grid topik & pilih topik pertama dari modul aktif sebelum DataTable init
        renderTopikGrid(true);

        $('#grid-topik').on('click', '.topik-chip', function(){
            var tid = $(this).data('topik-id');
            if (String(tid) !== String(ACTIVE_TOPIK_ID)) {
                selectTopik(tid, true);
            }
        });

        $('#filter-modul').on('change', function(){
            $('#cari-topik').val('');
            renderTopikGrid(true);
        });

        $('#cari-topik').on('input', function(){
            renderTopikGrid(false);
        });

        $('#btn-prev-topik').on('click', function(){
            for (var i = 0; i < VISIBLE_TOPIK.length; i++) {
                if (String(VISIBLE_TOPIK[i].topik_id) === String(ACTIVE_TOPIK_ID) && i > 0) {
                    selectTopik(VISIBLE_TOPIK[i - 1].topik_id, true);
                    break;
                }
            }
        });

        $('#btn-next-topik').on('click', function(){
            for (var i = 0; i < VISIBLE_TOPIK.length; i++) {
                if (String(VISIBLE_TOPIK[i].topik_id) === String(ACTIVE_TOPIK_ID) && i < VISIBLE_TOPIK.length - 1) {
                    selectTopik(VISIBLE_TOPIK[i + 1].topik_id, true);
                    break;
                }
            }
        });

        SOAL_TABLE = $('#table-soal').dataTable({
            "paging": true,
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "bProcessing": true,
            "bServerSide": true,
            "searching": true,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth": "40px"},
                {"bSearchable": false, "bSortable": false, "sWidth": "110px"},
                {"bSearchable": false, "bSortable": false}
            ],
            "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
            "autoWidth": false,
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "topik", "value": $('#topik').val() } );
            }
        });
    });
</script>
