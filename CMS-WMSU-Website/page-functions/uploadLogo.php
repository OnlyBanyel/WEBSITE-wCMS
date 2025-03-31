<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

if (isset($_FILES['logoImage'])) {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    $fileTmpPath = $_FILES['logoImage']['tmp_name'];
    $fileName = uniqid("logo_", true) . "." . pathinfo($_FILES['logoImage']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Return JSON response
        $subpage = $_SESSION['account']['subpage_assigned'];
        $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName; // Web-accessible path
        $pagesObj->uploadLogo($relativePath, $subpage);

        unset($_SESSION['collegeData']);
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($_SESSION['account']['subpage_assigned']);

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to Upload Image."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No file uploaded."]);
}
?>
