<?php
$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
$file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
$handle = fopen($file, 'w');
$condensed = Chr(27) . Chr(33) . Chr(4);
$bold1 = Chr(27) . Chr(69);
$bold0 = Chr(27) . Chr(70);
$initialized = chr(27).chr(64);
$condensed1 = chr(15);
$condensed0 = chr(18);
$Data  = $initialized;
$Data .= $condensed;
$Data .= "----------------------------------------------------------\n";
$Data .= "|Kode   | Nama Barang       | Jumlah Brg    |    Harga   |\n";
$Data .= "----------------------------------------------------------\n";
$Data .= "| MC    | Milk Chocolate    |        9      |     500    |\n";
$Data .= "| BC    | Butter Cookies    |       10      |    1000    |\n";
$Data .= "| BC    | Butter Cookies    |       10      |    1000    |\n";
$Data .= "| BC    | Butter Cookies    |       10      |    1000    |\n";
$Data .= "| BC    | Butter Cookies    |       10      |    1000    |\n";
$Data .= "----------------------------------------------------------\n";
fwrite($handle, $Data);
fclose($handle);
shell_exec(" cat ".$file." | lpr -o raw -H localhost -P Epson-Dot-Matrix");  # Lakukan cetak
//shell_exec(" cat ".$file." | lpr -o raw -H localhost -P L220-Series");  # Lakukan cetak
unlink($file);
// // smb://192.168.1.202/EPSONL220
?>