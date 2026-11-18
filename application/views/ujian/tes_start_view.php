<style type="text/css">
	.cbt-confirm-wrap {
		max-width: 540px;
		margin: 0 auto 30px auto;
		padding: 18px 12px 0 12px;
	}
	.cbt-confirm-card {
		background: #ffffff;
		border-radius: 12px;
		border-top: 4px solid #00a65a;
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
		padding: 22px 20px;
	}
	.cbt-confirm-header {
		text-align: center;
		margin-bottom: 18px;
		padding-bottom: 14px;
		border-bottom: 1px solid #e2e8f0;
	}
	.cbt-confirm-icon {
		width: 54px;
		height: 54px;
		border-radius: 50%;
		background: #e8f8f0;
		color: #00a65a;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 24px;
		margin: 0 auto 10px auto;
	}
	.cbt-confirm-title {
		font-size: 20px;
		font-weight: 800;
		color: #1f2d3d;
		margin: 0 0 4px 0;
	}
	.cbt-confirm-sub {
		font-size: 13.5px;
		color: #64748b;
		margin: 0;
	}
	.cbt-confirm-list {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		padding: 6px 16px;
		margin-bottom: 18px;
	}
	.cbt-confirm-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 11px 0;
		border-bottom: 1px dashed #e2e8f0;
		gap: 12px;
	}
	.cbt-confirm-row:last-child {
		border-bottom: none;
	}
	.cbt-confirm-label {
		font-size: 13.5px;
		font-weight: 600;
		color: #64748b;
	}
	.cbt-confirm-val {
		font-size: 15px;
		font-weight: 800;
		color: #1e293b;
		text-align: right;
	}
	.cbt-token-box {
		background: #fffbeb;
		border: 1.5px solid #fcd34d;
		border-radius: 10px;
		padding: 14px 16px;
		margin-bottom: 18px;
		text-align: center;
	}
	.cbt-token-box label {
		display: block;
		font-size: 12.5px;
		font-weight: 800;
		color: #b45309;
		letter-spacing: 0.6px;
		margin-bottom: 8px;
	}
	.cbt-token-input {
		height: 48px;
		font-size: 18px;
		font-weight: 800;
		text-align: center;
		letter-spacing: 2px;
		border-radius: 8px;
		border: 1.5px solid #f59e0b;
	}
	.cbt-confirm-actions {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}
	.cbt-btn-start {
		height: 52px;
		font-size: 17px;
		font-weight: 800;
		letter-spacing: 0.5px;
		border-radius: 10px !important;
		box-shadow: 0 4px 12px rgba(0, 166, 90, 0.25);
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
	}
	.cbt-btn-back {
		height: 44px;
		font-size: 14.5px;
		font-weight: 700;
		border-radius: 10px !important;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		color: #475569;
	}
</style>

<div class="container">
	<div class="cbt-confirm-wrap">
		<?php echo form_open($url.'/mulai_tes','id="form-konfirmasi-tes"'); ?>
		<input type="hidden" name="tes-id" id="tes-id" value="<?php if(!empty($tes_id)){ echo $tes_id; } ?>">
		<div class="cbt-confirm-card">
			<div class="cbt-confirm-header">
				<div class="cbt-confirm-icon">
					<i class="fa fa-check-square-o"></i>
				</div>
				<h1 class="cbt-confirm-title">Konfirmasi Mulai Ujian</h1>
				<p class="cbt-confirm-sub">Periksa kembali data ujian Anda sebelum menekan tombol mulai</p>
			</div>

			<div id="form-pesan"></div>

			<div class="cbt-confirm-list">
				<div class="cbt-confirm-row">
					<span class="cbt-confirm-label">Nama Peserta</span>
					<span class="cbt-confirm-val"><?php if(!empty($nama)){ echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); } ?></span>
				</div>
				<?php if(!empty($group)){ ?>
				<div class="cbt-confirm-row">
					<span class="cbt-confirm-label">Kelas / Grup</span>
					<span class="cbt-confirm-val"><?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?></span>
				</div>
				<?php } ?>
				<div class="cbt-confirm-row">
					<span class="cbt-confirm-label">Mata Uji / Tes</span>
					<span class="cbt-confirm-val" style="color: #00733e;"><?php if(!empty($tes_nama)){ echo htmlspecialchars($tes_nama, ENT_QUOTES, 'UTF-8'); } ?></span>
				</div>
				<div class="cbt-confirm-row">
					<span class="cbt-confirm-label">Alokasi Waktu</span>
					<span class="cbt-confirm-val"><?php if(!empty($tes_waktu)){ echo htmlspecialchars($tes_waktu, ENT_QUOTES, 'UTF-8'); } ?></span>
				</div>
				<div class="cbt-confirm-row">
					<span class="cbt-confirm-label">Poin Dasar / Maksimal</span>
					<span class="cbt-confirm-val"><?php if(!empty($tes_poin)){ echo $tes_poin; } ?> / <?php if(!empty($tes_max_score)){ echo $tes_max_score; } ?></span>
				</div>
			</div>

			<?php if(!empty($tes_token)){ echo $tes_token; } ?>

			<div class="cbt-confirm-actions">
				<button type="submit" id="btn-tambah-simpan" class="btn btn-success btn-block btn-flat cbt-btn-start">
					<i class="fa fa-play-circle" style="font-size: 20px;"></i> MULAI KERJAKAN SEKARANG
				</button>
				<a href="<?php echo site_url('tes_dashboard'); ?>" class="btn btn-default btn-block btn-flat cbt-btn-back">
					<i class="fa fa-arrow-left"></i> Kembali ke Daftar Ujian
				</a>
			</div>
		</div>
		</form>
	</div>
</div><!-- /.container -->

<script type="text/javascript">
    $(function () {
        $('#form-konfirmasi-tes').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/mulai_tes",
                    type:"POST",
                    data:$('#form-konfirmasi-tes').serialize(),
                    cache: false,
                    timeout: 60000,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html('');
                            window.open("<?php echo site_url(); ?>/tes_kerjakan/index/"+obj.tes_id, "_self");
                        }else if(obj.status==2){
                            window.open("<?php echo site_url().'/'.$url; ?>/", "_self");
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    },
                    statusCode: {
                        500: function(respon) {
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err('Terjadi kesalahan pada persiapan Tes. Silahkan hubungi petugas.'));
                        }
                    },
                    error: function(xmlhttprequest, textstatus, message) {
                        if(textstatus==="timeout") {
                            $("#modal-proses").modal('hide');
                            notify_error("Gagal menyiapkan Tes, Halaman akan di Refresh !");
                            setInterval(function() {
                                window.location.reload();
                            }, 4000);
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(textstatus);
                            setInterval(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    }
            });
            return false;
        });
    });
</script>