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

<!-- BEGIN PAGE -->
<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title">
                    Tambah Barcode Barang
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
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <form class="form-horizontal" id="submitBarcode" action="action/barcodebrg/save.php" method="POST">
                            <div class="control-group">
                                <div id="pesan"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="item">Barang</label>
                                <div class="controls">
                                    <input class="span12" type="text" id="item" name="item" autocomplete="off" placeholder="Ketik Ukuran Ban">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="qty">QTY</label>
                                <div class="controls">
                                    <input class="span12" type="text" id="qty" name="qty" autocomplete="off" placeholder="Quantiti Ban">
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label">Pilih Satuan</label>
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
                            <div class="control-group">
                                <label class="control-label" for="barcode">Barcode</label>
                                <div class="controls">
                                    <input class="span12" type="text" id="barcode" name="barcode" autocomplete="off" placeholder="Barcode Ban">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success" id="save"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </form>
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

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/barcodebrginput.js"></script>