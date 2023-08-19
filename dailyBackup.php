<?php
require_once('function/db_backup_library.php');

$dbbackup = new db_backup;
$dbbackup->connect("localhost", "root", "", "inventori");
$dbbackup->backup();
$dbbackup->download();
?>