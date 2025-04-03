<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;



if (isset($_FILES['deptImg']) && isset($_POST['sectionID'])) {
    $value = $_POST['deptName'];
    $textID = $_POST['textID'];
    $sectionID = $_POST['sectionID']; 
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    $fileTmpPath = $_FILES['deptImg']['tmp_name'];
    $fileName = uniqid("department_", true) . "." . pathinfo($_FILES['deptImg']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $subpage = $_SESSION['account']['subpage_assigned'];
        $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName;

        $pagesObj->changeContent($textID, $subpage, $value);
        $pagesObj->uploadImgs($relativePath, $sectionID, $subpage);
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);

        echo json_encode(["success" => true, "newPath" => $relativePath]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to Upload Image."]);
    }
} elseif (isset($_POST['deptName'])) {
    $value = $_POST['deptName'];
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
    echo json_encode(["success" => false, "message" => "No file uploaded or section ID missing."]);
}
?>
