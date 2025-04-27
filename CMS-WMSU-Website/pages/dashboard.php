<?php 
session_start();
require_once "../classes/element_styler.class.php";

$styler = new ElementStyler();

// Check if user is logged in
if (empty($_SESSION['account'])){
  header('Location: login-form.php');
  exit;
}

// Remember the last role to reset localStorage when role changes
if (!isset($_SESSION['lastRole']) || $_SESSION['lastRole'] != $_SESSION['account']['role_id']) {
  echo "<script>localStorage.removeItem('activePage');</script>";
  $_SESSION['lastRole'] = $_SESSION['account']['role_id'];
}

// Get page parameter or default to college-profile for content managers
$page = isset($_GET['page']) ? $_GET['page'] : 'college-profile';
$validPages = ['college-profile', 'college-overview', 'courses-offered', 'departments', 'shs', 'messages', 'account-management', 'academics-account'];

// Validate page
if (!in_array($page, $validPages)) {
  $page = 'college-profile';
}

// Include the head section
include_once "../__includes/head.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | WMSU CMS</title>
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

    /* Body Content */
    .main-content {
        margin-left: 250px; /* Adjust based on sidebar width */
        padding: 20px;
        height: 100vh;
        transition: margin-right 0.3s ease; /* Add transition for smooth panel opening */
    }

    .main-content-section{
        height: 100vh;
    }
    
    /* Style for elements being edited */
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
    
    /* Styleable elements */
    .styleable {
        cursor: pointer;
        transition: outline 0.2s ease;
    }
    
    .styleable:hover {
        outline: 1px dotted #BD0F03;
    }
    
    /* Preview elements - these will receive the styles */
    .preview-element {
        transition: all 0.3s ease;
    }
  </style>
</head> 
<body>
  <!-- Navbar -->
  <?php require_once '../__includes/navbar.php'?>
  
  <!-- Sidebar -->
  <div class="sidebar-container">
    <?php require_once '../__includes/sidebar.php'; ?>
  </div>

  <!-- Main Content -->
  <div class="main-content">    
    <section class='main-content-section' id="main-content-section">
      <?php include_once "../page-views/$page.php"; ?>
    </section>
  </div>
  
  <!-- Include the style panel -->
  <?php include_once "../components/styles-panel.php"; ?>

  <!-- Include the element styler script -->
  <script src="../js/element-styler.js"></script>
  
  <!-- Keyboard shortcut info -->
  <div class="fixed bottom-4 right-4 bg-gray-800 text-white px-3 py-2 rounded-md text-sm opacity-70 hover:opacity-100 transition-opacity">
    Press <kbd class="bg-gray-700 px-2 py-1 rounded">Alt+S</kbd> to toggle style panel
  </div>
</body>
</html>
