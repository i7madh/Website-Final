<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Website/Connection/conn.php';
unset($_SESSION['SBUser']);
header('Location: login.php');
?>
