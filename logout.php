<?php
require_once 'function/session.php';

// remove all session variables
session_unset();

// destroy the session 
session_destroy();

header('location: index.php')
?>