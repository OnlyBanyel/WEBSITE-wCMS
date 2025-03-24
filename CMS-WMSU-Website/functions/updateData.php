<?php
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

$updateObj = new Pages;

header('Content-Type: application/json'); // Ensure response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $newValue = $_POST['value'];

    if ($updateObj->editValue($newValue, $id, $column)) {
        $updatedData = $updateObj->getRowById($id);

        $pageID = 3;
        $subpageID = 1;
        $_SESSION['ccsPage'] = $updateObj->fetchPageData($pageID, $subpageID);
        $_SESSION['ccsPage']['CarouselElement'] = $updateObj->fetchSectionsByIndicator('Carousel Element');
        $_SESSION['ccsPage']['CardElementFront'] = $updateObj->fetchSectionsByIndicator('Card Element Front');
        $_SESSION['ccsPage']['CardElementBack'] = $updateObj->fetchSectionsByIndicator('Card Element Back');
        $_SESSION['ccsPage']['AccordionCourses'] = $updateObj->fetchSectionsByIndicator('Accordion Courses');

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
