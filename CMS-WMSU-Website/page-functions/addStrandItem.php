<?php
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/login.class.php";

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
    
    // Get outcomes data
    $outcomeContents = isset($_POST['outcome_content']) ? $_POST['outcome_content'] : [];
    $outcomeSectionIDs = isset($_POST['outcome_sectionid']) ? $_POST['outcome_sectionid'] : [];
    $outcomeIsNew = isset($_POST['outcome_isnew']) ? $_POST['outcome_isnew'] : [];
    
        // Begin transaction
        $pagesObj->beginTransaction();
        
        // Handle strand name
        if ($isNew) {
            // Insert new strand name
            $newStrandData = [
                'subpage' => $subpage,
                'indicator' => 'Strand',
                'description' => 'strand-name',
                'elemType' => 'text',
                'content' => $strandName,
                'imagePath' => ''
            ];
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
            
            // Insert strand description
            $descResult = $pagesObj->addContent(
                $subpage,
                'Strand',
                'text',
                $strandDesc,
                '',
                'strand-desc'
            );
            
            if (!$descResult) {
                throw new Exception("Failed to add strand description");
            }
            
            $descID = $descResult;
            
            // Insert strand end description
            $endDescResult = $pagesObj->addContent(
                $subpage,
                'Strand',
                'text',
                $strandEndDesc,
                '',
                'strand-desc-end'
            );
            
            if (!$endDescResult) {
                throw new Exception("Failed to add strand end description");
            }
            
            $endDescID = $endDescResult;
        } else {
            // Update existing strand name
            $result = $pagesObj->changeContent($strandID, $subpage, $strandName);
            if (!$result) {
                throw new Exception("Failed to update strand name");
            }
            
            // Update strand description
            if (!empty($descID) && $descID != 'temp_desc_' . $strandID) {
                $descResult = $pagesObj->changeContent($descID, $subpage, $strandDesc);
                if (!$descResult) {
                    throw new Exception("Failed to update strand description");
                }
            } else {
                // Insert new description if it doesn't exist
                $descResult = $pagesObj->addContent(
                    $subpage,
                    'Strand',
                    'text',
                    $strandDesc,
                    '',
                    'strand-desc'
                );
                
                if (!$descResult) {
                    throw new Exception("Failed to add strand description");
                }
                
                $descID = $descResult;
            }
            
            // Update strand end description
            if (!empty($endDescID) && $endDescID != 'temp_end_desc_' . $strandID) {
                $endDescResult = $pagesObj->changeContent($endDescID, $subpage, $strandEndDesc);
                if (!$endDescResult) {
                    throw new Exception("Failed to update strand end description");
                }
            } else {
                // Insert new end description if it doesn't exist
                $endDescResult = $pagesObj->addContent(
                    $subpage,
                    'Strand',
                    'text',
                    $strandEndDesc,
                    '',
                    'strand-desc-end'
                );
                
                if (!$endDescResult) {
                    throw new Exception("Failed to add strand end description");
                }
                
                $endDescID = $endDescResult;
            }
        }
        
        // Handle outcomes/subjects
        foreach ($outcomeContents as $index => $content) {
            $sectionID = $outcomeSectionIDs[$index];
            $isNewOutcome = $outcomeIsNew[$index] === '1';
            
            if ($isNewOutcome) {
                // Insert new outcome
                $itemNumber = $index + 1;
                $outcomeResult = $pagesObj->addContent(
                    $subpage,
                    'Strand',
                    'text',
                    $content,
                    '',
                    'strand-item-' . $itemNumber
                );
                
                if (!$outcomeResult) {
                    throw new Exception("Failed to add strand item");
                }
            } else {
                // Update existing outcome
                $outcomeResult = $pagesObj->changeContent($sectionID, $subpage, $content);
                if (!$outcomeResult) {
                    throw new Exception("Failed to update strand item");
                }
            }
        }
        
        
        echo json_encode(['success' => true, 'message' => 'Strand updated successfully']);
        echo json_encode(['success' => false, 'message' => 'Error']);
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
