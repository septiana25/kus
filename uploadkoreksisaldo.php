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
                    Upload Koreksi Saldo
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
                        Upload Koreksi Saldo
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
                                <li class="active"><a href="#tab_1_1" data-toggle="tab">Data</a></li>
                                <li class=""><a href="#tab_1_2" data-toggle="tab">Form</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    <p>Data Koreksi Saldo</p>
                                    <div class="form-actions">
                                        <button class="btn btn-primary" type="button" id="checkingData"><i class="fa fa-check"></i> Check Data</button>
                                        <button class="btn btn-warning" type="button" id="processData"><i class="fa fa-cogs"></i> Prosess Koreksi</button>
                                    </div>
                                    <table class="table table-striped table-bordered" id="tabelKoreksiSaldo">
                                        <thead>
                                            <tr>
                                                <th width="10%">Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th width="10%">Lokasi Rak</th>
                                                <th width="10%">Saldo Akhir</th>
                                                <th width="12%">Status</th>
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
                                    <p>Form Upload Koreksi Saldo</p>

                                    <form class="form-horizontal" method="POST" id="submitUploadKoreksiSaldo" enctype="multipart/form-data">
                                        <div class="control-group">
                                            <label class="control-label">File CSV </label>
                                            <div class="controls">

                                                <input type="file" class="form-control" id="file-csv" placeholder="File CSV" multiple="true" name="file-csv" class="file-loading" style="width:auto;" accept=".csv" />
                                            </div>
                                            <div class="controls">
                                                <span class="label label-important">NOTE!</span>
                                                <span>
                                                    File Harus Berformat CSV
                                                </span>
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
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->

    <?php require_once 'include/footer.php'; ?>

    <script src="jsAction/uploadkoreskisaldo.js"></script>