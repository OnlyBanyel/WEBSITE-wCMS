<?php
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

$dbObj = new Database;
$ccsPage = new Pages;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $elementType = $_POST['elementType'] ?? null;
    $value = $_POST['value'] ?? null;
    $indicator = $_POST['indicator'] ?? null;
    $description = $_POST['description'] ?? null;

    if (!$elementType || $value === null || !$indicator || !$description) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit;
    }

    // Determine the column to update
    $column = ($elementType === 'text') ? 'content' : 'imagePath';

    // Insert the new content
    $result = $ccsPage->insertSectionContent($column, $value, $indicator, $description, $elementType);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Element inserted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to insert element."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
