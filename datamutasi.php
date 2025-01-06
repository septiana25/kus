<?php
/* dataMutasi */
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
                    Daftar Item Mutasi
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
                        Daftar Item Mutasi
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
                            echo '<a href="#myModalMutasiRak" role="button" class="btn btn-primary tambah" id="modalProsesMutasi" data-toggle="modal"> <i class="fa fa-random" aria-hidden="true"></i> Proses Mutasi</a>';
                        }
                        ?>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelDataMutasi">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Rak</th>
                                    <th>Tahun Produksi</th>
                                    <th>Qty</th>
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

        <!-- BEGIN MODAL CLOSE Mutasi-->
        <div id="modalApproved" class="modal modal-form hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-pencil"></i> Batalkan Data Mutasi</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitCloseMutasi" action="action/return/updatemutasi.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group ">
                        <label for="brg" class="control-label"><strong>Nama Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="brg" name="brg" type="text" readonly />
                            <input type="hidden" id="id_mutasi" name="id_mutasi" />
                        </div>
                    </div>
                    <div class="control-group ">
                        <label for="rak" class="control-label"><strong>Rak Tujuan</strong>
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
                    <button class="btn btn-primary" id="simpanCloseMutasiBtn" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL CLOSE Mutasi-->

        <!-- BEGIN MODAL PROSESS Mutasi-->
        <div id="myModalMutasiRak" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Proses Mutasi</h3>
            </div>
            <form class="form-horizontal" id="submitProsesMutasi" action="action/return/prosesmutasi.php" method="POST">
                <div class="modal-body modal-full">
                    <p id="pesanHapus" style="color: #dc5d3a">Semua data yang ada akan diproses</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="prosesMutasiBtn">Proses</button>
                </div>
            </form>
        </div>
        <!-- END MODAL PROSESS Mutasi-->

    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/datamutasi.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>