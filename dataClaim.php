<?php require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';
      require_once 'function/fungsi_rupiah.php';

      $tahun          = date("Y");
      $tahun1          = date("y");
      $bulan          = date("m");

      $cek_saldo = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
      if ($cek_saldo->num_rows >=1 ) {

      require_once 'include/header.php';
      require_once 'include/menu.php';

      //query barang
      
      //query rak

        

      if ($_GET['p'] == "print") {
        $id_c = $_GET['id'];
        $queryCekClaim = "SELECT * FROM claim WHERE id_claim=$id_c";
        $resultCekClaim = $koneksi->query($queryCekClaim);
        $rowCek = $resultCekClaim->fetch_array();
        $toko      = $rowCek['toko'];
        $keputusan = $rowCek['keputusan'];

        echo "<div class='div-request div-hide'>Nota</div>";
        


      ?> 

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Cetak Nota Penggantian / Tolakan
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
                           Cetak Nota Penggantian
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
                        <!-- <a href="#myModal1" role="button" class="btn btn-primary tambah" id="addBarangKlrBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a> -->
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                      <?php
                        $no = 1;
                        $queryClaim = "SELECT id_claim, pengaduan, no_claim, brg, pattern, dot, tahun, nominal FROM claim JOIN barang USING(id_brg) WHERE toko='$toko' AND keputusan='$keputusan' AND nota='N' LIMIT 0,10";
                        $resultClaim = $koneksi->query($queryClaim);
                        $cekRow = $resultClaim->num_rows;
                        // echo "total Row ".$cekRow;
                        if ($resultClaim->num_rows == 0) {
                          echo '<div class="hiddenBtn div-hide">hiddenBtn</div>';
                        }

                      ?>
                      <form class="cmxform form-horizontal" action="action/claim/simpanNotaPenggantian.php" id="submitNota" method="POST">
                      <!-- Hitung id Claim  -->
                        <input id="totalID" name="totalID" type="hidden" value="<?php echo $cekRow; ?>" />
                        
                          <table class="table">
                            <tr>
                              <th>Nama Toko</th>
                              <!-- <th>No Register Claim</th> -->
                              <th></th>
                              <th>Keputusan</th>
                            </tr>
                            <tbody>
                              <tr>

                                <td>
                                  <input id="toko" name="toko" type="text" class="input-xlarge" value="<?php echo $toko; ?>" readonly="true" />
                                </td>

                                <!-- <td>
                                  <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("d-m-Y") ?>" data-date-format="dd-mm-yyyy">
                                    <input id="tgl" name="tgl" class="input-small" size="16" type="text" value="<?php echo date("d-m-Y") ?>" readonly="">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                  </div>
                                    <input id="noReg" name="noReg" type="text" class="input-small" value="" minlength="3" maxlength="3" placeholder="3 Digit Terakhir" />
                                    <p class="help-block catatan">*KTA0717 3 Digit Terakhir</p>

                                </td> -->

                                <td>
                                  <input id="keputusan" name="keputusan" type="hidden" class="input-small" value="<?php echo $keputusan; ?>" />
                                </td>

                                <td>
                                  <?php
                                  if ($keputusan == 'Ganti') {
                                    echo '<span class="label label-success">Ganti</span>';
                                  }elseif ($keputusan == 'Tolak'){
                                    echo '<span class="label label-warning">Tolak</span>';
                                  }else{
                                    echo '<span class="label label-info">Ganti SC</span>';
                                  }
                                  ?>
                                </td>

                              </tr>
                            </tbody>
                          </table>
  
                          <table class="table table-striped table-bordered" id="">
                              <thead>
                              <!-- <tr>
                                <th colspan="5"></th>
                                <th colspan="5"><center>Kerusakan</center></th>
                                <th></th>
                              </tr> -->

                              <tr>
                                  <th >No</th>
                                  <th >Type</th>
                                  <th >No Seri</th>
                                  <!-- <th >No Claim</th> -->
                                  <th >No CM</th>
                                  <th >Tgl CM</th>
                                  <th >Nominal</th>
                                  <th >Keterangan</th>

                              </tr>
                              </thead>
                              <tbody>
                              
                              <?php
                                /*<td>'.$row['no_claim'].'</td>*/
                                $total = "";
                                while ($row = $resultClaim->fetch_array()) {
                                $noSeri = $row['pattern'].'-'.$row['dot'].'-'.$row['tahun'];
                                echo '
                                <tr>
                                  <td>'.$no.'</td>
                                  <td>'.$row['brg'].'</td>
                                  <td>'.$noSeri.'
                                  <input id="noSeri'.$no.'" name="noSeri'.$no.'" type="hidden" value="'.$noSeri.'" />
                                  </td>

                                  <input id="id_claim'.$no.'" name="id_claim'.$no.'" type="hidden" value="'.$row['id_claim'].'" />

                                  <td>
                                  <input id="noCM'.$no.'" name="noCM'.$no.'" type="text" minlength="9" maxlength="9" class="input-small" placeholder="Credit Memo" required="true" onkeyup="validAngka(this)"/>
                                      
                                  </td>
                                  <td>
                                  <div class="input-append date datepicker" id="dp3" data-date="'.date("d-m-Y").'" data-date-format="dd-mm-yyyy">
                                    <input id="tglCM'.$no.'" name="tglCM'.$no.'" class="input-small" size="16" type="text" value="'.date("d-m-Y").'" readonly="">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                  </div>
                                  </td>

                                  <td style="text-align: right;">'.format_rupiah($row['nominal']).'</td>

                                  <td>
                                    <input id="ket'.$no.'" name="ket'.$no.'" type="text" class="input-small" placeholder="Keterangan" required="true" onkeydown="HurufBesar(this)"/>
                                  </td>
                                </tr>';
                                $total +=$row['nominal'];
                                $no++;
                                 } ?>
                                
                                  <input id="total" name="total" type="hidden" class="input-small" value="<?php echo $total; ?>" />

                                <tr>
                                  <td colspan="5" style="text-align: center; font-weight: bold;">Total</td>
                                  <td style="text-align: right; font-weight: bold;"><?php echo format_rupiah($total); ?></td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td colspan="7" style="text-align: center;" class="print">
                                    <button class="btn btn-primary" id="simpanNotaBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                                  </td>
                                </tr>
                              </tbody>
                          </table>
                      </form>
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

      <?php
      }else{
        echo "<div class='div-request div-hide'>dataClaim</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                    Data Claim
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
                           Claim
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
                        <!-- <a href="#myModal1" role="button" class="btn btn-primary tambah" id="addBarangKlrBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a> -->
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelClaim">
                            <thead>
                            <!-- <tr>
                              <th colspan="5"></th>
                              <th colspan="5"><center>Kerusakan</center></th>
                              <th></th>
                            </tr> -->
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Toko</th>
                                <th>Sales</th>
                                <th>Ukuran</th>
                                <th>Kerusakan</th>
                                <th>Keputusan</th>
                                <th>Nominal</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                        
                            </tbody>
                        </table>
                    </div>

                  <!-- BEGIN MODAL EDIT-->
                    <div id="modalEdit" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel1" class="center"><i class="fa fa-edit"></i> Edit Claim</h3>
                        </div>
                        <form class="cmxform form-horizontal" id="submitEditClaim" action="action/claim/simpanEditClaim.php" method="POST" >
                            <div class="modal-body modal-full">
                              <div class="control-group">
                                  <label class="control-label">Toko</label>
                                  <div class="controls">
                                    <input class="span7" id="editToko" name="editToko" type="text" readonly="true">
                                  </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label">Ukuran</label>
                                  <div class="controls">
                                    <input class="span10" id="editUkuran" name="editUkuran" type="text" readonly="true">
                                  </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label">Keputusan</label>
                                  <div class="controls">
                                    <select id="editKeputusan" name="editKeputusan" class="span7" tabindex="1">
                                      <option value="">Pilih Keputusan</option>
                                      <option value="Ganti">Ganti</option>
                                      <option value="Ganti SC">Ganti SC</option>
                                      <option value="Tolak">Tolak</option>
                                    </select>
                                  </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label">Nominal</label>
                                  <div class="controls">
                                    <input class="span7" id="editNominal" name="editNominal" type="text" placeholder="Input Besar Nominal" onkeyup="validAngka(this)">
                                    <p class="help-block catatan">*Jika Tolak Isi 0</p>
                                  </div>
                              </div>
                                <div class="control-group">
                                  <div id="edit-pesan"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" id="simpanEditClaimBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                            </div>
                        </form>
                    </div>
                  <!-- END MODAL EDIT-->

                  <!-- BEGIN MODAL HAPUS MASUK-->
                    <div id="hapusModalClaim" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA CLAIM</h3>
                        </div>
                        <div class="modal-body">
                            <p id="pesanHapus" style="color: #dc5d3a"></p>

                        </div>
                        <div class="modal-footer div-hide">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                            <button class="btn btn-danger" id="hapusClaimBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-trash"></i> Hapus</button>
                        </div>
                    </div>
                  <!-- END MODAL HAPUS MASUK-->  

                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>
            <!-- END ADVANCED TABLE widget-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->

    <?php 
      }//end page
    require_once 'include/footer.php'; 
    ?>

    <script src="jsAction/dataClaim.js"></script> 
    <?php 
    }else{
      if ($_SESSION['level'] == 'administrator') {
        include_once 'saldo.php';
        
      }else{
        
      }
    }//end cek saldo

    ?>