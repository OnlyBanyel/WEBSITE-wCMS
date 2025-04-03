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

    $outcomes = isset($_POST['outcomes']) && is_array($_POST['outcomes']) ? $_POST['outcomes'] : [];

    // Try updating overview title
    $updateTitle = $pagesObj->changeContent($overviewSectionID, $subpage, $overviewTitle);
    if (!$updateTitle) {
        $errors[] = "Failed to update overview title (SectionID: $overviewSectionID).";
    }

    // Try updating overview top content (if provided)
    $updateTopContent = true;
    if (isset($_POST['overviewTopContent']) && isset($_POST['topContentSectionID'])) {
        $overviewTopContent = $_POST['overviewTopContent'];
        $topContentSectionID = $_POST['topContentSectionID'];
        $updateTopContent = $pagesObj->changeContent($topContentSectionID, $subpage, $overviewTopContent);
        if (!$updateTopContent) {
            $errors[] = "Failed to update overview top content (SectionID: $topContentSectionID).";
        }
    }

    // Try updating outcomes
    $updateOutcomes = true;
    foreach ($outcomes as $outcome) {
        if (isset($outcome['content']) && isset($outcome['sectionID'])) {
            $outcomeUpdate = $pagesObj->changeContent($outcome['sectionID'], $subpage, $outcome['content']);
            if (!$outcomeUpdate) {
                $errors[] = "Failed to update outcome (SectionID: {$outcome['sectionID']}).";
                $updateOutcomes = false;
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
