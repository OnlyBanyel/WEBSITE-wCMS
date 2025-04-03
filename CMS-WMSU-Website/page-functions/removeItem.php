<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login();
$pagesObj = new Pages();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    // Validate required fields
    if (!isset($_POST['sectionID']) || !is_numeric($_POST['sectionID'])) {
        throw new Exception('Invalid section ID');
    }

    $sectionID = (int)$_POST['sectionID'];
    $subpage = $_SESSION['account']['subpage_assigned'];

    // Delete the item
    $deleteSuccess = $pagesObj->deleteContent($sectionID, $subpage);
    

    if ($deleteSuccess) {
        $response['success'] = true;
        $response['message'] = 'Item deleted successfully';
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    } else {
        throw new Exception('Failed to delete item from database');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Delete Error: " . $e->getMessage());
}

echo json_encode($response);
?>