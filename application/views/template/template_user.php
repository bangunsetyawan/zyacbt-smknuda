<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php if(!empty($site_name)){ echo $site_name; } ?> | <?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=10, user-scalable=yes' name='viewport'>
	<meta name="description" content="Aplikasi Ujian Online Berbasis Komputer SMK NU Darussalam">
	<meta name="keywords" content="CBT SMK NU Darussalam, Ujian Online SMK NU Darussalam, ZYACBT">
	<meta name="author" content="Achmad Lutfi">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon" />
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url(); ?>icon-192.png" />
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo base_url(); ?>icon-512.png" />
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>apple-touch-icon.png" />
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

    <link href="<?php echo base_url(); ?>public/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
  
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
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>public/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>public/app.js" type="text/javascript"></script>
    <style type="text/css">
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
        flex-shrink: 0;
      }
      @media (max-width: 480px) {
        .cbt-topbar { padding: 0 14px; }
        .cbt-brand { font-size: 18px; }
        .cbt-clock-badge { font-size: 13px; padding: 5px 11px; }
      }
    </style>
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
            <div class="cbt-clock-badge" title="Waktu Server">
              <i class="fa fa-clock-o"></i>
              <span id="timestamp"><?php echo date('H:i:s', !empty($timestamp) ? $timestamp : time()); ?> WIB</span>
            </div>
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
			<?php
				if(!empty($link_login_operator)){
					if($link_login_operator=='ya'){
						?>
							<strong> <a href="<?php echo site_url(); ?>/manager/" >Log In Operator</a></strong>
						<?php
					}
				}else{
					?>
						<strong> <a href="<?php echo site_url(); ?>/manager/" >Log In Operator</a></strong>
					<?php
				}
			?>
        </div>
        <div class="container">
          <strong>&copy; 2026 achmadlutfi.wordpress.com</strong>
        </div><!-- /.container -->
      </footer>
    </div><!-- ./wrapper -->

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
    });
  </script>
  </body>
</html>
