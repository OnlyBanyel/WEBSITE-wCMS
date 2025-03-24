<?php
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

$updateHomeObj = new Pages;

header('Content-Type: application/json'); // Ensure response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $newValue = $_POST['value'];

    if ($updateHomeObj->editValue($newValue, $id, $column)) {
        $updatedData = $updateHomeObj->getRowById($id);

        $pageID = 3;
        $_SESSION['homePage'] = $updateHomeObj->fetchPageData($pageID, NULL);
        $_SESSION['homePage']['WmsuNews'] = $updateHomeObj->fetchSectionsByIndicator('Wmsu News');
        $_SESSION['homePage']['ResearchArchives'] = $updateHomeObj->fetchSectionsByIndicator('Research Archives');
        $_SESSION['homePage']['AboutWMSU'] = $updateHomeObj->fetchSectionsByIndicator('About WMSU');
        $_SESSION['homePage']['PresCorner'] = $updateHomeObj->fetchSectionsByIndicator('Pres Corner');
        $_SESSION['homePage']['Services'] = $updateHomeObj->fetchSectionsByIndicator('Services');

        echo json_encode([
            "status" => "success",
            "message" => "Content updated successfully",
            "updatedData" => $updatedData // Send updated row
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating content"]);
    }
}
?>
