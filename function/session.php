<?php
session_start();
if (!$_SESSION['id_userKUS']) {
	header('location:index.php');
}
?>