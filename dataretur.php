<?php
/* dataretur */
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

require_once 'include/header.php';
require_once 'include/menu.php';
echo "<div class='div-request div-hide'>retur</div>";
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
                    Daftar Item Retur
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
                        Daftar Item Retur
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
                            echo '<a href="#addModalMasuk" role="button" class="btn btn-primary tambah" id="addReturBtnModal" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
                        }
                        ?>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelDataRetur">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Rak</th>
                                    <th>Qty</th>
                                    <th>User</th>
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

        <!-- BEGIN MODAL TAMBAH BARANG  RETUR-->
        <div id="addModalMasuk" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign-alt"></i> FORM INPUT ITEM RETUR</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitItemReur" action="action/return/simpanitemretur.php" method="POST">
                <div class="modal-body modal-full tinggi">
                    <div class="control-group ">
                        <label class="control-label"><strong>Kategori</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="barang" name="barang" class="choiceChosen" data-placeholder="Pilih Barang...">
                                <option value=""></option>
                                <?php
                                $barang = $koneksi->query("SELECT id_brg, kdbrg, brg FROM barang ORDER BY brg ASC");
                                while ($rowBrg = $barang->fetch_array()) {
                                    echo "<option value='$rowBrg[0]'> $rowBrg[1] - $rowBrg[2]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ">
                        <label class="control-label"><strong>Rak</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="addrak" name="addrak" class="choiceChosen" data-placeholder="Pilih Rak...">
                                <option value=""></option>
                                <?php
                                $rak = $koneksi->query("SELECT id_rak, rak FROM rak ORDER BY rak ASC");
                                while ($rowRak = $rak->fetch_array()) {
                                    echo "<option value='$rowRak[0]'> $rowRak[1]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="addqty" class="control-label"><strong>Qty</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="addqty" name="addqty" type="text" placeholder="Quantity" maxlength="5" onkeydown="validAngka(this)" />
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
        <!-- END MODAL TAMBAH BARANG RETUR-->

        <!-- BEGIN MODAL CLOSE RETUR-->
        <div id="modalApproved" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-pencil"></i> CLOSE DATA RETUR</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitCloseRetur" action="action/return/updateRetur.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group ">
                        <label for="brg" class="control-label"><strong>Nama Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="brg" name="brg" type="text" readonly />
                            <input type="hidden" id="id_retur" name="id_retur" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="rak" class="control-label"><strong>Rak</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="rak" name="rak" type="text" readonly />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="qty" class="control-label"><strong>QTY</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="qty" name="qty" type="text" readonly />
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="pesan"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="simpanCloseReturBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL CLOSE RETUR-->

    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/dataretur.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>