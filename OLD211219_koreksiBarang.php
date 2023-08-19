<?php require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';

      $tahun          = date("Y");
      $bulan          = date("m");

      require_once 'include/header.php';
      require_once 'include/menu.php';

      $p = isset($_GET['p']) ? $_GET['p'] : false;

      if ($p == 'plus')
      {

        // $hed = "Koreksi Plus Barang";
        
      }
      elseif ($p == 'minus')
      {

        $hed = "Koreksi Minus Barang";

      }
      else
      {
        $hed = "Halaman Tidak Ada";
      }


?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     <?php echo $hed; ?> 
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Master Barang</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           <?php echo $hed; ?>
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
            <?php

              if ($p == 'plus')
              {
                echo "<div class='div-request div-hide'>plus</div>";

                //$bln = isset($_GET['bln']) ? $_GET['bln'] : $bulan;

            ?>
                        <!-- <div class="hidden">
                          <input type="text" id="nilaibulanfak"  value="<?php echo $bln; ?>">
                        </div> -->
                <div class="widget-title">
                    <a href="#myModalRetur" role="button" class="btn btn-primary tambah" id="addReturnBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                    <a href="#alternatfRetur" role="button" class="btn btn-warning tambah" id="addReturnBtnModal" data-toggle="modal"> <i class=" icon-plus"></i> Retur Alternatif</a>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                        
                    <div class="actions">
                        <select name="bulanfaktur" id="bulanfaktur">
                            <option>Pilih Bulan....</option>
                            <option value="01">Januari</option>
                            <option value="02">Febuari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="widget-body">
                    <table class="table table-striped table-bordered" id="tabelRetur">
                        <thead>
                        <tr>
                            <th  width="10%">Lokasi Rak</th>
                            <th>Nama Barang</th>
                            <th class="hidden-phone" width="15%">No Faktur</th>
                            <th class="hidden-phone" width="15%">No Retur</th>
                            <th class="hidden-phone"  width="10%">Ket</th>
                            <th class="hidden-phone"  width="10%">Tanggal</th>
                            <th class="hidden-phone" width="6%">Jam</th>
                            <th>Total</th>
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

                <!-- BEGIN MODAL RETUR-->
                  <div id="myModalRetur" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT RETUR BARANG</h3>
                      </div>
                      <form class="cmxform form-horizontal" id="submitRetur" action="action/return/simpanReturn.php" method="POST" >
                          <div class="modal-body modal-full tinggi">
                              <div class="control-group">
                                  <label class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <select id="NofakAwal" name="NofakAwal" class="chosen-select" data-placeholder="Pilih No Faktur..." >
                                    <option value=""></option>
                                    <?php
                                    $QeryNofak = "SELECT no_faktur, SUBSTR(no_faktur, -8) FROM keluar WHERE no_faktur !=0 AND MONTH(tgl) = $bln  AND YEAR(tgl) = $tahun ORDER BY no_faktur ASC";
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
                                      <input class="input-large" id="fakturRetur" name="fakturRetur" type="text"  placeholder="Input No Retur" onkeyup="validAngka(this)" maxlength="6" />
                                  </div>
                              </div>
                              <!-- <div class="control-group no-nota2">
                                  <div class="controls">
                                    <input class="input-small" id="fakturRetur" name="fakturRetur" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
                                  </div>
                              </div> -->

                              <div class="control-group">
                                  <label class="control-label"><strong>Tanggal</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                                      <input id="tglRtr" name="tglRtr" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                                      <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                  </div>
                              </div>

                              <div class="control-group no-nota">
                                  <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <input class="input-large" id="keterangan" name="keterangan" type="text"  placeholder="Input Keterangan"/>
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
                                              <select class="span9" id="id_det_klr" name="id_det_klr">
                                                <option value="">Pilih Ukuran..</option>
                                              </select>
                                            </div>
                                          </td>
                                          <td>
                                            <div>
                                              <select id="id_rakRtr" name="id_rakRtr" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." >
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
                                              <input class="span12 " id="jmlRtr" name="jmlRtr" type="text"  placeholder="Jumlah Barang Keluar" onkeyup="validAngka(this)"/>
                                            </div>
                                          </td>
                                        </tr>
                                  </tbody>
                                </table>
                              </div>

                              <div class="control-group">
                                <div id="pesanRtr"></div>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button class="btn btn-primary" id="simpanReturnBtr" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                              <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                          </div>
                      </form>
                  </div>
                    <!-- END MODAL RETUR-->

                    <!-- BEGIN MODAL ALTERNATIF RETUR-->
                  <div id="alternatfRetur" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT RETUR ALTERNATIF</h3>
                      </div>
                      <form class="cmxform form-horizontal" id="submitAlternatfRetur" action="action/return/simpanAtlerRetur.php" method="POST" >
                          <div class="modal-body modal-full tinggi">
                              <div class="control-group">
                                  <label class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                      <input class="input-small" type="text" id="alterFaktur" name="alterFaktur" value="LAMA" readonly="true">
                                      <input class="input-large" type="text" id="alterFaktur1" name="alterFaktur1" placeholder="Lima Digit Terakhir" minlength="5" maxlength="5">
                                  </div>
                              </div>

                              <div class="control-group no-nota">
                                  <label class="control-label"><strong>No Retun</strong><p class="titik2">:</p></label>
                                  <!-- <div class="controls">
                                    <input class="input-small" name="awal" type="text"  value="17.000" readonly="true" />
                                  </div> -->
                                  <div class="controls">
                                      <input class="input-large" id="alterRetur" name="alterRetur" type="text"  placeholder="Input No Retur" onkeyup="validAngka(this)" maxlength="6" />
                                  </div>
                              </div>
                              <!-- <div class="control-group no-nota2">
                                  <div class="controls">
                                    <input class="input-small" id="fakturRetur" name="fakturRetur" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
                                  </div>
                              </div> -->

                              <div class="control-group">
                                  <label class="control-label"><strong>Tanggal</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                                      <input id="alterTglRtr" name="alterTglRtr" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                                      <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                  </div>
                              </div>

                              <div class="control-group no-nota">
                                  <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <input class="input-large" id="alterKet" name="alterKet" type="text"  placeholder="Input Keterangan"/>
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
                                              <select id="alterBrg" name="alterBrg" class="chosen-select" data-placeholder="Pilih Type Ban..." >
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
                                            <div>
                                              <select id="alterId_rak" name="alterId_rak" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." >
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
                                              <input class="span12 " id="alterJmlRtr" name="alterJmlRtr" type="text"  placeholder="Jumlah Barang Keluar" onkeyup="validAngka(this)"/>
                                            </div>
                                          </td>
                                        </tr>
                                  </tbody>
                                </table><table class="table" id="tabelBarangRtrAlter">
                                  <thead>
                                    <tr>
                                      <th style="width:50%">Nama Toko</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                        <tr>
                                          <td>
                                            <div>
                                              <select id="id_toko" name="id_toko" class="chosen-select input-small" data-placeholder="Pilih Nama Toko..." >
                                              <option value=""></option>
                                              <?php
                                              $toko = "SELECT id_toko, toko FROM toko ORDER BY toko ASC";
                                              $toko1 = $koneksi->query($toko);
                                              while ($toko2 = $toko1->fetch_array()) {
                                                echo "<option value='$toko2[1]'>$toko2[1]</option>";
                                              }
                                              ?>
                                              </select>
                                            </div>
                                          </td>
                                        </tr>
                                  </tbody>
                                </table>
                              </div>
                              <div class="control-group">
                                <div id="pesanRtrAlter"></div>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button class="btn btn-primary" id="simpanReturnBtr" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                              <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                          </div>
                      </form>
                  </div>
                    <!-- END MODAL ALTERNATIF RETUR-->

                   <!-- BEGIN MODAL EDIT RETUR-->
                  <div id="editModalRetur" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM EDIT RETUR BARANG</h3>
                      </div>
                      <form class="cmxform form-horizontal" id="submitEditRetur" action="action/return/editRetur.php" method="POST" >
                          <div class="modal-body modal-full tinggi">
                              <div class="control-group">
                                  <label class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <input class="input-xlarge" type="text" name="editFaktur" id="editFaktur" readonly="true" />
                                  </div>
                              </div>

                              <div class="control-group no-nota">
                                  <label class="control-label"><strong>Tgl & No Retur</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                      <input id="editTgl" name="editTgl" class="input-small" type="text" readonly="true" />
                                  </div>
                              </div>

                              <div class="control-group no-nota2">
                                  <div class="controls">
                                      <input type="text" class="input-tengah" name="editRetur" id="editRetur"  readonly="true" />
                                  </div>
                              </div>

                              <div class="control-group">
                                  <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                    <input class="span12" id="editKet" name="editKet" type="text"  placeholder="Input Keterangan"/>
                                  </div>
                              </div>

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
                                            <div class="control-group">
                                              <input class="input-xxlarge" type="text" name="editBrg" id="editBrg" readonly="true"/>
                                            </div>
                                          </td>
                                          <td>
                                            <div class="control-group">
                                              <input class="input-large" type="text" name="editRak" id="editRak" readonly="true" />
                                            </div>
                                          </td>
                                          <td>
                                            <div class="control-group">
                                              <input class="span12 " id="editJml" name="editJml" type="text"  readonly="true" />
                                            </div>
                                          </td>
                                        </tr>
                                  </tbody>
                                </table>
                              </div>

                              <div class="control-group">
                                <div id="pesanEditRetur"></div>
                              </div>
                          </div>
                          <div class="modal-footer hidden">
                              <input class="span12" id="editId" name="editId" type="hidden"  placeholder="Input Keterangan"/>
                              <button class="btn btn-primary" id="editReturBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                              <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                          </div>
                      </form>
                  </div>
                  <!-- END MODAL EDIT RETUR-->

            <?php

              }
              elseif($p == 'minus')
              {
                echo "<div class='div-request div-hide'>minus</div>";
                ?>

                    <div class="widget-title">
                        <a href="#myModalKoreksiMinus" role="button" class="btn btn-primary tambah" id="addMinusBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                          <span class="tools">
                              <a href="javascript:;" class="icon-chevron-down"></a>
                              <!-- <a href="javascript:;" class="icon-remove"></a> -->
                          </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelMinus">
                            <thead>
                            <tr>
                                <th width="10%">Lokasi Rak</th>
                                <th>Nama Barang</th>
                                <th class="hidden-phone" width="15%">No Koreksi</th>
                                <th class="hidden-phone"  width="10%">Ket</th>
                                <th class="hidden-phone"  width="10%">Tanggal</th>
                                <th class="hidden-phone" width="6%">Jam</th>
                                <th>Jumlah</th>
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

                      <!-- BEGIN MODAL KOREKSI MINUS BARANG-->
                    <div id="myModalKoreksiMinus" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT KOREKSI MINUS BARANG</h3>
                        </div>
                        <form class="cmxform form-horizontal" id="submitMinus" action="action/barang/simpanMinus.php" method="POST" >
                            <div class="modal-body modal-full tinggi">
                                <!-- <div class="control-group">
                                    <label class="control-label"><strong>Asal Lokasi Rak</strong><p class="titik2">:</p></label>
                                    <div class="controls">
                                      <select id="asalMinus" name="asalMinus" class="chosen-select" data-placeholder="Pilih Asal Lokasi Rak..." >
                                      <option value=""></option>
                                      <?php
                                      $toko = "SELECT id_rak, rak FROM rak ORDER BY rak ASC";
                                      $toko1 = $koneksi->query($toko);
                                      while ($toko2 = $toko1->fetch_array()) {
                                        echo "<option value='$toko2[0]'>$toko2[1]</option>";
                                      }
                                      ?>
                                      </select>
                                    </div>
                                </div> -->

                                <!-- <div class="control-group no-nota">
                                    <label class="control-label"><strong>No Mutasi</strong><p class="titik2">:</p></label>
                                    <div class="controls">
                                      <input class="input-small" name="awal" type="text"  value="17.000" readonly="true" />
                                    </div>
                                    <div class="controls">
                                        <input type="text" class="input-small m-wrap" id="NoMTSRak" name="NoMTSRak" value="<?php echo"MG".date('ym'); ?>-0" readonly="true">
                                    </div>
                                </div> -->
                                <!-- <div class="control-group no-nota2">
                                    <div class="controls">
                                      <input class="input-tengah" id="NoMTSRakAkhr" name="NoMTSRakAkhr" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" minlength="5" maxlength="5" />
                                    </div>
                                </div> -->

                                <div class="control-group">
                                    <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                                    <div class="controls">
                                        <input class="input-xxlarge" id="ketMinus" name="ketMinus" type="text"  placeholder="Input Keterangan"/>
                                    </div>
                                </div>

                                <div class="control-group no-nota" style="margin-left: 600px;">
                                    <!-- <label class="control-label"><strong>Tanggal</strong><p class="titik2">:</p></label> -->
                                    <div class="controls">
                                        <input id="tglMinus" name="tglMinus" class="input-small" size="5" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
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
                                                <select id="id_brgMinus" name="id_brgMinus" class="chosen-select" data-placeholder="Pilih Type Ban..." >
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
                                              <div>
                                                <select id="id_rakMinus" name="id_rakMinus" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." >
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
                                                <input class="span12 " id="jmlMinus" name="jmlMinus" type="text"  placeholder="Jumlah Koreksi" maxlength="5" onkeyup="validAngka(this)"/>
                                              </div>
                                            </td>
                                          </tr>
                                    </tbody>
                                  </table>
                                </div>

                                <div class="control-group">
                                  <div id="pesanMinus"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" id="simpanMinusBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                            </div>
                        </form>
                    </div>
                      <!-- END MODAL KOREKSI MINUS BARANG-->

                      <!-- BEGIN MODAL EDIT KOREKSI MINUS BARANG-->
                    <div id="editModalMinus" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM EDIT KOREKSI MINUS BARANG</h3>
                        </div>
                        <form class="cmxform form-horizontal" id="editMinusForm" action="action/barang/editMinus.php" method="POST" >
                            <div class="modal-body modal-full tinggi">

                              <div class="control-group">
                                  <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                                  <div class="controls">
                                      <input class="input-xxlarge" id="editketMinus" name="editketMinus" type="text"  placeholder="Input Keterangan"/>
                                  </div>
                              </div>

                              <div class="control-group no-nota" style="margin-left: 600px;">
                                  <div class="controls">
                                      <input id="edittglMinus" name="edittglMinus" class="input-small" size="5" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                                  </div>
                              </div>

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
                                            <div class="control-group">
                                              <input class="input-xxlarge" type="text" name="editBrgMinus" id="editBrgMinus" readonly="true"/>
                                            </div>
                                          </td>
                                          <td>
                                            <div class="control-group">
                                              <input class="input-large" type="text" name="editRakMinus" id="editRakMinus" readonly="true" />
                                            </div>
                                          </td>
                                          <td>
                                            <div class="control-group">
                                              <input class="span12 " id="editJmlMinus" name="editJmlMinus" type="text" placeholder="Jumlah Koreksi" maxlength="5" onkeyup="validAngka(this)" />
                                            </div>
                                          </td>
                                        </tr>
                                  </tbody>
                                </table>
                              </div>

                              <div class="control-group">
                                <div id="pesanEditMinus"></div>
                              </div>
                          </div>
                          <div class="modal-footer hidden">
                              <input class="span12" id="editIdDetKlr" name="editIdDetKlr" type="hidden""/>
                              <button class="btn btn-primary" id="editMinusBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                              <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                          </div>
                        </form>
                    </div>
                      <!-- END MODAL EDIT KOREKSI MINUS BARANG-->

                <?php

              }
              else
              {
                echo "Halaman Yang Anda Minta Tidak Ada";
              }

            ?>
                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

              <!-- BEGIN MODAL HAPUS-->
              <div id="hapusModalKoreksi" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA</h3>
                  </div>
                  <div class="modal-body">
                      <p id="pesanHapus" style="color: #dc5d3a"></p>

                  </div>
                  <div class="modal-footer hidden">
                      <input class="span12" id="status_klr" name="status_klr" type="hidden"/>
                      <input class="span12" id="hapusId_klr" name="hapusId_klr" type="hidden"/>
                      <input class="span12" id="hapusId" name="hapusId" type="hidden""/>
                      <input class="span12" id="hapusJml_klr" name="hapusJml_klr" type="hidden"/>
                      <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      <button class="btn btn-danger" id="hapusKoreksiBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-trash"></i> Hapus</button>
                  </div>
              </div>
              <!-- END MODAL HAPUS-->

            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

    <script src="jsAction/koreksiBarang.js"></script> 

    <script src="assets/chosen/chosen1.jquery.min.js"></script>