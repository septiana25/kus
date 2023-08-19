<?php require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';

      $tahun          = date("Y");
      $bulan          = date("m");

      $cek_saldo = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
      if ($cek_saldo->num_rows >=1 ) {

      require_once 'include/header.php';
      require_once 'include/menu.php';

      //query barang
      
      //query rak

        echo "<div class='div-request div-hide'>notaPengganti</div>";
?>
      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Nota Penggantian
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Claim</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Nota Penggantian
                       </li>

                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>

            <!-- BEGIN MODAL MASUK-->
              <div id="modalVIewData" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="fa fa-eye"></i> Data Nota Penggantian</h3>
                  </div>
                  <!-- <form class="cmxform form-horizontal" id="submitBarangKlr" action="action/barangKeluar/simpanKeluar.php" method="POST" > -->
                      <div class="modal-body modal-full tinggi">

                          <div class="control-group">
                            <table width="100%" role="none">
                              <tr>
                                <td>
                                  <input class="input-xlarge " id="toko" name="toko" type="text" readonly="true" />
                                </td>
                                <td>
                                  <input class="input-xlarge " id="keputusan" name="keputusan" type="text" readonly="true" />
                                </td>
                              </tr>
                            </table>
                          </div>
                          <div class="control-group">
                            <table class="table table-striped table-bordered" id="tabelNota">
                              <thead>
                                <tr>
                                  <th >No</th>
                                  <th >Ukuran</th>
                                  <th >No Seri</th>
                                  <th >No Portal</th>
                                  <th >JML (RP)</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>

                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <!-- <button class="btn btn-primary" id="simpanBarangKlrBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button> -->
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  <!-- </form> -->
              </div>
                <!-- END MODAL MASUK-->

            <!-- END PAGE HEADER-->
            <div id="hapus-pesan"></div>
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget red">
                    <div class="widget-title">
                        <!-- <a href="#myModal1" role="button" class="btn btn-primary tambah" id="addBarangKlrBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a> -->
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tableNotaPengganti">
                            <thead>
                            <!-- <tr>
                              <th colspan="5"></th>
                              <th colspan="5"><center>Kerusakan</center></th>
                              <th></th>
                            </tr> -->
                            <tr>
                                <th>No</th>
                                <th>Toko</th>
                                <!-- <th>No Register</th> -->
                                <th>Tgl</th>
                                <th>Item</th>
                                <th>Keputusan</th>
                                <th>Total</th>
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

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/notaPengganti.js"></script> 
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>