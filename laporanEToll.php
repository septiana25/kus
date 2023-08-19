<?php require_once 'function/koneksi.php';
      require_once 'function/setjam.php';
      require_once 'function/session.php';

      $tahun          = date("Y");
      $bulan          = date("m");

      $cek_saldo = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
      if ($cek_saldo->num_rows >=1 ) {

      require_once 'include/header.php';
      require_once 'include/menu.php';
        echo "<div class='div-request div-hide'>laporanEToll</div>";
?>

      <!-- BEGIN PAGE -->  
      <div id="main-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <h3 class="page-title">
                     Laporan Perfaktur
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Laporan</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Laporan Perfaktur
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
                      <!-- BEGIN FORM-->
                      <form class="form-horizontal" action="action/etoll/viewLaporanEToll.php" method="POST" id="submitLaporanEToll">
                      <div class="control-group">
                            <label class="control-label">No E-Toll</label>
                            <div class="controls">
                                <select id="noEToll" name="noEToll" class="chosen-select " data-placeholder="Pilih No E-Toll" tabindex="1">
                                  <option value=""></option>
                                  <?php
                                  $noToll = "SELECT * FROM tblEToll ORDER BY no_toll ASC";
                                  $resnoToll = $koneksi->query($noToll);
                                  while ($rowToll = $resnoToll->fetch_array()) {
                                    echo "<option value='$rowToll[0]'>$rowToll[1] $rowToll[2]</option>";
                                  }
                                  ?>
                                </select>
                            </div>
                        </div>
<!--                         <div class="control-group">
                            <label class="control-label">Pilih Bulan</label>
                            <div class="controls">
                                <select id="bulan" name="bulan" class="span6 " data-placeholder="Choose a Category" tabindex="1" >
                                    <option value="">Pilih Bulan...</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Pilih Tahun</label>
                            <div class="controls">
                                <select id="tahun" name="tahun" class="span6 " data-placeholder="Choose a Category" tabindex="1">
                                    <option value="">Pilih Tahun...</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                        </div> -->


                          <div class="form-actions">
                              <!-- <button type="button" class="btn">Cancel</button> -->
                              <!-- <button type="submit" class="btn btn-info" id="printEToll"><i class="fa fa-print"></i> Print</button> -->
                              <button type="submit" class="btn btn-success" id="lihatLaporanEToll"><i class="fa fa-eye"></i> Print</button>
                          </div>
                      </form>
                      <!-- END BEGIN FORM-->
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

    <script>
      $(document).ready(function() {
        $("#activeLaporan").addClass('active');
        $("#activeLaporanEToll").addClass('active');

      //active combobox
        var config = {
          '.chosen-select'           : {},
          '.chosen-select-deselect'  : {allow_single_deselect:true},
          '.chosen-select-no-single' : {disable_search_threshold:10},
          '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
          '.chosen-select-width'     : {width:"95%"}
        }

        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
        $(".chosen-select").chosen({width: "95%"});

        $("#submitLaporanEToll").unbind('submit').bind('submit', function() {

          var noEToll = $("#noEToll").val();

          if (noEToll == "") {
            $("#noEToll").after('<span class="help-inline">No E-Toll Masih Kosong</span>');
            $("#noEToll").closest('.control-group').addClass('error');
          }else{
            $("#noEToll").closest('.control-group').addClass('success');
            $(".help-inline").remove();           
          }

          if (noEToll) {

            $("#lihatLaporanEToll").button('loading');

            var form = $(this);
            $.ajax({
              url  : form.attr('action'),
              type : form.attr('method'),
              data : form.serialize(),
              dataType : 'text',
              success:function(data){

                $("#lihatLaporanEToll").button('reset');

                var mywindow = window.open('', 'Laporan EToll', 'height=400, width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('</head><body>');
                mywindow.document.write(data);
                mywindow.document.write('</body></html>');

                // mywindow.document.close(); //necessary for IE >= 10
                // mywindow.focus() //necessary for IE <= 10
                // mywindow.close();
              }
            });
          }
          return false;
        });

      });
    </script> 
    <?php 
    }else{
      include_once 'saldo.php';
    } 
    ?>