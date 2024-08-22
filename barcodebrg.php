<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

require_once 'include/header.php';
require_once 'include/menu.php';
echo "<div class='div-request div-hide'>barcodebrg</div>";
?>
<style>
    @media screen and (max-width: 631px) {
        .d-none-mobile {
            display: none;
        }
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
                    Barcode Barang
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
                        Barcode Barang
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
                            echo '<a href="barcodebrgInput.php" role="button" class="btn btn-primary tambah"> <i class=" icon-plus"></i>Tambah Data</a>';
                        }
                        ?>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelBarcode">
                            <thead>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>QTY</th>
                                    <th class="d-none-mobile">Action</th>
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

        <!-- BEGIN MODAL EDIT BARCODE BARANG-->
        <div id="editModalBarcodebrg" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-pencil"></i> FORM EDIT BARCODE BARANG</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitBarcodeBarang" action="action/barcodebrg/updateBarcode.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group ">
                        <label for="barcodebrg" class="control-label"><strong>Barcode Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="barcodebrg" name="barcodebrg" type="text" placeholder="Barcode Barang" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="brg" class="control-label"><strong>Nama Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="brg" name="brg" type="text" readonly />
                            <input type="hidden" id="id_brg" name="id_brg" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label class="control-label"><strong>Pilih Satuan</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select tabindex="0" id="satuan" name="satuan" class="span12" data-placeholder="Choose a Category" tabindex="1">
                                <option value="">Pilih Satuan...</option>
                                <option value="koli">Koli</option>
                                <option value="kardus">Kardus</option>
                                <option value="karung">Karung</option>
                                <option value="set">SET</option>
                                <option value="pcs">PCS</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="qty" class="control-label"><strong>QTY Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="qty" name="qty" type="number" placeholder="QTY Barang" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="pesan"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="simpanBarcodebrgBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL EDIT BARCODE BARANG-->

        <!-- BEGIN MODAL HAPUS BARCODE BARANG-->
        <div id="hapusModalBarcodebrg" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="center" id="myModalLabel1"><i class="icon-trash"></i> HAPUS DATA BARCODE BARANG</h3>
            </div>
            <form class="form-horizontal" id="submitDeleteBarcodebrg" action="action/barcodebrg/deleteBarcode.php" method="POST">
                <div class="modal-body modal-full">
                    <p id="pesanHapus" style="color: #dc5d3a"></p>
                    <input type="hidden" id="hapusid" name="hapusid" />
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                    <button class="btn btn-danger" id="hapusBarangBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-trash"></i> Hapus</button>
                </div>
            </form>
        </div>
        <!-- END MODAL HAPUS BARCODE BARANG-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/barcodebrg.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>