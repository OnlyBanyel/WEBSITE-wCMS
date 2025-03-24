<?php
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

$updateHomeObj = new Pages;

header('Content-Type: application/json'); // Ensure response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $column = isset($_POST['column']) ? trim($_POST['column']) : null;
    $newValue = isset($_POST['value']) ? trim($_POST['value']) : null;

    if (!$id || !$column || $newValue === null) {
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
        exit;
    }

    // Update value in the database
    if ($updateHomeObj->editValue($newValue, $id, $column)) {
        $updatedData = $updateHomeObj->getRowById($id);

        // Refresh session data
        $pageID = 3;
        $_SESSION['homePage'] = $updateHomeObj->fetchPageData($pageID);
        $_SESSION['homePage']['WmsuNews'] = $updateHomeObj->fetchSectionsByIndicator('Wmsu News', $pageID);
        $_SESSION['homePage']['ResearchArchives'] = $updateHomeObj->fetchSectionsByIndicator('Research Archives', $pageID);
        $_SESSION['homePage']['AboutWMSU'] = $updateHomeObj->fetchSectionsByIndicator('About WMSU', $pageID);
        $_SESSION['homePage']['PresCorner'] = $updateHomeObj->fetchSectionsByIndicator('Pres Corner', $pageID);
        $_SESSION['homePage']['Services'] = $updateHomeObj->fetchSectionsByIndicator('Services', $pageID);

        echo json_encode([
            "status" => "success",
            "message" => "Content updated successfully",
            "updatedData" => $updatedData // Send updated row data
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating content"]);
    }
}
?>
