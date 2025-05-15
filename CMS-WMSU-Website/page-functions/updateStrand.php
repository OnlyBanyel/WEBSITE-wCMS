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
    
    // Log received data for debugging
    error_log("Updating strand with data: " . print_r($_POST, true));

    // Get form data
    $subpage = isset($_POST['subpage']) ? intval($_POST['subpage']) : 31; // Default to SHS subpage
    $strandID = $_POST['strandID'];
    $descID = $_POST['descID'];
    $endDescID = $_POST['endDescID'];
    $isNew = $_POST['isNew'] === '1';
    
    $strandName = $_POST['strandName'];
    $strandDesc = $_POST['strandDesc'];
    $strandEndDesc = $_POST['strandEndDesc'];
    
    try {
        // Handle strand name (main strand entry)
        if ($isNew) {
            // Create new strand
            $strandID = $pagesObj->addContent(
                $subpage,
                'Strand',
                'text',
                $strandName,
                '',
                'strand-name'
            );
            
            if (!$strandID) {
                throw new Exception("Failed to add strand name");
            }
            
            // Create description
            $descID = $pagesObj->addContent(
                $subpage,
                'Strand',
                'text',
                $strandDesc,
                '',
                'strand-desc'
            );
            
            if (!$descID) {
                throw new Exception("Failed to add strand description");
            }
            
            // Create end description
            $endDescID = $pagesObj->addContent(
                $subpage,
                'Strand',
                'text',
                $strandEndDesc,
                '',
                'strand-desc-end'
            );
            
            if (!$endDescID) {
                throw new Exception("Failed to add strand end description");
            }
        } else {
            // Update existing strand
            $pagesObj->changeContent($strandID, $subpage, $strandName);
            
            // Update description
            $pagesObj->changeContent($descID, $subpage, $strandDesc);
            
            // Update end description
            $pagesObj->changeContent($endDescID, $subpage, $strandEndDesc);
        }
        
        // Handle outcomes/subjects
        if (isset($_POST['outcome_content']) && is_array($_POST['outcome_content'])) {
            $outcomeContents = $_POST['outcome_content'];
            $outcomeSectionIDs = isset($_POST['outcome_sectionid']) ? $_POST['outcome_sectionid'] : [];
            $outcomeIsNew = isset($_POST['outcome_isnew']) ? $_POST['outcome_isnew'] : [];
            
            for ($i = 0; $i < count($outcomeContents); $i++) {
                $content = $outcomeContents[$i];
                $sectionID = isset($outcomeSectionIDs[$i]) && !empty($outcomeSectionIDs[$i]) ? $outcomeSectionIDs[$i] : null;
                $isNewOutcome = isset($outcomeIsNew[$i]) && $outcomeIsNew[$i] === '1';
                
                if (empty(trim($content))) {
                    continue; // Skip empty outcomes
                }
                
                if ($isNewOutcome || is_null($sectionID)) {
                    // Create new outcome
                    $itemNumber = $i + 1;
                    $outcomeID = $pagesObj->addContent(
                        $subpage,
                        'Strand',
                        'text',
                        trim($content),
                        '',
                        'strand-item-' . $itemNumber
                    );
                    
                    if (!$outcomeID) {
                        throw new Exception("Failed to add strand outcome");
                    }
                } else {
                    // Update existing outcome
                    $pagesObj->changeContent($sectionID, $subpage, trim($content));
                }
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Strand updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
