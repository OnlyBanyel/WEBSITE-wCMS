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
<style>
    .sidebar-container {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        transition: width 0.3s ease;
        z-index: 1000;
    }

    /* Adjust sidebar width on hover */
    /* Body Content */
    .main-content {
        margin-left: 250px; /* Adjust based on sidebar width */
        padding: 20px;
    }

    .main-content-section{
        width: 100vw;
        height: 100vh;
}




    </style>
    <link rel="stylesheet" href="../css/academics-page.css">
    
    <?php require_once '../__includes/head.php' ?>
</head> 

    <!-- navbar -->
    <?php require_once '../__includes/navbar.php'?>
    <!-- siddebar -->
     <div class="sidebar-container">
     <?php require_once '../__includes/sidebar.php'; ?>
     </div>

     <div class="welcome-container">
     <h2>Welcome <?php
    echo $_SESSION['account']['firstName']?>!</h2>
     </div>
     
<div class="main-content">
<h2>Welcome <?php
    echo $_SESSION['account']['firstName']?>!</h2>
    
    <section id="main-content-section">

    </section>
</div>
</html>