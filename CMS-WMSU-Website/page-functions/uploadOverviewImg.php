<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

// Debug array to track execution
$debug = [];
$debug['request_method'] = $_SERVER['REQUEST_METHOD'];
$debug['post_data'] = $_POST;
$debug['files_data'] = $_FILES;

if (isset($_FILES['overviewImg']) && isset($_POST['imageIndex'])) {
    $sectionID = $_POST['imageIndex']; 
    $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    $fileTmpPath = $_FILES['overviewImg']['tmp_name'];
    $fileName = uniqid("overviewImg_", true) . "." . pathinfo($_FILES['overviewImg']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;
    $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName;
    
    $debug['section_id'] = $sectionID;
    $debug['is_new'] = $isNew;
    $debug['upload_dir'] = $uploadDir;
    $debug['dest_path'] = $destPath;
    $debug['relative_path'] = $relativePath;

    // Check if upload directory exists and is writable
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $debug['error'] = "Failed to create upload directory";
            echo json_encode(["success" => false, "message" => "Failed to create upload directory", "debug" => $debug]);
            exit;
        }
    }
    
    if (!is_writable($uploadDir)) {
        $debug['error'] = "Upload directory is not writable";
        echo json_encode(["success" => false, "message" => "Upload directory is not writable", "debug" => $debug]);
        exit;
    }

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $subpage = $_SESSION['account']['subpage_assigned'];
        $debug['subpage'] = $subpage;

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
            
            $debug['add_result'] = $result;
        } else {
            // Update existing image
            $result = $pagesObj->uploadImgs($relativePath, $sectionID, $subpage);
            $debug['update_result'] = $result;
        }

        if ($result) {
            $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
            echo json_encode(["success" => true, "newPath" => $relativePath, "debug" => $debug]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update database.", "debug" => $debug]);
        }
    } else {
        $debug['error'] = "Failed to move uploaded file";
        echo json_encode(["success" => false, "message" => "Failed to Upload Image.", "debug" => $debug]);
    }
} else {
    $debug['error'] = "Missing required parameters";
    echo json_encode(["success" => false, "message" => "Missing required parameters.", "debug" => $debug]);
}
?>
