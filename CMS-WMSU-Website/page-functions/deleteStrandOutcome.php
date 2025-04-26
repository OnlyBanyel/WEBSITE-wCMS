<?php
session_start();
require_once "../classes/pages.class.php";

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']);
    exit;
}

// Process deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['outcomeID'])) {
    $pagesObj = new Pages();
    $outcomeID = $_POST['outcomeID'];
    
    try {
        // Delete the outcome
        if (!$pagesObj->deleteVal($outcomeID)) {
            throw new Exception("Failed to delete subject");
        }
        
        echo json_encode(['success' => true, 'message' => 'Subject deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
