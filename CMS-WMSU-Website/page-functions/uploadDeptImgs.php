<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

header('Content-Type: application/json');

// Handle both text and image updates
if (isset($_POST['deptName']) && isset($_POST['textID'])) {
    $value = $_POST['deptName'];
    $textID = $_POST['textID'];
    $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Handle department name update
    if ($isNew) {
        // Add new department
        $result = $pagesObj->addContent(
            $subpage,
            'Departments',
            'text',
            $value,
            null,
            'department-name'
        );
        
        if (!$result) {
            echo json_encode(["success" => false, "message" => "Failed to add new department."]);
            exit;
        }
        
        // Get the newly created department ID for image association
        $textID = $result;
    } else {
        // Update existing department
        $result = $pagesObj->changeContent($textID, $subpage, $value);
        
        if (!$result) {
            echo json_encode(["success" => false, "message" => "Failed to update department name."]);
            exit;
        }
    }
    
    if (isset($_FILES['deptImg']) && $_FILES['deptImg']['error'] === UPLOAD_ERR_OK) {
        $sectionID = $_POST['sectionID']; 
        $imgIsNew = isset($_POST['imgIsNew']) && $_POST['imgIsNew'] === '1';
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileTmpPath = $_FILES['deptImg']['tmp_name'];
        $fileName = uniqid("department_", true) . "." . pathinfo($_FILES['deptImg']['name'], PATHINFO_EXTENSION);
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName;

            if ($imgIsNew) {
                // Add new image
                $imgResult = $pagesObj->addContent(
                    $subpage,
                    'College Overview',
                    'image',
                    null,
                    $relativePath,
                    'geninfo-front-img'
                );
            } else {
                // Update existing image
                $imgResult = $pagesObj->uploadImgs($relativePath, $sectionID, $subpage);
            }
            
            if (!$imgResult) {
                // Log error but continue
                error_log("Failed to update department image: " . $sectionID);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload image."]);
            exit;
        }
    }
    
    // Refresh session data
    $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    
    echo json_encode(["success" => true, "isNew" => $isNew]);
} else {
    echo json_encode(["success" => false, "message" => "Missing required parameters."]);
}
?>
