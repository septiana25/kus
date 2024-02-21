<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';

require_once 'include/header.php';
require_once 'include/menu.php';


$id = isset($_GET['id']) ? $_GET['id'] : 0;
if (empty($id)) {
    echo "<script>document.location.href='barang.php'</script>";
    exit;
}

echo "<div class='div-request div-hide'>barang</div>
      <div class='div-idsaldo div-hide'>" . htmlspecialchars($id) . "</div>";

$query = "SELECT rak, brg
    FROM detail_brg
    LEFT JOIN barang USING(id_brg)
    LEFT JOIN rak USING(id_rak)
    WHERE id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row == null) {
    echo "<script>document.location.href='barang.php'</script>";
    exit;
}

?>
<style>
    .fontBold {
        font-weight: bold;
    }

    #infosaldo {
        display: flex;
        justify-content: space-between;
        font-size: 15px;
        font-weight: 600;
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
                    Detail Saldo
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
                        Detail Saldo
                    </li>

                </ul>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <div>
            <h4 class="fontBold"><?= $row['brg'] ?></h4>
            <h4 class="fontBold"><?= $row['rak'] ?></h4>
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
                            echo '<a href="#addModalDetailSaldo" role="button" class="btn btn-primary tambah" id="addDetailSaldo" data-toggle="modal"> <i class=" icon-plus"></i>Tambah Data</a>';
                        }
                        ?>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <!-- <a href="javascript:;" class="icon-remove"></a> -->
                        </span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped table-bordered" id="tabelDetailSaldo">
                            <thead>
                                <tr>
                                    <th>Tanggal Produksi</th>
                                    <th>Tahun Produksi</th>
                                    <th>Saldo</th>
                                    <?php
                                    if ($_SESSION['level'] == "administrator") {
                                        echo '<th width="8%">Action</th>';
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
            <!-- END ADVANCED TABLE widget-->
        </div>

        <!-- BEGIN MODAL TAMBAH DETAIL SALDO-->
        <div id="addModalDetailSaldo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Tambah Tahun Produksi</h3>
            </div>
            <form class="form-horizontal" id="submitDetailSaldo" action="action/saldo/saveDetail.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infosaldo"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="rak">Nama Barang</label>
                        <div class="controls">
                            <input class="span12" type="text" id="brg" name="brg" readonly>
                            <input class="span12" type="hidden" id="id" name="id" value="<?= $id ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="rak">Rak</label>
                        <div class="controls">
                            <input class="span12" type="text" id="rak" name="rak" readonly>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="tahunprod">Tahun Produksi</label>
                        <div class="controls">
                            <input class="span12" type="text" id="tahunprod" name="tahunprod" autocomplete="off" placeholder="Tahun Produksi" minlength="4" maxlength="4" onkeyup="validAngka(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="qty">QTY</label>
                        <div class="controls">
                            <input class="span12" type="number" id="qty" name="qty" autocomplete="off" placeholder="Quantity" onkeyup="validAngka(this)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="save">Save changes</button>
                </div>
            </form>
        </div>
        <!-- END MODAL TAMBAH DETAIL SALDO-->

        <!-- BEGIN MODAL TAMBAH DETAIL SALDO-->
        <div id="editModalDetailSaldo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Edit Tahun Produksi</h3>
            </div>
            <form class="form-horizontal" id="editDetailSaldo" action="action/saldo/updateTahunProduksi.php" method="POST">
                <div class="modal-body modal-full">
                    <div class="control-group">
                        <div id="infosaldo"></div>
                        <div id="pesan"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="editTahunprod">Tahun Produksi</label>
                        <div class="controls">
                            <input class="span12" type="hidden" id="editIdDetail" name="editIdDetail" readonly>
                            <input class="span12" type="text" id="editTahunprod" name="editTahunprod" autocomplete="off" placeholder="Tahun Produksi" minlength="4" maxlength="4" onkeyup="validAngka(this)">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="editQty">QTY</label>
                        <div class="controls">
                            <input class="span12" type="number" id="editQty" name="editQty" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" id="update">Update changes</button>
                </div>
            </form>
        </div>
        <!-- END MODAL TAMBAH DETAIL SALDO-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<?php require_once 'include/footer.php'; ?>

<script src="jsAction/detailsaldo.js"></script>