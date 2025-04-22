<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

header('Content-Type: application/json');

if (isset($_POST['collegeName'])) {
    $value = $_POST['collegeName'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    $textID = $_POST['textID'];
    $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';

    $result = false;
    
    if ($isNew) {
        // Add new college name
        $result = $pagesObj->addContent(
            $subpage,
            'College Profile',
            'text',
            $value,
            null,
            'carousel-logo-text'
        );
    } else {
        // Update existing college name
        $result = $pagesObj->changeContent($textID, $subpage, $value);
    }
    
    if ($result) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        echo json_encode(["success" => true, "isNew" => $isNew]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to " . ($isNew ? "add" : "update") . " college name."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No college name provided."]);
}
?>
