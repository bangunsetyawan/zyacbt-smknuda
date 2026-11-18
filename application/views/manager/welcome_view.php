<div class="container">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Selamat Datang
        <small> di Halaman Login Administrator <?php if(!empty($site_name)){ echo $site_name; } ?></small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="callout callout-success">
        <h4>Informasi</h4>
        <p>
            Selamat datang di Halaman Login Operator / Administrator Ujian Online Berbasis Komputer <b><?php if(!empty($site_name)){ echo $site_name; } ?></b>. Untuk memulai silahkan melakukan 
            proses Login dengan menggunakan username dan password yang sudah dimiliki.
        </p>
    </div>
    
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-success" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                <div class="box-header with-border" style="display: flex; align-items: center; gap: 10px;">
                    <img src="<?php echo base_url(); ?>logo-smk.png" alt="Logo SMK NU Darussalam" style="height: 36px; width: auto;" />
                    <h3 class="box-title" style="margin: 0;">Login Operator — <?php if(!empty($site_name)){ echo $site_name; } ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open('welcome/login','id="form-login" class="form-horizontal"')?>
                    <div class="box-body">
						<div id="form-pesan">
						</div>
						
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" id="btn-login" class="btn btn-success pull-right" >Login</button>
                    </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
        <div class="col-md-3"></div>
    </div>
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
    $(function () {
        $('#username').focus();   
        
        $('#btn-login').click(function(){
            $('#form-login').submit();
        });
        
        $('#form-login').submit(function(){
            $("#modal-proses").modal('show');
                $.ajax({
                    url:"<?php echo site_url(); ?>/manager/welcome/login",
     			    type:"POST",
     			    data:$('#form-login').serialize(),
     			    cache: false,
      		        success:function(respon){
         		    	var obj = $.parseJSON(respon);
      		            if(obj.status==1){
      		                window.open("<?php echo site_url(); ?>/manager/dashboard","_self");
          		        }else{
                            $('#form-pesan').html(pesan_err(obj.error));
                            $("#modal-proses").modal('hide');
          		        }
         			}
      		});
            
      		return false;
        });    
    });
</script>