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
                    Promosi Keluar
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
                        Promosi Keluar
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
                        <a href="#addModalPromosi" role="button" class="btn btn-primary tambah" id="addBtnModalPromosiKeluar" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                        <a href="action/promosi/exportpromosikeluar.php" class="btn btn-success tambah"><i class="icon-file-excel"></i> Export Excel</a>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelPromosiKeluar">
                            <thead>
                                <tr>
                                    <th width="10%">No Transaksi</th>
                                    <th width="5%">Divisi</th>
                                    <th width="10%">Sales</th>
                                    <th width="20%">Toko</th>
                                    <th>Item</th>
                                    <th width="10%">Qty</th>
                                    <th width="6%">Tanggal</th>
                                    <th width="5%" class="hidden-phone">Action</th>
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

        <!-- BEGIN MODAL Tambah PromosiKeluar-->
        <div id="addModalPromosi" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Tambah Promosi</h3>
            </div>
            <form class="form-horizontal" id="submitAddPromosi" action="action/promosi/simpanpromosikeluar.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infoSO"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="norek">No Transaksi</label>
                        <div class="controls">
                            <input class="span4" type="text" id="noAwal" name="noAwal" readonly>
                            <input class="span8" type="text" id="noAkhir" name="noAkhir" autocomplete="off" placeholder="3 Digit Terakhir" onkeyup="validAngka(this)" minlength="3" maxlength="3">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="divisi">Divisi</label>
                        <div class="controls">
                            <select id="divisi" name="divisi" class="span12">
                                <option value="">Pilih Divisi...</option>
                                <option value="KUS">KUS</option>
                                <option value="KTA">KTA</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ">
                        <label class="control-label" for="toko">Toko</label>
                        <div class="controls">
                            <select id="toko" name="toko" class="span12 chosen-select" data-placeholder="Pilih Toko...">
                                <option value=""></option>
                                <?php
                                $toko = $koneksi->query("SELECT id_toko, toko, alamat FROM toko ORDER BY toko ASC");
                                while ($row = $toko->fetch_assoc()) {
                                    echo "<option value='$row[id_toko]'>$row[toko] - $row[alamat]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="sales">Sales</label>
                        <div class="controls">
                            <input class="span12" type="text" id="sales" name="sales" placeholder="Sales" onkeyup="HurufBesar(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="item">Item</label>
                        <div class="controls">
                            <select class="span12" id="item" name="item">
                                <option value="">Pilih Ukuran..</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="qty">Qty</label>
                        <div class="controls">
                            <input class="span12" type="text" id="qty" name="qty" autocomplete="off" placeholder="Quantiti" onkeyup="validAngka(this)">
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
        <!-- END MODAL Tambah PromosiKeluar-->

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
                        <label class="control-label" for="nopol">PromosiKeluar</label>
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
                        <label class="control-label" for="qtyEdit">Qty</label>
                        <div class="controls">
                            <input class="span12" type="number" id="qtyEdit" name="qtyEdit" autocomplete="off" placeholder="Quantiti" onkeyup="validAngka(this)">
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
        <!-- BEGIN MODAL PRINT NOTA -->
        <div id="printNota" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Print Bukti Terima</h3>
            </div>
            <div class="modal-body modal-full">
                <h3 id="noNota" style="color: #dc5d3a"></h3>
                <input class="span12" type="hidden" id="noTrans" name="noTrans" readonly>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary" type="button" id="printNotaBtn">Print</button>
            </div>

        </div>
        <!-- END MODAL PRINT NOTA-->

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

<script src="jsAction/promosikeluar.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>