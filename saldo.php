<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$tahun          = date("Y");
$bulan          = date("m");

//$bulan          = 12;
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$query = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
if ($query->num_rows >=1) {
  header("location:dashboard.php");
}else{
  require_once 'include/header.php';
  require_once 'include/menu.php';
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     PERPINDAHAN SALDO
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <div id="hapus-pesan"></div>
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
              <div class="span12">
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget yellow">
                  <div class="widget-title">
                    <?php
                      if ($_SESSION['level'] == "administrator") {
                        echo '
                           <a href="#updateSaldo" role="button" class="btn btn-primary tambah" id="addSaldoBtnModal" data-toggle="modal"> <i class=" fa fa-exchange"></i> PEREMAJAAN</a>';

                        
                      }
                    ?>
                   
                    <!-- <a href="#modalBackup" role="button" class="btn  tambah" id="BackupBtnModal" data-toggle="modal"> <i class=" fa fa-database"></i> BACKUP DATABASE</a> -->
                      <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <!-- <a href="javascript:;" class="icon-remove"></a> -->
                      </span>
                  </div>
                  <!-- END EXAMPLE TABLE widget-->
                  <div class="loading">
                    <i class="fa fa-refresh fa-spin fa-4x fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="widget-body">
                      <table class="table table-striped table-bordered" id="tabelSaldo">
                          <thead>
                          <tr>
                              <th width="20%">Lokasi Rak</th>
                              <th class="hidden-phone">Nama Barang</th>
                              <!-- <th width="20%" class="hidden-phone">Rak</th> -->
                              <th width="10%" class="hidden-phone">Saldo Awal</th>
                              <th width="10%" class="hidden-phone">Saldo Akhir</th>
                          </tr>
                          </thead>
                          <tbody>
                      
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- END ADVANCED TABLE widget-->
            
            <!-- BEGIN MODAL TAMBAH BARANG-->
            <div id="updateSaldo" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel1" class="center"><i class="fa fa-exchange"></i> PERPINDAHAN SALDO BULAN <?php echo $BulanIndo[(int)$bulan - 1]." ".$tahun;?></h3>
                </div>
                <form class="cmxform form-horizontal" id="submitSaldo" action="action/saldo/updateSaldo.php" method="POST" >
                    <div class="modal-body modal-full">
                        <div class="control-group ">
                          <input class="span12 " id="bulan" name="bulan" type="hidden" value="<?php echo $bulan; ?>" />
                          <!-- <input class="span12 " id="tahun" name="tahun" type="hidden" value="<?php echo $tahun1; ?>" /> -->
                        </div>

                          <div class="alert alert-block alert-info fade in">
                              <!-- <button data-dismiss="alert" class="close" type="button">×</button> -->
                              <h4 class="alert-heading">Info!</h4>
                              <p>
                                  Tekan MULAI untuk proses perpindahan saldo bulan <?php echo $BulanIndo[(int)$bulan - 1]." ".$tahun;?>
                              </p>
                          </div>
                          <div class="alert alert-block alert-error fade in">
                              <!-- <button data-dismiss="alert" class="close" type="button">×</button> -->
                              <h4 class="alert-heading">Warning!</h4>
                              <p>
                                  Tunggu beberapa detik, sampai data dalam tabel terisi. JANGAN CLOSE HALAMAN karena akan menyebabkan gagal dalam proses perpindahan saldo.
                              </p>
                          </div>

                    </div>
                    <div class="modal-footer hidden-saldo">
                        <button class="btn btn-primary" id="simpanBarangBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-exchange"></i> Mulai</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                    </div>
                </form>
            </div>
            <!-- END MODAL TAMBAH BARANG--> 

            <!-- BEGIN MODAL BACKUP-->
            <div id="modalBackup" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel1" class="center"><i class="fa fa-database"></i> BACKUP DATABASE</h3>
                </div>
                <form class="cmxform form-horizontal" id="submitBackup" action="action/backup.php" method="POST" >
                    <div class="modal-body modal-full">
                        <div class="control-group ">
                          <input class="span12 " id="bulan" name="bulan" type="hidden" value="<?php echo $bulan; ?>" />
                          <input class="span12 " id="tahun" name="tahun" type="hidden" value="<?php echo $tahun; ?>" />
                        </div>

                          <div class="alert alert-block alert-info fade in">
                              <!-- <button data-dismiss="alert" class="close" type="button">×</button> -->
                              <h4 class="alert-heading">Info!</h4>
                              <p>
                                  Tekan BACKUP untuk proses backup database
                              </p>
                          </div>
                          <div class="alert alert-block alert-error fade in">
                              <!-- <button data-dismiss="alert" class="close" type="button">×</button> -->
                              <h4 class="alert-heading">Warning!</h4>
                              <p>
                                  Setelah backup selesai tombol PEREMAJAAN akan tampil.
                              </p>
                          </div>

                    </div>
                    <div class="modal-footer hidden-backup">
                        <button class="btn btn-primary" id="simpanBarangBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-database"></i> Backup</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                    </div>
                </form>
            </div>
            <!-- END MODAL BACKUP-->            

         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
<?php require_once 'include/footer.php'; }?>
<script src="jsAction/saldo.js"></script> 
