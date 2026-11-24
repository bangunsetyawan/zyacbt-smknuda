<style type="text/css">
	.cbt-exam-wrap {
		max-width: 780px;
		margin: 0 auto 30px auto;
		padding: 14px 10px 0 10px;
	}

	/* Bar Info Ujian + Kontrol Ukuran Teks (%) */
	.cbt-exam-topbar {
		background: #ffffff;
		border-radius: 12px;
		border-left: 5px solid #00a65a;
		box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
		padding: 12px 15px;
		margin-bottom: 14px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
	}
	.cbt-exam-name {
		font-size: 17px;
		font-weight: 800;
		color: #0f172a;
		margin: 0;
		line-height: 1.3;
	}
	.cbt-exam-name small {
		display: block;
		font-size: 11.5px;
		font-weight: 800;
		color: #00a65a;
		text-transform: uppercase;
		letter-spacing: 0.6px;
		margin-bottom: 2px;
	}
	.cbt-zoom-bar {
		display: flex;
		align-items: center;
		gap: 6px;
		background: #f1f5f9;
		border: 1.5px solid #cbd5e1;
		border-radius: 9px;
		padding: 4px 6px;
	}
	.cbt-zoom-label {
		font-size: 12px;
		font-weight: 800;
		color: #334155;
		padding: 0 4px;
		white-space: nowrap;
	}
	.cbt-zoom-btn {
		border: none;
		background: #ffffff;
		color: #334155;
		font-weight: 800;
		border-radius: 6px;
		padding: 5px 10px;
		font-size: 13px;
		cursor: pointer;
		border: 1px solid #cbd5e1;
		transition: all 0.15s ease;
	}
	.cbt-zoom-btn.active {
		background: #00a65a;
		border-color: #008d4c;
		color: #ffffff;
		box-shadow: 0 2px 6px rgba(0, 166, 90, 0.28);
	}
	@media (max-width: 480px) {
		.cbt-exam-topbar {
			flex-direction: column;
			align-items: stretch;
		}
		.cbt-zoom-bar {
			justify-content: space-between;
		}
		.cbt-zoom-btn {
			flex: 1;
			text-align: center;
			padding: 6px 8px;
		}
	}

	/* Kartu Soal Utama */
	.cbt-question-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1.5px solid #cbd5e1;
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
		overflow: hidden;
		margin-bottom: 16px;
	}
	.cbt-question-header {
		background: #00a65a;
		color: #ffffff;
		padding: 13px 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
	}
	.cbt-question-badge {
		font-size: 18px;
		font-weight: 800;
		letter-spacing: 0.3px;
		margin: 0;
	}
	.cbt-timer-pill {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		background: rgba(0, 0, 0, 0.26);
		border: 1px solid rgba(255, 255, 255, 0.35);
		color: #ffffff;
		padding: 6px 13px;
		border-radius: 20px;
		font-size: 15px;
		font-weight: 800;
		font-family: monospace;
		letter-spacing: 0.5px;
		white-space: nowrap;
	}
	.cbt-timer-pill.is-warning {
		background: #dc2626;
		border-color: #fecaca;
	}

	/* Isi Soal (Tegas, Kontras Tinggi, Nyaman untuk Bacaan Panjang/Literasi) */
	.cbt-question-body {
		padding: 16px;
		background: #f8fafc;
	}
	#isi-tes-soal {
		font-size: 17px;
		line-height: 1.65;
		color: #0f172a;
	}
	.cbt-question-stem {
		background: #ffffff;
		border: 2px solid #94a3b8;
		border-left: 6px solid #00a65a;
		border-radius: 10px;
		padding: 18px 16px;
		margin-bottom: 18px;
		color: #0f172a;
		font-weight: 500;
		box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
		overflow-x: auto;
	}
	.cbt-question-stem p {
		margin-bottom: 12px;
	}
	.cbt-question-stem p:last-child {
		margin-bottom: 0;
	}
	.cbt-question-stem code {
		background: #e2e8f0;
		color: #0f172a;
		font-weight: 700;
		padding: 2px 6px;
		border-radius: 4px;
	}

	/* Pilihan Jawaban */
	.cbt-options-list {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}
	.cbt-opt-card {
		display: flex;
		align-items: flex-start;
		gap: 13px;
		background: #ffffff;
		border: 2px solid #cbd5e1;
		border-radius: 10px;
		padding: 14px;
		margin: 0 !important;
		cursor: pointer;
		font-weight: 600;
		transition: all 0.15s ease;
		position: relative;
		box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
	}
	.cbt-opt-card:hover {
		border-color: #00a65a;
		background: #f4fbf7;
	}
	.cbt-opt-card input[type="radio"] {
		position: absolute;
		opacity: 0;
		pointer-events: none;
	}
	.cbt-opt-letter {
		width: 38px;
		height: 38px;
		border-radius: 8px;
		background: #e2e8f0;
		border: 1.5px solid #94a3b8;
		color: #0f172a;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		font-weight: 800;
		flex-shrink: 0;
		margin-top: 1px;
		transition: all 0.15s ease;
	}
	.cbt-opt-text {
		flex: 1;
		font-size: 1em;
		line-height: 1.55;
		color: #0f172a;
		padding-top: 4px;
		overflow-x: auto;
	}
	.cbt-opt-text p:last-child {
		margin-bottom: 0;
	}
	.cbt-opt-card.is-selected {
		border-color: #00a65a;
		border-width: 2.5px;
		background: #e8f8f0;
		box-shadow: 0 4px 12px rgba(0, 166, 90, 0.16);
	}
	.cbt-opt-card.is-selected .cbt-opt-letter {
		background: #00a65a;
		border-color: #008d4c;
		color: #ffffff;
	}

	/* Input Essay / Jawaban Singkat */
	.cbt-essay-input {
		width: 100%;
		min-height: 160px;
		font-size: 17px !important;
		line-height: 1.55 !important;
		border: 2px solid #94a3b8 !important;
		border-radius: 10px !important;
		padding: 14px !important;
		margin-bottom: 12px;
		color: #0f172a !important;
	}
	.cbt-short-input {
		height: 52px !important;
		font-size: 18px !important;
		font-weight: 700;
		border: 2px solid #94a3b8 !important;
		border-radius: 10px !important;
		padding: 10px 16px !important;
		margin-bottom: 12px;
		color: #0f172a !important;
	}
	.cbt-btn-save-essay {
		height: 50px;
		font-size: 16.5px;
		font-weight: 800;
		border-radius: 10px !important;
	}

	/* Tombol Navigasi Bawah Soal (Sebelumnya | Ragu-ragu | Selanjutnya / Selesai) */
	.cbt-question-footer {
		background: #ffffff;
		border-top: 1.5px solid #e2e8f0;
		padding: 14px 16px;
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		gap: 10px;
	}
	.cbt-nav-btn {
		height: 50px;
		border-radius: 10px !important;
		font-size: 15.5px;
		font-weight: 800;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 7px;
		padding: 0 10px;
		border: 1.5px solid #cbd5e1;
	}
	.cbt-nav-btn-prev {
		background: #f1f5f9;
		border-color: #94a3b8;
		color: #0f172a;
	}
	.cbt-nav-btn-ragu {
		background: #f59e0b;
		border-color: #d97706;
		color: #ffffff !important;
		cursor: pointer;
		user-select: none;
	}
	.cbt-nav-btn-ragu input[type="checkbox"] {
		width: 18px;
		height: 18px;
		margin: 0;
		cursor: pointer;
	}
	.cbt-nav-btn-next {
		background: #00a65a;
		border-color: #008d4c;
		color: #ffffff !important;
		box-shadow: 0 3px 10px rgba(0, 166, 90, 0.22);
	}
	.cbt-nav-btn-finish {
		background: #dc2626 !important;
		border-color: #b91c1c !important;
		color: #ffffff !important;
		box-shadow: 0 3px 10px rgba(220, 38, 38, 0.28);
	}
	@media (max-width: 520px) {
		.cbt-question-footer {
			grid-template-columns: 1fr 1fr;
		}
		.cbt-nav-btn-ragu {
			grid-column: 1 / -1;
			order: 3;
			height: 46px;
		}
	}

	/* Kartu Daftar Nomor Soal */
	.cbt-numpad-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1.5px solid #cbd5e1;
		box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
		padding: 16px;
	}
	.cbt-numpad-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 12px;
		padding-bottom: 10px;
		border-bottom: 1px solid #e2e8f0;
		flex-wrap: wrap;
		gap: 8px;
	}
	.cbt-numpad-title {
		font-size: 17px;
		font-weight: 800;
		color: #0f172a;
		margin: 0;
	}
	.cbt-legend {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 12.5px;
		font-weight: 700;
		color: #334155;
		flex-wrap: wrap;
	}
	.cbt-legend-item {
		display: inline-flex;
		align-items: center;
		gap: 5px;
	}
	.cbt-legend-dot {
		width: 14px;
		height: 14px;
		border-radius: 4px;
		display: inline-block;
	}
	.cbt-numpad-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
		gap: 8px;
		margin-bottom: 6px;
	}
	.cbt-num-btn {
		height: 48px !important;
		width: 100% !important;
		margin: 0 !important;
		font-size: 16px !important;
		font-weight: 800 !important;
		border-radius: 9px !important;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0 !important;
		transition: transform 0.1s ease;
	}
	.cbt-num-btn.btn-default {
		background: #f8fafc;
		border: 1.5px solid #94a3b8;
		color: #0f172a;
	}
	.cbt-num-btn.is-current {
		outline: 3px solid #0f172a;
		outline-offset: 1px;
	}
	.cbt-last-question-hint {
		margin-top: 10px;
		background: #f1f5f9;
		border-radius: 8px;
		padding: 10px 12px;
		font-size: 13px;
		font-weight: 600;
		color: #475569;
		text-align: center;
	}

	/* Pop-up Modal Hentikan / Selesai Tes (Besar & Jelas di HP) */
	#modal-hentikan .modal-header {
		background: #dc2626;
		color: #ffffff;
		padding: 16px 18px;
	}
	#trx-judul {
		font-size: 19px;
		font-weight: 800;
	}
	.cbt-stop-alert {
		background: #fef2f2;
		border: 1px solid #fecaca;
		border-left: 5px solid #dc2626;
		border-radius: 8px;
		padding: 12px 14px;
		color: #991b1b;
		font-size: 15px;
		font-weight: 600;
		line-height: 1.45;
		margin-bottom: 16px;
	}
	.cbt-stop-field {
		margin-bottom: 14px;
	}
	.cbt-stop-field label {
		font-size: 13.5px;
		font-weight: 700;
		color: #475569;
		margin-bottom: 5px;
		display: block;
	}
	.cbt-stop-field .form-control {
		height: 46px;
		font-size: 15.5px;
		font-weight: 800;
		background: #f8fafc;
		border-radius: 8px;
		border: 1.5px solid #cbd5e1;
		color: #1e293b;
	}
	.cbt-stop-check-box {
		background: #fffbeb;
		border: 2px solid #f59e0b;
		border-radius: 10px;
		padding: 14px;
		margin-top: 10px;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.cbt-stop-check-box input[type="checkbox"] {
		width: 24px;
		height: 24px;
		margin: 0;
		flex-shrink: 0;
		cursor: pointer;
	}
	.cbt-stop-check-box span {
		font-size: 15.5px;
		font-weight: 800;
		color: #92400e;
		line-height: 1.35;
	}
	.cbt-stop-footer {
		padding: 14px 18px 18px 18px;
		display: flex;
		flex-direction: column;
		gap: 10px;
		border-top: 1px solid #e2e8f0;
	}
	.cbt-btn-confirm-stop {
		height: 52px;
		font-size: 17px;
		font-weight: 800;
		border-radius: 10px !important;
		background: #dc2626 !important;
		border-color: #b91c1c !important;
		color: #fff !important;
	}
	.cbt-btn-cancel-stop {
		height: 46px;
		font-size: 15.5px;
		font-weight: 700;
		border-radius: 10px !important;
	}

	/* Proteksi Anti-Blok, Anti-Copy, Anti-Drag & Anti-Long-Press pada Soal & Jawaban */
	.cbt-exam-wrap,
	.cbt-exam-wrap *,
	#isi-tes-soal,
	.cbt-question-stem,
	.cbt-options-list,
	.cbt-opt-card,
	.cbt-opt-text {
		-webkit-user-select: none !important;
		-moz-user-select: none !important;
		-ms-user-select: none !important;
		user-select: none !important;
		-webkit-touch-callout: none !important;
		-webkit-user-drag: none !important;
	}
	.cbt-exam-wrap img {
		pointer-events: none !important;
		-webkit-user-drag: none !important;
		-webkit-touch-callout: none !important;
	}
	textarea.cbt-essay-input,
	input.cbt-short-input {
		-webkit-user-select: text !important;
		-moz-user-select: text !important;
		-ms-user-select: text !important;
		user-select: text !important;
		-webkit-touch-callout: none !important;
	}
</style>

<div class="container">
	<div class="cbt-exam-wrap" oncopy="return false;" oncut="return false;" oncontextmenu="return false;" ondragstart="return false;">
		<!-- Topbar Nama Tes + Zoom Ukuran Teks (%) -->
		<div class="cbt-exam-topbar">
			<h1 class="cbt-exam-name">
				<small>Mata Uji Sedang Dikerjakan</small>
				<?php if(!empty($tes_name)){ echo htmlspecialchars($tes_name, ENT_QUOTES, 'UTF-8'); } ?>
			</h1>
			<div class="cbt-zoom-bar" title="Atur Ukuran Teks Soal">
				<span class="cbt-zoom-label"><i class="fa fa-text-height"></i> Ukuran Teks:</span>
				<button type="button" class="cbt-zoom-btn active" onclick="setZoomPercent(100, this)">100%</button>
				<button type="button" class="cbt-zoom-btn" onclick="setZoomPercent(120, this)">120%</button>
				<button type="button" class="cbt-zoom-btn" onclick="setZoomPercent(140, this)">140%</button>
			</div>
		</div>

		<!-- Kartu Soal & Jawaban -->
		<?php echo form_open('tes_kerjakan/simpan_jawaban','id="form-kerjakan"')?>
			<input type="hidden" name="tes-id" id="tes-id" value="<?php if(!empty($tes_id)){ echo $tes_id; } ?>">
			<input type="hidden" name="tes-user-id" id="tes-user-id" value="<?php if(!empty($tes_user_id)){ echo $tes_user_id; } ?>">
			<input type="hidden" name="tes-soal-id" id="tes-soal-id" value="<?php if(!empty($tes_soal_id)){ echo $tes_soal_id; } ?>">
			<input type="hidden" name="tes-soal-nomor" id="tes-soal-nomor" value="<?php if(!empty($tes_soal_nomor)){ echo $tes_soal_nomor; } ?>">
			<input type="hidden" name="tes-soal-jml" id="tes-soal-jml" value="<?php if(!empty($tes_soal_jml)){ echo $tes_soal_jml; } ?>">
			<input type="hidden" name="tes-soal-ragu" id="tes-soal-ragu" value="<?php echo !empty($tes_ragu) ? 1 : 0; ?>">

			<div class="cbt-question-card">
				<div class="cbt-question-header">
					<h2 class="cbt-question-badge">
						SOAL NO. <span id="judul-soal"><?php if(!empty($tes_soal_nomor)){ echo $tes_soal_nomor; } ?></span>
						<span style="font-size: 14px; font-weight: 600; opacity: 0.9;">/ <?php if(!empty($tes_soal_jml)){ echo $tes_soal_jml; } ?></span>
					</h2>
					<div class="cbt-timer-pill" id="timer-box">
						<i class="fa fa-hourglass-half"></i>
						<span id="sisa-waktu">--:--</span>
					</div>
				</div>

				<div class="cbt-question-body">
					<div id="isi-tes-soal">
						<?php if(!empty($tes_soal)){ echo $tes_soal; } ?>
					</div>
				</div>

				<div class="cbt-question-footer">
					<button type="button" class="btn btn-default cbt-nav-btn cbt-nav-btn-prev hide" id="btn-sebelumnya">
						<i class="fa fa-arrow-left"></i> Sebelumnya
					</button>
					<div class="btn btn-warning cbt-nav-btn cbt-nav-btn-ragu" id="btn-ragu" onclick="ragu()">
						<input type="checkbox" name="btn-ragu-checkbox" id="btn-ragu-checkbox" <?php if(!empty($tes_ragu)){ echo "checked"; } ?> onclick="event.stopPropagation(); ragu();" />
						<span>Ragu-Ragu</span>
					</div>
					<button type="button" class="btn btn-success cbt-nav-btn cbt-nav-btn-next" id="btn-selanjutnya">
						Selanjutnya <i class="fa fa-arrow-right"></i>
					</button>
					<button type="button" class="btn btn-danger cbt-nav-btn cbt-nav-btn-finish hide" id="btn-hentikan">
						<i class="fa fa-flag-checkered"></i> Selesai Ujian
					</button>
				</div>
			</div>
		</form>

		<!-- Kartu Navigasi Daftar Nomor Soal -->
		<div class="cbt-numpad-card">
			<div class="cbt-numpad-header">
				<h3 class="cbt-numpad-title"><i class="fa fa-th"></i> Navigasi Nomor Soal</h3>
				<div class="cbt-legend">
					<span class="cbt-legend-item"><i class="cbt-legend-dot" style="background:#3c8dbc;"></i> Dijawab</span>
					<span class="cbt-legend-item"><i class="cbt-legend-dot" style="background:#f39c12;"></i> Ragu</span>
					<span class="cbt-legend-item"><i class="cbt-legend-dot" style="background:#f8fafc; border:1.5px solid #94a3b8;"></i> Belum</span>
				</div>
			</div>

			<div class="cbt-numpad-grid">
				<?php if(!empty($tes_daftar_soal)){ echo $tes_daftar_soal; } ?>
			</div>

			<div class="cbt-last-question-hint">
				<i class="fa fa-info-circle"></i> Tombol <b>Selesai Ujian</b> akan muncul pada soal nomor terakhir (No. <?php if(!empty($tes_soal_jml)){ echo $tes_soal_jml; } ?>).
			</div>
		</div>
	</div>

	<!-- Pop-up Modal Konfirmasi Hentikan Tes -->
	<div class="modal" style="max-height: 100%; overflow-y: auto;" id="modal-hentikan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<?php echo form_open($url.'/hentikan_tes','id="form-hentikan"'); ?>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">&times;</button>
					<div id="trx-judul"><i class="fa fa-exclamation-triangle"></i> Konfirmasi Selesai Ujian</div>
				</div>
				<div class="modal-body" style="padding: 18px;">
					<div id="form-pesan"></div>
					<div class="cbt-stop-alert">
						Apakah Anda yakin ingin mengakhiri ujian ini?<br />
						<b>Perhatian:</b> Jawaban ujian yang sudah diakhiri tidak dapat diubah kembali.
					</div>

					<div class="cbt-stop-field">
						<label>Nama Mata Uji</label>
						<input type="hidden" name="hentikan-tes-id" id="hentikan-tes-id">
						<input type="hidden" name="hentikan-tes-user-id" id="hentikan-tes-user-id">
						<input type="text" class="form-control" id="hentikan-tes-nama" name="hentikan-tes-nama" readonly>
					</div>

					<div class="cbt-stop-field">
						<label>Status Pengerjaan Soal</label>
						<input type="text" class="form-control" id="hentikan-dijawab" name="hentikan-dijawab" readonly>
					</div>

					<label class="cbt-stop-check-box" for="hentikan-centang">
						<input type="checkbox" id="hentikan-centang" name="hentikan-centang" value="1">
						<span>Saya yakin sudah selesai dan ingin mengakhiri ujian ini sekarang.</span>
					</label>
				</div>
				<div class="cbt-stop-footer">
					<button type="submit" id="tambah-simpan" class="btn btn-danger btn-block cbt-btn-confirm-stop">
						<i class="fa fa-check-circle"></i> YA, SELESAI &amp; KUMPULKAN JAWABAN
					</button>
					<button type="button" class="btn btn-default btn-block cbt-btn-cancel-stop" data-dismiss="modal">
						<i class="fa fa-arrow-left"></i> Batal, Kembali ke Soal
					</button>
				</div>
			</div>
		</div>
	</form>
	</div>
</div><!-- /.container -->

<script type="text/javascript">
    function setZoomPercent(percent, btnEl){
        var basePx = 17;
        var calcPx = Math.round((basePx * percent) / 100);
        $('#isi-tes-soal').css({
            "font-size": calcPx + "px",
            "line-height": "1.65"
        });
        $('.cbt-zoom-btn').removeClass('active');
        if(btnEl){
            $(btnEl).addClass('active');
        }
    }

    function updateNavButtons(nomor, total){
        nomor = parseInt(nomor);
        total = parseInt(total);
        if(total <= 1){
            $('#btn-sebelumnya').addClass('hide');
            $('#btn-selanjutnya').addClass('hide');
            $('#btn-hentikan').removeClass('hide');
        }else if(nomor <= 1){
            $('#btn-sebelumnya').addClass('hide');
            $('#btn-selanjutnya').removeClass('hide');
            $('#btn-hentikan').addClass('hide');
        }else if(nomor >= total){
            $('#btn-sebelumnya').removeClass('hide');
            $('#btn-selanjutnya').addClass('hide');
            $('#btn-hentikan').removeClass('hide');
        }else{
            $('#btn-sebelumnya').removeClass('hide');
            $('#btn-selanjutnya').removeClass('hide');
            $('#btn-hentikan').addClass('hide');
        }
    }

    function highlightCurrentSoal(nomor){
        $('.cbt-num-btn').removeClass('is-current');
        $('#btn-soal-' + nomor).addClass('is-current');
        updateNavButtons(nomor, $('#tes-soal-jml').val());
    }

    // Highlight pilihan jawaban saat diklik
    $(document).on('change', 'input[name="soal-jawaban"]', function(){
        $('.cbt-opt-card').removeClass('is-selected');
        $(this).closest('.cbt-opt-card').addClass('is-selected');
    });

    function ragu(){
        $("#modal-proses").modal('show');

        $.ajax({
            url:'<?php echo site_url().'/'.$url; ?>/get_tes_soal_by_tessoal/'+$('#tes-soal-id').val(),
            type:"POST",
            cache: false,
            timeout: 10000,
            success:function(respon){
                var data = $.parseJSON(respon);
                if(data.data==1){
                    if($('#tes-soal-ragu').val()==0){
                        var ragu=1;
                    }else{
                        var ragu=0;
                    }
                    $.ajax({
                            url:'<?php echo site_url().'/'.$url; ?>/update_tes_soal_ragu/'+$('#tes-soal-id').val()+'/'+ragu,
                            type:"POST",
                            cache: false,
                            timeout: 5000,
                            success:function(respon){
                                var data = $.parseJSON(respon);
                                if(data.data==1){
                                    notify_success('Status Ragu-ragu berhasil diubah');
                                }
                            },
                            error: function(xmlhttprequest, textstatus, message) {
                                if(textstatus==="timeout") {
                                    $("#modal-proses").modal('hide');
                                    notify_error("Gagal mengubah Soal, Silahkan Refresh Halaman");
                                }else{
                                    $("#modal-proses").modal('hide');
                                    notify_error(textstatus);
                                }
                            }
                    });

                    if(data.tessoal_dikerjakan==1){
                        if($('#tes-soal-ragu').val()==0){
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).removeClass('btn-primary');
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).addClass('btn-warning');
                            $('#btn-ragu-checkbox').prop("checked", true);
                            $('#tes-soal-ragu').val(1);
                        }else{
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).removeClass('btn-warning');
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).addClass('btn-primary');
                            $('#btn-ragu-checkbox').prop("checked", false);
                            $('#tes-soal-ragu').val(0);
                        }
                    }else{
                        if($('#tes-soal-ragu').val()==0){
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).removeClass('btn-default');
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).addClass('btn-warning');
                            $('#btn-ragu-checkbox').prop("checked", true);
                            $('#tes-soal-ragu').val(1);
                        }else{
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).removeClass('btn-warning');
                            $('#btn-soal-'+$('#tes-soal-nomor').val()).addClass('btn-default');
                            $('#btn-ragu-checkbox').prop("checked", false);
                            $('#tes-soal-ragu').val(0);
                        }
                    }
                }
                $("#modal-proses").modal('hide');
            },
            error: function(xmlhttprequest, textstatus, message) {
                if(textstatus==="timeout") {
                    $("#modal-proses").modal('hide');
                    notify_error("Gagal mengubah soal, Silahkan Refresh Halaman");
                }else{
                    $("#modal-proses").modal('hide');
                    notify_error(textstatus);
                }
            }
        });
    }

    var essayDirty = false;

    // Blokir total Copy, Cut, Klik Kanan (Context Menu), Select, dan Drag di halaman ujian
    $(document).on('copy cut contextmenu dragstart', function(e){
        e.preventDefault();
        return false;
    });

    $(document).on('selectstart', function(e){
        var tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
        if(tag !== 'textarea' && tag !== 'input'){
            e.preventDefault();
            return false;
        }
    });

    // Blokir shortcut keyboard Copy/Cut/Paste/Select-All/View-Source/Print/Save
    $(document).on('keydown', function(e){
        if(e.ctrlKey || e.metaKey){
            var key = (e.key || '').toLowerCase();
            var code = e.which || e.keyCode;
            // C(67), V(86), X(88), A(65), U(85), S(83), P(80)
            if(key === 'c' || key === 'v' || key === 'x' || key === 'a' || key === 'u' || key === 's' || key === 'p' ||
               code === 67 || code === 86 || code === 88 || code === 65 || code === 85 || code === 83 || code === 80){
                e.preventDefault();
                if(key === 'v' || code === 86){
                    notify_error('Tempel (Paste) dinonaktifkan! Ketik jawaban Anda sendiri.');
                }
                return false;
            }
        }
    });

    function initEssaySafeVal(){
        $('#isi-tes-soal').find('textarea.cbt-essay-input, input.cbt-short-input').each(function(){
            $(this).data('lastSafeVal', $(this).val() || '');
        });
    }

    // Blokir Paste & Drop pada input Esai / Jawaban Singkat (termasuk Clipboard Keyboard HP)
    $(document).on('focus keydown touchstart mousedown', 'textarea.cbt-essay-input, input.cbt-short-input', function(){
        if(typeof $(this).data('lastSafeVal') !== 'string'){
            $(this).data('lastSafeVal', $(this).val() || '');
        }
    });

    $(document).on('paste drop', 'textarea.cbt-essay-input, input.cbt-short-input', function(e){
        e.preventDefault();
        notify_error('Tempel (Paste) dinonaktifkan! Ketik jawaban Anda sendiri.');
        return false;
    });

    $(document).on('beforeinput', 'textarea.cbt-essay-input, input.cbt-short-input', function(e){
        var oe = e.originalEvent;
        if(oe && (oe.inputType === 'insertFromPaste' || oe.inputType === 'insertFromDrop' || oe.inputType === 'insertFromPasteAsQuotation')){
            e.preventDefault();
            notify_error('Tempel (Paste) dinonaktifkan! Ketik jawaban Anda sendiri.');
            return false;
        }
    });

    $(document).on('input', 'textarea.cbt-essay-input, input.cbt-short-input', function(e){
        var $el = $(this);
        var curVal = $el.val() || '';
        var prevVal = $el.data('lastSafeVal');
        if(typeof prevVal !== 'string'){
            prevVal = '';
        }
        var oe = e.originalEvent;
        var isPasteType = (oe && (oe.inputType === 'insertFromPaste' || oe.inputType === 'insertFromDrop' || oe.inputType === 'insertFromPasteAsQuotation'));

        // Jika terdeteksi paste dari clipboard bar keyboard HP (atau lonjakan > 30 karakter sekaligus)
        if(isPasteType || (curVal.length - prevVal.length > 30)){
            $el.val(prevVal);
            notify_error('Tempel (Paste) dari Clipboard tidak diizinkan! Ketik manual jawaban Anda.');
            return false;
        }
        $el.data('lastSafeVal', curVal);
        essayDirty = true;
    });

    function autoSaveEssayBefore(callback){
        var $essayEl = $('#isi-tes-soal').find('textarea.cbt-essay-input, input.cbt-short-input');
        if(essayDirty && $essayEl.length > 0 && $.trim($essayEl.val()) !== ''){
            essayDirty = false;
            $.ajax({
                url:"<?php echo site_url().'/'.$url; ?>/simpan_jawaban",
                type:"POST",
                data:$('#form-kerjakan').serialize(),
                cache: false,
                timeout: 8000,
                success:function(respon){
                    try {
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            notify_success(obj.pesan);
                            $('#btn-soal-'+obj.nomor_soal).removeClass('btn-default').removeClass('btn-warning').addClass('btn-primary');
                        }
                    } catch(e){}
                    if(typeof callback === 'function') callback();
                },
                error:function(){
                    if(typeof callback === 'function') callback();
                }
            });
        }else{
            essayDirty = false;
            if(typeof callback === 'function') callback();
        }
    }

    function soal(tessoal_id){
        autoSaveEssayBefore(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                url:'<?php echo site_url().'/'.$url; ?>/get_soal_by_tessoal/'+tessoal_id+'/'+$('#tes-user-id').val(),
                type:"POST",
                cache: false,
                timeout: 10000,
                success:function(respon){
                    var data = $.parseJSON(respon);
                    if(data.data==1){
                        $('#tes-soal-id').val(data.tes_soal_id);
                        $('#tes-soal-nomor').val(data.tes_soal_nomor);
                        $('#isi-tes-soal').html(data.tes_soal);
                        $('#tes-soal-ragu').val(data.tes_ragu);
                        $('#judul-soal').html(data.tes_soal_nomor);
                        essayDirty = false;
                        initEssaySafeVal();
                        highlightCurrentSoal(data.tes_soal_nomor);

                        if(data.tes_ragu==0){
                            $('#btn-ragu-checkbox').prop("checked", false);
                        }else{
                            $('#btn-ragu-checkbox').prop("checked", true);
                        }

                    }else if(data.data==2){
                        window.location.reload();
                    }
                    $("#modal-proses").modal('hide');
                },
                error: function(xmlhttprequest, textstatus, message) {
                    if(textstatus==="timeout") {
                        $("#modal-proses").modal('hide');
                        notify_error("Gagal mengambil Soal, Silahkan Refresh Halaman");
                    }else{
                        $("#modal-proses").modal('hide');
                        notify_error(textstatus);
                    }
                }
            });
        });
    }

    function audio(status){
        var audio_player_status = $('#audio-player-status').val();
        var audio_player_update = $('#audio-player-update').val();
        if(status==1){
            if(audio_player_update==0){
                $('#audio-player-update').val('1');
                $.getJSON('<?php echo site_url().'/'.$url; ?>/update_status_audio/'+$('#tes-soal-id').val(), function(data){
                    if(data.data==1){
                        notify_success(data.pesan);
                    }
                });
            }
        }
        
        if(audio_player_status==0){
            $('#audio-player-status').val('1');
            $('#audio-player').trigger('play');
            $('#audio-player-judul').html('Pause');
            $('#audio-player-judul-logo').removeClass('fa-play');
            $('#audio-player-judul-logo').addClass('fa-pause');
        }else{
            $('#audio-player-status').val('0');
            $('#audio-player').trigger('pause');
            $('#audio-player-judul').html('Play');
            $('#audio-player-judul-logo').removeClass('fa-pause');
            $('#audio-player-judul-logo').addClass('fa-play');
        }
    }

    function audio_ended(status){
        if(status==1){
            $('#audio-control').addClass('hide');
        }else{
            $('#audio-player-status').val('0');
            $('#audio-player-judul').html('Play');
            $('#audio-player-judul-logo').removeClass('fa-pause');
            $('#audio-player-judul-logo').addClass('fa-play');
        }
    }

    function jawab(){
        essayDirty = false;
        $('#form-kerjakan').submit();
    }

    function hentikan_tes(){
        autoSaveEssayBefore(function(){
            $("#modal-proses").modal('show');
            $('#hentikan-centang').prop("checked", false);
            $.getJSON('<?php echo site_url().'/'.$url; ?>/get_tes_info/'+$('#tes-id').val(), function(data){
                if(data.data==1){
                    $('#hentikan-tes-id').val(data.tes_id);
                    $('#hentikan-tes-user-id').val(data.tes_user_id);
                    $('#hentikan-tes-nama').val(data.tes_nama);
                    $('#hentikan-dijawab').val(data.tes_dijawab+" Dijawab  |  "+data.tes_blum_dijawab+" Belum Dijawab");
                    $('#hentikan-belum-dijawab').val(data.tes_blum_dijawab);

                    $("#modal-hentikan").modal('show');
                }else{
                    window.location.reload();
                }
                $("#modal-proses").modal('hide');
            });
        });
    }

    function soal_navigasi(navigasi){
        var tes_soal_nomor = parseInt($('#tes-soal-nomor').val());
        var tes_soal_jml = parseInt($('#tes-soal-jml').val());
        var tes_soal_tujuan = tes_soal_nomor+navigasi;

        if((tes_soal_tujuan>=1 && tes_soal_tujuan<=tes_soal_jml)){
            $('#btn-soal-'+tes_soal_tujuan).trigger('click');
        }
    }

    function formatCountdown(sec){
        if(sec < 0) sec = 0;
        var h = Math.floor(sec / 3600);
        var m = Math.floor((sec % 3600) / 60);
        var s = sec % 60;
        var mm = ('0' + m).slice(-2);
        var ss = ('0' + s).slice(-2);
        if(h > 0){
            var hh = ('0' + h).slice(-2);
            return hh + ':' + mm + ':' + ss;
        }
        return mm + ':' + ss;
    }

    $(function () {
        var sisa_detik = <?php echo !empty($detik_sisa) ? (int)$detik_sisa : 0; ?>;
        var elSisa = document.getElementById("sisa-waktu");
        var elTimerBox = document.getElementById("timer-box");

        highlightCurrentSoal($('#tes-soal-nomor').val());
        initEssaySafeVal();
        if(elSisa) elSisa.textContent = formatCountdown(sisa_detik);

        setInterval(function() {
            sisa_detik = sisa_detik - 1;
            if(elSisa) elSisa.textContent = formatCountdown(sisa_detik);
            if(sisa_detik <= 300 && elTimerBox){
                elTimerBox.classList.add('is-warning');
            }
            if(sisa_detik < 1){
                window.location.reload();
            }
        }, 1000);

        $('#btn-sebelumnya').click(function(){
            soal_navigasi(-1);
        });

        $('#btn-selanjutnya').click(function(){
            soal_navigasi(1);
        });

        $('#btn-hentikan').click(function(){
            hentikan_tes();
        });

        $('#form-kerjakan').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/simpan_jawaban",
                    type:"POST",
                    data:$('#form-kerjakan').serialize(),
                    cache: false,
                    timeout: 10000,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            notify_success(obj.pesan);
                            $('#btn-soal-'+obj.nomor_soal).removeClass('btn-default');
                            $('#btn-soal-'+obj.nomor_soal).removeClass('btn-warning');
                            $('#btn-soal-'+obj.nomor_soal).addClass('btn-primary');
                        }else if(obj.status==2){
                            window.location.reload();
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(obj.pesan);
                        }
                    },
                    error: function(xmlhttprequest, textstatus, message) {
                        if(textstatus==="timeout") {
                            $("#modal-proses").modal('hide');
                            notify_error("Gagal menyimpan jawaban, Silahkan Refresh Halaman");
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(textstatus);
                        }
                    }
            });
            return false;
        });

        $('#form-hentikan').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/hentikan_tes",
                    type:"POST",
                    data:$('#form-hentikan').serialize(),
                    cache: false,
                    timeout: 10000,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            window.location.reload();
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(obj.pesan);
                        }
                    },
                    error: function(xmlhttprequest, textstatus, message) {
                        if(textstatus==="timeout") {
                            $("#modal-proses").modal('hide');
                            notify_error("Gagal menghentikan Tes, Silahkan Refresh Halaman");
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(textstatus);
                        }
                    }
            });
            return false;
        });
    });
</script>
