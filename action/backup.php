<?php
require_once('../function/db_backup_library.php');
$valid['success'] =  array('success' => false , 'messages' => array());
$dbbackup = new db_backup;
$dbbackup->connect("localhost", "root", "", "inventoriKUS");
$dbbackup->backup();

if ($dbbackup->save("../database/") === TRUE) {
    $valid['success']  = true;
    $valid['messages'] = "<strong>Success! </strong>Backup Database Berhasil";
}else{
    $valid['success']  = false;
    $valid['messages'] = "<strong>Error! </strong>Gagal Melakukan Backup Database";
}
echo json_encode($valid);
?>