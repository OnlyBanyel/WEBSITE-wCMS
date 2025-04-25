<?php
session_start();
require_once "../classes/pages.class.php";
require_once "../tools/functions.php";

// Check if user is logged in and has appropriate permissions
if (!isset($_SESSION['logged_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize Pages object
$pagesObj = new Pages();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get outcome ID
    $outcomeID = $_POST['outcomeID'] ?? '';
    
    if (empty($outcomeID)) {
        echo json_encode(['success' => false, 'message' => 'Outcome ID is required']);
        exit;
    }
    
    // Delete outcome
    $result = $pagesObj->deleteStrandOutcome($outcomeID);
    
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
