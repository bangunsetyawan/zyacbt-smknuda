<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Cetak Kartu Login
		<small>Slip akun login ujian (hitam-putih simpel, ukuran pas wadah B2/B3, 21 kartu per lembar A4)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Cetak Kartu Login</li>
	</ol>
</section>

<style>
    .preview-slip-cut {
        width: 260px; /* proporsional 68mm x 37.5mm pas wadah B2/B3 */
        margin: 0 auto;
        border: 1px dashed #666;
        padding: 5px;
        background: #fff;
    }
    .preview-slip-box {
        border: 1.2px solid #000;
        padding: 7px 9px;
        font-family: Arial, Helvetica, sans-serif;
        color: #000;
        background: #fff;
    }
    .preview-slip-head {
        border-bottom: 1px solid #000;
        padding-bottom: 3px;
        margin-bottom: 5px;
        text-align: center;
        line-height: 1.2;
    }
    .preview-slip-head .p-title {
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }
    .preview-slip-head .p-school {
        font-size: 9px;
    }
    .preview-slip-nama {
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .preview-slip-kelas {
        font-size: 9.5px;
        margin-bottom: 5px;
    }
    .preview-slip-cred {
        width: 100%;
        border-collapse: collapse;
        border-top: 1px dotted #444;
        font-size: 10.5px;
    }
    .preview-slip-cred td {
        padding-top: 3px;
        vertical-align: middle;
    }
    .preview-mono {
        font-family: "Courier New", Courier, monospace;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.4px;
    }
</style>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-print"></i> Pengaturan Cetak Kartu Login</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label for="group">Pilih Kelas (Group)</label>
                        <select name="group" id="group" class="form-control">
                            <?php if(!empty($select_group)){ echo $select_group; } ?>
                        </select>
                        <p class="help-block">Pilih satu kelas tertentu atau cetak seluruh kelas sekaligus.</p>
                    </div>

                    <div class="form-group">
                        <label for="filter-detail">Filter Ruang / Sesi <small class="text-muted">(Opsional)</small></label>
                        <select name="detail" id="filter-detail" class="form-control">
                            <option value="">Semua Ruang / Sesi</option>
                            <?php if(!empty($details)){ foreach($details as $d){ ?>
                                <option value="<?php echo html_escape($d->detail); ?>"><?php echo html_escape($d->detail); ?></option>
                            <?php } } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="judul-ujian">Judul / Keterangan Singkat <small class="text-muted">(Opsional, kosongkan agar makin ringkas)</small></label>
                        <input type="text" id="judul-ujian" class="form-control" value="<?php echo html_escape($cbt_nama); ?>" placeholder="Contoh: SMK NU Darussalam">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label>Ukuran Kertas</label>
                        <div>
                            <label style="font-weight:normal; margin-right:18px; cursor:pointer;">
                                <input type="radio" name="kertas" value="a4" checked> <strong>A4</strong> (21 kartu/lembar &mdash; 3 &times; 7)
                            </label>
                            <label style="font-weight:normal; cursor:pointer;">
                                <input type="radio" name="kertas" value="f4"> <strong>F4 / Folio</strong> (24 kartu/lembar &mdash; 3 &times; 8)
                            </label>
                        </div>
                    </div>
                </div>
				
				<div class="box-footer">
                    <button type="button" class="btn btn-primary" id="btn-cetak-kartu">
                        <i class="fa fa-print"></i> Preview &amp; Cetak Kartu Login
                    </button>
                    <a href="<?php echo site_url('manager/peserta_daftar'); ?>" class="btn btn-default pull-right">
                        <i class="fa fa-users"></i> Kembali ke Peserta &amp; Kelas
                    </a>
                </div>
            </div>
        </div>

        <!-- Live Preview Contoh Slip Login B2/B3 -->
        <div class="col-md-5">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Contoh Kartu Login (Ukuran Wadah B2/B3)</h3>
                </div>
                <div class="box-body" style="background: #f3f4f6; padding: 22px 12px;">
                    <div class="preview-slip-cut">
                        <div class="preview-slip-box">
                            <div class="preview-slip-head">
                                <div class="p-title">KARTU LOGIN UJIAN</div>
                                <div class="p-school" id="preview-subjudul"><?php echo html_escape($cbt_nama); ?></div>
                            </div>
                            <div class="preview-slip-nama">ARINI SILVIYATUN NAJAH</div>
                            <div class="preview-slip-kelas">Kelas: X AKL</div>
                            <table class="preview-slip-cred">
                                <tr>
                                    <td style="width:46px;">User</td>
                                    <td style="width:8px;">:</td>
                                    <td><span class="preview-mono">1026000001</span></td>
                                </tr>
                                <tr>
                                    <td>Pass</td>
                                    <td>:</td>
                                    <td><span class="preview-mono">1026000001</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <p class="text-center text-muted" style="font-size:12px; margin-top:12px; margin-bottom:0;">
                        Ukuran jadi <strong>64 &times; 37 mm</strong> &mdash; pas masuk wadah kartu <strong>B2 / B3</strong> tanpa dilipat, hitam-putih polos, <strong>21 kartu per lembar A4</strong> (24/F4).
                    </p>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
	$(function(){
        $('#judul-ujian').on('keyup input', function(){
            var txt = $.trim($(this).val());
            if(txt === ''){
                $('#preview-subjudul').hide();
            }else{
                $('#preview-subjudul').text(txt).show();
            }
        });

        $('#btn-cetak-kartu').click(function(){
			var grup_id = $('#group').val() || 'semua';
            var detail  = $('#filter-detail').val() || '';
            var judul   = $.trim($('#judul-ujian').val() || '');
            var kertas  = $('input[name="kertas"]:checked').val() || 'a4';

            var params = [];
            if(detail !== '') params.push('detail=' + encodeURIComponent(detail));
            if(judul !== '')  params.push('judul=' + encodeURIComponent(judul));
            params.push('kertas=' + encodeURIComponent(kertas));

            var targetUrl = "<?php echo site_url($url.'/cetak_kartu'); ?>/" + encodeURIComponent(grup_id) + "?" + params.join('&');
			window.open(targetUrl, '_blank');
		});
    });
</script>
