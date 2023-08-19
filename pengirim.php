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
      //$brg = "SELECT id_brg, brg FROM barang ORDER BY brg ASC";
      //$brg1 = $koneksi->query($brg);
      //query rak
      //$rak = $koneksi->query("SELECT id_rak, rak FROM rak ORDER BY rak ASC");

        echo "<div class='div-request div-hide'>rak</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Data Pengirim
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Pengirim
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
                <div class="widget red">
                    <div class="widget-title">
                        <a href="#addMoadlPengirim" role="button" class="btn btn-primary tambah" id="addPengirimBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelPengirim">
                            <thead>
                            <tr>
                                <th width="6%">No</th>
                                <th>Nama Pengirim</th>
                                <?php
                                if ($_SESSION['level'] == "administrator") {
                                  echo '<th width="15%" class="hidden-phone">Action</th>';
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

            <!-- BEGIN MODAL RAK-->
              <div id="addMoadlPengirim" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT PENGIRIM</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitPengirim" action="action/pengirim/simpanPengirim.php" method="POST" >
                      <div class="modal-body modal-full tinggi-sedang">
                          <div class="control-group ">
                              <label for="cname" class="control-label"><strong>Nama Pengirim</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                  <input class="span12" id="pengirim" name="pengirim" type="text"  placeholder="Nama Pengirim" onkeydown="upperCaseF(this)"/>
                              </div>
                          </div>
                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">

                          <button class="btn btn-primary" id="simpanPengirimBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL RAK-->

                <!-- BEGIN MODAL EDIT RAK-->
              <div id="editMoadlPengirim" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-edit-sign"></i> FORM EDIT PENGIRIM</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="editRakForm" action="action/rak/editRak.php" method="POST" >
                      <div class="modal-body modal-full tinggi-sedang">
                          <div class="control-group ">
                              <label for="cname" class="control-label"><strong>Nama Pengirim</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                  <input class="span12" id="editNamaPengirim" name="pengirim" type="text"  placeholder="Nama Pengirim" onkeydown="upperCaseF(this)"/>
                              </div>
                          </div>
                          <div class="control-group">
                            <div id="edit-pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="editPengirimBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
              <!-- END MODAL EDIT RAK-->

              <!-- BEGIN MODAL HAPUS MASUK-->
              <div id="hapusModalPengirim" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA PENGIRIM</h3>
                  </div>
                  <div class="modal-body">
                      <p id="pesanHapus" style="color: #dc5d3a"></p>

                  </div>
                  <div class="modal-footer">
                      <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      <button class="btn btn-danger" id="hapusPengirimBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-trash"></i> Hapus</button>
                  </div>
              </div>
              <!-- END MODAL HAPUS MASUK-->

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>
    <script src="jsAction/pengirim.js"></script> 
    <!-- <script src="assets/chosen/chosen.jquery.min.js"></script> -->
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>