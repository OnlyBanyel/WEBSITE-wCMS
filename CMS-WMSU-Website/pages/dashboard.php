<?php 
session_start();

if (empty($_SESSION['account'])){
    header('Location: login-form.php');
    exit;
}

if (!isset($_SESSION['lastRole']) || $_SESSION['lastRole'] != $_SESSION['account']['role_id']) {
    echo "<script>localStorage.removeItem('activePage');</script>";
    $_SESSION['lastRole'] = $_SESSION['account']['role_id'];
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
        height: 100vh;
    }

    .main-content-section{
        height: 100vh;
}




    </style>
    
    <?php require_once '../__includes/head.php' ?>
</head> 

    <!-- navbar -->
    <?php require_once '../__includes/navbar.php'?>
    <!-- siddebar -->
     <div class="sidebar-container">
     <?php require_once '../__includes/sidebar.php'; ?>
     </div>

     
<div class="main-content">    
    <section class='main-content-section'id="main-content-section"></section>
</div>
</html>