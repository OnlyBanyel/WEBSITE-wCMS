<?php 
session_start();
require_once '../classes/db_connection.class.php';
$dbObj = new Database;


if (empty($_SESSION['account'])){
    header('Location: login-form.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management System for WMSU</title>
    <?php require_once '../__includes/head.php' ?>
</head> 

    <!-- navbar -->
    <?php require_once '../__includes/navbar.php'?>
    <!-- siddebar -->
     <?php require_once '../__includes/sidebar.php'; ?>
<body>
    <h2>Welcome <?php
    echo $_SESSION['account']['firstName']?>!</h2>
    <section id="main-content-section"></section>
</body>
</html>