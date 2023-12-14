<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

require_once 'include/header.php';
require_once 'include/menu.php';
echo "<div class='div-request div-hide'>barcoderak</div>";
?>

<style>
    .modal-full {
        padding-bottom: 25vh;
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
                    Barcode Rak
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        Master Rak
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        Barcode Rak
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
                            echo '<a href="#addModalBarcode" role="button" class="btn btn-primary tambah" id="addBarcodeBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
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
                                    <th>Rak</th>
                                    <th>Action</th>
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

        <!-- BEGIN MODAL TAMBAH BARCODE RAK-->
        <div id="addModalBarcode" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Input Barcode Rak</h3>
            </div>
            <form class="form-horizontal" id="submitBarcode" action="action/barcoderak/save.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="rak">Rak</label>
                        <div class="controls">
                            <input class="span12" type="text" id="rak" name="rak" autocomplete="off" placeholder="Ketik Rak">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="barcode">Barcode</label>
                        <div class="controls">
                            <input class="span12" type="text" id="barcode" name="barcode" autocomplete="off" placeholder="Barcode Ban">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="save">Save changes</button>
                </div>
            </form>
        </div>


        <!-- END MODAL TAMBAH BARCODE RAK-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/barcoderak.js"></script>