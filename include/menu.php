<!-- BEGIN SIDEBAR -->
<div class="sidebar-scroll">
    <div id="sidebar" class="nav-collapse collapse">

        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <div class="navbar-inverse">
            <form class="navbar-search visible-phone">
                <input type="text" class="search-query" placeholder="Search" />
            </form>
        </div>
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="sidebar-menu">
            <li class="sub-menu" id="activeDashboard">
                <a class="" href="dashboard.php">
                    <i class="icon-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <?php
            $transaksi = '
                  <li class="sub-menu" id="activeTransaksi">
                      <a href="javascript:;" class="">
                          <i class="icon-shopping-cart"></i>
                          <span>Transaksi</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeBarangMasuk"><a class="" href="barangMsk.php">Barang Masuk</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activePOMasuk"><a class="" href="pomasuk.php">PO Masuk</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeBarangKeluar"><a class="" href="barangKlr.php">Barang Keluar</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeMutasi"><a class="" href="mutasiRetur.php?p=mutasi">Mutasi Barang</a><i class="icon-circle-arrow-right kanan"></i>
                          </li><li id="activeRetur"><a class="" href="mutasiRetur.php?p=retur&bln=' . date('m') . '&thn=' . date('Y') . '">Retur Barang</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
                  ';

            $claim = '
                  <li class="sub-menu" id="activeClaim">
                      <a href="javascript:;" class="">
                          <i class="fa fa-cubes"></i>
                          <span>Claim</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeTamabahClaim"><a class="" href="tambahClaim.php">Tambah Claim</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeDataClaim"><a class="" href="dataClaim.php?p=data">Data Claim</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeNotaPengganti"><a class="" href="notaPenggantian.php">Nota Penggantian</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeNotaTolakan"><a class="" href="notaTolakan.php">Nota Tolakan</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
                  ';

            $etoll = '
                  <li class="sub-menu" id="activeEToll">
                      <a href="javascript:;" class="">
                          <i class="fa fa-road"></i>
                          <span>E-Toll</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeTrans_toll"><a class="" href="trans_toll.php">Transaksi E-Toll</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeTambahSaldoToll"><a class="" href="tmbhSaldoToll.php">Tambah Saldo</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeDataPosting"><a class="" href="dataPosting.php">Data Posting</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeMasterToll"><a class="" href="masterToll.php">Master E-toll</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
                  ';

            $efaktur = '
                  <li class="sub-menu" id="activeEfaktur">
                      <a href="efaktur.php">
                          <i class="fa fa-sticky-note"></i>
                          <span>E-Faktur</span>
                      </a>
                  </li>
                  ';

            $masterBarang = '
                  <li class="sub-menu" id="activeMaster">
                      <a href="javascript:;" class="">
                          <i class="icon-tasks"></i>
                          <span>Master Barang</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeBarang"><a class="" href="barang.php">Data Barang</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeBarcodeBrg"><a class="" href="barcodebrg.php">Barcode Barang</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeBarcodeRak"><a class="" href="barcoderak.php">Barcode Rak</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeKoreksiPlus"><a class="" href="koreksiBarang.php?p=plus">Koreksi Plus</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeKoreksiMin"><a class="" href="koreksiBarang.php?p=minus">Koreksi Minus</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeRak"><a class="" href="rak.php">Lokasi Rak</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeKategori"><a class="" href="kategori.php">Kategori</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
                  ';
            $order = '
                  <li class="sub-menu" id="activeOrder">
                      <a href="javascript:;" class="">
                          <i class="icon-tasks"></i>
                          <span>Master Order</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeTamabhOrder"><a class="" href="order.php?p=tambahOrder">Tambah Order</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeLiatPesanan"><a class="" href="order.php?p=liatPesanan">Order Sales</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeliatSemuaOrder"><a class="" href="order.php?p=liatSemuaOrder">Semua Order</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeKategori"><a class="" href="kategori.php">Kategori</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
                  ';

            $pengirim = '
                  <li class="sub-menu" id="activePengirim">
                      <a class="" href="pengirim.php">
                          <i class="fa fa-truck"></i>
                          <span>Pengirim</span>
                      </a>
                  </li>
                  ';

            $toko = '
                  <li class="sub-menu" id="activeToko">
                      <a class="" href="toko.php">
                          <i class="fa fa-users"></i>
                          <span>Toko</span>
                      </a>
                  </li>
                  ';

            $laporan = '
                  <li class="sub-menu" id="activeLaporan">
                      <a href="javascript:;" class="">
                          <i class="fa fa-file-text"></i>
                          <span>Laporan</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeLaporanKeluar"><a class="" href="laporan.php?p=LapKlr">Laporan Keluar</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeLaporanMasuk"><a class="" href="laporan.php?p=LapMsk">Laporan Masuk</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeLaporanRetur"><a class="" href="laporan.php?p=LapRtr">Laporan Retur</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeLaporanMutasi"><a class="" href="laporan.php?p=LapMTS">Laporan Mutasi</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeKartu"><a class="" href="laporan.php?p=LapKartuStok">Kartu Stock</a><i class="icon-circle-arrow-right kanan"></i></li>
                          <li id="activeLaporanLimit"><a class="" href="cekLimit.php">Cek Limit Stock</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
                  ';

            $laporanClaim = '
                  <li class="sub-menu" id="activeLaporanClaim">
                      <a class="" href="laporanClaim.php">
                          <i class="fa fa-file-text"></i>
                          <span>Laporan Claim</span>
                      </a>
                  </li>
                  ';
            $upload = '
                 <li class="sub-menu" id="activeUpload">
                      <a href="javascript:;" class="">
                          <i class="fa fa-upload"></i>
                          <span>Upload</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeUploadKoreksiSaldo"><a class="" href="laporan.php?p=LapKlr">Koreksi Saldo</a><i class="icon-circle-arrow-right kanan"></i></li>
                      </ul>
                  </li>
            ';

            $setting = '
                  <li class="sub-menu" id="activeSetting">
                      <a href="javascript:;" class="">
                          <i class="fa fa-cogs"></i>
                          <span>Setting</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li id="activeUser"><a class="" href="backup.php">Manajemen User</a><i class="icon-circle-arrow-right kanan"></i>
                          <li id="activeBackup"><a class="" href="dailyBackup.php">Backup Database</a><i class="icon-circle-arrow-right kanan"></i>
                          <li id="activeBackup"><a class="" href="backup.php">Log</a><i class="icon-circle-arrow-right kanan"></i>
                      </ul>
                  </li>
                  ';

            if ($_SESSION['level'] == 'administrator') {
                echo $transaksi;
                echo $masterBarang;
                echo $toko;
                echo $laporan;
                echo $upload;
                echo $setting;
            } elseif ($_SESSION['level'] == 'user') {
                echo $transaksi;
                echo $masterBarang;
                echo $toko;
                echo $laporan;
            } elseif ($_SESSION['level'] == 'tamu') {
                echo $transaksi;
                echo $masterBarang;
            } elseif ($_SESSION['level'] == 'claim') {
                echo $claim;
                echo $laporanClaim;
            }
            ?>

            <li class="sub-menu">
                <a href="logout.php">
                    <i class="fa fa-key"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->