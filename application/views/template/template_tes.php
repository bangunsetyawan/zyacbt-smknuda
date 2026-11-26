<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php if(!empty($site_name)){ echo $site_name; } ?> | <?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=10, user-scalable=yes' name='viewport' />
	<meta name="description" content="Aplikasi Ujian Online Berbasis Komputer SMK NU Darussalam" />
	<meta name="keywords" content="CBT SMK NU Darussalam, Ujian Online SMK NU Darussalam, ZYACBT" />
	<meta name="author" content="Achmad Lutfi" />
    <meta name="google" value="notranslate" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>favicon-32x32.png?v=2.0" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>favicon-16x16.png?v=2.0" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico?v=2.0" type="image/x-icon" />
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url(); ?>icon-192.png?v=2.0" />
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo base_url(); ?>icon-512.png?v=2.0" />
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>apple-touch-icon.png?v=2.0" />
    <meta property="og:title" content="<?php if(!empty($site_name)){ echo $site_name; } ?> | <?php echo $title; ?>" />
    <meta property="og:description" content="Ujian Online Berbasis Komputer SMK NU Darussalam" />
    <meta property="og:image" content="<?php echo base_url(); ?>og-preview.png" />
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url(); ?>public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>public/plugins/pnotify/pnotify.custom.min.css" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    <link href="<?php echo base_url(); ?>public/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet" type="text/css" />
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
    
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>public/plugins/adminlte/js/app.min.js" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>public/app.js" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>public/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datatables/dataTables.reload.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>public/plugins/pnotify/pnotify.custom.min.js" type="text/javascript"></script>
    
    <!-- membuat gambar responsive pada soal -->
    <style type="text/css">
      #isi-tes-soal img {
        display: block;
        max-width: 100%;
        height: auto;
      }
      .main-header .navbar {
        min-height: 54px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
      }
      .cbt-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 54px;
        padding: 0 18px;
        width: 100%;
      }
      .cbt-topbar::before,
      .cbt-topbar::after {
        display: none !important;
        content: none !important;
      }
      .cbt-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff !important;
        text-decoration: none !important;
        font-size: 19px;
        font-weight: 700;
        letter-spacing: 0.5px;
        line-height: 1;
        white-space: nowrap;
      }
      .cbt-right-nav {
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .cbt-clock-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(0, 0, 0, 0.22);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        padding: 6px 13px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        font-family: monospace;
        letter-spacing: 0.5px;
        white-space: nowrap;
      }
      @media (max-width: 480px) {
        .cbt-topbar { padding: 0 12px; }
        .cbt-brand { font-size: 18px; }
        .cbt-clock-badge { font-size: 13px; padding: 5px 11px; }
      }
      /* Modal Ubah Password & Modal Proses lebih jelas di HP */
      .modal-dialog {
        margin: 18px 12px;
      }
      @media (min-width: 540px) {
        .modal-dialog {
          max-width: 480px;
          margin: 40px auto;
        }
      }
      .modal-content {
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
      }
      .modal-header {
        background: #00a65a;
        color: #fff;
        padding: 15px 18px;
      }
      .modal-header .close {
        color: #fff;
        opacity: 0.9;
        font-size: 26px;
      }
      .modal-title {
        font-size: 18px;
        font-weight: 800;
      }
    </style>

    <script type="text/javascript">
    function notify_success(pesan){
      new PNotify({
        title: 'Berhasil',
        text: pesan,
        type: 'success',
        history: false,
        delay:2000
      });
    }
        
    function notify_info(pesan){
      new PNotify({
        title: 'Informasi',
        text: pesan,
        type: 'info',
        history: false,
        delay:2000
      });
    }
    
    function notify_error(pesan){
      new PNotify({
        title: 'Error',
        text: pesan,
        type: 'error',
        history: false,
        delay:2000
      });
    } 
  </script>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="skin-green layout-top-nav">
    <div class="wrapper">

      <header class="main-header">               
        <nav class="navbar navbar-static-top">
          <div class="container cbt-topbar">
            <a href="<?php echo base_url(); ?>" class="cbt-brand">
              <b>ZYA CBT</b>
            </a>

            <div class="navbar-custom-menu cbt-right-nav">
                <div class="cbt-clock-badge" title="Waktu Server">
                  <i class="fa fa-clock-o"></i>
                  <span id="timestamp"><?php echo date('H:i:s', !empty($timestamp) ? $timestamp : time()); ?> WIB</span>
                </div>
                <ul class="nav navbar-nav" style="margin: 0;">
                  <!-- User Account Menu -->
                  <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <img src="<?php echo base_url(); ?>public/images/avatar.png" class="user-image" alt="User Image" />
                      <!-- hidden-xs hides the username on small devices so only the image appears. -->
                      <span class="hidden-xs"><?php if(!empty($nama)){ echo htmlspecialchars((string)$nama, ENT_QUOTES, 'UTF-8'); }else{ echo 'User Tes'; } ?></span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- The user image in the menu -->
                      <li class="user-header" style="max-height: 70px;">
                        <p>
                          <?php if(!empty($nama)){ echo htmlspecialchars((string)$nama, ENT_QUOTES, 'UTF-8'); }else{ echo 'User Tes'; } ?>
                          <?php if(!empty($group)){ echo ' | '.htmlspecialchars((string)$group, ENT_QUOTES, 'UTF-8'); } ?>
                        </p>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer">
                        <div class="pull-left">
                          <a data-toggle="modal" href="#modal-password" class="btn btn-default btn-flat">Password</a>
                        </div>
                        <div class="pull-right">
                          <a href="<?php echo site_url(); ?>/welcome/logout" class="btn btn-default btn-flat">Log out</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div><!-- /.navbar-custom-menu -->
          </div><!-- /.container-fluid -->
        </nav>
      </header>
      <!-- Full Width Column -->
      <div class="content-wrapper">
            <?php 
            if(!empty($content)){
                echo $content; 
            }
            ?>
      </div><!-- /.content-wrapper -->
      <footer class="main-footer no-print">
        <div class="pull-right hidden-xs">
          <?php if(!empty($nama)){ echo htmlspecialchars((string)$nama, ENT_QUOTES, 'UTF-8'); } ?> | <strong> <a href="<?php echo site_url(); ?>/welcome/logout" >Log out</a></strong>
        </div>
        <div class="container">
          <strong>&copy; 2026 achmadlutfi.wordpress.com</strong>
        </div><!-- /.container -->
      </footer>
    </div><!-- ./wrapper -->

    <div class="modal" id="modal-password" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <?php echo form_open('tes_dashboard/password','id="form-password"')?>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Ubah Password</h4>
          </div>
          <div class="modal-body">
                <span id="form-pesan-password"></span>
                <div class="box-body">
                  <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" class="form-control" id="password-old" name="password-old" placeholder="Old Password">
                  </div>
                  <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control" id="password-new" name="password-new" placeholder="New Password">
                  </div>
                  <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="Confirm Password">
                  </div>
                </div>  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="password-submit">Ubah Password</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
      <?php echo form_close(); ?> 
    </div><!-- /.modal -->
    
    <div class="modal" id="modal-proses" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div style="text-align: center;">
              <img width="50" src="<?php echo base_url(); ?>public/images/loading.gif" /> <br />Data Sedang diproses...              
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


  <script type="text/javascript">
    $(function () {
        var serverTime = <?php echo !empty($timestamp) ? (int)$timestamp : time(); ?>;
        var elTime = document.getElementById("timestamp");

        setInterval(function() {
          serverTime += 1;
          var d = new Date(serverTime * 1000);
          var hh = ('0' + d.getHours()).slice(-2);
          var mm = ('0' + d.getMinutes()).slice(-2);
          var ss = ('0' + d.getSeconds()).slice(-2);
          if (elTime) elTime.textContent = hh + ':' + mm + ':' + ss + ' WIB';
        }, 1000);

        $('#modal-password').on('shown.bs.modal', function (e) {
          $('#form-pesan-password').html('');
          $('#password-old').val('');
          $('#password-new').val('');
          $('#password-confirm').val('');
          $('#password-old').focus();
        });
        
        $('#form-password').submit(function(){        
          $.ajax({
            url:"<?php echo site_url(); ?>/tes_dashboard/password",
            type:"POST",
            data:$('#form-password').serialize(),
            cache: false,
            success:function(respon){
              var obj = $.parseJSON(respon);
              if(obj.status==1){
                $('#form-pesan-password').html('');
                $('#modal-password').modal('hide');
                notify_success('Password berhasil diubah');
              }else{
                $('#form-pesan-password').html(pesan_err(obj.error));
              }
            }
          });
          return false;
        });
    });
  </script>


  </body>
</html>
