<?php
session_start();
require_once '../classes/pages.class.php';
require_once '../classes/login.class.php';

// Initialize classes
$pagesObj = new Pages();
$loginObj = new Login();

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get subpage from session
$subpage = $_SESSION['account']['subpage_assigned'];

// Check if sectionID is provided
if (!isset($_POST['sectionID']) || empty($_POST['sectionID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing section ID']);
    exit;
}

$sectionID = $_POST['sectionID'];

// Log the deletion attempt
error_log("Attempting to delete item with sectionID: $sectionID for subpage: $subpage");

// Delete the item
$result = $pagesObj->deleteItem($sectionID, $subpage);

if ($result) {
    // Refresh session data
    $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    
    echo json_encode([
        'success' => true,
        'message' => 'Item deleted successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete item'
    ]);
}
?>
