<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'action/pomasuk/fetchScanMasukApi.php';
$tahun          = date("Y");
$bulan          = date("m");
//$bulan1          = 7;

require_once 'include/header.php';
require_once 'include/menu.php';
echo "<div class='div-request div-hide'>pomasuk</div>";

$id = $_GET['id'];

$result =  getScanMasuk($id, $koneksi);
if ($result->status == "fail") {
    echo "<script>location.href='pomasuk.php?message=Belum Ada Data Scan'</script>";
}
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
                        <table class="table">
                            <tr>
                                <th>No PO</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>
                                        <input id="nopo" name="nopo" type="text" class="span12" value="<?= $result->data[0]->suratJalan ?>" readonly="true" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Rak</th>
                                    <th>QTY</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                echo "<form method='post' action='submit.php'>";
                                foreach ($result->data as $item) {
                                    $date = new DateTime($item->tanggal_masuk);
                                    $formattedDate = $date->format('Y-m-d');
                                    echo "<tr>";
                                    echo "<input type='hidden' name='suratJLN' value='" . $item->suratJalan . "' />";
                                    echo "<input type='hidden' name='id_brg' value='" . $item->id_item . "' />";
                                    echo "<input type='hidden' name='id_rak' value='" . $item->id_rak . "' />";
                                    echo "<input type='hidden' name='tgl' value='" . $formattedDate . "' />";
                                    echo "<td> " . $item->item . " </td>";
                                    echo "<td width='10%'> " . $item->rak . " </td>";
                                    echo "<td width='4%'>
                                            <input type='text' name='qty' value='" . $item->qty . "' class='input-small'/>
                                        </td>";
                                    echo "<td width='10%'>
                                            <input type='text' name='ket' placeholder='Keterangan'/>
                                        </td>";
                                    echo "<td width='10%'>
                                            <input type='submit' class='btn btn-primary' value='Posting' />
                                        </td>";
                                    echo "</tr>";
                                }
                                echo "</form>";
                                ?>
                            </tbody>
                        </table>
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

<script src="jsAction/pomasuk.js"></script>

<script src="assets/chosen/chosen1.jquery.min.js"></script>