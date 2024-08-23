<?php require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$tahun          = date("Y");
$bulan          = date("m");

require_once 'include/header.php';
require_once 'include/menu.php';


function generateMonthSelect($name, $id, $class = '', $selected = '', $placeholder = 'Pilih Bulan...')
{
  $months = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
  ];

  $output = "<select id=\"$id\" name=\"$name\" class=\"$class\" data-placeholder=\"Choose a Category\" tabindex=\"1\">\n";
  $output .= "\t<option value=\"\">$placeholder</option>\n";

  foreach ($months as $value => $label) {
    $isSelected = ($selected == $value) ? ' selected' : '';
    $output .= "\t<option value=\"$value\"$isSelected>$label</option>\n";
  }

  $output .= "</select>";

  return $output;
}

function generateYearSelect($name, $id, $class = '', $selected = '', $startYear = 2018, $endYear = null, $placeholder = 'Pilih Tahun...')
{
  $endYear = date('Y');

  $output = "<select id=\"$id\" name=\"$name\" class=\"$class\" data-placeholder=\"Choose a Category\" tabindex=\"1\">\n";
  $output .= "\t<option value=\"\">$placeholder</option>\n";

  for ($year = $endYear; $year >= $startYear; $year--) {
    $isSelected = ($selected == $year) ? ' selected' : '';
    $output .= "\t<option value=\"$year\"$isSelected>$year</option>\n";
  }

  $output .= "</select>";

  return $output;
}

$p = isset($_GET['p']) ? $_GET['p']  : false;

if ($p == 'LapMsk') {
  $lapp = "Masuk";
} else if ($p == 'LapKlr') {
  $lapp = "Keluar";
} else if ($p == 'LapBrg') {
  $lapp = "Barang";
} else if ($p == 'LapKartuStok') {
  $lapp = "Kartu Stock";
} else if ($p == 'LapRtr') {
  $lapp = "Retur";
} else if ($p == 'LapMTS') {
  $lapp = "Mutasi";
} else {
  $lapp = "Error";
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
          Laporan Aktifitas <?php echo $lapp; ?>
        </h3>
        <ul class="breadcrumb">
          <li>
            <a href="#">Home</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="#">Laporan</a>
            <span class="divider">/</span>
          </li>
          <li class="active">
            Laporan Aktifitas <?php echo $lapp; ?>
          </li>

        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
      </div>
    </div>
    <!-- END PAGE HEADER-->

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

            <?php
            if ($p == 'LapKlr') {
              echo "<div class='div-request div-hide'>laporanKeluar</div>";
            ?>

              <!-- BEGIN FORM LAPORAN MASUK-->
              <div class="tabbable custom-tab">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1_1" data-toggle="tab">REKAP</a></li>
                  <li class=""><a href="#tab_1_2" data-toggle="tab">RINCI</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1_1">
                    <p>Laporan Rekap Transaksi Keluar</p>
                    <form class="form-horizontal" action="laporanKeluar.php" method="POST" id="submitLaporan">
                      <div class="control-group">
                        <label class="control-label">Pilih Bulan</label>
                        <div class="controls">
                          <?php echo generateMonthSelect('bulan', 'bulan', 'span6', '', 'Pilih Bulan...'); ?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Pilih Tahun</label>
                        <div class="controls">
                          <?php echo generateYearSelect('tahun', 'tahun', 'span6', '', 2018, null, 'Pilih Tahun...'); ?>
                        </div>
                      </div>
                      <div class="form-actions">
                        <!-- <button type="button" class="btn">Cancel</button> -->
                        <button type="submit" class="btn btn-success" id="cariLaporanBtn"><i class="fa fa-search"></i> Cari</button>
                        <a class="btn btn-primary" id="exportExcelBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        <a class="btn btn-warning" id="exportPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane" id="tab_1_2">
                    <p>Laporan Rinci Transaksi Keluar</p>

                    <form class="form-horizontal" action="#" method="POST" id="submitLaporanKlrRinci">
                      <div class="control-group">
                        <label class="control-label">Pilih Bulan</label>
                        <div class="controls">
                          <?php echo generateMonthSelect('bulan1', 'bulan1', 'span6', '', 'Pilih Bulan...'); ?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Pilih Tahun</label>
                        <div class="controls">
                          <?php echo generateYearSelect('tahun1', 'tahun1', 'span6', '', 2018, null, 'Pilih Tahun...'); ?>
                        </div>
                      </div>
                      <div class="form-actions">
                        <!-- <button type="button" class="btn">Cancel</button> -->
                        <!-- <button type="submit" class="btn btn-success" id="cariLaporanMskBtn"><i class="fa fa-search"></i> Cari</button> -->
                        <a class="btn btn-primary" id="exportLapKlrExcelRinciBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        <!-- <a class="btn btn-warning" id="exportLapMskPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a> -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>

              <!-- END BEGIN FORM LAPORAN MASUK-->

            <?php
            } else if ($p == 'LapMsk') {
              echo "<div class='div-request div-hide'>laporanMasuk</div>";
            ?>

              <!-- BEGIN FORM LAPORAN KELUAR-->

              <div class="tabbable custom-tab">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1_1" data-toggle="tab">REKAP</a></li>
                  <li class=""><a href="#tab_1_2" data-toggle="tab">RINCI</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1_1">
                    <p>Laporan Rekap Transaksi Masuk</p>

                    <form class="form-horizontal" action="laporanMasuk.php" method="POST" id="submitLaporanMasuk">
                      <div class="control-group">
                        <label class="control-label">Pilih Bulan</label>
                        <div class="controls">
                          <?php echo generateMonthSelect('bulan', 'bulan', 'span6', '', 'Pilih Bulan...'); ?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Pilih Tahun</label>
                        <div class="controls">
                          <?php echo generateYearSelect('tahun', 'tahun', 'span6', '', 2018, null, 'Pilih Tahun...'); ?>
                        </div>
                      </div>
                      <div class="form-actions">
                        <!-- <button type="button" class="btn">Cancel</button> -->
                        <button type="submit" class="btn btn-success" id="cariLaporanMskBtn"><i class="fa fa-search"></i> Cari</button>
                        <a class="btn btn-primary" id="exportLapMskExcelBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        <a class="btn btn-warning" id="exportLapMskPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                      </div>
                    </form>

                  </div>
                  <div class="tab-pane" id="tab_1_2">
                    <p>Laporan Rinci Transaksi Masuk</p>

                    <form class="form-horizontal" action="#" method="POST" id="submitLaporanKlrRinci">
                      <div class="control-group">
                        <label class="control-label">Pilih Bulan</label>
                        <div class="controls">
                          <?php echo generateMonthSelect('bulan', 'bulan', 'span6', '', 'Pilih Bulan...'); ?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Pilih Tahun</label>
                        <div class="controls">
                          <?php echo generateYearSelect('tahun1', 'tahun1', 'span6', '', 2018, null, 'Pilih Tahun...'); ?>
                        </div>
                      </div>
                      <div class="form-actions">
                        <!-- <button type="button" class="btn">Cancel</button> -->
                        <!-- <button type="submit" class="btn btn-success" id="cariLaporanMskBtn"><i class="fa fa-search"></i> Cari</button> -->
                        <a class="btn btn-primary" id="exportLapMskExcelRinciBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        <!-- <a class="btn btn-warning" id="exportLapMskPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a> -->
                      </div>
                    </form>

                  </div>
                </div>
              </div>


              <!-- END BEGIN FORM LAPORAN KELUAR-->

            <?php

            } else if ($p == 'LapKartuStok') {
              echo "<div class='div-request div-hide'>laporanKartuStock</div>";
            ?>

              <!-- BEGIN FORM LAPORAN KARTU STOCK-->
              <form class="form-horizontal" action="action/laporan/lapKartuStok.php" method="POST" id="submitLapKartuStok">
                <div class="control-group">
                  <label class="control-label">Pilih Barang</label>
                  <div class="controls">
                    <select id="id_brgKartu" name="id_brgKartu" class="chosen-select input-xxlarge" data-placeholder="Pilih Type Ban...">
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
                </div>
                <div class="control-group">
                  <label class="control-label">Pilih Bulan</label>
                  <div class="controls">
                    <?php echo generateMonthSelect('bulanKartu', 'bulanKartu', 'span6', '', 'Pilih Bulan...'); ?>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Pilih Tahun</label>
                  <div class="controls">
                    <?php echo generateYearSelect('tahunKartu', 'tahunKartu', 'span6', '', 2018, null, 'Pilih Tahun...'); ?>
                  </div>
                </div>
                <div class="form-actions">
                  <!-- <button type="button" class="btn">Cancel</button> -->
                  <button type="submit" class="btn btn-success" id="cariLaporanKartuBtn"><i class="fa fa-eye"></i> View</button>
                  <a class="btn btn-primary" id="exportLapKartuExcelBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                  <a class="btn btn-warning" id="exportLapKartuPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                </div>
              </form>
              <!-- END BEGIN FORM LAPORAN KARTU STOCK-->

            <?php
            } else if ($p == 'LapRtr') {
              echo "<div class='div-request div-hide'>laporanRtr</div>";
            ?>

              <!-- BEGIN FORM LAPORAN BARANG-->
              <form class="form-horizontal" action="action/return/laporanRetur.php" method="POST" id="submitLaporanRetur">
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Awal</label>
                  <div class="controls">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tglAwalRtr" name="tglAwalRtr" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Akhir</label>
                  <div class="controls">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tglAkhirRtr" name="tglAkhirRtr" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-actions">
                  <!-- <button type="button" class="btn">Cancel</button> -->
                  <button type="submit" class="btn btn-success" id="cariLaporanRtrBtn"><i class="fa fa-search"></i> Cari</button>
                  <a class="btn btn-primary" id="exportLapRtrExcelBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                  <a class="btn btn-warning" id="exportLapRtrPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                </div>
              </form>
              <!-- END BEGIN FORM LAPORAN BARANG-->

            <?php
            } else if ($p == 'LapMTS') {
              echo "<div class='div-request div-hide'>laporanMutasi</div>";
            ?>

              <!-- BEGIN FORM LAPORAN KARTU STOCK-->
              <form class="form-horizontal" action="action/laporan/laporanMutasi.php" method="POST" id="submitLaporanMutasi">
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Awal</label>
                  <div class="controls">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tglAwalMTS" name="tglAwalMTS" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Akhir</label>
                  <div class="controls">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tglAkhirMTS" name="tglAkhirMTS" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-actions">
                  <!-- <button type="button" class="btn">Cancel</button> -->
                  <button type="submit" class="btn btn-success" id="cariLaporanMTSBtn"><i class="fa fa-search"></i> Cari</button>
                  <a class="btn btn-primary" id="exportLapMTSExcelBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                  <a class="btn btn-warning" id="exportLapMTSPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                </div>
              </form>
              <!-- END BEGIN FORM LAPORAN KARTU STOCK-->

            <?php
            } else if ($p == 'LapBrg') {
              echo "<div class='div-request div-hide'>laporanBrg</div>";
            ?>

              <!-- BEGIN FORM LAPORAN BARANG-->
              <form class="form-horizontal" action="laporanMasuk.php" method="POST" id="submitLaporanMasuk">
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Awal</label>
                  <div class="controls">
                    <select id="id_brg" name="id_brg" class="chosen-select input-xxlarge" data-placeholder="Pilih Type Ban...">
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
                </div>
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Awal</label>
                  <div class="controls">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tglAwal" name="tglAwal" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Pilih Tanggal Akhir</label>
                  <div class="controls">
                    <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                      <input id="tglAkhir" name="tglAkhir" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                      <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-actions">
                  <!-- <button type="button" class="btn">Cancel</button> -->
                  <button type="submit" class="btn btn-success" id="cariLaporanMskBtn"><i class="fa fa-search"></i> Cari</button>
                  <a class="btn btn-primary" id="exportLapMskExcelBtn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                  <a class="btn btn-warning" id="exportLapMskPDFBtn"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                </div>
              </form>
              <!-- END BEGIN FORM LAPORAN BARANG-->

            <?php
            } else {
              echo "Halaman yang ada minta tidak ada";
            }

            ?>


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

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/laporan.js"></script>