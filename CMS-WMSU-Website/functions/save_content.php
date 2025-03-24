<?php
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

if (isset($_POST['elementType'], $_POST['value'], $_POST['indicator'], $_POST['description'])) {
    $elementType = $_POST['elementType'];
    $value = $_POST['value'];
    $indicator = $_POST['indicator'];
    $description = $_POST['description'];

    $dbObj = new Database;
    $pagesObj = new Pages;

    // Determine the correct column for insertion
    $column = ($elementType === 'text') ? 'content' : 'imagePath';
    $elemType = ($elementType === 'text') ? 'text' : 'image';

    // Check if a section with this indicator exists
    $existingSection = $pagesObj->getRowByIndicator($indicator);

    if ($existingSection) {
        // Update existing section
        $indicator = preg_replace('/(?<!\ )[A-Z]/', ' $0', $indicator);
        $indicator = ucfirst(trim($indicator)); // Capitalize first letter
        $result = $pagesObj->updateSectionContent(
            $existingSection['sectionID'],
            $column,
            $value,
            $indicator,
            $description,
            $elemType,
        );
    } else {
        // Insert new section
        $pageID = $_POST['pageID'] ?? null; // Ensure pageID is provided
        $subpageID = $_SESSION['subpageID'] ?? null;

        if ($pageID === null) {
            echo json_encode(['status' => 'error', 'message' => 'Missing page ID']);
            exit;
        }

        $result = $pagesObj->insertSectionContent($column, $value, $indicator, $description, $elemType, $pageID, $subpageID);
    }

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save content']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
