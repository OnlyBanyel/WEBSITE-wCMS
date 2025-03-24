<?php
require_once '../classes/db_connection.class.php';
$dbObj = new Database;
$conn = $dbObj->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sectionID = $_POST['sectionID'] ?? null;
    $elementType = $_POST['elementType'] ?? null;
    $value = $_POST['value'] ?? null;
    
    if (!$sectionID || !$elementType || $value === null) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
        exit;
    }

    // Determine the column to update based on element type
    $column = ($elementType === 'text') ? 'content' : 'imagePath';
    
    try {
        $sql = "UPDATE page_sections SET $column = :value WHERE sectionID = :sectionID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':sectionID', $sectionID);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Element updated successfully.', 'updatedData' => [$column => $value]]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
