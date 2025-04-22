<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login();
$pagesObj = new Pages();

header('Content-Type: application/json'); // Ensure JSON response

// Collect errors for debugging
$errors = [];

// Check if required fields are set
if (isset($_POST['overviewTitle']) && isset($_POST['overviewSectionID'])) {
    $overviewTitle = $_POST['overviewTitle'];
    $overviewSectionID = $_POST['overviewSectionID'];
    $subpage = $_SESSION['account']['subpage_assigned'];

    // Check if this is a completely new item
    $isNewItem = isset($_POST['isNewItem']) && $_POST['isNewItem'] === '1';

    // Try updating overview title
    if ($isNewItem || strpos($overviewSectionID, 'temp_') === 0) {
        // Add new title
        $newSectionID = $pagesObj->addContent(
            $subpage,
            'College Overview',
            'text',
            $overviewTitle,
            null,
            $overviewSectionID === 'temp_title_0' ? 'geninfo-front-title' : 
            ($overviewSectionID === 'temp_title_1' ? 'geninfo-front-title' : 'geninfo-front-title')
        );
        
        if (!$newSectionID) {
            $errors[] = "Failed to create new overview title.";
        }
    } else {
        // Update existing title
        $updateTitle = $pagesObj->changeContent($overviewSectionID, $subpage, $overviewTitle);
        if (!$updateTitle) {
            $errors[] = "Failed to update overview title (SectionID: $overviewSectionID).";
        }
    }

    // Try updating overview top content (if provided)
    $updateTopContent = true;
    if (isset($_POST['overviewTopContent']) && isset($_POST['topContentSectionID'])) {
        $overviewTopContent = $_POST['overviewTopContent'];
        $topContentSectionID = $_POST['topContentSectionID'];
        
        if ($isNewItem || strpos($topContentSectionID, 'temp_') === 0) {
            // Add new content
            $newTopContentID = $pagesObj->addContent(
                $subpage,
                'College Overview',
                'text',
                $overviewTopContent,
                null,
                'geninfo-back-head'
            );
            
            if (!$newTopContentID) {
                $errors[] = "Failed to create new overview content.";
                $updateTopContent = false;
            }
        } else {
            // Update existing content
            $updateTopContent = $pagesObj->changeContent($topContentSectionID, $subpage, $overviewTopContent);
            if (!$updateTopContent) {
                $errors[] = "Failed to update overview top content (SectionID: $topContentSectionID).";
            }
        }
    }

    // Decode outcomes JSON
    $outcomes = [];
    if (isset($_POST['outcomes'])) {
        $outcomes = json_decode($_POST['outcomes'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errors[] = "Invalid outcomes format.";
        }
    }

    // Try updating outcomes
    $updateOutcomes = true;
    foreach ($outcomes as $outcome) {
        if (isset($outcome['content']) && isset($outcome['sectionID'])) {
            // Handle new items (negative sectionID)
            if (isset($outcome['isNew']) && $outcome['isNew']) {
                // Add new content - you'll need to implement addContent() in your Pages class
                $newSectionID = $pagesObj->addContent(
                    $subpage,
                    'College Overview', // Adjust indicator as needed
                    'text',
                    $outcome['content'],
                    null,
                    'CG-list-item' // Adjust description as needed
                );
                
                if (!$newSectionID) {
                    $errors[] = "Failed to create new outcome.";
                    $updateOutcomes = false;
                }
            } else {
                // Update existing content
                $outcomeUpdate = $pagesObj->changeContent($outcome['sectionID'], $subpage, $outcome['content']);
                if (!$outcomeUpdate) {
                    $errors[] = "Failed to update outcome (SectionID: {$outcome['sectionID']}).";
                    $updateOutcomes = false;
                }
            }
        } else {
            $errors[] = "Outcome missing required fields.";
            $updateOutcomes = false;
            break;
        }
    }

    // If all updates succeed
    if ($updateTitle && $updateTopContent && $updateOutcomes) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        echo json_encode(["success" => true]);
        exit;
    } else {
        error_log(json_encode($errors)); // Log errors
        echo json_encode(["success" => false, "message" => "Update failed.", "errors" => $errors]);
        exit;
    }
} else {
    error_log("Missing required POST fields.");
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}
?>
