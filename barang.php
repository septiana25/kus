<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

require_once 'include/header.php';
require_once 'include/menu.php';
echo "<div class='div-request div-hide'>barang</div>";
?>

<!-- BEGIN PAGE -->
<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title">
                    Data Barang
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        Master Barang
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        Data Barang
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
                            echo '<a href="#addModalMasuk" role="button" class="btn btn-primary tambah" id="addBarangBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
                        }
                        ?>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                        <div class="actions">
                            <a href="#modalCariData" id="exportLapBrgExcelBtn" role="button" class="btn btn-info" data-toggle="modal"><i class="fa fa-search"></i> Export Excel</a>
                            <!-- <a role="button" class="btn btn-info" id="exportLapBrgExcelBtn"> <i class="fa fa-file-excel-o"></i> Export Excel</a> -->

                            <a href="#" role="button" class="btn btn-warning" id="reload" data-toggle="modal"><i class="fa fa-refresh"></i> Reload</a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelBarang">
                            <thead>
                                <tr>
                                    <th width="8%">No Urut</th>
                                    <th width="10%">Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th width="9%">Lokasi Rak</th>
                                    <th width="11%">Kategori</th>
                                    <th width="8%">Saldo Awal</th>
                                    <th width="9%">Saldo Akhir</th>
                                    <?php
                                    if ($_SESSION['level'] == "administrator") {
                                        echo '<th width="8%" class="hidden-phone">Action</th>';
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
            <!-- END ADVANCED TABLE widget-->
        </div>

        <!-- BEGIN MODAL TAMBAH BARANG-->
        <div id="addModalMasuk" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign-alt"></i> FORM INPUT DATA BARANG</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitBarang" action="action/barang/simpanBarang.php" method="POST">
                <div class="modal-body modal-full tinggi">
                    <div class="control-group ">
                        <label class="control-label"><strong>Kategori</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="kategori" name="id_kat" class="choiceChosen" data-placeholder="Pilih Kategori...">
                                <option value=""></option>
                                <?php
                                $kat = $koneksi->query("SELECT id_kat, kat FROM kat ORDER BY kat ASC");
                                while ($kat1 = $kat->fetch_array()) {
                                    echo "<option value='$kat1[0]'>$kat1[1]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="NOurut" class="control-label"><strong>Nomor Urut</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="NOurut" name="NOurut" type="text" placeholder="Nomor Urut" maxlength="5" onkeydown="validAngka(this)" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="KDbarang" class="control-label"><strong>Kode Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="KDbarang" name="KDbarang" type="text" placeholder="Kode Barang" maxlength="20" onkeydown="upperCaseF(this)" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="barang" class="control-label"><strong>Nama Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="barang" name="barang" type="text" placeholder="Nama Barang" onkeydown="upperCaseF(this)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="pesan"></div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button class="btn btn-primary" id="simpanBarangBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL TAMBAH BARANG-->

        <!-- BEGIN MODAL EDIT BARANG-->
        <div id="editModalBarang" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-edit-sign"></i> FORM EDIT DATA BARANG</h3>
            </div>
            <form class="cmxform form-horizontal" id="editBarangForm" action="action/barang/editBarang.php" method="POST">
                <div class="modal-body modal-full tinggi">
                    <div class="control-group ">
                        <label class="control-label"><strong>Kategori</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="editKategori" name="id_kat" class="span12" data-placeholder="Pilih Kategori...">
                                <option value=""></option>
                                <?php
                                $kat = $koneksi->query("SELECT id_kat, kat FROM kat ORDER BY kat ASC");
                                while ($kat1 = $kat->fetch_array()) {
                                    echo "<option value='$kat1[0]'>$kat1[1]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="editNOurut" class="control-label"><strong>Nomor Urut</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="editNOurut" name="editNOurut" type="text" placeholder="Nomor Urut" maxlength="5" onkeydown="validAngka(this)" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="editKDbarang" class="control-label"><strong>Kode Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="editKDbarang" name="editKDbarang" type="text" placeholder="Kode Barang" maxlength="20" onkeydown="upperCaseF(this)" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="editBarang" class="control-label"><strong>Nama Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="editBarang" name="editBarang" type="text" placeholder="Nama Barang" onkeydown="upperCaseF(this)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="edit-pesan"></div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button class="btn btn-primary" id="editBarangBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL EDIT BARANG-->

        <!-- BEGIN MODAL HAPUS MASUK-->
        <div id="hapusModalBarang" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA BARANG</h3>
            </div>
            <div class="modal-body">
                <p id="pesanHapus" style="color: #dc5d3a"></p>

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                <button class="btn btn-danger" id="hapusBarangBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-trash"></i> Hapus</button>
            </div>
        </div>
        <!-- END MODAL HAPUS MASUK-->

        <!-- BEGIN MODAL CARI DATA LAMA-->
        <div id="modalCariData" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> EXPORT EXCEL DATA BARANG</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitLapExcelBrg" action="#" method="POST">
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

                    <button class="btn btn-primary" id="simpanExportBrgBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-search"></i> Cari</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL CARI DATA LAMA-->

    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/barang.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>