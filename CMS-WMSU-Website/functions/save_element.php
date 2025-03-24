<?php
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

$dbObj = new Database;
$ccsPage = new Pages;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sectionID = $_POST['sectionID'] ?? null;
    $elementType = $_POST['elementType'] ?? null;
    $value = $_POST['value'] ?? null;

    if (!$sectionID || !$elementType || $value === null) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit;
    }

    // Determine the column to update
    $column = ($elementType === 'text') ? 'content' : 'imagePath';

    // Prepare and execute the update query
    $updateSQL = "UPDATE page_sections SET $column = :value WHERE sectionID = :sectionID";
    $stmt = $dbObj->connect()->prepare($updateSQL);
    $stmt->bindParam(":value", $value);
    $stmt->bindParam(":sectionID", $sectionID);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Element updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update element."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
