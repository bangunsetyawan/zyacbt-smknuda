<style type="text/css">
	.cbt-login-wrap {
		max-width: 420px;
		margin: 10px auto 30px auto;
		padding: 0 12px;
	}
	.cbt-login-header {
		text-align: center;
		margin-bottom: 18px;
	}
	.cbt-login-header img {
		height: 86px;
		width: auto;
		display: block;
		margin: 0 auto 12px auto;
	}
	.cbt-login-title {
		font-size: 22px;
		font-weight: 800;
		color: #1f2d3d;
		letter-spacing: 0.5px;
		text-transform: uppercase;
		line-height: 1.25;
		margin: 0;
	}
	.cbt-event-badge {
		margin-top: 10px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		background: #e8f8f0;
		border: 1.5px solid #8fd9b6;
		color: #00733e;
		border-radius: 8px;
		padding: 8px 16px;
		font-size: 14.5px;
		font-weight: 800;
		line-height: 1.35;
		box-shadow: 0 2px 6px rgba(0, 166, 90, 0.08);
	}
	.cbt-login-card {
		background: #ffffff;
		border-top: 4px solid #00a65a;
		border-radius: 10px;
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
		padding: 26px 22px;
	}
	.cbt-login-card-title {
		text-align: center;
		font-weight: 700;
		font-size: 17px;
		color: #333;
		margin: 0 0 18px 0;
	}
	.cbt-input-group {
		position: relative;
		margin-bottom: 16px;
	}
	.cbt-input-group .form-control {
		height: 50px;
		font-size: 17px;
		font-weight: 600;
		padding: 10px 44px 10px 16px;
		border-radius: 8px;
		border: 1.5px solid #cfd8dc;
		box-shadow: none;
		transition: border-color 0.2s, box-shadow 0.2s;
	}
	.cbt-input-group .form-control:focus {
		border-color: #00a65a;
		box-shadow: 0 0 0 3px rgba(0, 166, 90, 0.15);
	}
	.cbt-input-group .form-control-feedback {
		width: 44px;
		height: 50px;
		line-height: 50px !important;
		font-size: 17px;
		color: #78909c;
	}
	.cbt-login-actions {
		margin-top: 18px;
	}
	.cbt-show-pass {
		margin-bottom: 16px;
		font-size: 15px;
		color: #455a64;
	}
	.cbt-btn-submit {
		height: 50px;
		font-size: 17px;
		font-weight: 700;
		letter-spacing: 0.5px;
		border-radius: 8px !important;
		box-shadow: 0 4px 10px rgba(0, 166, 90, 0.25);
	}
</style>

<div class="container">
	<!-- Main content -->
    <section class="content" style="padding-top: 20px;">
    	<?php echo form_open('welcome/login','id="form-login"')?>
    	<div class="cbt-login-wrap">
    		<div class="cbt-login-header">
    			<img src="<?php echo base_url(); ?>logo-smk.png" alt="Logo SMK NU Darussalam" />
        		<h1 class="cbt-login-title">CBT SMK NU DARUSSALAM</h1>

        		<?php if(!empty($cbt_keterangan)){ ?>
        		<div class="cbt-event-badge">
        			<i class="fa fa-calendar-check-o"></i>
        			<span><?php echo htmlspecialchars($cbt_keterangan, ENT_QUOTES, 'UTF-8'); ?></span>
        		</div>
        		<?php } ?>
      		</div>

      		<div class="cbt-login-card">
        		<p class="cbt-login-card-title">Login Peserta Ujian</p>
                <div id="form-pesan"></div>

          		<div class="cbt-input-group has-feedback">
            		<input type="text" id="username" inputmode="numeric" autocomplete="off" name="username" class="form-control" placeholder="Nomor Peserta / Username"/>
            		<span class="glyphicon glyphicon-user form-control-feedback"></span>
          		</div>

          		<div class="cbt-input-group has-feedback">
            		<input type="password" id="password" inputmode="numeric" autocomplete="off" name="password" class="form-control" placeholder="Password"/>
            		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
          		</div>

          		<div class="cbt-login-actions">
                  	<div class="checkbox icheck cbt-show-pass">
                    	<label>
                      		<input type="checkbox" id="show-password"> &nbsp;Tampilkan Password
                    	</label>
                  	</div>
		            <button type="submit" class="btn btn-success btn-block btn-flat cbt-btn-submit">
		            	<i class="fa fa-sign-in"></i> &nbsp;MASUK UJIAN
		            </button>
	          	</div>
    		</div>
    	</div>
    	</form>
    </section><!-- /.content -->
</div><!-- /.container -->

<script type="text/javascript">
    function showpassword(){
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
          increaseArea: '20%' // optional
        });  

        $('#show-password').on('ifChanged', function(event){
          showpassword();
        });
        
        $('#form-login').submit(function(){
          $("#modal-proses").modal('show');
            $.ajax({
              url:"<?php echo site_url(); ?>/welcome/login",
     			    type:"POST",
     			    data:$('#form-login').serialize(),
     			    cache: false,
      		        success:function(respon){
         		    	var obj = $.parseJSON(respon);
      		            if(obj.status==1){
      		                window.open("<?php echo site_url(); ?>/tes_dashboard","_self");
          		        }else{
                            $('#form-pesan').html(pesan_err(obj.error));
                            $("#modal-proses").modal('hide');
                            $('#username').focus();   
          		        }
         			},
					error: function(request, status, errorThrown) {
						$("#modal-proses").modal('hide');
						// There's been an error, do something with it!
						// Only use status and errorThrown.
						// Chances are request will not have anything in it.
						$('#form-pesan').html(pesan_err("Terjadi Kesalahan Sistem. Silahkan hubungi Administrator.<br/> "+errorThrown));
					}
      		});
            
      		return false;
        });    
    });
</script>