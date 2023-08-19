<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/tgl_indo.php';

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
   <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="../../assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="../../assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
   <link href="../../assets/font/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="../../assets/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" />
   <link href="../../assets/css/styleLaporan.css" rel="stylesheet" />
   <link href="../../assets/css/style-responsive.css" rel="stylesheet" />
   <link href="../../assets/css/style-default.css" rel="stylesheet" id="style_color" />
   <link href="../../assets/css/custom.css" rel="stylesheet" />
   <link href="../../assets/pace/pace-flash.css" rel="stylesheet" />
   <link href="../../assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="../../assets/uniform/css/uniform.default.css" />


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
         <div class="container-fluid" style="width: 99%;">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title" style="text-align: center;">
                     Laporan Perfaktur
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
                      <span class="tools">
                          <a href="javascript:;" class="icon-chevron-down"></a>
                          <!-- <a href="javascript:;" class="icon-remove"></a> -->
                      </span>
                    </div>
<?php

$query = "SELECT no_faktur AS faktur, tgl, pengirim, ket FROM keluar 
          WHERE SUBSTRING(no_faktur, -13, 2)!='MG' AND no_faktur !=0 ORDER BY faktur ASC";
    $result = $koneksi->query($query);

    $fetch = $result->fetch_all(MYSQL_ASSOC);
    if ($result->num_rows > 0) {

?>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="sample_1">
                            <thead>
                            <tr>
                                
                                <th>No</th>
                                <th>No Faktur</th>
                                <th>Tanggal Kirim</th>
                                <th>Pengirim</th>
                                <th>Ket</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                            <!-- isi tabel -->
                            <?php
                            $no=1;
                            foreach ($fetch as $key => $val) {

                            ?>
                            <tr>
                              <td><?php echo $no; ?></td>
                              <td><?php echo $val['faktur']; ?></td>
                              <td><?php echo TanggalIndo($val['tgl']); ?></td>
                              <td><?php echo $val['pengirim']; ?></td>
                              <td><?php echo $val['ket']; ?></td>
                            </tr>
                            <?php
                            $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
<?php
}else{
  echo "No Faktur Tidak Ada";
}
?>
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
   <script src="../../assets/js/jquery-1.8.3.min.js"></script>
   <script src="../../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="../../assets/js/jquery.blockui.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="js/excanvas.js"></script>
   <script src="js/respond.js"></script>
   <![endif]-->
   <script type="text/javascript" src="../../assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.min.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/DT_bootstrap.js"></script>
   <script src="../../assets/js/jquery.scrollTo.min.js"></script>

   <!--common script for all pages-->
   <script src="../../assets/js/common-scripts.js"></script>

   <!-- script for this page only -->
   <script src="../../assets/js/dynamic-table.js"></script>
   <!-- <script src="../../jsAction/faktur.js"></script> --> 
   <!-- END JAVASCRIPTS -->    
</body>
<!-- END BODY -->
</html>
