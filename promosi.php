<?php require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

require_once 'include/header.php';
require_once 'include/menu.php';

echo "<div class='div-request div-hide'>promosi</div>";
?>

<!-- BEGIN PAGE -->
<div id="main-content">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title">
                    Data Promosi
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        <a href="#">Promosi</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        Data Promosi
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
                        <a href="#addModalPromosi" role="button" class="btn btn-primary tambah" id="addBtnModalEkspedisi" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelEkspedisi">
                            <thead>
                                <tr>
                                    <th width="10%">Divisi</th>
                                    <th>Item</th>
                                    <th width="30%">Jenis</th>
                                    <th width="10%">Saldo</th>
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

        <!-- BEGIN MODAL Tambah Ekspedisi-->
        <div id="addModalPromosi" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Tambah Promosi</h3>
            </div>
            <form class="form-horizontal" id="submitAddPromosi" action="action/promosi/simpanpromosi.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infoSO"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="divisi">Divisi Item</label>
                        <div class="controls">
                            <input class="span12" type="text" id="divisi" name="divisi" autocomplete="off" placeholder="Divisi Item" onkeydown="HurufBesar(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="item">Item</label>
                        <div class="controls">
                            <input class="span12" type="text" id="item" name="item" autocomplete="off" placeholder="Item Promosi" onkeydown="HurufBesar(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="jenis">Jenis</label>
                        <div class="controls">
                            <input class="span12" type="text" id="jenis" name="jenis" autocomplete="off" placeholder="Jenis Promosi" onkeydown="HurufBesar(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="note">Note</label>
                        <div class="controls">
                            <textarea class="span12" id="note" name="note" placeholder="Note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="update">Simpan</button>
                </div>
            </form>
        </div>
        <!-- END MODAL Tambah Ekspedisi-->

        <!-- BEGIN MODAL EDIT Sales Order-->
        <div id="editModalKoreksiSaldo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Edit Sales Order</h3>
            </div>
            <form class="form-horizontal" id="submitEditSalesOrder" action="action/upload/updatesalesorder.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infoSO"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="nopol">Ekspedisi</label>
                        <div class="controls">
                            <input class="span12" type="hidden" id="id_so" name="id_so" readonly>
                            <input class="span12" type="text" id="nopol" name="nopol" autocomplete="off" placeholder="Plat Nomor">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="kode_toko">Kode Toko</label>
                        <div class="controls">
                            <input class="span12" type="text" id="kode_toko" name="kode_toko" autocomplete="off" placeholder="Kode Toko">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="kdbrg">Kode Barang</label>
                        <div class="controls">
                            <input class="span12" type="text" id="kdbrg" name="kdbrg" autocomplete="off" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="qty">QTY</label>
                        <div class="controls">
                            <input class="span12" type="number" id="qty" name="qty" autocomplete="off" placeholder="Quantiti" onkeyup="validAngka(this)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="update">Simpan</button>
                </div>
            </form>
        </div>
        <!-- END MODAL EDIT Sales Order-->

        <!-- BEGIN MODAL HAPUS Sales Order-->
        <div id="deleteModalKoreksiSaldo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Hapus Sales Order</h3>
            </div>
            <form class="form-horizontal" id="submitDeleteSalesOrder" action="action/upload/deletesalesorder.php" method="POST">
                <div class="modal-body modal-full">
                    <p id="pesanHapus" style="color: #dc5d3a"></p>
                    <input class="span12" type="hidden" id="hapusid" name="hapusid" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="update">Hapus</button>
                </div>
            </form>
        </div>
        <!-- END MODAL HAPUS Sales Order-->
        <!-- BEGIN MODAL DISABLE Sales Order-->
        <div id="disableaccess" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">WARNING</h3>
            </div>
            <div class="modal-body modal-full">
                <p id="pesanHapus" style="color: #dc5d3a">DALAM PENGEMBANGAN</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

            </div>
        </div>
        <!-- END MODAL HAPUS Sales Order-->

        <!-- END ADVANCED TABLE widget-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/promosi.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>