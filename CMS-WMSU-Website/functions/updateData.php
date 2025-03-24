<?php
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

$dbObj = new Database;
$ccsPage = new Pages;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $newValue = $_POST['value'] ?? null;
    $column = $_POST['column'] ?? null;

    if ($id && $newValue !== null && $column) {
        $updateSuccess = $ccsPage->editValue($newValue, $id, $column);

        if ($updateSuccess) {
            // Fetch the updated row to return updated data
            $updatedData = $ccsPage->getRowById($id);

            // Send JSON response
            echo json_encode(["status" => "success", "updatedData" => $updatedData]);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Database update failed"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
        exit;
    }
}
?>
