<style type="text/css">
	.cbt-mgr-wrap {
		max-width: 420px;
		margin: 14px auto 32px auto;
		padding: 0 12px;
	}
	.cbt-mgr-header {
		text-align: center;
		margin-bottom: 18px;
	}
	.cbt-mgr-header img {
		height: 82px;
		width: auto;
		display: block;
		margin: 0 auto 12px auto;
	}
	.cbt-mgr-title {
		font-size: 21px;
		font-weight: 800;
		color: #1f2d3d;
		letter-spacing: 0.4px;
		text-transform: uppercase;
		line-height: 1.25;
		margin: 0;
	}
	.cbt-mgr-badge {
		margin-top: 10px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		background: #e8f8f0;
		border: 1.5px solid #8fd9b6;
		color: #00733e;
		border-radius: 8px;
		padding: 7px 15px;
		font-size: 14px;
		font-weight: 700;
		line-height: 1.35;
		box-shadow: 0 2px 6px rgba(0, 166, 90, 0.08);
	}
	.cbt-mgr-card {
		background: #ffffff;
		border-top: 4px solid #00a65a;
		border-radius: 10px;
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
		padding: 26px 22px;
	}
	.cbt-mgr-card-title {
		text-align: center;
		font-weight: 700;
		font-size: 17px;
		color: #333;
		margin: 0 0 18px 0;
	}
	.cbt-mgr-input-group {
		position: relative;
		margin-bottom: 16px;
	}
	.cbt-mgr-input-group .form-control {
		height: 50px;
		font-size: 16px;
		font-weight: 600;
		padding: 10px 44px 10px 16px;
		border-radius: 8px;
		border: 1.5px solid #cfd8dc;
		box-shadow: none;
		transition: border-color 0.2s, box-shadow 0.2s;
	}
	.cbt-mgr-input-group .form-control:focus {
		border-color: #00a65a;
		box-shadow: 0 0 0 3px rgba(0, 166, 90, 0.15);
	}
	.cbt-mgr-input-group .form-control-feedback {
		width: 44px;
		height: 50px;
		line-height: 50px !important;
		font-size: 17px;
		color: #78909c;
	}
	.cbt-mgr-show-pass {
		margin-bottom: 16px;
		font-size: 15px;
		color: #455a64;
	}
	.cbt-mgr-btn-submit {
		height: 50px;
		font-size: 17px;
		font-weight: 700;
		letter-spacing: 0.5px;
		border-radius: 8px !important;
		box-shadow: 0 4px 10px rgba(0, 166, 90, 0.25);
	}
	.cbt-mgr-note {
		margin-top: 14px;
		text-align: center;
		font-size: 12.5px;
		color: #78909c;
	}
</style>

<div class="container">
	<section class="content" style="padding-top: 20px;">
		<?php echo form_open('welcome/login', 'id="form-login"')?>
		<div class="cbt-mgr-wrap">
			<div class="cbt-mgr-header">
				<img src="<?php echo base_url(); ?>logo-smk.png" alt="Logo SMK NU Darussalam" />
				<h1 class="cbt-mgr-title">CBT <?php if(!empty($site_name)){ echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); }else{ echo 'SMK NU DARUSSALAM'; } ?></h1>
				<div class="cbt-mgr-badge">
					<i class="fa fa-shield"></i>
					<span>Portal Pengawas &amp; Operator</span>
				</div>
			</div>

			<div class="cbt-mgr-card">
				<p class="cbt-mgr-card-title">Login Pengawas / Operator</p>
				<div id="form-pesan"></div>

				<div class="cbt-mgr-input-group has-feedback">
					<input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder="Username" />
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>

				<div class="cbt-mgr-input-group has-feedback">
					<input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Password" />
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>

				<div class="checkbox icheck cbt-mgr-show-pass">
					<label>
						<input type="checkbox" id="show-password"> &nbsp;Tampilkan Password
					</label>
				</div>

				<button type="submit" id="btn-login" class="btn btn-success btn-block btn-flat cbt-mgr-btn-submit">
					<i class="fa fa-sign-in"></i> &nbsp;MASUK PENGAWAS
				</button>

				<p class="cbt-mgr-note">Khusus Pengawas Ruang, Proktor, dan Administrator.</p>
			</div>
		</div>
		</form>
	</section><!-- /.content -->
</div>

<div class="modal" id="modal-proses" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				Data Sedang diproses...
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	function showpassword() {
		var x = document.getElementById("password");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}

	$(function () {
		$('#username').focus();

		$('#show-password').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%'
		});

		$('#show-password').on('ifChanged', function () {
			showpassword();
		});

		$('#form-login').submit(function () {
			$("#modal-proses").modal('show');
			$.ajax({
				url: "<?php echo site_url(); ?>/manager/welcome/login",
				type: "POST",
				data: $('#form-login').serialize(),
				cache: false,
				success: function (respon) {
					var obj = $.parseJSON(respon);
					if (obj.status == 1) {
						window.open("<?php echo site_url(); ?>/manager/dashboard", "_self");
					} else {
						$('#form-pesan').html(pesan_err(obj.error));
						$("#modal-proses").modal('hide');
						$('#username').focus();
					}
				},
				error: function (request, status, errorThrown) {
					$("#modal-proses").modal('hide');
					$('#form-pesan').html(pesan_err("Terjadi Kesalahan Sistem. Silahkan hubungi Administrator.<br/> " + errorThrown));
				}
			});

			return false;
		});
	});
</script>
