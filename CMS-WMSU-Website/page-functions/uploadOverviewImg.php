<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

if (isset($_FILES['overviewImg']) && isset($_POST['imageIndex'])) {
    $sectionID = $_POST['imageIndex']; 
    $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    $fileTmpPath = $_FILES['overviewImg']['tmp_name'];
    $fileName = uniqid("overviewImg_", true) . "." . pathinfo($_FILES['overviewImg']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $subpage = $_SESSION['account']['subpage_assigned'];
        $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName;

        if ($isNew) {
            // Add new image
            $result = $pagesObj->addContent(
                $subpage,
                'College Overview',
                'image',
                null,
                $relativePath,
                'geninfo-front-img'
            );
        } else {
            // Update existing image
            $result = $pagesObj->uploadImgs($relativePath, $sectionID, $subpage);
        }

        if ($result) {
            $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
            echo json_encode(["success" => true, "newPath" => $relativePath]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update database."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to Upload Image."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing required parameters."]);
}
?>
