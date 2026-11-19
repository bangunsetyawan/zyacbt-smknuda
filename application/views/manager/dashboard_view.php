<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?php if(!empty($site_name)){ echo $site_name; } ?>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Tribute</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            Teruntuk Putri kami tercinta. Asyfiya Aniqa Putri, 28 Februari 2018 – 1 Maret 2018
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    <div class="box box-primary collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Perjanjian Penggunaan</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <dl>
                <dd>
                    Dengan menggunakan ZYA CBT, maka anda setuju untuk :
                    <ol>
                        <li>Tidak mengubah Nama Aplikasi Ujian Online <b>ZYA CBT</b> menjadi nama aplikasi lain</li>
                        <li>Tidak mengubah footer yang menunjukkan alamat website Aplikasi Ujian Online ZYA CBT</li>
                        <li>Tidak menjual Aplikasi Ujian Online ZYA CBT</li>
						<li>Tidak mengambil keuntungan dari Aplikasi Ujian Online ZYA CBT tanpa ijin</li>
                        <li>Tidak menghapus tribute dan Perjanjian Penggunaan</li>
                    </ol>
                    Semoga Aplikasi Ujian Online ZYA CBT dapat bermanfaat untuk kita semua.
                </dd>
            </dl>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <!-- 4 Info Box Statistik Operasional -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo isset($total_grup) ? (int)$total_grup : 0; ?></h3>
                    <p>Total Kelas / Grup</p>
                </div>
                <div class="icon">
                    <i class="fa fa-sitemap"></i>
                </div>
                <a href="<?php echo site_url('manager/peserta_group'); ?>" class="small-box-footer">Kelola Grup <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo isset($total_peserta) ? (int)$total_peserta : 0; ?></h3>
                    <p>Total Peserta</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="<?php echo site_url('manager/peserta_daftar'); ?>" class="small-box-footer">Daftar Peserta <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo isset($total_topik) ? (int)$total_topik : 0; ?> <small style="color:#fff;font-size:55%;">/ <?php echo isset($total_soal) ? (int)$total_soal : 0; ?> Soal</small></h3>
                    <p>Total Topik / Bank Soal</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
                <a href="<?php echo site_url('manager/modul_daftar'); ?>" class="small-box-footer">Daftar Soal <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?php echo isset($total_jadwal_hari_ini) ? (int)$total_jadwal_hari_ini : 0; ?></h3>
                    <p>Jadwal Ujian Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <a href="<?php echo site_url('manager/tes_daftar'); ?>" class="small-box-footer">Daftar Tes <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal & Pantauan Ujian Hari Ini -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tv"></i> Jadwal &amp; Pantauan Ujian Hari Ini <small>(<?php echo date('d M Y'); ?>)</small></h3>
            <div class="box-tools pull-right">
                <a href="<?php echo site_url('manager/tes_tambah'); ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Tambah Tes</a>
                <a href="<?php echo site_url('manager/tes_hasil'); ?>" class="btn btn-default btn-xs"><i class="fa fa-bar-chart"></i> Hasil Tes</a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <?php if(!empty($jadwal_hari_ini)): ?>
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th style="width:40px;" class="text-center">No</th>
                        <th>Nama Ujian</th>
                        <th style="width:155px;">Jam Sesi</th>
                        <th>Kelas / Grup</th>
                        <th style="width:105px;" class="text-center">Status Sesi</th>
                        <th style="width:170px;" class="text-center">Pantauan Siswa</th>
                        <th style="width:110px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($jadwal_hari_ini as $item): ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($item->tes_nama, ENT_QUOTES, 'UTF-8'); ?></strong>
                            <div style="margin-top:3px;">
                                <span class="label <?php echo $item->kategori_class; ?>" <?php if(!empty($item->kategori_style)){ echo 'style="'.$item->kategori_style.'"'; } ?>><?php echo $item->kategori; ?></span>
                                <span class="label label-default"><?php echo (int)$item->total_soal; ?> Soal</span>
                                <span class="label label-default"><?php echo (int)$item->tes_duration_time; ?> Menit</span>
                            </div>
                        </td>
                        <td>
                            <i class="fa fa-clock-o text-muted"></i>
                            <?php echo date('H:i', strtotime($item->tes_begin_time)); ?> - <?php echo date('H:i', strtotime($item->tes_end_time)); ?> WIB
                            <br /><small class="text-muted"><?php echo date('d/m', strtotime($item->tes_begin_time)); ?> s/d <?php echo date('d/m', strtotime($item->tes_end_time)); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($item->grup_text, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center">
                            <span class="label <?php echo $item->status_class; ?>"><?php echo $item->status_sesi; ?></span>
                        </td>
                        <td class="text-center">
                            <span class="label label-warning" title="Sedang Mengerjakan"><i class="fa fa-pencil"></i> <?php echo (int)$item->jml_mengerjakan; ?> Aktif</span>
                            <span class="label label-success" title="Selesai Mengerjakan"><i class="fa fa-check"></i> <?php echo (int)$item->jml_selesai; ?> Selesai</span>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo site_url('manager/tes_hasil?tes_id='.$item->tes_id); ?>" class="btn btn-info btn-xs" title="Buka Hasil & Kontrol Proktor"><i class="fa fa-eye"></i> Hasil Tes</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding:20px;" class="text-center text-muted">
                <i class="fa fa-calendar-times-o fa-2x" style="margin-bottom:8px;"></i>
                <p style="margin-bottom:8px;">Belum ada jadwal ujian untuk hari ini (<?php echo date('d M Y'); ?>).</p>
                <a href="<?php echo site_url('manager/tes_tambah'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Buat Jadwal Tes</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

	<div class="callout callout-info">
    	<h4>Informasi</h4>
        <p>Ini adalah area administratif ZYA CBT, yang memiliki platform dan bahasa user-friendly untuk membuat, mengelola dan melaksanakan ujian online.</p>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <div class="box-title">Konfigurasi System</div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <b><u>Waktu Server</u></b>
                    <br />
                    <b><?php if(!empty($waktu_server)){ echo $waktu_server; } ?></b>
                    <br />
					<u>Timezone</u>
                    <br />
                    <b><?php if(!empty($timezone)){ echo $timezone; } ?></b>
                    <br />
                    Pastikan waktu server sesuai dengan waktu saat ini. Jika ada perbedaan, cek timezone server dan timezone di konfigurasi PHP.
                </div>
                <div class="col-md-4">
                    <b><u>Informasi Konfigurasi Upload PHP</u></b>
                    <br />
                    POST_MAX_SIZE = <?php if(!empty($post_max_size)){ echo $post_max_size; } ?>
                    <br />
                    UPLOAD_MAX_FILESIZE = <?php if(!empty($upload_max_filesize)){ echo $upload_max_filesize; } ?>
                </div>
                <div class="col-md-4">
                    <b><u>Folder Upload</u></b>
                    <br />
                    Folder "uploads" = <span class="label <?php echo (!empty($dir_uploads) && $dir_uploads=='Writeable') ? 'label-success' : 'label-danger'; ?>"><?php if(!empty($dir_uploads)){ echo $dir_uploads; } ?></span>
                    <br />
                    Folder "public/uploads" = <span class="label <?php echo (!empty($dir_public_uploads) && $dir_public_uploads=='Writeable') ? 'label-success' : 'label-danger'; ?>"><?php if(!empty($dir_public_uploads)){ echo $dir_public_uploads; } ?></span>
                    <br />
                    Pastikan kedua folder diatas memiliki nilai Writeable.
                </div>
            </div>
            <p>
            </p>
        </div>
    </div>
    <div class="box box-success box-solid">
		<div class="box-header with-border">
        	<h3 class="box-title">Petunjuk Penggunaan</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
        	<dl>
        		<dt>Data Modul</dt>
                <dd>
                	Kelompok Data Modul digunakan untuk menambah modul, topik, dan soal. Serta digunakan untuk mengatur file dengan memanfaatkan File Manager.
                	<ol>
                		<li>Topik</li>
                		<li>Soal</li>
                		<li>Import Soal Word</li>
						<li>Import Soal Spreadsheet</li>
                		<li>Daftar Soal</li>
                		<li>File Manager</li>
                	</ol>
                </dd>
                <dt>Data Peserta</dt>
                <dd>
                	Kelompok Data Peserta digunakan untuk mengatur Peserta, dan Group.
                	<ol>
                		<li>Daftar Group</li>
                		<li>Daftar Peserta</li>
                		<li>Import Data Peserta</li>
						<li>Cetak Kartu</li>
						<li>Reset Login</li>
                	</ol>
                <dt>Data Tes</dt>
                <dd>
                	Kelompok Data Tes digunakan untuk mengatur Tes, mengevaluasi jawaban essay, dan melihat Hasil tes.
                	<ol>
                		<li>Tambah Tes</li>
                		<li>Daftar Tes</li>
                		<li>Evaluasi Tes</li>
                		<li>Hasil tes</li>
                		<li>Token</li>
                	</ol>
                </dd>
				<dt>Laporan</dt>
                <dd>
                	Laporan digunakan untuk menampilkan analisis butir soal, dan rekap hasil tes.
                	<ol>
                		<li>Analisis Butir Soal</li>
                        <li>Rekap Hasil Tes</li>
                	</ol>
                </dd>
                <dt>Tool</dt>
                <dd>
                    Kelompok Tool digunakan untuk membackup database, file pedukung soal, dan Export Import Data Soal
                    <ol>
                        <li>Backup Data</li>
                        <li>Export Import Soal</li>
                    </ol>
                </dd>
            </dl>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</section><!-- /.content -->