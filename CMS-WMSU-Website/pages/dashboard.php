<?php
session_start();
require_once "../classes/element_styler.class.php";

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    header('Location: login-form.php');
    exit;
}

// Initialize the ElementStyler
$styler = new ElementStyler();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Dashboard</title>
    <?php include_once "../__includes/head.php"; ?>
    <style>
        .style-editing {
            outline: 2px dashed #BD0F03 !important;
            position: relative;
        }
        
        .style-editing::after {
            content: "Editing";
            position: absolute;
            top: -20px;
            right: 0;
            background-color: #BD0F03;
            color: white;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 3px;
            z-index: 100;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include_once "../__includes/sidebar.php"; ?>
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include_once "../__includes/navbar.php"; ?>
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div id="main-content-section" class="container mx-auto px-6 py-8">
                    <!-- Content will be loaded here dynamically -->
                </div>
            </main>
        </div>
    </div>
    
    <?php if ($_SESSION['account']['role_id'] == 1 || $_SESSION['account']['role_id'] == 2): ?>
        <?php include_once "../components/style-sidebar.php"; ?>
        <script src="../js/style-sidebar.js"></script>
    <?php endif; ?>
    
    <script src="../js/script.js"></script>
</body>
</html>
