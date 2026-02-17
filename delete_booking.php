<?php
session_start();
require 'config.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

if(isset($_GET['id'])){
    $stmt = $con->prepare("DELETE FROM booking WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: admin_dashboard.php");
exit();
?>
