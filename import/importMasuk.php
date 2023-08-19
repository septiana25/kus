<?php 
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>
<form name="myForm" id="myForm" onSubmit="return validateForm()" action="importMasuk.php" method="post" enctype="multipart/form-data">
    <input type="file" id="filepegawaiall" name="filepegawaiall" />
    <input type="submit" name="submit" value="Import" /><br/>
    <label><input type="checkbox" name="drop" value="1" /> <u>Kosongkan tabel sql terlebih dahulu.</u> </label>
</form>
<?php 
if (isset($_POST['submit'])) {
?>
<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
<div id="info"></div>
<?php
}
?>

<script type="text/javascript">
//    validasi form (hanya file .xls yang diijinkan)
    function validateForm()
    {
        function hasExtension(inputID, exts) {
            var fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        }

        if(!hasExtension('filepegawaiall', ['.xls'])){
            alert("Hanya file XLS (Excel 2003) yang diijinkan.");
            return false;
        }
    }
</script>

<?php
//koneksi ke database, username,password  dan namadatabase menyesuaikan 


//memanggil file excel_reader
require_once 'excel_reader.php';
require_once '../function/koneksi.php';
require_once '../function/setjam.php';
        $tgl            = date("Y-m-d");
        $jam            = date("H:i:s");
        $bulan          = date("m");
        $tahun          = date("Y");
//jika tombol import ditekan
if(isset($_POST['submit'])){

    $target = basename($_FILES['filepegawaiall']['name']) ;
    move_uploaded_file($_FILES['filepegawaiall']['tmp_name'], $target);
    
    $data = new Spreadsheet_Excel_Reader($_FILES['filepegawaiall']['name'],false);
    
//    menghitung jumlah baris file xls
    $baris = $data->rowcount($sheet_index=0);
    
//    jika kosongkan data dicentang jalankan kode berikut
    if($_POST['drop']==1){
//             kosongkan tabel pegawai
             $truncate ="TRUNCATE TABLE detail_brg";
             mysql_query($truncate);
    }
    
//    import data excel mulai baris ke-2 (karena tabel xls ada header pada baris 1)
    for ($i=2; $i<=$baris; $i++)
    {
//        menghitung jumlah real data. Karena kita mulai pada baris ke-2, maka jumlah baris yang sebenarnya adalah 
//        jumlah baris data dikurangi 1. Demikian juga untuk awal dari pengulangan yaitu i juga dikurangi 1
        $barisreal = $baris-1;
        $k = $i-1;
        
// menghitung persentase progress
        $percent = intval($k/$barisreal * 100)."%";

// mengupdate progress
        echo '<script language="javascript">
        document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.'; background-color:lightblue\">&nbsp;</div>";
        document.getElementById("info").innerHTML="'.$k.' data berhasil diinsert ('.$percent.' selesai).";
        </script>';

//       membaca data (kolom ke-1 sd terakhir)
        $id_barang = $data->val($i, 1);
        $id_rak    = $data->val($i, 2);
        $jml       = $data->val($i, 3);

//      setelah data dibaca, masukkan ke tabel pegawai sql
        $detail_brg     = $koneksi->query("SELECT * FROM detail_brg WHERE id_brg='$id_barang' AND id_rak='$id_rak'");
        $rowDetail_brg  = $detail_brg->fetch_array();
        $id             = $rowDetail_brg['id'];        
        
        $insert_brg = "INSERT INTO detail_brg (id_brg,
                                               id_rak)
                                    VALUES    ('$id_barang', 
                                               '$id_rak')";

        if ($koneksi->query($insert_brg) === TRUE) {//jika detail_brg berhasil di simpan
            $id = $koneksi->insert_id;

            $insert_msk = "INSERT INTO masuk (id,
                                              tgl)
                                     VALUES  ('$id',
                                              '$tgl')";
            if ($koneksi->query($insert_msk)) {//jika insert_msk berhasil di simpan
                $id_msk = $koneksi->insert_id;

                $insert_det_msk = "INSERT INTO detail_masuk (id_msk,
                                                             jam,
                                                             jml_msk)
                                                     VALUES ('$id_msk',
                                                             '$jam',
                                                             '$jml')";
                if ($koneksi->query($insert_det_msk)) {
                    $id_det_msk = $koneksi->insert_id;//get id detail keluar
                    $id_msk = $koneksi->insert_id;
                    $insert_saldo = "INSERT INTO saldo (id,
                                                    tgl,
                                                    saldo_akhir)
                                             VALUES('$id',
                                                    '$tgl',
                                                    '$jml')";   
                }
                if ($koneksi->query($insert_saldo)=== TRUE) {
                    
                }else{
                    $delete = $koneksi->query("DELETE FROM detail_masuk WHERE id_det_msk='$id_det_msk'");
                }
                                     
                

            }//end jika insert_msk berhasil di simpan
        }//end jika detail_brg berhasil di simpan
      flush();

//      kita tambahkan sleep agar ada penundaan, sehingga progress terbaca bila file yg diimport sedikit
//      pada prakteknya sleep dihapus aja karena bikin lama hehe
      //sleep(1);

    }
        
//    hapus file xls yang udah dibaca
    unlink($_FILES['filepegawaiall']['name']);
}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo "Selesai dalam ".$total_time." detik";
?>