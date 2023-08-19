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


        echo "<div class='div-request div-hide'>return</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Return Barang
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Transaksi Barang</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Return Barang
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
                    <?php
                        if ($_SESSION['aksi'] == "1") {
                          echo '<a href="#myModal1" role="button" class="btn btn-primary tambah" id="addReturnBtnModal" data-toggle="modal"> <i class=" icon-plus"></i> Tambah Data</a>';
                        }
                    ?>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelReturn">
                            <thead>
                            <tr>
                                <th>No Faktur</th>
                                <th>Pengirim</th>
                                <th>Lokasi Rak</th>
                                <th>Nama Barang</th>
                                <th class="hidden-phone">Tanggal</th>
                                <th class="hidden-phone">Jam</th>
                                <th>Total</th>
                                <th>Ket</th>
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

            <!-- BEGIN MODAL MASUK-->
              <div id="myModal1" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT RETURN BARANG</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitReturn" action="action/return/simpanReturn.php" method="POST" >
                      <div class="modal-body modal-full tinggi">
                          <div class="control-group">
                              <label class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <select id="nofak" name="nofak" class="chosen-select" data-placeholder="Pilih No Faktur..." >
                                <option value=""></option>
                                <?php
                                $QeryNofak = "SELECT no_faktur, SUBSTR(no_faktur, -5) FROM keluar WHERE no_faktur !=0 ORDER BY no_faktur ASC";
                                $ResNofak = $koneksi->query($QeryNofak);
                                while ($rowNofak = $ResNofak->fetch_array()) {
                                  $trimFaktur = (int) $rowNofak[1];
                                  echo "<option value='$rowNofak[0]'>$rowNofak[0] $trimFaktur</option>";
                                }
                                ?>
                                </select>
                              </div>
                          </div>

                          <div class="control-group no-nota">
                              <label class="control-label"><strong>No Retun</strong><p class="titik2">:</p></label>
                              <!-- <div class="controls">
                                <input class="input-small" name="awal" type="text"  value="17.000" readonly="true" />
                              </div> -->
                              <div class="controls">
                                  <select class="input-medium m-wrap" id="awal" name="awal" tabindex="1">
                                      <option value="R<?php echo date('y'); ?>.000">R<?php echo date('y'); ?>.000</option>
                                  </select>
                              </div>
                          </div>
                          <div class="control-group no-nota2">
                              <div class="controls">
                                <input class="input-small" id="fakturReturn" name="fakturReturn" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
                              </div>
                          </div>

                          <div class="control-group">
                              <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span12" id="keterangan" name="keterangan" type="text"  placeholder="Input Keterangan"/>
                              </div>
                          </div>
                          <div class="batas"></div>

                          <div class="control-group">
                            <table class="table" id="tabelBarangKeluar">
                              <thead>
                                <tr>
                                  <th style="width:50%">Nama Barang</th>
                                  <th style="width:20%">Lokasi Rak</th>
                                  <th style="width:20%">Jumlah</th>
                                </tr>
                              </thead>
                              <tbody>
                                    <tr>
                                      <td>
                                        <div >
                                          <select class="span9" id="id_brg" name="id_brg">
                                          	<option value="">Pilih Ukuran..</option>
                                          </select>
                                        </div>
                                      </td>
                                      <td>
                                        <div>
                                          <select id="id_rak" name="id_rak" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." >
                                          <option value=""></option>
                                          <?php
                                          $rak = "SELECT id_rak, rak FROM rak ORDER BY rak ASC";
                                          $rak1 = $koneksi->query($rak);
                                          while ($rak2 = $rak1->fetch_array()) {
                                            echo "<option value='$rak2[0]'>$rak2[1]</option>";
                                          }
                                          ?>
                                          </select>
                                        </div>
                                      </td>
                                      <td>
                                        <div >
                                          <input class="span12 " id="jml" name="jml" type="text"  placeholder="Jumlah Barang Keluar" onkeyup="validAngka(this)"/>
                                        </div>
                                      </td>
                                    </tr>
                              </tbody>
                            </table>
                          </div>

                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="simpanReturnBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL MASUK-->

              <!-- BEGIN MODAL HAPUS MASUK-->
              <div id="hapusModalKeluar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA KELUAR</h3>
                  </div>

                  <div class="modal-body">
                      <p id="pesanHapus" style="color: #dc5d3a"></p>
                  </div>                  

                  <div class="modal-footer">
                      <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      <button class="btn btn-danger" id="hapusKeluarBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-trash"></i> Hapus</button>
                  </div>
              </div>
              <!-- END MODAL HAPUS MASUK-->

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/return.js"></script> 
    <script src="assets/chosen/chosen1.jquery.min.js"></script>
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>