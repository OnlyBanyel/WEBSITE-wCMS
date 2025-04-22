<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

if (isset($_FILES['logoImage'])) {
    $imageIndex = $_POST['imageIndex']; 
    $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    $fileTmpPath = $_FILES['logoImage']['tmp_name'];
    $fileName = uniqid("collegeprofile_", true) . "." . pathinfo($_FILES['logoImage']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Return JSON response
        $subpage = $_SESSION['account']['subpage_assigned'];
        $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName; // Web-accessible path
        
        $success = false;
        
        if ($isNew) {
            // Add new carousel image
            $success = $pagesObj->addContent(
                $subpage,
                'College Profile',
                'image',
                null,
                $relativePath,
                'carousel-img'
            );
        } else {
            // Update existing carousel image
            $success = $pagesObj->uploadProfileImgs($relativePath, $imageIndex, $subpage);
        }
        
        if ($success) {
            // Refresh session data
            unset($_SESSION['collegeData']);
            $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
            
            echo json_encode(["success" => true, "newPath" => $relativePath]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update database."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to Upload Image."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No file uploaded."]);
}
?>
