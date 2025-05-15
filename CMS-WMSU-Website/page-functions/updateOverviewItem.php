<?php
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/login.class.php";

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize classes
$pagesObj = new Pages();
$loginObj = new Login();

// Get the subpage from the session
$subpage = $_SESSION['account']['subpage_assigned'];

// Debug information
$debug = [];
$debug['post_data'] = $_POST;
$debug['section_type'] = isset($_POST['section_type']) ? $_POST['section_type'] : 'unknown';

// Process the form data
try {
    // Process section title
    if (isset($_POST['overviewTitle']) && isset($_POST['overviewSectionID'])) {
        $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';
        $sectionID = $_POST['overviewSectionID'];
        $sectionType = isset($_POST['sectionType']) ? (int)$_POST['sectionType'] : 0;
        
        // Determine the correct description based on section type
        $description = 'geninfo-front-title';
        
        if ($isNew || strpos($sectionID, 'temp_') === 0) {
            // Add new title
            $result = $pagesObj->addContent(
                $subpage,
                'College Overview',
                'text',
                $_POST['overviewTitle'],
                null,
                $description
            );
            
            if (!$result) {
                throw new Exception("Failed to add overview title");
            }
            
            $debug['title_added'] = true;
            $debug['title_id'] = $result;
        } else {
            // Update existing title
            $result = $pagesObj->changeContent($sectionID, $subpage, $_POST['overviewTitle']);
            
            if (!$result) {
                throw new Exception("Failed to update overview title");
            }
            
            $debug['title_updated'] = true;
        }
    }
    
    // Process section content
    if (isset($_POST['overviewTopContent']) && isset($_POST['topContentSectionID'])) {
        $isNew = isset($_POST['topContentIsNew']) && $_POST['topContentIsNew'] === '1';
        $sectionID = $_POST['topContentSectionID'];
        $sectionType = isset($_POST['section_type']) ? $_POST['section_type'] : '';
        
        // Determine the correct description based on section type
        $description = 'geninfo-back-head';
        
        // Debug
        $debug['content_section_type'] = $sectionType;
        $debug['content_section_id'] = $sectionID;
        $debug['content_is_new'] = $isNew;
        
        if ($isNew || strpos($sectionID, 'temp_') === 0) {
            // Add new content
            $result = $pagesObj->addContent(
                $subpage,
                'College Overview',
                'text',
                $_POST['overviewTopContent'],
                null,
                $description
            );
            
            if (!$result) {
                throw new Exception("Failed to add overview content");
            }
            
            $debug['content_added'] = true;
            $debug['content_id'] = $result;
        } else {
            // Update existing content
            $result = $pagesObj->changeContent($sectionID, $subpage, $_POST['overviewTopContent']);
            
            if (!$result) {
                throw new Exception("Failed to update overview content");
            }
            
            $debug['content_updated'] = true;
        }
    }
    
    // Process outcomes
    $newItems = [];
    if (isset($_POST['outcome_content']) && is_array($_POST['outcome_content'])) {
        $outcomes = $_POST['outcome_content'];
        $sectionIDs = $_POST['outcome_sectionid'] ?? [];
        $isNew = $_POST['outcome_isnew'] ?? [];
        $outcomeTypes = $_POST['outcome_type'] ?? [];
        $sectionType = isset($_POST['section_type']) ? $_POST['section_type'] : '';
        
        // Debug
        $debug['outcomes_section_type'] = $sectionType;
        $debug['outcomes_count'] = count($outcomes);
        
        // Map section types to outcome types
        $outcomeTypeMap = [
            'goals' => 'CG-list-item',
            'mission' => 'CM-list-item',
            'vision' => 'CV-list-item'
        ];
        
        // Default outcome type
        $defaultOutcomeType = $outcomeTypeMap[$sectionType] ?? 'CG-list-item';
        
        foreach ($outcomes as $index => $content) {
            if (empty($content)) continue;
            
            $itemSectionID = $sectionIDs[$index] ?? '';
            $itemIsNew = isset($isNew[$index]) && ($isNew[$index] === '1' || $isNew[$index] === true);
            $outcomeType = $outcomeTypes[$index] ?? $defaultOutcomeType;
            
            // Debug
            $debug['outcome_' . $index] = [
                'content' => $content,
                'section_id' => $itemSectionID,
                'is_new' => $itemIsNew,
                'outcome_type' => $outcomeType
            ];
            
            if ($itemIsNew || strpos($itemSectionID, 'temp_') === 0) {
                // Add new outcome
                $result = $pagesObj->addContent(
                    $subpage,
                    'College Overview',
                    'text',
                    $content,
                    null,
                    $outcomeType
                );
                
                if (!$result) {
                    throw new Exception("Failed to add outcome: $content");
                }
                
                // Track new items for UI update
                $newItems[] = [
                    'tempId' => $itemSectionID,
                    'newId' => $result
                ];
                
                $debug['outcome_' . $index]['added'] = true;
                $debug['outcome_' . $index]['new_id'] = $result;
            } else {
                // Update existing outcome
                $result = $pagesObj->changeContent($itemSectionID, $subpage, $content);
                
                if (!$result) {
                    throw new Exception("Failed to update outcome (ID: $itemSectionID)");
                }
                
                $debug['outcome_' . $index]['updated'] = true;
            }
        }
    }
    
    // Refresh session data
    $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    
    // Return success response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Changes saved successfully',
        'newItems' => $newItems,
        'debug' => $debug
    ]);
    
} catch (Exception $e) {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => $debug
    ]);
}
?>
