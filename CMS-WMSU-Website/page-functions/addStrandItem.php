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
        $subpage = isset($_POST['subpage']) ? intval($_POST['subpage']) : 31; // Default to SHS subpage
        $strandID = isset($_POST['strandID']) ? $_POST['strandID'] : null;
        $descID = isset($_POST['descID']) ? $_POST['descID'] : null;
        $endDescID = isset($_POST['endDescID']) ? $_POST['endDescID'] : null;
        $isNew = isset($_POST['isNew']) && $_POST['isNew'] === 'true';
        
        $strandName = isset($_POST['strandName']) ? $_POST['strandName'] : '';
        $strandDesc = isset($_POST['strandDesc']) ? $_POST['strandDesc'] : '';
        $strandEndDesc = isset($_POST['strandEndDesc']) ? $_POST['strandEndDesc'] : '';
        
        // Handle strand name
        if ($isNew) {
            // Insert new strand name
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
            
            echo json_encode([
                'success' => true, 
                'message' => 'Strand added successfully',
                'strandID' => $strandID,
                'descID' => $descID,
                'endDescID' => $endDescID
            ]);
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
            
            echo json_encode([
                'success' => true, 
                'message' => 'Strand updated successfully',
                'strandID' => $strandID,
                'descID' => $descID,
                'endDescID' => $endDescID
            ]);
        }
        
        // Handle outcomes/subjects
        /*
        foreach ($outcomeContents as $index => $content) {
            $sectionID = $outcomeSectionIDs[$index];
            $isNewOutcome = $outcomeIsNew[$index] === 'true';
            
            if ($isNewOutcome || strpos($sectionID, 'temp_') === 0) {
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
        */
        
        // Commit transaction
        /*
        $pagesObj->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Strand updated successfully',
            'strandID' => $strandID,
            'descID' => $descID,
            'endDescID' => $endDescID
        ]);
        */
    } catch (Exception $e) {
        // Rollback transaction on error
        /*
        $pagesObj->rollback();
        */
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
