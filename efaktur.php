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


        echo "<div class='div-request div-hide'>keluar</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     E-Faktur
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           E-Faktur
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
                        <a href="#ModalEFatur" role="button" class="btn btn-primary tambah" id="addEfakturBtnModal" data-toggle="modal"> <i class=" icon-plus"></i> E-Faktur</a>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tableEfaktur">
                            <thead>
                            <tr>
                                <th>No Faktur</th>
                                <th>Asal Faktur</th>
                                <th class="hidden-phone">Tanggal</th>
                                <?php
                                  if ($_SESSION['level'] == "administrator") {
                                    echo '<th>Action</th>';
                                    
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

              <!-- BEGIN MODAL TAMBAH E-FAKTUR-->
              <div id="ModalEFatur" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT E-FAKTUR</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitEfaktur" action="action/efaktur/simpanEfaktur.php" method="POST" >
                      <div class="modal-body modal-full tinggi-sedang">
                          <div class="control-group ">
                              <label for="cname" class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                  <input class="input-small" id="awalFaktur" name="awalFaktur" type="text" value="<?php echo date('y'); ?>.000" readonly="true" />
                              </div>
                              <div class="posisi-kanan">
                                  <input class="input-large" id="efaktur" name="efaktur" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
                              </div>
                          </div>
                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">

                          <button class="btn btn-primary" id="simpanEfakturBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
              <!-- END MODAL TAMBAH E-FAKTUR-->

              <!-- BEGIN MODAL EDIT E-FAKTUR-->
              <div id="ModalEditEFatur" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT E-FAKTUR</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="editEfaktur" action="action/efaktur/editEfaktur.php" method="POST" >
                      <div class="modal-body modal-full tinggi-sedang">
                          <div class="control-group ">
                              <label for="cname" class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                  <input class="input-small" id="editAwalFaktur" name="editAwalFaktur" type="text" readonly="true" />
                              </div>
                              <div class="posisi-kanan">
                                  <input class="input-large" id="editAkhirFaktur" name="editAkhirFaktur" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
                              </div>
                          </div>
                          <div class="control-group">
                            <div id="edit-pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">

                          <button class="btn btn-primary" id="editEfakturBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
              <!-- END MODAL EDIT E-FAKTUR-->

              <!-- BEGIN MODAL HAPUS E-FAKTUR-->
              <div id="hapusModalEfaktur" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA E-FAKTUR</h3>
                  </div>

                  <div class="modal-body">
                      <p id="pesanHapus" style="color: #dc5d3a"></p>
                  </div>                  

                  <div class="modal-footer">
                      <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      <button class="btn btn-danger" id="hapusEfakturBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-trash"></i> Hapus</button>
                  </div>
              </div>
              <!-- END MODAL HAPUS E-FAKTUR-->

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/efaktur.js"></script> 
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>