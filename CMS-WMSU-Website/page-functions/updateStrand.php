<?php
session_start();
require_once "../classes/pages.class.php";

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']);
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pagesObj = new Pages();
    
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
        // Begin transaction
        $pagesObj->beginTransaction();
        
        // Handle strand name (main strand entry)
        if ($isNew) {
            // Create new strand
            $strandID = $pagesObj->addPageSection([
                'subpage' => $subpage,
                'indicator' => 'Strand',
                'description' => 'strand-name',
                'content' => $strandName
            ]);
            
            // Create description
            $descID = $pagesObj->addPageSection([
                'subpage' => $subpage,
                'indicator' => 'Strand',
                'description' => 'strand-desc',
                'content' => $strandDesc
            ]);
            
            // Create end description
            $endDescID = $pagesObj->addPageSection([
                'subpage' => $subpage,
                'indicator' => 'Strand',
                'description' => 'strand-desc-end',
                'content' => $strandEndDesc
            ]);
        } else {
            // Update existing strand
            $pagesObj->updatePageSection($strandID, [
                'content' => $strandName
            ]);
            
            // Update description
            $pagesObj->updatePageSection($descID, [
                'content' => $strandDesc
            ]);
            
            // Update end description
            $pagesObj->updatePageSection($endDescID, [
                'content' => $strandEndDesc
            ]);
        }
        
        // Handle outcomes/subjects
        if (isset($_POST['outcome_content']) && is_array($_POST['outcome_content'])) {
            $outcomeContents = $_POST['outcome_content'];
            $outcomeSectionIDs = $_POST['outcome_sectionid'];
            $outcomeIsNew = $_POST['outcome_isnew'];
            
            for ($i = 0; $i < count($outcomeContents); $i++) {
                $content = $outcomeContents[$i];
                $sectionID = $outcomeSectionIDs[$i];
                $isNewOutcome = $outcomeIsNew[$i] === '1';
                
                if ($isNewOutcome) {
                    // Create new outcome
                    $pagesObj->addPageSection([
                        'subpage' => $subpage,
                        'indicator' => 'Strand',
                        'description' => 'strand-item-' . ($i + 1),
                        'content' => $content
                    ]);
                } else {
                    // Update existing outcome
                    $pagesObj->updatePageSection($sectionID, [
                        'content' => $content
                    ]);
                }
            }
        }
        
        // Commit transaction
        $pagesObj->commit();
        
        echo json_encode(['success' => true, 'message' => 'Strand updated successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $pagesObj->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
