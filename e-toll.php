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

        echo "<div class='div-request div-hide'>trans_toll</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Transaksi E-Toll
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
                           Transaksi E-Toll
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
                        <a href="#myModal1" role="button" class="btn btn-primary tambah" id="addEtollBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                            <div class="actions">
                                <a href="#modalPosting" role="button" class="btn btn-warning" data-toggle="modal"><i class="fa fa-external-link-square"></i> Posting</a>
                            </div>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelTrans_Toll">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>No E-Toll</th>
                                <th>Rute</th>
                                <th>Totol Bayar</th>
                                <th>Keterangan</th>
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

            <!-- BEGIN MODAL MASUK-->
              <div id="myModal1" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT TRANSAKSI E-TOLL</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitEToll" action="action/etoll/simpanTransToll.php" method="POST" >
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

<!--                           <div class="control-group no-nota">
    <label class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
    <div class="controls">
      <input class="input-small" name="awal" type="text"  value="17.000" readonly="true" />
    </div>
    <div class="controls">
        <select class="input-medium m-wrap" name="awal" tabindex="1">
            <option value="<?php echo date('y'); ?>.000"><?php echo date('y'); ?>.000</option>
            <option value="<?php echo"MG".date('ym'); ?>-0"><?php echo"MG".date('ym'); ?>-0</option>
        </select>
    </div>
</div>
<div class="control-group no-nota2">
    <div class="controls">
      <input class="input-small" id="noFaktur" name="noFaktur" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
    </div>
</div> -->

                          <div class="control-group">
                              <label class="control-label"><strong>Rute</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span6" id="rute" name="rute" type="text"  placeholder="Input Rute Awal Toll" onkeydown="hurufBesar(this)" />
                                <input class="span6" id="ruteAkhir" name="ruteAkhir" type="text"  placeholder="Input Rute Akhir Toll" onkeydown="hurufBesar(this)" />
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label"><strong>Total Bayar</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span12" id="bayar" name="bayar" type="text"  placeholder="Input Total Bayar Toll" onkeyup="harusAngka(this)" />
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span12" id="keterangan" name="keterangan" type="text"  placeholder="Input Keterangan" onkeydown="hurufBesar(this)"/>
                              </div>
                          </div>
                          <div class="batas"></div>

                          <!-- <div class="control-group">
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
                                          <select id="id_brg" name="id_brg" class="chosen-select" data-placeholder="Pilih Type Ban..." >
                                          <option value=""></option>
                                          <?php
                                          $brg = "SELECT id_brg, brg FROM barang ORDER BY brg ASC";
                                          $brg1 = $koneksi->query($brg);
                                          while ($brg2 = $brg1->fetch_array()) {
                                            echo "<option value='$brg2[0]'>$brg2[1]</option>";
                                          }
                                          ?>
                                          </select>
                                        </div>
                                      </td>
                                      <td>
                                        <div >
                                          <select id="rak" name="id_rak" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." >
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
                                          <input class="span12 " id="jumlah" name="jml" type="text"  placeholder="Jumlah Barang Keluar" onkeyup="validAngka(this)"/>
                                        </div>
                                      </td>
                                    </tr>
                              </tbody>
                            </table>
                          </div> -->

                          <div class="control-group">
                            <div id="pesan"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="simpanTransTollBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL MASUK-->

                <!-- BEGIN MODAL EDIT TRANSAKSI E-TOLL-->
              <div id="editModalTransToll" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT TRANSAKSI E-TOLL</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="editSubmitTrans" action="action/etoll/editTransToll.php" method="POST" >
                      <div class="modal-body modal-full tinggi">
                          <div class="control-group">
                              <label class="control-label"><strong>No E-Toll</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <!-- <select id="editNoEtoll" name="editNoEtoll" data-placeholder="Pilih No E-Toll..." >
                                <option value=""></option>
                                <?php
                                $noToll = "SELECT * FROM tblEToll ORDER BY no_toll ASC";
                                $resnoToll = $koneksi->query($noToll);
                                while ($rowToll = $resnoToll->fetch_array()) {
                                  echo "<option value='$rowToll[0]'>$rowToll[1]</option>";
                                }
                                ?>
                                </select> -->
                                <input class="span12" id="editNoEtoll" name="editNoEtoll" type="text" readonly="true" />
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label"><strong>Rute</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span6" id="editRute" name="editRute" type="text"  placeholder="Input Rute Awal Toll" onkeydown="hurufBesar(this)" />
                                <input class="span6" id="editRuteAkhir" name="editRuteAkhir" type="text"  placeholder="Input Rute Akhir Toll" onkeydown="hurufBesar(this)" />
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label"><strong>Total Bayar</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span12" id="editBayar" name="editBayar" type="text"  placeholder="Input Total Bayar Toll" onkeyup="harusAngka(this)" />
                              </div>
                          </div>
                          <div class="control-group">
                              <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <input class="span12" id="editKeterangan" name="editKeterangan" type="text"  placeholder="Input Keterangan" onkeydown="hurufBesar(this)"/>
                              </div>
                          </div>
                          <div class="batas"></div>

                          <div class="control-group">
                            <div id="pesan-edit"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="editTransTollBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL EDIT TRANSAKSI E-TOLL-->

                <!-- BEGIN MODAL POSTING TRANSAKSI E-TOLL-->
              <div id="modalPosting" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM POSTING E-TOLL</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="postingSubmitTrans" action="action/etoll/editTransToll.php" method="POST" >
                      <div class="modal-body modal-full tinggi">
                          <div class="control-group">
                              <table class="table" id="tabelDataPosting">
                                <thead>
                                  <tr>
                                    <th>No E-Toll</th>
                                    <th>Pemegang Kartu</th>
                                    <th>No Polisi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                      <div>
                                        <select id="postNoEtoll" name="postNoEtoll" class="chosen-select" data-placeholder="Pilih No E-Toll..." >
                                        <option value=""></option>
                                        <?php
                                        $noToll = "SELECT * FROM tblEToll ORDER BY no_toll ASC";
                                        $resnoToll = $koneksi->query($noToll);
                                        while ($rowToll = $resnoToll->fetch_array()) {
                                          echo "<option value='$rowToll[0]'>$rowToll[1]</option>";
                                        }
                                        ?>
                                        </select>
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>

                              
                          </div>
                          <div class="modal-loading" style="width:50px; margin:auto;">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                          </div>
                          <div class="control-group">
                            <table class="table table-striped table-bordered" id="tabelPosting">
                              <thead>
                                <tr>
                                  <th >No</th>
                                  <th >Saldo Awal</th>
                                  <!-- <th >Top UP</th>
                                  <th >Asal Gerbang</th>
                                  <th >Keluar Gerbang</th>
                                  <th >Nominal</th> -->
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                          <div class="control-group">
                            <div id="pesan-edit"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="postingTransTollBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL POSTING TRANSAKSI E-TOLL-->

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