<?php require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';

      $tahun          = date("Y");
      $bulan          = date("m");


      require_once 'include/header.php';
      require_once 'include/menu.php';
        echo "<div class='div-request div-hide'>laporanLimit</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Laporan Cek Limit Stock
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Laporan</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Laporan Cek Limit Stock
                       </li>

                   </ul>
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
                    <form id="formPrintLimit" action="printStockLimit.php" method="POST">
                      <!-- <a role="button" class="btn btn-primary" id="printCekLimtit" type="submit"> <i class=" fa fa-print"></i> Print</a> -->
                      <!-- <input type="hidden" name="date" id="date" value="<?php echo date("Y-m-d"); ?>"> -->
                      <button class="btn btn-success tambah" id="printCekLimtit" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-eye"></i> View</button>
                      <a href="#" role="button" class="btn btn-primary tambah" id="exportExcel" > <i class="fa fa-file-excel-o"></i> Export Excel</a>
                    </form>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                <!-- BEGIN MODAL EDIT BARANG-->
                <div id="editModalLimit" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel1" class="center"><i class="icon-edit-sign"></i> FORM EDIT DATA BARANG</h3>
                    </div>
                    <form class="cmxform form-horizontal" id="editLimitForm" action="action/cekLimit/editLimit.php" method="POST" >
                        <div class="modal-body modal-full tinggi">
                            <div class="control-group ">
                                <label for="cname" class="control-label"><strong>Nama Barang</strong><p class="titik2">:</p></label>
                                <div class="controls">
                                    <input class="span12 " id="editBarang" name="barang" type="text"  placeholder="Nama Barang" onkeydown="upperCaseF(this)" readonly="true" />
                                </div>
                            </div>
                            <div class="control-group ">
                                <label for="cname" class="control-label"><strong>Set Limit</strong><p class="titik2">:</p></label>
                                <div class="controls">
                                    <input class="span12 " id="setLimit" name="setLimit" type="text"  placeholder="Input Limit Barang" maxlength="5" onkeyup="harusAnggka(this)"/>
                                </div>
                            </div>
                            <div class="control-group">
                              <div id="edit-pesan"></div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button class="btn btn-primary" id="editLimitBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                        </div>
                    </form>
                </div>
                <!-- END MODAL EDIT BARANG-->
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelLimit">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Barang</th>
                                <th width="20%">Set Limit</th>
                                <?php
                                  if ($_SESSION['level'] == "administrator") {
                                    echo '<th width="10%">Action</th>';
                                    
                                  }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                        
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

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/cekLimit.js"></script> 