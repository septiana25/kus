<?php require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

$tahun          = date("Y");
$bulan          = date("m");

require_once 'include/header.php';
require_once 'include/menu.php';

//query barang
$brg = "SELECT id_brg, brg FROM barang ORDER BY brg ASC";
$brg1 = $koneksi->query($brg);
//query rak
$rak = $koneksi->query("SELECT id_rak, rak FROM rak ORDER BY rak ASC");

echo "<div class='div-request div-hide'>ekspedisi</div>";
?>

<!-- BEGIN PAGE -->
<div id="main-content">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title">
                    Ekspedisi
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        <a href="#">Ekspedisi</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        Data Ekspedisi
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
                        <!--<a href="#myModal1" role="button" class="btn btn-primary tambah" id="addTokoBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>-->
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelEkspedisi">
                            <thead>
                                <tr>
                                    <th width="20%">Flat Nomor</th>
                                    <th width="30%">Supir</th>
                                    <th>Jenis</th>
                                    <?php
                                    if ($_SESSION['level'] == "administrator") {
                                        echo '<th width="15%" class="hidden-phone">Action</th>';
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

        <!-- BEGIN MODAL TOKO-->
        <div id="myModal1" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> FORM INPUT TOKO</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitToko" action="action/toko/simpanToko.php" method="POST">
                <div class="modal-body modal-full tinggi2">
                    <div class="control-group" style="margin-bottom: 15px;">
                        <label for="cname" class="control-label"><strong>Nama Toko</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="namaToko" name="namaToko" type="text" placeholder="Input Nama Toko" onkeydown="HurufBesar(this)" />
                        </div>
                    </div>
                    <div class="control-group" style="margin-bottom: 15px;">
                        <label for="cname" class="control-label"><strong>Alamat</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <textarea class="input-xxlarge" style="width: 477px" rows="3" name="alamat" id="alamat" placeholder="Input Alamat Toko"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="pesan"></div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button class="btn btn-primary" id="simpanTokoBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL TOKO-->

        <!-- BEGIN MODAL EDIT TOKO-->
        <div id="editModalToko" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-pencil"></i> FORM EDIT TOKO</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitEditToko" action="action/toko/editToko.php" method="POST">
                <div class="modal-body modal-full tinggi2">
                    <div class="control-group" style="margin-bottom: 15px;">
                        <label for="cname" class="control-label"><strong>Nama Toko</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="editNamaToko" name="editNamaToko" type="text" placeholder="Input Nama Toko" onkeydown="HurufBesar(this)" />
                        </div>
                    </div>
                    <div class="control-group" style="margin-bottom: 15px;">
                        <label for="cname" class="control-label"><strong>Alamat</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <textarea class="input-xxlarge" style="width: 477px" rows="3" name="editAlamat" id="editAlamat" placeholder="Input Alamat Toko"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <div id="pesanEdit"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="span12 " id="editIdToko" name="editIdToko" type="hidden" />
                    <button class="btn btn-primary" id="editTokoBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL EDIT TOKO-->

        <!-- BEGIN MODAL HAPUS TOKO-->
        <div id="hapusModalToko" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA TOKO</h3>
            </div>
            <div class="modal-body">
                <p id="pesanHapus" style="color: #dc5d3a"></p>
            </div>
            <div class="modal-footer hidden">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                <button class="btn btn-danger" id="hapusTokoBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-trash"></i> Hapus</button>
            </div>
        </div>
        <!-- END MODAL HAPUS TOKO-->

        <!-- END ADVANCED TABLE widget-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/ekspedisi.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>