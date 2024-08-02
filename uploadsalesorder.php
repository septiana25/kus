<?php require_once 'function/koneksi.php';
require_once 'function/session.php';

require_once 'include/header.php';
require_once 'include/menu.php';

?>

<!-- BEGIN PAGE -->
<div id="main-content">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title">
                    Upload Sales Order
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        <a href="#">Upload</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        Upload Seles Order
                    </li>

                </ul>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->

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

                        <!-- BEGIN FORM LAPORAN MASUK-->
                        <div class="tabbable custom-tab">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1_1" data-toggle="tab">Belum Proses</a></li>
                                <li class=""><a href="#tab_1_2" data-toggle="tab">Hasil Proses</a></li>
                                <li class=""><a href="#tab_1_3" data-toggle="tab">Form</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    <p>Data Sales Order</p>
                                    <div class="form-actions">
                                        <!-- disabled="disabled" -->
                                        <a href="#addModalSalesOrder" role="button" class="btn btn-primary tambah" id="addBtnModalSO" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>
                                        <button class="btn btn-success" type="button" id="checkingData"><i class="fa fa-check"></i> Check Data</button>
                                        <button class="btn btn-warning" type="button" id="processData"><i class="fa fa-cogs"></i> Prosess Sales Order</button>
                                    </div>
                                    <table class="table table-striped table-bordered" id="tabelSalesOrder">
                                        <thead>
                                            <tr>
                                                <th width="10%">Ekspedisi</th>
                                                <th width="25%">Toko</th>
                                                <th width="10%">Faktur</th>
                                                <th>Nama Barang</th>
                                                <th width="5%">QTY</th>
                                                <th width="5%">Sisa</th>
                                                <th width="5%">Status</th>
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
                                <div class="tab-pane" id="tab_1_2">
                                    <p>Hasil Proses Sales Order</p>
                                    <table class="table table-striped table-bordered" id="tabelProsessSalesOrder">
                                        <thead>
                                            <tr>
                                                <th>Ekspedisi</th>
                                                <th width="10%">Tanggal Proses</th>
                                                <th width="5%">Faktur</th>
                                                <th width="25%">Status</th>
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
                                <div class="tab-pane" id="tab_1_3">
                                    <p>Form Upload Sales Order</p>

                                    <form class="form-horizontal" method="POST" id="submitUploadSalesOrder" enctype="multipart/form-data">
                                        <div class="control-group">
                                            <label class="control-label">File CSV </label>
                                            <div class="controls">
                                                <input class="span12" type="hidden" id="type" name="type" value="3" readonly>
                                                <input type="file" class="form-control" id="file-csv" placeholder="File CSV" multiple="true" name="file-csv" class="file-loading" style="width:auto;" accept=".csv" />
                                            </div>
                                            <div class="controls">
                                                <span class="label label-important">NOTE!</span>
                                                <span>
                                                    File Harus Berformat CSV
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div class="controls">
                                                <a class="btn btn-info" href="sample/sample_sales_order.xlsx" target="_blank">Sample Format Upload</a>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button class="btn btn-success" type="submit" id="uploadFile"><i class="fa fa-upload"></i> Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- END BEGIN FORM LAPORAN MASUK-->
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
        </div>

        <!-- BEGIN MODAL Add Sales Order-->
        <div id="addModalSalesOrder" class="modal modal-form hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1" class="center"><i class="icon-plus-sign"></i> INPUT SALES ORDER</h3>
            </div>
            <form class="cmxform form-horizontal" id="submitDataSO" action="action/upload/saveDataSO.php" method="POST">
                <div class="modal-body modal-full tinggi2">
                    <div class="control-group" style="margin-bottom: 15px;">
                        <label class="control-label"><strong>Ekspedisi</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="nopol" name="nopol" class="chosen-select" data-placeholder="Pilih Ekspedisi...">
                                <option value=""></option>
                                <?php
                                $ResultEkspedisi = $koneksi->query("SELECT nopol, supir FROM ekspedisi ORDER BY supir ASC");
                                while ($ekspedisi = $ResultEkspedisi->fetch_assoc()) {
                                    echo "<option value='$ekspedisi[nopol]'>$ekspedisi[supir]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <td width="1%">
                                <div class="control-group" style="margin-bottom: 0px;">
                                    <label class="control-label"><strong>No Faktur</strong>
                                        <p class="titik2">:</p>
                                    </label>
                                    <?php
                                    $carSeriPJK = $koneksi->query("SELECT seriPajak FROM tblSeriPajak");
                                    $rowPJK = $carSeriPJK->fetch_array();
                                    ?>
                                    <div class="controls">
                                        <input type="text" class="input-small" id="awalFaktur" name="awalFaktur" value="<?php echo $rowPJK[0]; ?>" readonly="true">
                                    </div>
                                </div>
                            </td width="50%">
                            <td>
                                <div class="control-group" style="margin-bottom: 0px;">
                                    <input class="span12" id="noFaktur" name="noFaktur" type="text" placeholder="Delapan Digit Terakhir" onkeyup="validAngka(this)" minlength="8" maxlength="8" />
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div class="control-group" style="margin-bottom: 15px;">
                        <label class="control-label"><strong>Toko</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="kode_toko" name="kode_toko" class="chosen-select" data-placeholder="Pilih Toko...">
                                <option value=""></option>
                                <?php
                                //query barang
                                $toko = "SELECT kode_toko, toko FROM toko WHERE kode_toko IS NOT NULL ORDER BY toko ASC";
                                $toko1 = $koneksi->query($toko);
                                while ($toko2 = $toko1->fetch_assoc()) {
                                    echo "<option value='$toko2[kode_toko]'>$toko2[toko]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group" style="margin-bottom: 15px;">
                        <label class="control-label"><strong>Nama Barang</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <select id="kdbrg" name="kdbrg" class="chosen-select" data-placeholder="Pilih Type Ban...">
                                <option value=""></option>
                                <?php
                                //query barang
                                $brg = "SELECT brg, kdbrg FROM barang ORDER BY brg ASC";
                                $brg1 = $koneksi->query($brg);
                                while ($brg2 = $brg1->fetch_assoc()) {
                                    echo "<option value='$brg2[kdbrg]'>$brg2[kdbrg] $brg2[brg]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group" style="margin-bottom: 15px;">
                        <label for="cname" class="control-label"><strong>Qty</strong>
                            <p class="titik2">:</p>
                        </label>
                        <div class="controls">
                            <input class="span12 " id="qty" name="qty" type="text" placeholder="Quantity" onkeyup="validAngka(this)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="pesan"></div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button class="btn btn-primary" type="submit" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-floppy-o"></i> Simpan</button>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- END MODAL Add Sales Order-->

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
                        <label class="control-label" for="editNopol">Ekspedisi</label>
                        <div class="controls">
                            <input class="span12" type="hidden" id="id_so" name="id_so" readonly>
                            <input class="span12" type="text" id="editNopol" name="editNopol" autocomplete="off" placeholder="Plat Nomor">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="editKodeToko">Kode Toko</label>
                        <div class="controls">
                            <input class="span12" type="text" id="editKodeToko" name="editKodeToko" autocomplete="off" placeholder="Kode Toko">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="editKdbrg">Kode Barang</label>
                        <div class="controls">
                            <input class="span12" type="text" id="editKdbrg" name="editKdbrg" autocomplete="off" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="editQty">editQTY</label>
                        <div class="controls">
                            <input class="span12" type="number" id="editQty" name="editQty" autocomplete="off" placeholder="Quantiti" onkeyup="validAngka(this)">
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
                <p id="pesanHapus" style="color: #dc5d3a">DATA SUDAH ADA YANG DICEK/DIPROSES. TIDAK BISA UBAH</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

            </div>
        </div>
        <!-- END MODAL HAPUS Sales Order-->
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->

    <?php require_once 'include/footer.php'; ?>

    <script src="jsAction/uploadsalesorder.js"></script>
    <script src="assets/chosen/chosen1.jquery.min.js"></script>