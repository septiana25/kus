<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'action/class/pomasuk.php';

require_once 'include/header.php';
require_once 'include/menu.php';

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<script>location.href='pomasuk.php?message=Belum Ada Data Scan'</script>";
}

$id = $_GET["id"];
echo "<div class='div-request div-hide'>pomasuk</div>
        <div class='div-request-id div-hide' id='idPO'>" . $id . "</div>
";

function getPoMasusById($id, $koneksi)
{
    $pomasuk = new PoMasuk($koneksi);
    $result = $pomasuk->getPoMasukById($id);
    return $result;
}

$result = getPoMasusById($id, $koneksi);
if ($result->num_rows == 0) {
    echo "<script>location.href='pomasuk.php?message=Belum Ada Data Scan'</script>";
}
$fetchedData = $result->fetch_array();
?>
<!-- BEGIN PAGE -->
<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title">
                    PO Masuk
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <span class="divider">/</span>
                    </li>
                    <li>
                        Transaksi Barang
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
                        <div id="pesan"></div>
                        <table class="table">
                            <tr>
                                <th>No PO</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>
                                        <input id="nopo" name="nopo" type="text" class="span12" value="<?= $fetchedData['suratJln'] ?>" readonly="true" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="dataPosting"></div>
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

<script src="jsAction/pomasukdetail.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>