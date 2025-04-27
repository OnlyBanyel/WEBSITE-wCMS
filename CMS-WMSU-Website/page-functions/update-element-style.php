<?php
session_start();
require_once __DIR__ . "/../classes/element_styler.class.php";

// Check if user is logged in and has appropriate permissions
if (!isset($_SESSION['account']) || ($_SESSION['account']['role_id'] != 1 && $_SESSION['account']['role_id'] != 2)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$sectionID = isset($_POST['section_id']) ? intval($_POST['section_id']) : 0;
$styleCategory = isset($_POST['style_category']) ? $_POST['style_category'] : '';
$styleValue = isset($_POST['style_value']) ? $_POST['style_value'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : 'save';

// Validate input
if ($sectionID <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Initialize the ElementStyler
$styler = new ElementStyler();

// Process the request
$result = false;
$message = '';

try {
    if ($action === 'save' && !empty($styleCategory)) {
        $result = $styler->saveElementStyle($sectionID, $styleCategory, $styleValue);
        $message = 'Style saved successfully';
    } elseif ($action === 'reset') {
        $result = $styler->removeAllElementStyles($sectionID);
        $message = 'All styles reset successfully';
    } elseif ($action === 'get') {
        $styles = $styler->getElementStyles($sectionID);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'styles' => $styles]);
        exit;
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    exit;
}

// Return the result
header('Content-Type: application/json');
echo json_encode(['success' => $result, 'message' => $message]);
exit;
?>
