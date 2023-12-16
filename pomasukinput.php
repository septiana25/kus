<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

require_once 'include/header.php';
require_once 'include/menu.php';
echo "<div class='div-request div-hide'>pomasuk</div>";
?>

<style>
    .form-horizontal .width-label {
        width: 176px !important;
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
                    Tambah PO Masuk
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        Transaksi
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        PO Masuk
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
                        <form class="form-horizontal" id="submitPoMasuk" action="action/pomasuk/save.php" method="POST">
                            <div class="control-group">
                                <div id="pesan"></div>
                            </div>
                            <div class="control-group" style="margin-bottom: 0px;">
                                <label class="control-label width-label" for="tgl">Tanggal</label>
                                <div class="input-append date datepicker" id="dp3" data-date="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd">
                                    <input id="tgl" name="tgl" class="input-xlarge" size="16" type="text" value="<?php echo date("Y-m-d") ?>" readonly="true">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="nopo">Surat Jalan</label>
                                <div class="controls">
                                    <input class="span12" type="text" id="nopo" name="nopo" onkeyup="convertToUpperCase(this)" autocomplete="off" placeholder="Surat Jalan">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="nopol">Plat Nomor</label>
                                <div class="controls">
                                    <input class="span12" type="text" id="nopol" name="nopol" onkeyup="convertToUpperCase(this)" autocomplete="off" placeholder="Ketik Ukuran Ban">
                                </div>
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
                                    <input class="span12" type="number" id="qty" name="qty" autocomplete="off" placeholder="Quantiti Ban">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="note">Keterangan</label>
                                <div class="controls">
                                    <input class="span12" type="text" id="note" name="note" onkeyup="convertToUpperCase(this)" autocomplete="off" placeholder="Keterangan">
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

<script src="jsAction/pomasukinput.js"></script>