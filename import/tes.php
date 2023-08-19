<?php 
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>
<form name="myForm" id="myForm" onSubmit="return validateForm()" action="tes.php" method="post" enctype="multipart/form-data">
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

<?php
//koneksi ke database, username,password  dan namadatabase menyesuaikan 


//memanggil file excel_reader
require_once '../function/koneksi.php';
require_once '../function/setjam.php';
  $bulan          = 6;
  $tahun          = date("Y");
  $keamrin        = $bulan - 1;
//jika tombol import ditekan
if(isset($_POST['submit'])){
  $query1 = $koneksi->query("SELECT COUNT(*) FROM saldo WHERE MONTH(tgl)=$keamrin AND YEAR(tgl)=$tahun");
  $rowData = $query1->fetch_array();
  $totalData = $rowData[0];   
//    menghitung jumlah baris file xls
    $baris = $rowData[0];
    

    $query = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$keamrin AND YEAR(tgl)=$tahun");
//    import data excel mulai baris ke-2 (karena tabel xls ada header pada baris 1)
    $i=1;
    while ($row = $query->fetch_array())
    {
//        menghitung jumlah real data. Karena kita mulai pada baris ke-2, maka jumlah baris yang sebenarnya adalah 
//        jumlah baris data dikurangi 1. Demikian juga untuk awal dari pengulangan yaitu i juga dikurangi 1
        $barisreal = $baris;
        $k 		   = $i;
        
// menghitung persentase progress
        $percent = intval($k/$barisreal * 100)."%";

// mengupdate progress
        echo '<script language="javascript">
        document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.'; background-color:lightblue\">&nbsp;</div>";
        document.getElementById("info").innerHTML="'.$k.' data berhasil diinsert ('.$percent.' selesai).";
        </script>';

//       membaca data (kolom ke-1 sd terakhir)
        $id = $row[0];

//      setelah data dibaca, masukkan ke tabel pegawai sql
      $insert = $koneksi->query("INSERT INTO tes (id) VALUES ('$id')");
      
      flush();

//      kita tambahkan sleep agar ada penundaan, sehingga progress terbaca bila file yg diimport sedikit
//      pada prakteknya sleep dihapus aja karena bikin lama hehe
      //sleep(1);

    }
        
$i++;
}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo "Selesai dalam ".$total_time." detik";
?>