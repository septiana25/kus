<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$kal       = CAL_GREGORIAN;
$bulan     = $_POST['bulan'];
$tahun     = $_POST['tahun'];
$hari      = cal_days_in_month($kal, $bulan, $tahun);
$hari1     = $hari + 2; 
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
   <link href="assets/css/styleLaporan.css" rel="stylesheet" />
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
                     Laporan Transaksi Keluar Bulan <?php echo $BulanIndo[(int)$bulan-1]." ".$tahun ?>
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
                        <a href="<?php echo "action/laporan/exportexcel.php?b=$bulan&t=$tahun"; ?>" role="button" class="btn btn-primary tambah" id="addBarangBtnModal" > <i class="fa fa-file-excel-o"></i> Export Excel</a>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
<?php

  if ($hari == 31) {
    require_once 'action/laporan/bulan_31.php';
  }
  elseif ($hari == 30) {
    require_once 'action/laporan/bulan_30.php';
  }
  elseif ($hari == 29) {
    require_once 'action/laporan/bulan_29.php';
  }
  elseif ($hari == 28) {
    require_once 'action/laporan/bulan_28.php';
  }

$result = $koneksi->query($sql);

?>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="sample_1">
                            <thead>
                            <tr>
                                
                                <th style="width: 50px">No</th>
                                <th >Nama Barang</th>
                                <th >Kategori</th>
                                <th style="width: 50px">S.Awal</th>
                                <th style="width: 50px">T.Masuk</th>
                                <?php
                                for ($i=1; $i <= $hari ; $i++) { 
                                  echo "<th class='hidden-phone'>$i</th>";
                                }
                                ?>
                                <th style="width: 50px">Adjusmen</th>
                                <th style="width: 50px">T.Keluar</th>
                                <th style="width: 50px">S.Akhir</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- isi tabel -->
                            <?php
                            $no =1;
                            while ($row = $result->fetch_array()) {
                            
                            ?>
                            <tr>
                              <td><?php echo $no; ?></td>
                              <td><?php echo utf8_encode($row['brg']); ?></td>
                              <td><?php echo $row['kat']; ?></td>
                              <td><?php echo $row['s_awal']; ?></td>
                              <td><?php echo $row['b_masuk']; ?></td>
                              <?php

                              for ($i=3; $i <= $hari1 ; $i++) { 
                                echo "<td class='hidden-phone'>$row[$i]</td>";
                              }
                              ?>
                              <td><?php echo $row['adjusmen']; ?></td>
                              <td><?php echo $row['total_keluar']; ?></td>
                              <td><?php echo $row['s_akhir']; ?></td>
                            </tr>
                            <?php
                            $no++;
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