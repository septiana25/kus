<?php
require_once 'function/session.php';
require_once 'include/header.php';
require_once 'include/menu.php';

$nopol = trim($koneksi->real_escape_string($_GET['expedition']));
if (!isset($nopol) || empty($nopol)) {
    echo "<script>document.location.href='uploadsalesorder.php'</script>";
    exit;
}

echo "<div class='div-nopol div-hide'>" . $nopol . "</div>";
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
                        <a href="#">Upload</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active">
                        Detail Proses Seles Order
                    </li>

                </ul>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <div id="hapus-pesan"></div>
        <!-- BEGIN ADVANCED TABLE widget-->
        <div class="form-actions">
            <!-- disabled="disabled" -->
            <button class="btn btn-primary" type="button" id="printSo"><i class="fa fa-print"></i> Cetak SO</button>
            <button class="btn btn-success" type="button" id="printSoExcel"><i class="fa fa-print"></i> Cetak SO (Excel)</button>
            <button class="btn btn-warning" type="button" id="processKeluar"><i class="fa fa-cogs"></i> Proses Keluar</button>
        </div>
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
                        <table class="table table-striped table-bordered" id="tabelDetailSO">
                            <thead>
                                <tr>
                                    <th width="5%">Supir</th>
                                    <th width="10%">No Faktur</th>
                                    <th width="20%">Toko</th>
                                    <th>Barang</th>
                                    <th width="5%">Rak</th>
                                    <th width="5%">Tahun</th>
                                    <th width="5%">QTY</th>
                                    <?php
                                    if ($_SESSION['level'] == "administrator") {
                                        echo '<th width="5%" class="hidden-phone">Action</th>';
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

        <!-- BEGIN MODAL EDIT Sales Order-->
        <div id="editModalQtySO" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Edit QTY Sales Order</h3>
            </div>
            <form class="form-horizontal" id="submitEditQtyDetailSO" action="action/upload/updateqtydetailso.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infoSO"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="qtybrg">Barang</label>
                        <div class="controls">
                            <input class="span12" type="hidden" id="qtyid_pro" name="qtyid_pro" readonly>
                            <input class="span12" type="text" id="qtybrg" name="qtybrg" readonly>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="qtytahunprod">Tahun</label>
                        <div class="controls">
                            <input class="span12" type="number" id="qtytahunprod" name="qtytahunprod" readonly>
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

        <!-- BEGIN MODAL EDIT Ekspedisi Sales Order-->
        <div id="editModalEkspedisiSO" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Edit QTY Sales Order</h3>
            </div>
            <form class="form-horizontal" id="submitEditEkspedisiDetailSO" action="action/upload/updateekspedisidetailso.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infoSO"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="noFaktur">No Faktur</label>
                        <div class="controls">
                            <input class="span12" type="text" id="noFaktur" name="noFaktur" autocomplete="off" placeholder="No Faktur" onkeyup="validAngka(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="ekspedisi">Ekspedisi</label>
                        <input class="span12" type="hidden" id="ekspedisiid_pro" name="ekspedisiid_pro" readonly>
                        <div class="controls">
                            <select id="nopol" name="nopol" class="chosen-select" data-placeholder="Pilih Ekspedisi...">
                                <option value="">Pilih Ekspedisi</option>
                                <?php
                                $ResultEkspedisi = $koneksi->query("SELECT nopol, supir FROM ekspedisi ORDER BY supir ASC");
                                while ($ekspedisi = $ResultEkspedisi->fetch_assoc()) {
                                    echo "<option value='$ekspedisi[nopol]'>$ekspedisi[supir]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="update">Simpan</button>
                </div>
            </form>
        </div>
        <!-- END MODAL EDIT Ekspedisi Sales Order-->

        <!-- BEGIN MODAL EDIT Sales Order-->
        <div id="editModalTahunSO" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Edit QTY Sales Order</h3>
            </div>
            <form class="form-horizontal" id="submitEditTahunDetailSO" action="action/upload/updatetahundetailso.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infoSO"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="tahunbrg">Barang</label>
                        <div class="controls">
                            <input class="span12" type="hidden" id="tahunid_pro" name="tahunid_pro" readonly>
                            <input class="span12" type="text" id="tahunbrg" name="tahunbrg" readonly>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="tahunprod">Tahun</label>
                        <div class="controls">
                            <input class="span12" type="number" id="tahunprod" name="tahunprod" autocomplete="off" placeholder="Tahun" onkeyup="validAngka(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="tahunqty">QTY</label>
                        <div class="controls">
                            <input class="span12" type="number" id="tahunqty" name="tahunqty" readonly>
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

<script src="jsAction/detailprosesssalesorder.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>