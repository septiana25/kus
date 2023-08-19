<?php require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';

      $tahun          = date("Y");
      $bulan          = date("m");

      $cek_saldo = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
      if ($cek_saldo->num_rows >=1 ) {

      require_once 'include/header.php';
      require_once 'include/menu.php';
        echo "<div class='div-request div-hide'>DataPosting</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Data Posting
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">E-Toll</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Data Posting
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
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelDataPosting">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>No E-Toll</th>
                                <th>Pemegang</th>
                                <th>Top UP</th>
                                <th>Saldo Awal</th>
                                <th>Pemakaian</th>
                                <th>Saldo Akhir</th>
                                <th>Tanggal</th>
                                <th>Action</th>
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
                <!-- BEGIN MODAL POSTING TRANSAKSI E-TOLL-->
              <div id="viewModalPost" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="fa fa-eye"></i> VIEW DATA POSTING E-TOLL</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="printPostingSubmit" action="#" method="POST" >
                      <div class="modal-body modal-full tinggi">
                         <div class="control-group">
                             <!-- <label class="control-label"><strong>No E-Toll</strong><p class="titik2">:</p></label>
                             <div class="controls">
                               <select id="postNoEtoll" name="postNoEtoll" class="chosen-select" data-placeholder="Pilih No E-Toll..." >
                               <option value=""></option>
                               <?php
                               $noToll = "SELECT * FROM tblEToll ORDER BY no_toll ASC";
                               $resnoToll = $koneksi->query($noToll);
                               while ($rowToll = $resnoToll->fetch_array()) {
                                 echo "<option value='$rowToll[0]'>$rowToll[1] $rowToll[2]</option>";
                               }
                               ?>
                               </select>
                             </div> -->
                             <table class="table">
                               <thead>
                                 <tr>
                                   <th>No EToll</th>
                                   <th>Pemegang</th>
                                   <th>No Polisi</th>
                                 </tr>
                               </thead>
                               <tbody>
                                 <tr>
                                   <td><input type="text" name="NoEtoll" id="NoEtoll" readonly="true" class="span12"></td>
                                   <td><input type="text" name="pemegang" id="pemegang" readonly="true" class="span12"></td>
                                   <td><input type="text" name="no_pol" id="no_pol" readonly="true" class="span12"></td>
                                   <td><input type="hidden" name="id_post" id="id_post"></td>
                                 </tr>
                               </tbody>
                             </table>
                         </div>
                            <div class="modal-loading hide" style="width:50px; margin:auto;">
                              <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                              <span class="sr-only">Loading...</span>
                            </div>
                          <div class="control-group" id="tabelViewDataPost">
                            
                          </div>
                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="postingTransTollBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-print"></i> Print</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL POSTING TRANSAKSI E-TOLL-->



            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/e-toll.js"></script> 
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>