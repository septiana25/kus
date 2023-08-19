<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'function/tgl_indo.php';

// $bulan     = 01;
// $tahun     = 2018;
$bulan     = $koneksi->real_escape_string($_POST['cariBulan']);
$tahun     = $koneksi->real_escape_string($_POST['cariTahun']);
//echo "Pada Bulan ini Terdapat".$hari1."hari";
//aray bulan
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" style="background: white;"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>Laporan</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
   <link href="assets/font/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="assets/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" />
   <link href="assets/css/styleLaporan2.css" rel="stylesheet" />
   <!-- <link href="assets/css/style.css" rel="stylesheet" /> -->
   <link href="assets/css/style-responsive.css" rel="stylesheet" />
   <link href="assets/css/style-default.css" rel="stylesheet" id="style_color" />
   <link href="assets/css/custom.css" rel="stylesheet" />
   <link href="assets/pace/pace-flash.css" rel="stylesheet" />
   <link href="assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />


</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body >
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid" style="
    background: white">
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title" style="text-align: center;">
                     Laporan Transaksi Masuk Bulan <?php echo $BulanIndo[(int)$bulan-1]." ".$tahun ?>
                   </h3>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget red">
                    <div class="widget-title">
                        <!-- <h4><i class="icon-reorder" ></i> Laporan </h4> -->
                        <!-- <a href="<?php echo "action/laporan/exportexcel.php?b=$bulan&t=$tahun"; ?>" role="button" class="btn btn-primary tambah" id="addBarangBtnModal" > <i class="fa fa-file-excel-o"></i> Export Excel</a> -->
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>

                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="sample_1">
                            <thead>
                            <tr>
                                <th width="10%">Lokasi Rak</th>
                                <th>Nama Barang</th>
                                <th width="15%">Surat Jalan/No Retur</th>
                                <th class="hidden-phone"  width="10%">Ket</th>
                                <th class="hidden-phone"  width="10%">Tanggal</th>
                                <th class="hidden-phone" width="6%">Jam</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- isi tabel -->
                            <?php
                            $sql = "SELECT rak.rak, brg, tgl, jam, jml_msk, id_det_msk, MONTH(tgl) AS bulan, YEAR(tgl) AS tahun, ket,
                                    suratJln, retur
                                    FROM detail_masuk
                                    JOIN masuk AS msk USING(id_msk)
                                    JOIN detail_brg USING(id)
                                    JOIN barang USING(id_brg)
                                    JOIN rak USING(id_rak)
                                    WHERE retur = '0' AND MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun 
                                    ORDER BY id_det_msk DESC";

                            $result = $koneksi->query($sql);
                            while ($row = $result->fetch_array()) {
                            
                            ?>
                            <tr>
                              <td><?php echo $row['rak']; ?></td>
                              <td><?php echo utf8_encode($row['brg']); ?></td>
                              <td><?php echo $row['suratJln']; ?></td>
                              <td><?php echo $row['ket']; ?></td>
                              <td><?php echo TanggalIndo($row['tgl']); ?></td>
                              <td><?php echo $row['jam']; ?></td>
                              <td><?php echo $row['jml_msk']; ?></td>
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  
   </div>
   <!-- END CONTAINER -->


   <!-- BEGIN JAVASCRIPTS -->
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="assets/js/jquery-1.8.3.min.js"></script>
   <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/js/jquery.blockui.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="js/excanvas.js"></script>
   <script src="js/respond.js"></script>
   <![endif]-->
   <script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript" src="assets/data-tables/jquery.dataTables.min.js"></script>
   <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
   <script src="assets/js/jquery.scrollTo.min.js"></script>

   <!--common script for all pages-->
   <script src="assets/js/common-scripts.js"></script>

   <!--script for this page only-->
   <script src="assets/js/dynamic-table.js"></script>
   <!-- END JAVASCRIPTS -->    
</body>
<!-- END BODY -->
</html>