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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['strandID'])) {
    $pagesObj = new Pages();
    $strandID = $_POST['strandID'];
    
    try {
        // Get the strand data to find related items
        $strandData = $pagesObj->getRowById($strandID);
        
        if (!$strandData) {
            throw new Exception("Strand not found");
        }
        
        // Delete the strand name entry
        if (!$pagesObj->deleteVal($strandID)) {
            throw new Exception("Failed to delete strand");
        }
        
        // Find and delete related entries (description, end description, and outcomes)
        $relatedItemsSQL = "SELECT sectionID FROM page_sections 
                           WHERE subpage = 31 
                           AND indicator = 'Strand' 
                           AND (description = 'strand-desc' 
                                OR description = 'strand-desc-end' 
                                OR description LIKE 'strand-item-%')";
        
        $relatedItems = $pagesObj->execQuery($relatedItemsSQL);
        
        if ($relatedItems) {
            foreach ($relatedItems as $item) {
                $pagesObj->deleteVal($item['sectionID']);
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Strand deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
