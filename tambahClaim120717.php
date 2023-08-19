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
                            <th width="30%">Tanggal</th>
                            <th class="no_calim" width="30%">No Claim</th>
                            <th class="daerah" width="30%">Daerah</th>
                            <th width="10%">Dealer</th>
                          </tr>

                        <tbody>
                          <tr>
                            <td>
                              <div class="control-group">
                                <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("d-m-Y") ?>" data-date-format="dd-mm-yyyy">
                                  <input id="tgl" name="tgl" class="input-small" size="16" type="text" value="<?php echo date("d-m-Y") ?>" readonly="">
                                  <span class="add-on"><i class="icon-calendar"></i></span>
                                </div>                                
                              </div>
                            </td>
                            <td>
                            <?php
                            $queryNo = "SELECT no_claim, YEAR(tgl) FROM claim ORDER BY id_claim DESC LIMIT 0,1";
                            $resNo   = $koneksi->query($queryNo);
                            $rowNo   = $resNo->fetch_array();

                            if ($rowNo[1] == $tahun) {
                              $no_claim = $rowNo[0]+1;
                            }else{
                              $no_claim = 1;
                            }
                            
                            ?>
                              <input id="no_claim" name="no_claim" type="text" class="input-small" value="<?php echo $no_claim; ?>" readonly="true" />
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
                      <table class="table">
                        <tr>
                          <th>Nama Toko</th>
                          <th>Nama Sales</th>
                        </tr>
                        <tbody>
                          <tr>
                            <td>
                              <div class="control-group">
                                <input onkeydown="upperCaseF(this)" id="toko" name="toko" type="text" class="input-xlarge" placeholder="Input Nama Toko" autocomplete="off" />
                              </div>
                            </td>
                            <td>
                              <input onkeydown="upperCaseF(this)" id="sales" name="sales" type="text" class="input-xlarge" placeholder="Input Nama Sales" autocomplete="off" />
                            </td>
                          </tr>
                        </tbody>
                      </table>
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
                                    <select style="width: 98%" id="id_brg" name="id_brg" class="chosen-select-no-results" data-placeholder="Pilih Ukuran...">
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
                                <input onkeydown="upperCaseF(this)" id="tahun" name="tahun" type="text" class="input-small" placeholder="Input Tahun" autocomplete="off" />
                              </td>
                            </tr>
                          </tbody>
                      </table>
                      <div id="tes"></div>
                      <table class="table" id="kerusakanTable">
                          <tr>
                            <th colspan="5"><center>Kerusakan</center></th>
                          </tr>
                          <tr>
                            <th width="20%">Crown</th>
                            <th width="20%">Sidwall</th>
                            <th width="20%">Bead</th>
                            <th width="20%">Inner Liner</th>
                            <th width="20%">Outher</th>
                          </tr>
                          <tbody>
                            <tr>
                              <td>
                                <div class="control-group">
                                    <select style="width: 100%;" id="crown" name="crown" class="chosen-select-no-results" data-placeholder="Pilih Crown...">
                                      <option></option>
                                      <?php
                                      $crown = "SELECT id_k, lengkap_k FROM kerusakan WHERE group_k='Crown' ORDER BY lengkap_k ASC";
                                      $rest_crown = $koneksi->query($crown);
                                      while ($row_crown = $rest_crown->fetch_array()) {
                                        echo "<option value=".$row_crown[0].">$row_crown[1]</option>";
                                      }
                                      ?>
                                    </select>
                                </div>
                              </td><td>
                                <div class="control-group">
                                    <select style="width: 100%;" id="sidewall" name="sidewall" class="chosen-select-no-results" data-placeholder="Pilih Sidewall...">
                                      <option></option>
                                      <?php
                                      $sidewall = "SELECT id_k, lengkap_k FROM kerusakan WHERE group_k='Sidewall' ORDER BY lengkap_k ASC";
                                      $rest_sidewall = $koneksi->query($sidewall);
                                      while ($row_sidewall = $rest_sidewall->fetch_array()) {
                                        echo "<option value=".$row_sidewall[0].">$row_sidewall[1]</option>";
                                      }
                                      ?>
                                    </select>
                                </div>
                              </td><td>
                                <div class="control-group">
                                    <select style="width: 100%;" id="bead" name="bead" class="chosen-select-no-results" data-placeholder="Pilih Bead">
                                      <option></option>
                                      <?php
                                      $bead = "SELECT id_k, lengkap_k FROM kerusakan WHERE group_k='Bead' ORDER BY lengkap_k ASC";
                                      $rest_bead = $koneksi->query($bead);
                                      while ($row_bead = $rest_bead->fetch_array()) {
                                        echo "<option value=".$row_bead[0].">$row_bead[1]</option>";
                                      }
                                      ?>
                                    </select>
                                </div>
                              </td><td>
                                <div class="control-group">
                                    <select style="width: 100%;" id="inner" name="inner" class="chosen-select-no-results" data-placeholder="Pilih Inner Liner">
                                      <option></option>
                                      <?php
                                      $inner = "SELECT id_k, lengkap_k FROM kerusakan WHERE group_k='Inner Liner' ORDER BY lengkap_k ASC";
                                      $rest_inner = $koneksi->query($inner);
                                      while ($row_inner = $rest_inner->fetch_array()) {
                                        echo "<option value=".$row_inner[0].">$row_inner[1]</option>";
                                      }
                                      ?>
                                    </select>
                                </div>
                              </td><td>
                                <div class="control-group">
                                    <select style="width: 100%;" id="others" name="others" class="chosen-select-no-results" data-placeholder="Pilih Outher">
                                      <option></option>
                                      <?php
                                      $others = "SELECT id_k, lengkap_k FROM kerusakan WHERE group_k='Others' ORDER BY lengkap_k ASC";
                                      $rest_others = $koneksi->query($others);
                                      while ($row_others = $rest_others->fetch_array()) {
                                        echo "<option value=".$row_others[0].">$row_others[1]</option>";
                                      }
                                      ?>
                                    </select>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                      </table>
                      <div class="control-group">
                          <label class="control-label"><strong>Tread Depth</strong><p class="titik2">:</p></label>
                          <div class="controls">
                              <input id="tread" name="tread" type="text" autocomplete="off" placeholder="Input Tread Depth" class="span6" onkeydown="upperCaseF(this)" />
                          </div>
                      </div>
                      <table class="table">
                        <tr>
                          <th>Keputusan</th>
                          <th>Nominal</th>
                          <th>Dikurangi</th>
                          <th>Total Nominal</th>
                        </tr>
                        <tbody>
                          <tr>
                            <td>
                              <div class="control-group">
                                    <div class="controls" style="margin-left: 20px;">
                                        <label class="radio">
                                            <input type="radio" name="optionsRadios1" value="option1" />
                                            Ganti
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="optionsRadios1" value="option2" />
                                            Tolak
                                        </label>
                                    </div>
                                </div>                           
                            </td>
                            <td>
                              <input onkeydown="upperCaseF(this)" type="text" class="input-large" placeholder="Nominal Pengganti" autocomplete="off" />
                            </td>
                            <td>
                              <input type="text" class="input-mini" readonly="true" value="5%" />
                            </td>
                            <td>
                              <input id="nominal" name="nominal" type="text" class="input-large" readonly="ture" />
                            </td>
                          </tr>
                        </tbody>
                      </table>
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