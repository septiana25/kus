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

         $hed = "Koreksi Plus Barang";
        
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

              <div class="widget-title">
              <?php
                if ($_SESSION['aksi'] == "1") {
                  echo '<a href="#myModalKoreksiPlus" role="button" class="btn btn-primary tambah" id="addPlusBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
                }
              ?>
                    <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <!-- <a href="javascript:;" class="icon-remove"></a> -->
                    </span>
              </div>
              <div class="widget-body">
                  <table class="table table-striped table-bordered" id="tabelPlus">
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

                <!-- BEGIN MODAL KOREKSI PLUS BARANG-->
              <div id="myModalKoreksiPlus" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT KOREKSI PLUS BARANG</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitPlus" action="action/barang/simpanPlus.php" method="POST" >
                      <div class="modal-body modal-full tinggi">

                          <div class="control-group">
                              <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                  <input class="input-xxlarge" id="ketPlus" name="ketPlus" type="text"  placeholder="Input Keterangan"/>
                              </div>
                          </div>

                          <div class="control-group no-nota" style="margin-left: 600px;">
                              <!-- <label class="control-label"><strong>Tanggal</strong><p class="titik2">:</p></label> -->
                              <div class="controls">
                                  <input id="tglPlus" name="tglPlus" class="input-small" size="5" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true" required="true">
                              </div>
                          </div>

                          <div class="batas"></div>
                          

                          <div class="control-group">
                            <table class="table" id="tabelBarangMASUK">
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
                                          <select id="id_brgPlus" name="id_brgPlus" class="chosen-select" data-placeholder="Pilih Type Ban..." required="true">
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
                                          <select id="id_rakPlus" name="id_rakPlus" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." required="true">
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
                                          <input class="span12 " id="jmlPlus" name="jmlPlus" type="text"  placeholder="Jumlah Koreksi" maxlength="5" required="true" onkeyup="validAngka(this)"/>
                                        </div>
                                      </td>
                                    </tr>
                              </tbody>
                            </table>
                          </div>

                          <div class="control-group">
                            <div id="pesanPlus"></div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary" id="simpanPlusBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                      </div>
                  </form>
              </div>
                <!-- END MODAL KOREKSI PLUS BARANG-->

                <!-- BEGIN MODAL EDIT KOREKSI PLUS BARANG-->
              <div id="editModalPlus" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM EDIT KOREKSI PLUS BARANG</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="editPlusForm" action="action/barang/editPlus.php" method="POST" >
                      <div class="modal-body modal-full tinggi">

                        <div class="control-group">
                            <label class="control-label"><strong>Keterangan</strong><p class="titik2">:</p></label>
                            <div class="controls">
                                <input class="input-xxlarge" id="editketPlus" name="editketPlus" type="text"  placeholder="Input Keterangan"/>
                            </div>
                        </div>

                        <div class="control-group no-nota" style="margin-left: 600px;">
                            <div class="controls">
                                <input id="edittglPlus" name="edittglPlus" class="input-small" size="5" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true" required="true">
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
                                        <input class="input-xxlarge" type="text" name="editBrgPlus" id="editBrgPlus" readonly="true" required="true" />
                                      </div>
                                    </td>
                                    <td>
                                      <div class="control-group">
                                        <input class="input-large" type="text" name="editRakPlus" id="editRakPlus" readonly="true" required="true" />
                                      </div>
                                    </td>
                                    <td>
                                      <div class="control-group">
                                        <input class="span12 " id="editJmlPlus" name="editJmlPlus" type="text" placeholder="Jumlah Koreksi" maxlength="5" required="true" onkeyup="validAngka(this)" />
                                      </div>
                                    </td>
                                  </tr>
                            </tbody>
                          </table>
                        </div>

                        <div class="control-group">
                          <div id="pesanEditPlus"></div>
                        </div>
                    </div>
                    <div class="modal-footer hidden">
                        <input class="span12" id="editIdDetMsk" name="editIdDetMsk" type="hidden" required="true"/>
                        <button class="btn btn-primary" id="editPlusBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                    </div>
                  </form>
              </div>
                <!-- END MODAL EDIT KOREKSI MINUS BARANG-->

              <?php
              }
              elseif($p == 'minus')
              {
                echo "<div class='div-request div-hide'>minus</div>";
                ?>

                    <div class="widget-title">
                    <?php
                        if ($_SESSION['aksi'] == "1") {
                          echo '<a href="#myModalKoreksiMinus" role="button" class="btn btn-primary tambah" id="addMinusBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
                        }
                    ?>
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
                                        <input id="tglMinus" name="tglMinus" class="input-small" size="5" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true" required="true">
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
                                                <select id="id_brgMinus" name="id_brgMinus" class="chosen-select" data-placeholder="Pilih Type Ban..." required="true">
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
                                                <select id="id_rakMinus" name="id_rakMinus" class="chosen-select" data-placeholder="Pilih Lokasi Rak..." required="true" >
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
                                                <input class="span12 " id="jmlMinus" name="jmlMinus" type="text"  placeholder="Jumlah Koreksi" maxlength="5" required="true" onkeyup="validAngka(this)"/>
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
                                      <input class="input-xxlarge" id="editketMinus" name="editketMinus" type="text"  placeholder="Input Keterangan" required="true"/>
                                  </div>
                              </div>

                              <div class="control-group no-nota" style="margin-left: 600px;">
                                  <div class="controls">
                                      <input id="edittglMinus" name="edittglMinus" class="input-small" size="5" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true" required="true">
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
                                              <input class="input-xxlarge" type="text" name="editBrgMinus" id="editBrgMinus" readonly="true" required="true"/>
                                            </div>
                                          </td>
                                          <td>
                                            <div class="control-group">
                                              <input class="input-large" type="text" name="editRakMinus" id="editRakMinus" readonly="true" required="true"/>
                                            </div>
                                          </td>
                                          <td>
                                            <div class="control-group">
                                              <input class="span12 " id="editJmlMinus" name="editJmlMinus" type="text" placeholder="Jumlah Koreksi" maxlength="5" required="true" onkeyup="validAngka(this)" />
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
                              <input class="span12" id="editIdDetKlr" name="editIdDetKlr" type="hidden" required="true"/>
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