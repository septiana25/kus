<?php require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$tahun          = date("Y");
$bulan          = date("m");

require_once 'include/header.php';
require_once 'include/menu.php';

//query barang

//query rak


echo "<div class='div-request div-hide'>keluar</div>";
?>

<style>
  #id_rak {
    font-weight: bold;
    font-size: medium;
  }
</style>

<!-- BEGIN PAGE -->
<div id="main-content">

  <!-- BEGIN PAGE CONTAINER-->
  <div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
      <div class="span12">
        <h3 class="page-title">
          Barang Keluar
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
            Barang Keluar
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
              echo '<a href="#myModal1" role="button" class="btn btn-primary tambah" id="addBarangKlrBtnModal" data-toggle="modal"> <i class=" icon-plus"></i> Tambah Data</a>';
            }

            if ($_SESSION['level'] == "administrator") {
              echo '
                            <a href="#myModal2" role="button" class="btn btn-warning tambah" id="editSeriPJKBtnModal" data-toggle="modal"> <i class="fa fa-pencil-square"></i> Ubah NO Seri Pajak</a>
                          ';
            }
            ?>

            <span class="tools">
              <a href="javascript:;" class="icon-chevron-down"></a>
              <!-- <a href="javascript:;" class="icon-remove"></a> -->
            </span>
            <div class="actions">
              <a href="#modalCariData" id="cariDataLama" role="button" class="btn btn-info" data-toggle="modal"><i class="fa fa-search"></i> Cari Data Lama</a>
            </div>
          </div>
          <div class="widget-body">
            <table class="table table-striped table-bordered" id="tabelKeluar">
              <thead>
                <tr>
                  <th width="11%">No Faktur</th>
                  <th width="15%">Toko</th>
                  <th width="8%">Lokasi Rak</th>
                  <th>Nama Barang</th>
                  <th width="8%" class="hidden-phone">Tanggal</th>
                  <th width="10%" class="hidden-phone">Pengirim</th>
                  <th>Total</th>
                  <th width="11%">Ket</th>
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

    <!-- BEGIN MODAL KELUAR-->
    <div id="myModal1" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT BARANG KELUAR</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitBarangKlr" action="action/barangKeluar/simpanKeluar.php" method="POST">
        <div class="modal-body modal-full tinggi">
          <div class="control-group">
            <label class="control-label"><strong>Toko</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <select id="id_toko" name="id_toko" class="chosen-select input-small" data-placeholder="Pilih Nama Toko...">
                <option value=""></option>
                <?php
                $toko = "SELECT id_toko, toko FROM toko ORDER BY toko ASC";
                $toko1 = $koneksi->query($toko);
                while ($toko2 = $toko1->fetch_array()) {
                  echo "<option value='$toko2[0]'>$toko2[1]</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <div class="control-group no-nota">
            <label class="control-label"><strong>No Faktur</strong>
              <p class="titik2">:</p>
            </label>
            <?php
            $carSeriPJK = $koneksi->query("SELECT seriPajak FROM tblSeriPajak");
            $rowPJK = $carSeriPJK->fetch_array();
            ?>
            <div class="controls">
              <input type="text" class="input-small" name="awal" value="<?php echo $rowPJK[0]; ?>" readonly="true">
            </div>
          </div>
          <div class="control-group no-nota2">
            <div class="controls">
              <input class="input-tengah" id="noFaktur" name="noFaktur" type="text" placeholder="Delapan Digit Terakhir" onkeyup="validAngka(this)" minlength="8" maxlength="8" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label"><strong>Tanggal</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                <input id="tgl" name="tgl" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                <span class="add-on"><i class="icon-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="control-group no-nota">
            <label class="control-label"><strong>Pengirim</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12" id="pengirim" name="pengirim" type="text" placeholder="Input Pengirim" autocomplete="false" onkeyup="hurufBesar(this)" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label"><strong>Keterangan</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12" id="keterangan" name="keterangan" type="text" placeholder="Input Keterangan" />
            </div>
          </div>
          <div class="batas"></div>

          <div class="control-group">
            <table class="table" id="tabelBarangKeluar">
              <thead>
                <tr>
                  <th style="width:30%">Nama Barang</th>
                  <th style="width:50%">Lokasi Rak</th>
                  <th style="width:20%">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="control-group">
                      <select id="id_brg" name="id_brg" class="chosen-select" data-placeholder="Pilih Type Ban...">
                        <option value=""></option>
                        <?php
                        $brg = "
                                  SELECT b.id_brg AS id_brg, b.brg, saldo_akhir
                                        FROM(
                                        SELECT id_brg, rak, SUM(saldo_awal) AS saldo_awal, SUM(saldo_akhir) AS saldo_akhir, tgl
                                        FROM detail_brg
                                        LEFT JOIN rak USING(id_rak)
                                        LEFT JOIN saldo USING(id)
                                        GROUP BY id_brg 
                                        )d
                                        RIGHT JOIN (
                                        SELECT id_brg, kdbrg, brg, nourt, kat
                                        FROM barang
                                        JOIN kat USING(id_kat)
                                        )b ON b.id_brg=d.id_brg WHERE saldo_akhir !=0 ORDER BY rak, b.brg ASC
                                          ";
                        $brg1 = $koneksi->query($brg);
                        while ($brg2 = $brg1->fetch_array()) {
                          echo "<option value='$brg2[0]'>$brg2[1]</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="control-group">
                      <select class="span12" id="id_rak" name="id_rak">
                        <option value="">Pilih Ukuran..</option>
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="control-group">
                      <input class="span12 " id="jumlah" name="jml" type="text" placeholder="Jumlah Barang Keluar" onkeyup="validAngka(this)" />
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
          <button class="btn btn-primary" id="simpanBarangKlrBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        </div>
      </form>
    </div>
    <!-- END MODAL KELUAR-->

    <!-- BEGIN MODAL EDIT KELUAR-->
    <div id="editModalKeluar" class="modal modal-form-tinggi hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM EDIT BARANG KELUAR</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitEditBarangKlr" action="action/barangKeluar/editKeluar.php" method="POST">
        <div class="modal-body modal-full tinggi">
          <div class="control-group">
            <label class="control-label"><strong>Toko</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="input-xlarge" type="text" name="editToko" id="editToko" readonly="true" />
            </div>
          </div>

          <div class="control-group no-nota">
            <label class="control-label"><strong>Tgl & No Faktur</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input id="editTgl" name="editTgl" class="input-small" type="text" readonly="true" />
            </div>
          </div>

          <div class="control-group no-nota2">
            <div class="controls">
              <input type="text" class="input-tengah" name="editFaktur" id="editFaktur" readonly="true" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label"><strong>Keterangan</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12" id="editKet" name="editKet" type="text" placeholder="Input Keterangan" />
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
                      <input class="input-xxlarge" type="text" name="editBrg" id="editBrg" readonly="true" />
                    </div>
                  </td>
                  <td>
                    <div class="control-group">
                      <input class="input-large" type="text" name="editRak" id="editRak" readonly="true" />
                    </div>
                  </td>
                  <td>
                    <div class="control-group">
                      <input class="span12 " id="editJml" name="editJml" type="text" readonly="true" />
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="control-group">
            <div id="pesanEdit"></div>
          </div>
        </div>
        <div class="modal-footer hidden">
          <input class="span12" id="editId" name="editId" type="hidden" placeholder="Input Keterangan" />
          <button class="btn btn-primary" id="editBarangKlrBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        </div>
      </form>
    </div>
    <!-- END MODAL EDIT KELUAR-->

    <!-- BEGIN MODAL HAPUS KELUAR-->
    <div id="hapusModalKeluar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA KELUAR</h3>
      </div>

      <div class="modal-body">
        <p id="pesanHapus" style="color: #dc5d3a"></p>
      </div>

      <div class="modal-footer hidden">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        <button class="btn btn-danger" id="hapusKeluarBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-trash"></i> Hapus</button>
      </div>
    </div>
    <!-- END MODAL HAPUS KELUAR-->

    <!-- BEGIN MODAL EDIT SERI PAJAK-->
    <div id="myModal2" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign-alt"></i> FORM INPUT DATA BARANG</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitSeriPajak" action="action/barangKeluar/simpanSeriPajak.php" method="POST">
        <div class="modal-body modal-full tinggi">
          <div class="control-group ">
            <label class="control-label"><strong>No Seri Pajak Baru</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="noSeriPJKBaru" name="noSeriPJKBaru" type="text" placeholder="Input Seri Pajak Baru" minlength="11" maxlength="11" />
            </div>
          </div>
          <div class="control-group">
            <label for="cname" class="control-label"><strong>No Seri Pajak Lama</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="noSeriPJKLama" name="noSeriPJKLama" type="text" readonly="true" />
            </div>
          </div>

          <div class="control-group">
            <div class="alert alert-block alert-info fade in">
              <!-- <button data-dismiss="alert" class="close" type="button">×</button> -->
              <h4 class="alert-heading">Info!</h4>
              <p>
                Format Penulisan Seri Pajak <strong>010.002.18.</strong> panjang 11 karakter. Dan diakhiri <strong>TITIK</strong>
              </p>
            </div>
          </div>
          <div class="control-group">
            <div id="pesanSeriPJK"></div>
          </div>
        </div>
        <div class="modal-footer">

          <button class="btn btn-primary" id="simpanSeriPJKBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        </div>
      </form>
    </div>
    <!-- END MODAL EDIT SERI PAJAK-->

    <!-- BEGIN MODAL CARI DATA LAMA-->
    <div id="modalCariData" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM CARI DATA LAMA</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitCariData" action="laporanKeluarDataLama.php" method="POST">
        <div class="modal-body modal-full tinggi-sedang">
          <div class="control-group">
            <label class="control-label">Pilih Bulan</label>
            <div class="controls">
              <select id="cariBulan" name="cariBulan" class="span6 " data-placeholder="Choose a Category" tabindex="1">
                <option value="">Pilih Bulan...</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Pilih Tahun</label>
            <div class="controls">
              <select id="cariTahun" name="cariTahun" class="span6 " data-placeholder="Choose a Category" tabindex="1">
                <option value="">Pilih Tahun...</option>
                <?php
                for ($i = 2017; $i <= 2025; $i++) {
                  echo "<option value=" . $i . ">" . $i . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">

          <button class="btn btn-primary" id="simpanCariBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-search"></i> Cari</button>
          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        </div>
      </form>
    </div>
    <!-- END MODAL CARI DATA LAMA-->

    <!-- END ADVANCED TABLE widget-->
  </div>
  <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/barangKlr.js"></script>
<script src="assets/chosen/chosen1.jquery.min.js"></script>