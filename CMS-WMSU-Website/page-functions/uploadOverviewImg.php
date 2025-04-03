<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;



if (isset($_FILES['overviewImg']) && isset($_POST['imageIndex'])) {
    $sectionID = $_POST['imageIndex']; 
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    $fileTmpPath = $_FILES['overviewImg']['tmp_name'];
    $fileName = uniqid("overviewImg_", true) . "." . pathinfo($_FILES['overviewImg']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $subpage = $_SESSION['account']['subpage_assigned'];
        $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName;

        $pagesObj->uploadImgs($relativePath, $sectionID, $subpage);
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);

        echo json_encode(["success" => true, "newPath" => $relativePath]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to Upload Image."]);
    }
}else {
    echo json_encode(["success" => false, "message" => "Failed to Upload Image."]);
} 