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
                     Barang Return
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
                           Barang Return
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
                        <a href="#myModal1" role="button" class="btn btn-primary tambah" id="addBarangKlrBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <!-- <a href="javascript:;" class="icon-remove"></a> -->
                            </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelKeluar">
                            <thead>
                            <tr>
                                <th>No Faktur</th>
                                <th>Pengirim</th>
                                <th>Lokasi Rak</th>
                                <th>Nama Barang</th>
                                <th class="hidden-phone">Tanggal</th>
                                <th class="hidden-phone">Jam</th>
                                <th>Total</th>
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
                      <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT BARANG KELUAR</h3>
                  </div>
                  <form class="cmxform form-horizontal" id="submitBarangKlr" action="action/barangKeluar/simpanKeluar.php" method="POST" >
                      <div class="modal-body modal-full tinggi">
                          <div class="control-group">
                              <label class="control-label"><strong>Pengirim</strong><p class="titik2">:</p></label>
                              <div class="controls">
                                <select id="nama" name="nama" class="choiceChosen" data-placeholder="Pilih Nama Pengirim..." >
                                <option value=""></option>
                                <?php
                                $nama = "SELECT * FROM pengirim ORDER BY nama ASC";
                                $nama1 = $koneksi->query($nama);
                                while ($nama2 = $nama1->fetch_array()) {
                                  echo "<option value='$nama2[1]'>$nama2[1]</option>";
                                }
                                ?>
                                </select>
                              </div>
                          </div>

                          <div class="control-group no-nota">
                              <label class="control-label"><strong>No Faktur</strong><p class="titik2">:</p></label>
                              <!-- <div class="controls">
                                <input class="input-small" name="awal" type="text"  value="17.000" readonly="true" />
                              </div> -->
                              <div class="controls">
                                  <select class="input-medium m-wrap" name="awal" tabindex="1">
                                      <option value="17.000">17.000</option>
                                      <option value="MG1706-0">MG1706-0</option>
                                  </select>
                              </div>
                          </div>
                          <div class="control-group no-nota2">
                              <div class="controls">
                                <input class="input-small" id="noFaktur" name="noFaktur" type="text"  placeholder="Lima Digit Terakhir" onkeyup="validAngka(this)" maxlength="5" />
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
                                          <select id="id_brg" name="id_brg" class="choiceChosen" data-placeholder="Pilih Type Ban..." >
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
                                          <select id="rak" name="id_rak" class="choiceChosen" data-placeholder="Pilih Lokasi Rak..." >
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
                                          <input class="input-large" id="jumlah" name="jml" type="text"  placeholder="Jumlah Barang Keluar" onkeyup="validAngka(this)"/>
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
                          <button class="btn btn-primary" id="simpanBarangKlrBtn" type="submit" data-loading-text="Loading..." autocomplete="off" ><i class="fa fa-floppy-o"></i> Simpan</button>
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

    <script src="jsAction/barangKlr.js"></script> 

    <script src="assets/chosen/chosen.jquery.min.js"></script>
    <script>
      $(document).ready(function(){
        //Chosen
      $(".choiceChosen, .productChosen").chosen({});
      //Logic
        $(".choiceChosen").change(function(){
    if($(".choiceChosen option:selected").val()=="no"){
      $(".productChosen option[value='2']").attr('disabled',true).trigger("chosen:updated");
      $(".productChosen option[value='1']").removeAttr('disabled',true).trigger("chosen:updated");
    } else {
      $(".productChosen option[value='1']").attr('disabled',true).trigger("chosen:updated");
      $(".productChosen option[value='2']").removeAttr('disabled',true).trigger("chosen:updated");
    }
  });
      });
    function validAngka(a)
    {
      if(!/^[0-9.]+$/.test(a.value))
      {
      a.value = a.value.substring(0,a.value.length-1000);
      }
    }
    </script>
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>