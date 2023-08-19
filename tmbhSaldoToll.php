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
      $brg = "SELECT id_brg, brg FROM barang ORDER BY brg ASC";
      $brg1 = $koneksi->query($brg);
      //query rak
      $rak = $koneksi->query("SELECT id_rak, rak FROM rak ORDER BY rak ASC");

        echo "<div class='div-request div-hide'>tmbhSaldoToll</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Tambah Saldo E-Toll
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
                           Tambah Saldo E-Toll
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
                        <a href="#myModal1" role="button" class="btn btn-primary tambah" id="addTmbhSaldoTollBtn" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                            
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelTmbhToll">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>No E-Toll</th>
                                <th>Pemegang</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
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

            <!-- BEGIN MODAL TAMBAH SALDO E-TOLL-->
              <div id="myModal1" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT TAMBAH SALDO E-TOLL</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitTmbhSaldo" action="action/etoll/simpanTmbhSaldoToll.php" method="POST" >
                      <div class="modal-body modal-full tinggi">
                          <div class="control-group">
                              <label class="control-label"><strong>No E-Toll</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <select id="NoEtoll" name="NoEtoll" class="chosen-select" data-placeholder="Pilih No E-Toll..." >
                                <option value=""></option>
                                <?php
                                $noToll = "SELECT * FROM tblEToll ORDER BY no_toll ASC";
                                $resnoToll = $koneksi->query($noToll);
                                while ($rowToll = $resnoToll->fetch_array()) {
                                  echo "<option value='$rowToll[0]'>$rowToll[1] $rowToll[2]</option>";
                                }
                                ?>
                                </select>
                              </div>
                          </div>
                          <div class="control-group ">
                              <label for="cname" class="control-label"><strong>Nominal Saldo</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                  <input class="span12 " id="nominalSaldo" name="nominalSaldo" type="text"  placeholder="Input Nominal Saldo E-Toll" onkeyup="harusAngka(this)" />
                              </div>
                          </div>
                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">

                          <button class="btn btn-primary" id="simpanTmbhSaldoBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL TAMBAH SALDO E-TOLL-->

              <!-- BEGIN MODAL HAPUS MASUK-->
              <div id="hapusNoTollModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA MASUK</h3>
                  </div>
                  <div class="modal-body">
                      <p id="pesanHapus" style="color: #dc5d3a"></p>

                  </div>
                  <div class="modal-footer">
                      <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      <button class="btn btn-danger" id="hapusMasukBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-trash"></i> Hapus</button>
                  </div>
              </div>
              <!-- END MODAL HAPUS MASUK-->

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/e-toll.js"></script> 

    <script src="assets/chosen/chosen1.jquery.min.js"></script>
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>