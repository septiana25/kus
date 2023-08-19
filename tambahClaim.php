<?php
      require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';
      $tahun          = date("Y");
      $bulan          = date("m");
      //$bulan1          = 7;
$cek_saldo = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
if ($cek_saldo->num_rows >=1 ) {
    require_once 'include/header.php';
    require_once 'include/menu.php';
      echo "<div class='div-request div-hide'>tambahClaim</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                    Tambah Claim
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           Claim
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                          Tambah Claim
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
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                      <!-- BEGIN FORM-->
                      <form action="action/claim/simpanClaim.php" class="form-horizontal" id="submitClaim" method="POST">
                      <table class="table">

                          <tr>
                            <th >Tanggal</th>
                            <th >No Pengaduan</th>
                            <th >No Portal</th>
                            <th >Daerah</th>
                            <th >Tempat Pemeriksaan</th>
                          </tr>
                        <?php
                        $getNo = "SELECT pengaduan FROM claim ORDER BY id_claim DESC LIMIT 0,1";
                        $resNo = $koneksi->query($getNo);
                        $rowNO =$resNo->fetch_assoc();
                        $no = substr($rowNO['pengaduan'], 4);
                        $noNext = $no+1;
                        $thn = substr(date('Y'), 2);
                        $pjnNo = strlen($noNext);
                        if (empty($no)) {
                          $NoAwal ='00';
                        }elseif($pjnNo == 1){
                          $NoAwal = '00';
                        }elseif ($pjnNo == 2) {
                          $NoAwal = '0';
                        }elseif ($pjnNo == 3) {
                          $NoAwal ='';
                        }
                        $noPengaduan = date('m').$thn.$NoAwal.$noNext;


                        ?>
                        <tbody>
                          <tr>
                            <td>
                              <div class="control-group">
                                <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("d-m-Y") ?>" data-date-format="dd-mm-yyyy">
                                  <input id="tgl" name="tgl" class="input-small" size="16" type="text" value="<?php echo date("d-m-Y") ?>" readonly="true">
                                  <span class="add-on"><i class="icon-calendar"></i></span>
                                </div>                                
                              </div>
                            </td>
                            
                            <td>                              
                              <input id="pengaduan" name="pengaduan" type="text" class="input-small" readonly="true" value="<?php echo $noPengaduan; ?>" />
                              <!-- <p class="help-block catatan">*Bulan Tahun No</p> -->
                            </td>
                            <td>
                              <input id="no_claim" name="no_claim" type="text" class="input-small" minlength="7" maxlength="7" placeholder="Input No Portal" />
                            </td>
                            <td>
                              <input id="daerah" name="daerah" type="text" class="input-small" value="BDG" readonly="true" />
                            </td>
                            <td>
                                <input id="dealer" name="dealer" type="text" class="input-small" value="KTA" readonly="true" />   
                            </td>
                          </tr>
                        </tbody>

                      </table>
<!--                       <table class="table">
  <tr>
    <th>Kategori Ban</th>
  </tr>
  <tbody>
    <tr>
      <td>
        <div class="control-group">
          <select id="kategori" name="kategori" class="chosen-select-no-results" data-placeholder="Pilih Keputusan..." >
            <option></option>
            <option value="Bias">Bias</option>
            <option value="TBR">TBR</option>
            <option value="Radial">Radial</option>
            <option value="KENDA">KENDA</option>
            <option value="BD BELUGA">BD BELUGA</option>
            <option value="BD KRC">BD KRC</option>
            <option value="BD KRC">BD KRC</option>
          </select> 
        </div>                                 
      </td>
    </tr>
  </tbody>
</table> -->
                    
                      <!-- <div class="control-group">
                          <label class="control-label"><strong>Pemakai</strong><p class="titik2">:</p></label>
                          <div class="controls">
                              <select id="toko" name="toko" class="chosen-select-no-results" style="width: 40%" data-placeholder="Pilih Pemakai...">
                                <option></option>
                                <option value="1">Surya Tehnik</option>
                                <option value="2">Martha</option>
                                <option value="3">Jaya Abadi</option>
                                <option value="4">Bp. Ading</option>
                                <option value="5">APB</option>
                                <option value="6">Raja Ban</option>
                                <option value="7">Makin Makmur</option>
                              </select>
                          </div>
                      </div> -->
                      <table class="table">
                        <tr>
                          <th>Nama Toko</th>
                          <th>Nama Sales</th>
                        </tr>
                        <tbody>
                          <tr>
                            <td>
                              <input onkeydown="upperCaseF(this)" id="toko" name="toko" type="text" class="input-xlarge" placeholder="Input Nama Toko" autocomplete="off" />
                            </td>
                            <td>
                              <input onkeydown="upperCaseF(this)" id="sales" name="sales" type="text" class="input-xlarge" placeholder="Input Nama Sales" autocomplete="off" />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="control-group"></div>
                      <table class="table">
                          <tr>
                            <th width="40%">Ukuran</th>
                            <th class="brand" width="20%">Pattern</th>
                            <th width="20%">DOT</th>
                            <th width="20%">Tahun Produksi</th>
                          </tr>
                          <tbody>
                            <tr>
                              <td>
                                <div class="control-group">
                                    <select style="width: 98%" id="brg" name="brg" class="chosen-select-no-results" data-placeholder="Pilih Ukuran...">
                                      <option></option>
                                      <?php
                                        $size = "SELECT id_brg, brg FROM barang";
                                        $res_size = $koneksi->query($size);
                                        while ($row_s = $res_size->fetch_array()) {
                                          echo "<option value='".$row_s[0]."'>".$row_s[1]."</option>";
                                        }
                                      ?>
                                    </select>
                                </div>
                              </td>
                              <td>
                                <input onkeydown="upperCaseF(this)" id="pattern" name="pattern" type="text" class="input-large" placeholder="Input Pattern" autocomplete="off" />
                              </td>
                              <td>
                                <input onkeydown="upperCaseF(this)" id="dot" name="dot" type="text" class="input-large" placeholder="Input DOT" autocomplete="off" />
                              </td>
                              <td>
                                <input onkeyup="validAngka(this)" id="tahun" name="tahun" minlength="4" maxlength="4" type="text" class="input-medium" placeholder="Tahun Produksi" autocomplete="off" />
                              </td>
                            </tr>
                          </tbody>
                      </table>

                      <div id="tes"></div>

                      <table class="table">
                        <tr>
                          <th>Kerusakan</th>
                          <th>Tread Dept</th>
                        </tr>
                        <tbody>
                          <tr>
                            <td>
                              <div class="control-group">
                                <input id="kerusakan" name="kerusakan" type="text" autocomplete="off" placeholder="Input Penyebab Kerusakan" class="input-xxlarge" onkeydown="upperCaseF(this)" />
                              </div>                              
                            </td>
                            <td>
                              <input id="tread" name="tread" type="text" autocomplete="off" placeholder="Input Tread Dept" class="input-large" onkeyup="validAngka(this)" />
                            </td>
                          </tr>
                        </tbody>
                      </table>

<!--                       <table class="table">
                        <tr>
                          <th>Keputusan</th>
                          <th>Nominal</th>
                        </tr>
                        <tbody>
                          <tr>
                            <td>
                              <div class="control-group">
                                <select id="keputusan" name="keputusan" class="chosen-select-no-results" data-placeholder="Pilih Keputusan..." >
                                  <option></option>
                                  <option value="Ganti">Ganti</option>
                                  <option value="Tolak">Tolak</option>
                                  <option value="Ganti SC">Ganti SC</option>
                                </select> 
                              </div>                             
                            </td>
                            <td>
                              <input id="nominal" name="nominal" type="text" autocomplete="off" placeholder="Input Nominal" class="input-large" onkeyup="validAngka(this)"  />
                              <p class="help-block catatan">*Jika keputusan "Tolak" isi nominal dengan 0</p>
                            </td>
                          </tr>
                        </tbody>
                      </table> -->

                      <div class="form-actions">
                          <button type="submit" class="btn btn-success" id="simpanClaim"><i class="fa fa-save"></i> Submit</button>
                          <!-- <button class="btn" data-dismiss="modal" aria-hidden="true" id="reset"><i class="fa fa-times-circle"></i> Reset</button> -->
                      </div>
                      </form>
                      <!-- END FORM-->
                    </div>
                </div>
                <!-- END EXAMPLE TABLE widget-->
                </div>
                <!-- END ADVANCED TABLE widget-->
            </div>          
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
 
<?php require_once 'include/footerClaim.php'; ?>

  <script src="jsAction/claim.js"></script> 

<?php
}else{
  include_once 'saldo.php';
}
?>