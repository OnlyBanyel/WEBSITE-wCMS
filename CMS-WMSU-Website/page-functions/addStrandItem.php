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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pagesObj = new Pages();
    
    try {
        // Get form data
        $subpage = isset($_POST['subpage']) ? intval($_POST['subpage']) : 31;
        $strandName = isset($_POST['strandName']) ? trim($_POST['strandName']) : '';
        $strandDesc = isset($_POST['strandDesc']) ? trim($_POST['strandDesc']) : '';
        $strandEndDesc = isset($_POST['strandEndDesc']) ? trim($_POST['strandEndDesc']) : '';
        
        // Debug log
        error_log("Adding strand: " . $strandName . " to subpage: " . $subpage);
        
        // Validate required fields
        if (empty($strandName)) {
            throw new Exception("Strand name is required");
        }
        
        // Check if strand already exists
        $existing = $pagesObj->fetchSHSStrands();
        foreach ($existing as $strand) {
            if ($strand['content'] === $strandName && $strand['description'] === 'strand-name') {
                throw new Exception("A strand with this name already exists");
            }
        }
        
        // Generate a unique identifier for this strand group
        $strandGroupId = uniqid('strand_');
        
        // Insert new strand name
        $strandID = $pagesObj->addContent(
            $subpage,
            'Strand',
            'text',
            $strandName,
            '',
            'strand-name',
            $strandGroupId
        );
        
        if (!$strandID) {
            throw new Exception("Failed to add strand name");
        }
        
        // Insert strand description
        $descID = $pagesObj->addContent(
            $subpage,
            'Strand',
            'text',
            $strandDesc,
            '',
            'strand-desc',
            $strandGroupId
        );
        
        if (!$descID) {
            throw new Exception("Failed to add strand description");
        }
        
        // Insert strand end description
        $endDescID = $pagesObj->addContent(
            $subpage,
            'Strand',
            'text',
            $strandEndDesc,
            '',
            'strand-desc-end',
            $strandGroupId
        );
        
        if (!$endDescID) {
            throw new Exception("Failed to add strand end description");
        }
        
        // Handle outcomes if provided
        if (isset($_POST['outcome_content']) && is_array($_POST['outcome_content'])) {
            foreach ($_POST['outcome_content'] as $index => $content) {
                if (!empty(trim($content))) {
                    $itemNumber = $index + 1;
                    $outcomeID = $pagesObj->addContent(
                        $subpage,
                        'Strand',
                        'text',
                        trim($content),
                        '',
                        'strand-item-' . $itemNumber,
                        $strandGroupId
                    );
                    
                    if (!$outcomeID) {
                        throw new Exception("Failed to add strand outcome");
                    }
                }
            }
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Strand added successfully',
            'strandID' => $strandID,
            'descID' => $descID,
            'endDescID' => $endDescID,
            'strandGroupId' => $strandGroupId
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
