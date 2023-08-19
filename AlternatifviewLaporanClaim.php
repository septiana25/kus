<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'function/tgl_indo.php';

//$kal       = CAL_GREGORIAN;
$bulan     = 07;
$tahun     = 2017;
// $hari      = cal_days_in_month($kal, $bulan, $tahun);
// $hari1     = $hari + 3; 
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
<?php

$lapClaim = "SELECT pengaduan, toko, b.brg, k.kat, pattern, dot, kerusakan, keputusan, nominal, daerah, dealer, tglNota
FROM(
SELECT pengaduan, toko, brg, pattern, dot, kerusakan, keputusan, nominal, daerah, dealer, tglNota
FROM tblNota
JOIN tblDetNota USING(idNota)
JOIN claim USING(id_claim)
JOIN barang USING(id_brg)
)b
LEFT JOIN(
  SELECT kat, brg
  FROM barang
  JOIN claim USING(id_brg)
  JOIN kat USING(id_kat)
)k ON b.brg=k.brg 
GROUP BY kat, pengaduan
ORDER BY pengaduan ASC, kat
";

$result1 = $koneksi->query($lapClaim);
$fetch = $result1->fetch_all(MYSQL_ASSOC);

// echo "<pre>". print_r($fetch); die;
// echo '<pre>'.print_r($fetch, true).'</pre>';
foreach ($fetch as $key => $val) 
{
  $result[$val['kat']][] = $val;

}

// echo "<pre>". print_r($result); die;
// echo '<pre>'.print_r($result, true).'</pre>';



?>
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title" style="text-align: center;">
                     Laporan Transaksi Bulan <?php echo $BulanIndo[(int)$bulan-1]." ".$tahun ?>
                   </h3>
                   <p style="float: left; padding-right: 100px;">Dealer : <?php echo $val['dealer']; ?></p>
                   <p style="float: left; padding-right: 500px;">Daerah : <?php echo $val['daerah']; ?></p>
                   <p >Tanggal : <?php echo TanggalIndo($val['tglNota']); ?></p>
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
                  
                                <!-- <th>No</th> -->
                                <th class="hidden">No Pengaduan</th>
                                <th>No Pengaduan</th>
                                <th>Pemakai</th>
                                <th>No Urut</th>
                                <th>Ukuran</th>
                                <th>Pettern</th>
                                <th>DOT</th>
                                <th>Kerusakan</th>
                                <th>Keputusan</th>
                                <th>Nominal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- isi tabel -->
                            <?php
                            $no="";
                            foreach ($result as $kat => $array)
// echo '<pre>'.print_r($array, true).'</pre>';


                            {
                              $no =1;
                              foreach ($array as $key => $array2)
                              {

                                echo '
                                    <tr>
                                      <td class="hidden">'.$array2['kat'].'</td>
                                      <td>'.$array2['pengaduan'].'</td>
                                      <td>'.$array2['toko'].'</td>
                                      <td>'.$no.'</td>
                                      <td>'.$array2['brg'].'</td>
                                      <td>'.$array2['pattern'].'</td>
                                      <td>'.$array2['dot'].'</td>
                                      <td>'.$array2['kerusakan'].'</td>
                                      <td>'.$array2['keputusan'].'</td>
                                      <td>'.$array2['nominal'].'</td>
                                    </tr>
                                    ';
                              $no++;
                              }
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