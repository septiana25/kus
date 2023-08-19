<?php
 
require_once 'TableText.php';
 
$tp=new TableText(104,4);
$tp->setColumnLength(0, 5)
	->setColumnLength(1, 33)
	->setColumnLength(2, 44)
	->setColumnLength(3, 18)
	->setUseBodySpace(true);
 
$tp	->addSpace("header")
	->addColumn("KARTU KELUARGA", 4,"center")
	->commit("header")
	->addSpace("header")
	->addLine("header");
 
$tp	->addColumn("No. ", 1,"center")
	->addColumn("NAMA", 1,"center")
	->addColumn("ALAMAT", 1,"center")
	->addColumn("TANGGAL LAHIR", 1,"center")
	->commit("header");
 
$tp	->addColumn((1).".", 1)
	->addColumn("NURUL HUDA", 1,"left")
	->addColumn("SEDAYULAWAS, BRONDONG, LAMONGAN", 1,"center")
	->addColumn("14 Mei 1989", 1,"right")
	->commit("body");
 
$tp	->addColumn((2).".", 1)
	->addColumn("EKA SAFITRI", 1,"left")
	->addColumn("GUMINING, TAMBAKREJO, DUDUK SAMPEYAN, GRESIK", 1,"center")
	->addColumn("20 September 1989", 1,"right")
	->commit("body");
 
$tp	->addColumn((3).".", 1)
	->addColumn("SALMAN AL FARISI IBNU AL - HUDA", 1,"left")
	->addColumn("KALILOMBARU, KENJERAN, SURABAYA", 1,"center")
	->addColumn("21 Mei 2013", 1,"right")
	->commit("body");
 
$tp	->addColumn("TOTAL", 1,"left")
	->addColumn("10 Orang", 3,"right")
	->commit("footer")
	->addLine("footer")
	->addColumn("BARANG YANG SUDAH DIBELI TAK DAPAT DIBALIKIN", 4,"center")
	->commit("footer");
 
echo $tp->getText();
?>