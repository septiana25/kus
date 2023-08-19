<?php
  /*******************************************
    Export Excel dengan PHPExcel
 
    Dibuat oleh : Danni Moring
    pemrograman : PHP
    ******************************************/
   require_once 'function/koneksi.php';
?>
<html>
<head>
<title>Export ke Excel dengan PHPEXCEL</title>
</head>
<body>
  <a href="exportexcel.php">[ Export ke Excel ]</a>
  <table border="1">
    <tr>
	   <td><b>Nama</b></td>
	   <td><b>Alamat</b></td>
	   <td><b>Telp</b></td>
	</tr>
  <?php
  $strsql	= "SELECT * from barang";
  if ( $res = $koneksi->query($strsql) ) {
	  while ($row = $res->fetch_assoc()) {
  ?>
    <tr>
	   <td><?php echo $row['id_brg'] ?></td>
	   <td><?php echo $row['id_kat'] ?></td>
	   <td><?php echo $row['brg'] ?></td>
	</tr>
  <?php
      }
  } else { 
  ?>
     <tr>
	   <td>Tidak ada data</td>
	 </tr>
<?php
  }
  
/* tutup koneksinya */
$koneksi->close();
 
?>
 
</body>
</html>