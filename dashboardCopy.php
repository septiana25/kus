<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

    require_once 'include/header.php';
    require_once 'include/menu.php';
      $BulanIndo      = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

      $query_brg = $koneksi->query("SELECT IFNULL(total_msk, 0) - IFNULL(total_klr, 0) AS total_stok
                                    FROM( 
                                        SELECT id_brg, SUM( IFNULL(jml_msk, 0)) AS total_msk
                                        FROM detail_masuk
                                        LEFT JOIN detail_brg AS detBrg USING(id)
                                        LEFT JOIN masuk USING(id_msk)
                                        LEFT JOIN rak ON detBrg.id_rak = rak.id_rak
                                        RIGHT JOIN barang USING(id_brg)
                                        WHERE retur IN('0','1')
                                    )msk
                                    LEFT JOIN(
                                        SELECT id_brg, SUM( IFNULL(jml_klr, 0)) AS total_klr
                                        FROM detail_keluar
                                        LEFT JOIN detail_brg USING(id)
                                        LEFT JOIN keluar USING(id_klr)
                                        LEFT JOIN rak USING(id_rak)
                                        RIGHT JOIN barang USING(id_brg)
                                    )klr ON msk.id_brg=klr.id_brg");
      $row_brg = $query_brg->fetch_array();

      $query_rak = $koneksi->query("SELECT COUNT(*) FROM rak");
      $row_rak = $query_rak->fetch_array();

      $query_SaldoAwal = $koneksi->query("SELECT IFNULL(SUM(saldo_awal), 0) AS s_awal FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
      $row_SaldoAwal = $query_SaldoAwal->fetch_array();

      $query_msk = $koneksi->query("SELECT IFNULL( SUM(jml_msk), 0) AS total_msk
                                    FROM masuk
                                    LEFT JOIN detail_masuk USING(id_msk)
                                    WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND retur IN('0','1')");
      $row_msk = $query_msk->fetch_array();

      $query_klr = $koneksi->query("SELECT IFNULL( SUM(jml_klr), 0) AS total_klr
                                    FROM keluar
                                    LEFT JOIN detail_keluar USING(id_klr)
                                    WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
      $row_klr = $query_klr->fetch_array();
?>

<!-- BEGIN PAGE -->
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                     Dashboard
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Dashboard</a>
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <!--BEGIN METRO STATES-->
                <div class="metro-nav">
                    <div class="metro-nav-block nav-light-blue">
                        <a data-original-title="" href="#">
                            <i class="icon-tasks"></i>
                            <div class="info"><?php echo $row_brg[0]; ?></div>
                            <div class="status">Saldo Akhir</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-yellow">
                        <a data-original-title="" href="#">
                            <i class="icon-reorder"></i>
                            <div class="info"><?php echo $row_SaldoAwal[0]; ?></div>
                            <div class="status">Saldo Awal</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-olive">
                        <a data-original-title="" href="#">
                            <i class="icon-tags"></i>
                            <div class="info"><?php echo $row_rak[0]; ?></div>
                            <div class="status">Location Rak</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-green ">
                        <a data-original-title="" href="#">
                            <i class="icon-signin"></i>
                            <div class="info"><?php echo $row_msk[0]; ?></div>
                            <div class="status">Barang Masuk Bulan <?php echo $BulanIndo[(int)$bulan-1]; ?></div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-red">
                        <a data-original-title="" href="#">
                            <i class="icon-signout"></i>
                            <div class="info"><?php echo $row_klr[0]; ?></div>
                            <div class="status">Barang Keluar Bulan <?php echo $BulanIndo[(int)$bulan-1]; ?></div>
                        </a>
                    </div>
                </div>
                <!--END METRO STATES-->
            </div>

            <div class="row-fluid">
                 <div class="span6">
                     <!-- BEGIN NOTIFICATIONS PORTLET-->
                     <div class="widget blue">
                         <div class="widget-title">
                             <h4><i class="icon-bell"></i> Notification </h4>
                           <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                         </div>
                         <div class="widget-body">
                             <ul class="item-list scroller padding"  style="overflow: hidden; width: auto; height: 320px;" data-always-visible="1">
                                 <li>
                                     <span class="label label-success"><i class="icon-bell"></i></span>
                                     <span>New user registered.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">Just now</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-success"><i class="icon-bell"></i></span>
                                     <span>New order received.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">15 mins ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-warning"><i class="icon-bullhorn"></i></span>
                                     <span>Alerting a user account balance.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">3 hours ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-important"><i class=" icon-bug"></i></span>
                                     <span>Alerting administrators staff.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">9 mins ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-important"><i class=" icon-bug"></i></span>
                                     <span>Messages are not sent to users.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">10 hours ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-warning"><i class="icon-bullhorn"></i></span>
                                     <span>Web server #12 failed to relosd.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">3 mins ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-success"><i class="icon-bell"></i></span>
                                     <span>New order received.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">40 mins ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-warning"><i class="icon-bullhorn"></i></span>
                                     <span>Alerting a user account balance.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">1 hours ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-important"><i class=" icon-bug"></i></span>
                                     <span>Alerting administrators staff.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">1 mins ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-important"><i class=" icon-bug"></i></span>
                                     <span>Messages are not sent to users.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">11 hours ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-warning"><i class="icon-bullhorn"></i></span>
                                     <span>Web server #12 failed to relosd.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">1 day ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-success"><i class="icon-bell"></i></span>
                                     <span>New order received.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">10 mins ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-warning"><i class="icon-bullhorn"></i></span>
                                     <span>Alerting a user account balance.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">3 hours ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-important"><i class=" icon-bug"></i></span>
                                     <span>Alerting administrators support staff.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">6 hours ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-important"><i class=" icon-bug"></i></span>
                                     <span>Messages are not sent to users.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">3 days ago</span>

                                     </div>
                                 </li>
                                 <li>
                                     <span class="label label-warning"><i class="icon-bullhorn"></i></span>
                                     <span>Web server #12 failed to relosd.</span>
                                     <div class="pull-right">
                                         <span class="small italic ">1 day ago</span>

                                     </div>
                                 </li>
                             </ul>
                             <div class="space10"></div>
                             <a href="#" class="pull-right">View all notifications</a>
                             <div class="clearfix no-top-space no-bottom-space"></div>
                         </div>
                     </div>
                     <!-- END NOTIFICATIONS PORTLET-->
                 </div>
                 <div class="span6 responsive" data-tablet="span9 fix-margin" data-desktop="span9">
                    <!-- BEGIN CALENDAR PORTLET-->
                    <div class="widget yellow">
                        <div class="widget-title">
                            <h4><i class="icon-calendar"></i> Calendar</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                        </div>
                        <div class="widget-body">
                            <div id="calendar" class="has-toolbar"></div>
                        </div>
                    </div>
                    <!-- END CALENDAR PORTLET-->
                </div>
             </div>

           
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#activeDashboard').addClass('active');
  });
</script>



      



