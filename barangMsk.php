<?php require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$tahun          = date("Y");
$bulan          = date("m");

require_once 'include/header.php';
require_once 'include/menu.php';


//query rak


echo "<div class='div-request div-hide'>masuk</div>";
?>

<!-- BEGIN PAGE -->
<div id="main-content">

  <!-- BEGIN PAGE CONTAINER-->
  <div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
      <div class="span12">
        <h3 class="page-title">
          Barang Masuk
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
            Barang Masuk
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
              echo '<a href="#myModal1" role="button" class="btn btn-primary tambah" id="addBarangMskBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
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
            <table class="table table-striped table-bordered" id="tabelMasuk">
              <thead>
                <tr>
                  <th width="10%">Lokasi Rak</th>
                  <th>Nama Barang</th>
                  <th>Surat Jalan/No Retur</th>
                  <th class="hidden-phone" width="10%">Ket</th>
                  <th class="hidden-phone" width="10%">Tanggal</th>
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
        </div>
        <!-- END EXAMPLE TABLE widget-->
      </div>
    </div>

    <!-- BEGIN MODAL MASUK-->
    <div id="myModal1" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT BARANG MASUK</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitBarangMsk" action="action/barangMasuk/simpanMasuk.php" method="POST">
        <div class="modal-body modal-full tinggi2">
          <table class="table" id="tes">
            <thead>
              <tr>
                <th style="padding-top:0px; font-size: 14px" width="50%">
                  Tanggal</th>
                <th style="padding-top:0px; font-size: 14px" width="50%">
                  Surat Jalan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td width="50%">
                  <div class="control-group" style="margin-bottom: 0px;">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tgl" name="tgl" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </td width="50%">
                <td>
                  <div class="control-group" style="margin-bottom: 0px;">
                    <input class="span12 " id="suratJLN" name="suratJLN" maxlength="15" type="text" placeholder="Input Surat Jalan" onkeydown="HurufBesar(this)" />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="control-group" style="margin-bottom: 15px;">
            <label class="control-label"><strong>Nama Barang</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <select id="barang" name="id_brg" class="chosen-select" data-placeholder="Pilih Type Ban...">
                <option value=""></option>
                <?php
                //query barang
                $brg = "SELECT id_brg, brg, kdbrg FROM barang ORDER BY brg ASC";
                $brg1 = $koneksi->query($brg);
                while ($brg2 = $brg1->fetch_array()) {
                  echo "<option value='$brg2[0]'>$brg2[2] $brg2[1]</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label class="control-label"><strong>Lokasi Rak</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <select id="rak" name="id_rak" class="chosen-select" data-placeholder="Pilih Lokasi Rak...">
                <option value=""></option>
                <?php
                $rak = $koneksi->query("SELECT id_rak, rak FROM rak ORDER BY rak ASC");
                while ($rak1 = $rak->fetch_array()) {
                  echo "<option value='$rak1[0]'>$rak1[1]</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label for="cname" class="control-label"><strong>Keterangan</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="ket" name="ket" type="text" placeholder="Input Keterangan" onkeydown="HurufBesar(this)" />
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label for="tahunprod" class="control-label"><strong>Tahun Produksi</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="tahunprod" name="tahunprod" type="text" placeholder="Tahun Produksi" onkeyup="validAngka(this)" />
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label for="cname" class="control-label"><strong>Jumlah</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="jml" name="jml" type="text" placeholder="Jumlah Barang Masuk" onkeyup="validAngka(this)" />
            </div>
          </div>
          <div class="control-group">
            <div id="pesan"></div>
          </div>
        </div>
        <div class="modal-footer">

          <button class="btn btn-primary" id="simpanBarangMskBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        </div>
      </form>
    </div>
    <!-- END MODAL MASUK-->

    <!-- BEGIN MODAL EDIT MASUK-->
    <div id="editModalMasuk" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-pencil"></i> FORM EDIT BARANG MASUK</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitEditBarangMsk" action="action/barangMasuk/editMasuk.php" method="POST">
        <div class="modal-body modal-full tinggi2">
          <table class="table" id="tes">
            <thead>
              <tr>
                <th style="padding-top:0px; font-size: 14px" width="50%">
                  Tanggal</th>
                <th style="padding-top:0px; font-size: 14px" width="50%">
                  Surat Jalan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td width="50%">
                  <div class="control-group" style="margin-bottom: 0px;">
                    <input class="span12 " id="editTgl" name="editTgl" readonly="true" type="text" />
                  </div>
                </td width="50%">
                <td>
                  <div class="control-group" style="margin-bottom: 0px;">
                    <input class="span12 " id="editSuratJLN" readonly="true" type="text" />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="control-group" style="margin-bottom: 15px;">
            <label class="control-label"><strong>Nama Barang</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="editBrg" name="editBrg" type="text" readonly="true" />
              <input id="editIdDetMsk" name="editIdDetMsk" type="hidden" readonly="true" />
              <input id="editId" name="editId" type="hidden" readonly="true" />
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label class="control-label"><strong>Lokasi Rak</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="editRak" type="text" readonly="true" />
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label for="cname" class="control-label"><strong>Keterangan</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="editKet" name="editKet" type="text" placeholder="Input Keterangan" onkeydown="HurufBesar(this)" />
            </div>
          </div>
          <div class="control-group" style="margin-bottom: 15px;">
            <label for="cname" class="control-label"><strong>Jumlah</strong>
              <p class="titik2">:</p>
            </label>
            <div class="controls">
              <input class="span12 " id="editJml" type="text" readonly="true" />
            </div>
          </div>
          <div class="control-group">
            <div id="pesan-edit"></div>
          </div>
        </div>
        <div class="modal-footer">

          <button class="btn btn-primary" id="editBarangMskBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        </div>
      </form>
    </div>
    <!-- END MODAL EDIT MASUK-->

    <!-- BEGIN MODAL HAPUS MASUK-->
    <div id="hapusModalMasuk" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA MASUK</h3>
      </div>
      <div class="modal-body">
        <p id="pesanHapus" style="color: #dc5d3a"></p>

      </div>
      <div class="modal-footer hidden">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
        <button class="btn btn-danger" id="hapusMasukBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-trash"></i> Hapus</button>
      </div>
    </div>
    <!-- END MODAL HAPUS MASUK-->

    <!-- BEGIN MODAL CARI DATA LAMA-->
    <div id="modalCariData" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM CARI DATA LAMA</h3>
      </div>
      <form class="cmxform form-horizontal" id="submitCariData" action="laporanMasukDataLama.php" method="POST">
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

<script src="jsAction/barangMsk.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>