<style type="text/css">
	.cbt-dash-wrap {
		max-width: 720px;
		margin: 0 auto 30px auto;
		padding: 16px 12px 0 12px;
	}
	/* Kartu Profil Siswa */
	.cbt-profile-card {
		background: #ffffff;
		border-radius: 12px;
		border-left: 5px solid #00a65a;
		box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
		padding: 16px 18px;
		margin-bottom: 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		flex-wrap: wrap;
	}
	.cbt-profile-main {
		display: flex;
		align-items: center;
		gap: 14px;
	}
	.cbt-profile-avatar {
		width: 48px;
		height: 48px;
		border-radius: 50%;
		background: #e8f8f0;
		color: #00a65a;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
		flex-shrink: 0;
	}
	.cbt-profile-greeting {
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.6px;
		color: #00a65a;
		margin: 0 0 3px 0;
	}
	.cbt-profile-name {
		font-size: 20px;
		font-weight: 800;
		color: #1f2d3d;
		margin: 0 0 6px 0;
		line-height: 1.25;
	}
	.cbt-profile-badges {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
	}
	.cbt-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 4px 12px;
		border-radius: 20px;
		font-size: 13.5px;
		font-weight: 700;
	}
	.cbt-badge-class {
		background: #edf2f7;
		color: #2d3748;
	}
	.cbt-badge-room {
		background: #e6fffa;
		color: #00733e;
	}

	/* Kotak Informasi */
	.cbt-info-card {
		background: #f0f9ff;
		border: 1px solid #bae6fd;
		border-left: 5px solid #0284c7;
		border-radius: 10px;
		padding: 14px 16px;
		margin-bottom: 20px;
		color: #0c4a6e;
	}
	.cbt-info-title {
		display: flex;
		align-items: center;
		gap: 7px;
		font-size: 15.5px;
		font-weight: 800;
		margin: 0 0 6px 0;
		color: #0369a1;
	}
	.cbt-info-body {
		font-size: 15px;
		line-height: 1.55;
		margin: 0;
	}
	.cbt-info-body p:last-child {
		margin-bottom: 0;
	}

	/* Judul Seksi Daftar Tes + Tanggal Hari Ini + Tombol Refresh */
	.cbt-section-header {
		background: #ffffff;
		border: 1.5px solid #cbd5e1;
		border-radius: 12px;
		padding: 13px 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
		margin-bottom: 14px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
	}
	.cbt-section-title {
		font-size: 18px;
		font-weight: 800;
		color: #0f172a;
		margin: 0 0 3px 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}
	.cbt-section-date {
		font-size: 13.5px;
		font-weight: 700;
		color: #475569;
		margin: 0;
	}
	.cbt-btn-refresh {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 7px;
		background: #00a65a;
		color: #ffffff !important;
		border: 1.5px solid #008d4c;
		font-size: 14.5px;
		font-weight: 800;
		padding: 9px 15px;
		border-radius: 9px;
		cursor: pointer;
		text-decoration: none !important;
		box-shadow: 0 3px 8px rgba(0, 166, 90, 0.22);
		white-space: nowrap;
	}
	.cbt-btn-refresh:hover,
	.cbt-btn-refresh:focus {
		background: #008d4c;
		color: #ffffff !important;
	}

	/* Kartu Ujian (Mobile / PWA First) */
	.cbt-test-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1.5px solid #cbd5e1;
		border-left: 6px solid #00a65a;
		box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
		padding: 18px;
		margin-bottom: 16px;
		transition: transform 0.15s ease, box-shadow 0.15s ease;
	}
	.cbt-test-card.is-essay {
		border-left-color: #2563eb;
	}
	.cbt-test-card.is-mixed {
		border-left-color: #7c3aed;
	}
	.cbt-test-card.is-upcoming {
		background: #fcfcfd;
		border-color: #cbd5e1;
	}
	.cbt-test-card-badges {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 8px;
		flex-wrap: wrap;
		margin-bottom: 10px;
	}
	.cbt-type-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 12px;
		border-radius: 7px;
		font-size: 12.5px;
		font-weight: 800;
		letter-spacing: 0.4px;
		text-transform: uppercase;
	}
	.cbt-type-pg {
		background: #e8f8f0;
		color: #00733e;
		border: 1px solid #86efac;
	}
	.cbt-type-essay {
		background: #eff6ff;
		color: #1d4ed8;
		border: 1px solid #93c5fd;
	}
	.cbt-type-mixed {
		background: #f5f3ff;
		color: #6d28d9;
		border: 1px solid #c4b5fd;
	}
	.cbt-test-card-top {
		margin-bottom: 14px;
	}
	.cbt-test-title {
		font-size: 20px;
		font-weight: 800;
		color: #0f172a;
		margin: 0 0 4px 0;
		line-height: 1.3;
	}
	.cbt-test-sub {
		font-size: 14.5px;
		color: #475569;
		margin: 0;
	}
	.cbt-status-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 12px;
		border-radius: 20px;
		font-size: 13px;
		font-weight: 800;
		white-space: nowrap;
		flex-shrink: 0;
	}
	.cbt-status-ready {
		background: #dcfce7;
		color: #15803d;
		border: 1px solid #86efac;
	}
	.cbt-status-ongoing {
		background: #fef3c7;
		color: #b45309;
		border: 1px solid #fcd34d;
	}
	.cbt-status-upcoming {
		background: #f1f5f9;
		color: #334155;
		border: 1px solid #94a3b8;
	}
	.cbt-status-done {
		background: #e0f2fe;
		color: #0369a1;
		border: 1px solid #7dd3fc;
	}
	.cbt-status-expired {
		background: #fee2e2;
		color: #b91c1c;
		border: 1px solid #fca5a5;
	}

	/* Grid Info Waktu & Durasi (1 Baris Bersih Tanpa Info Dobel) */
	.cbt-test-meta {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		background: #f8fafc;
		border: 1.5px solid #e2e8f0;
		border-radius: 10px;
		padding: 13px 15px;
		margin-bottom: 16px;
	}
	.cbt-meta-item {
		display: flex;
		flex-direction: column;
	}
	.cbt-meta-label {
		font-size: 12px;
		font-weight: 800;
		text-transform: uppercase;
		color: #64748b;
		margin-bottom: 4px;
	}
	.cbt-meta-value {
		font-size: 17px;
		font-weight: 800;
		color: #0f172a;
	}
	.cbt-score-banner {
		grid-column: 1 / -1;
		border-top: 1px dashed #cbd5e1;
		padding-top: 10px;
		margin-top: 2px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-size: 15.5px;
		font-weight: 800;
		color: #0f172a;
	}

	/* Tombol Aksi Utama */
	.cbt-btn-action {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 9px;
		width: 100%;
		min-height: 54px;
		padding: 10px 14px;
		font-size: 17px;
		font-weight: 800;
		letter-spacing: 0.4px;
		border-radius: 10px !important;
		text-decoration: none !important;
		box-shadow: 0 4px 12px rgba(0, 166, 90, 0.25);
		white-space: normal;
		text-align: center;
		line-height: 1.25;
	}
	.cbt-btn-action-essay {
		background: #2563eb !important;
		border-color: #1d4ed8 !important;
		color: #ffffff !important;
		box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
	}
	.cbt-btn-action-essay:hover,
	.cbt-btn-action-essay:focus {
		background: #1d4ed8 !important;
		color: #ffffff !important;
	}
	.cbt-btn-action-ongoing {
		box-shadow: 0 4px 12px rgba(243, 156, 18, 0.25);
	}
	.cbt-btn-locked {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 9px;
		width: 100%;
		min-height: 52px;
		padding: 10px 14px;
		font-size: 15.5px;
		font-weight: 800;
		border-radius: 10px;
		text-align: center;
		line-height: 1.3;
		user-select: none;
	}
	.cbt-btn-locked-upcoming {
		background: #f1f5f9;
		border: 2px dashed #94a3b8;
		color: #334155;
	}
	.cbt-btn-locked-done {
		background: #ecfdf5;
		border: 2px solid #6ee7b7;
		color: #047857;
	}
	.cbt-btn-locked-expired {
		background: #fef2f2;
		border: 2px solid #fca5a5;
		color: #b91c1c;
	}

	.cbt-empty-state {
		background: #ffffff;
		border-radius: 12px;
		border: 1px dashed #cbd5e1;
		padding: 36px 20px;
		text-align: center;
		color: #64748b;
	}
	.cbt-empty-state i {
		font-size: 38px;
		color: #94a3b8;
		margin-bottom: 10px;
		display: block;
	}
	.cbt-empty-state h4 {
		font-size: 16px;
		font-weight: 700;
		color: #334155;
		margin: 0 0 6px 0;
	}
	@media (max-width: 480px) {
		.cbt-test-meta {
			grid-template-columns: 1fr 1fr;
		}
		.cbt-test-title {
			font-size: 17px;
		}
	}
</style>

<div class="container">
	<div class="cbt-dash-wrap">
		<!-- Kartu Profil Siswa -->
		<div class="cbt-profile-card">
			<div class="cbt-profile-main">
				<div class="cbt-profile-avatar">
					<i class="fa fa-graduation-cap"></i>
				</div>
				<div>
					<p class="cbt-profile-greeting">Selamat Datang Peserta Ujian</p>
					<h1 class="cbt-profile-name"><?php if(!empty($nama)){ echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); } ?></h1>
					<div class="cbt-profile-badges">
						<?php if(!empty($group)){ ?>
						<span class="cbt-badge cbt-badge-class">
							<i class="fa fa-users"></i> Kelas / Grup: <?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>
						</span>
						<?php } ?>
						<?php if(!empty($user_detail)){ ?>
						<span class="cbt-badge cbt-badge-room">
							<i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($user_detail, ENT_QUOTES, 'UTF-8'); ?>
						</span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Kotak Informasi -->
		<div class="cbt-info-card">
			<h4 class="cbt-info-title"><i class="fa fa-info-circle"></i> Informasi Pelaksanaan Ujian</h4>
			<div class="cbt-info-body">
				<?php
				if(!empty($informasi)){
					echo $informasi;
				}else{
					echo '<p>Silahkan pilih Tes yang diikuti dari daftar tes yang tersedia dibawah ini. Apabila tes tidak muncul, silahkan menghubungi Operator yang bertugas.</p>';
				}
				?>
			</div>
		</div>

		<!-- Daftar Tes (Kartu Besar Mobile / PWA) -->
		<?php
			$hari_indo = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu');
			$bulan_indo = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');
			$nama_hari = isset($hari_indo[date('l')]) ? $hari_indo[date('l')] : date('l');
			$nama_bulan = isset($bulan_indo[(int)date('n')]) ? $bulan_indo[(int)date('n')] : date('F');
			$tanggal_hari_ini = $nama_hari . ', ' . date('d') . ' ' . $nama_bulan . ' ' . date('Y');
		?>
		<div class="cbt-section-header">
			<div>
				<h2 class="cbt-section-title">
					<i class="fa fa-calendar-check-o" style="color: #00a65a;"></i> Jadwal Ujian Hari Ini
				</h2>
				<p class="cbt-section-date">
					<?php echo $tanggal_hari_ini; ?> &bull; <b><?php echo !empty($daftar_tes) ? count($daftar_tes) : 0; ?> Jadwal</b>
				</p>
			</div>
			<button type="button" onclick="window.location.reload();" class="cbt-btn-refresh">
				<i class="fa fa-refresh"></i> Refresh Jadwal
			</button>
		</div>

		<?php if(!empty($daftar_tes)){ ?>
			<?php foreach($daftar_tes as $tes){
				$cat_class = 'is-pg';
				$badge_class = 'cbt-type-pg';
				if(isset($tes->exam_category) && $tes->exam_category === 'essay'){
					$cat_class = 'is-essay';
					$badge_class = 'cbt-type-essay';
				}elseif(isset($tes->exam_category) && $tes->exam_category === 'mixed'){
					$cat_class = 'is-mixed';
					$badge_class = 'cbt-type-mixed';
				}
				$cat_label = !empty($tes->category_label) ? $tes->category_label : 'PILIHAN GANDA';
				$cat_icon = !empty($tes->category_icon) ? $tes->category_icon : 'fa-list-ul';
				$jml_soal = isset($tes->total_soal) ? (int)$tes->total_soal : 0;
			?>
				<div class="cbt-test-card <?php echo $cat_class; ?> <?php echo ($tes->status_type === 'upcoming') ? 'is-upcoming' : ''; ?>">
					<div class="cbt-test-card-badges">
						<span class="cbt-type-badge <?php echo $badge_class; ?>">
							<i class="fa <?php echo $cat_icon; ?>"></i>
							<?php echo htmlspecialchars($cat_label, ENT_QUOTES, 'UTF-8'); ?>
							<?php if($jml_soal > 0){ echo ' &bull; ' . $jml_soal . ' SOAL'; } ?>
						</span>

						<?php if($tes->status_type === 'ready'){ ?>
							<span class="cbt-status-pill cbt-status-ready">
								<i class="fa fa-circle" style="font-size: 8px;"></i> Siap Dikerjakan
							</span>
						<?php }elseif($tes->status_type === 'ongoing'){ ?>
							<span class="cbt-status-pill cbt-status-ongoing">
								<i class="fa fa-clock-o"></i> Sedang Berlangsung
							</span>
						<?php }elseif($tes->status_type === 'upcoming'){ ?>
							<span class="cbt-status-pill cbt-status-upcoming">
								<i class="fa fa-lock"></i> Menunggu Jam Buka
							</span>
						<?php }elseif($tes->status_type === 'expired'){ ?>
							<span class="cbt-status-pill cbt-status-expired">
								<i class="fa fa-times-circle"></i> Sesi Berakhir
							</span>
						<?php }else{ ?>
							<span class="cbt-status-pill cbt-status-done">
								<i class="fa fa-check-circle"></i> Selesai Dikerjakan
							</span>
						<?php } ?>
					</div>

					<div class="cbt-test-card-top">
						<h3 class="cbt-test-title"><?php echo htmlspecialchars($tes->tes_nama, ENT_QUOTES, 'UTF-8'); ?></h3>
						<?php if(!empty($tes->tes_detail) && $tes->tes_detail !== $tes->tes_nama){ ?>
							<p class="cbt-test-sub"><?php echo htmlspecialchars($tes->tes_detail, ENT_QUOTES, 'UTF-8'); ?></p>
						<?php } ?>
					</div>

					<div class="cbt-test-meta">
						<div class="cbt-meta-item">
							<span class="cbt-meta-label"><i class="fa fa-clock-o"></i> Jam Sesi Ujian</span>
							<span class="cbt-meta-value" style="color: #0369a1;">
								<?php echo date('H:i', strtotime($tes->tes_begin_time)); ?> &ndash; <?php echo date('H:i', strtotime($tes->tes_end_time)); ?> WIB
							</span>
						</div>
						<div class="cbt-meta-item">
							<span class="cbt-meta-label"><i class="fa fa-hourglass-half"></i> Waktu Pengerjaan</span>
							<span class="cbt-meta-value" style="color: #00733e;">
								<?php echo (int)$tes->tes_duration_time; ?> Menit
							</span>
						</div>
						<?php if($tes->nilai !== '' && $tes->nilai !== null){ ?>
						<div class="cbt-score-banner">
							<span><i class="fa fa-trophy" style="color: #d97706;"></i> Nilai Hasil Ujian</span>
							<span style="color: #00733e; font-size: 18px;"><?php echo htmlspecialchars($tes->nilai, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<?php } ?>
					</div>

					<?php if(!empty($tes->action_url)){ ?>
						<?php if($tes->status_type === 'ready'){ ?>
							<?php if(isset($tes->exam_category) && $tes->exam_category === 'essay'){ ?>
								<a href="<?php echo $tes->action_url; ?>" class="btn btn-primary btn-flat cbt-btn-action cbt-btn-action-essay">
									<i class="fa fa-pencil-square-o" style="font-size: 19px;"></i> <?php echo htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							<?php }else{ ?>
								<a href="<?php echo $tes->action_url; ?>" class="btn btn-success btn-flat cbt-btn-action">
									<i class="fa fa-play-circle" style="font-size: 19px;"></i> <?php echo htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							<?php } ?>
						<?php }elseif($tes->status_type === 'ongoing'){ ?>
							<a href="<?php echo $tes->action_url; ?>" class="btn btn-warning btn-flat cbt-btn-action cbt-btn-action-ongoing">
								<i class="fa fa-refresh" style="font-size: 18px;"></i> <?php echo htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8'); ?>
							</a>
						<?php }else{ ?>
							<a href="<?php echo $tes->action_url; ?>" class="btn btn-default btn-flat cbt-btn-action" style="box-shadow: none; border: 1.5px solid #cbd5e1;">
								<i class="fa fa-eye"></i> <?php echo htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8'); ?>
							</a>
						<?php } ?>
					<?php }else{ ?>
						<?php if($tes->status_type === 'upcoming'){ ?>
							<div class="cbt-btn-locked cbt-btn-locked-upcoming">
								<i class="fa fa-lock" style="font-size: 18px;"></i>
								<span><?php echo htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8'); ?></span>
							</div>
						<?php }elseif($tes->status_type === 'expired'){ ?>
							<div class="cbt-btn-locked cbt-btn-locked-expired">
								<i class="fa fa-ban" style="font-size: 18px;"></i>
								<span><?php echo htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8'); ?></span>
							</div>
						<?php }else{ ?>
							<div class="cbt-btn-locked cbt-btn-locked-done">
								<i class="fa fa-check-circle" style="font-size: 18px;"></i>
								<span><?php echo !empty($tes->action_text) ? htmlspecialchars($tes->action_text, ENT_QUOTES, 'UTF-8') : 'UJIAN TELAH SELESAI DIKERJAKAN'; ?></span>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
		<?php }else{ ?>
			<div class="cbt-empty-state">
				<i class="fa fa-calendar-o"></i>
				<h4>Belum Ada Jadwal Ujian Hari Ini</h4>
				<p style="margin: 0; font-size: 13.5px;">Saat ini tidak ada jadwal ujian yang terdaftar untuk kelas Anda pada hari ini. Silahkan hubungi Pengawas atau Operator jika jadwal belum muncul.</p>
			</div>
		<?php } ?>
	</div>
</div><!-- /.container -->