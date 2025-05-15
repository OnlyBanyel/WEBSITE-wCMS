<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Custom logging function
function logDebug($message, $data = null) {
    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    $logFile = $logDir . '/dept_upload_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $logMessage .= " Data: " . print_r($data, true);
    }
    
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
    error_log($logMessage);
}

// Set content type to JSON
header('Content-Type: application/json');

// Log raw request data
$rawPostData = file_get_contents('php://input');
logDebug("Raw POST data", $rawPostData);
logDebug("POST array", $_POST);
logDebug("FILES array", $_FILES);

// Handle department deletion
if (isset($_POST['deleteDepartment'])) {
    logDebug("Processing department deletion");
    $textID = $_POST['textID'];
    $isNew = $_POST['isNew'] === '1';
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    if (!$isNew) {
        // Only delete from database if it's not a temporary department
        $result = $pagesObj->deleteContent($textID);
        
        if (!$result) {
            logDebug("Failed to delete department", ["textID" => $textID]);
            echo json_encode(["success" => false, "message" => "Failed to delete department."]);
            exit;
        }
    }
    
    // Refresh session data
    $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    
    logDebug("Department deleted successfully", ["textID" => $textID]);
    echo json_encode(["success" => true]);
    exit;
}

// Get the update type (name or image)
$updateType = isset($_POST['updateType']) ? $_POST['updateType'] : null;
logDebug("Update type", $updateType);

// Handle department name update
if ($updateType === 'name' && isset($_POST['deptName']) && isset($_POST['textID'])) {
    logDebug("Processing department name update");
    $value = $_POST['deptName'];
    $textID = $_POST['textID'];
    $isNew = isset($_POST['isNew']) && ($_POST['isNew'] === '1' || $_POST['isNew'] === 'true' || $_POST['isNew'] === true);
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    logDebug("Department name update details", [
        "value" => $value,
        "textID" => $textID,
        "isNew" => $isNew,
        "subpage" => $subpage
    ]);
    
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
            logDebug("Failed to add new department");
            echo json_encode(["success" => false, "message" => "Failed to add new department."]);
            exit;
        }
        
        // Get the newly created department ID for image association
        $textID = $result;
        logDebug("New department added", ["new textID" => $textID]);
    } else {
        // Update existing department
        $result = $pagesObj->changeContent($textID, $subpage, $value);
        
        if (!$result) {
            logDebug("Failed to update department name", ["textID" => $textID]);
            echo json_encode(["success" => false, "message" => "Failed to update department name."]);
            exit;
        }
        
        logDebug("Department name updated successfully", ["textID" => $textID]);
    }
    
    // Refresh session data
    $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    
    echo json_encode(["success" => true, "message" => "Department name updated successfully"]);
    exit;
}

// Handle department image update
if ($updateType === 'image' && isset($_POST['textID']) && isset($_FILES['deptImg']) && $_FILES['deptImg']['error'] === UPLOAD_ERR_OK) {
    logDebug("Processing department image update");
    $textID = $_POST['textID'];
    $sectionID = $_POST['sectionID']; 
    $imgIsNew = isset($_POST['imgIsNew']) && ($_POST['imgIsNew'] === '1' || $_POST['imgIsNew'] === 'true' || $_POST['imgIsNew'] === true);
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    logDebug("Department image update details", [
        "textID" => $textID,
        "sectionID" => $sectionID,
        "imgIsNew" => $imgIsNew,
        "subpage" => $subpage
    ]);
    
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/";
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileTmpPath = $_FILES['deptImg']['tmp_name'];
    $fileName = uniqid("department_", true) . "." . pathinfo($_FILES['deptImg']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $relativePath = "/WEBSITE-wCMS/imgs/" . $fileName;
        logDebug("Image uploaded successfully", ["path" => $relativePath]);

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
            
            logDebug("Adding new image", ["result" => $imgResult]);
        } else {
            // Update existing image
            $imgResult = $pagesObj->uploadImgs($relativePath, $sectionID, $subpage);
            logDebug("Updating existing image", ["result" => $imgResult, "sectionID" => $sectionID]);
        }
        
        if (!$imgResult) {
            logDebug("Failed to update department image in database", ["sectionID" => $sectionID]);
            echo json_encode(["success" => false, "message" => "Failed to update image in database."]);
            exit;
        }
        
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        echo json_encode([
            "success" => true, 
            "message" => "Department image updated successfully",
            "newPath" => $relativePath
        ]);
    } else {
        logDebug("Failed to move uploaded file", [
            "tmp_path" => $fileTmpPath,
            "dest_path" => $destPath,
            "error" => error_get_last()
        ]);
        echo json_encode(["success" => false, "message" => "Failed to upload image."]);
    }
    exit;
}

// If we get here, something went wrong
logDebug("Invalid request - missing required parameters");
echo json_encode([
    "success" => false, 
    "message" => "Missing required parameters.",
    "received" => [
        "POST" => $_POST,
        "FILES" => isset($_FILES) ? array_keys($_FILES) : "No files"
    ]
]);
?>
