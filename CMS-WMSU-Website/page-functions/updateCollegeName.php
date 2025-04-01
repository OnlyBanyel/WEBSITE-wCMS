<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;


if (isset($_POST['collegeName'])) {
    $value = $_POST['collegeName'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    $textID = $_POST['textID'];

    if (isset($textID)){
    $pagesObj->changeContent($textID, $subpage, $value);
    $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    echo json_encode(["success" => true]);
    }else {
        echo json_encode(["success" => false, "message" => "Failed to Update Text."]);
    }
    
}
else {
    echo json_encode(["success" => false, "message" => "No Updates or section ID missing."]);
}
?>
