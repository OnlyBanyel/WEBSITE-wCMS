<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login();
$pagesObj = new Pages();

header('Content-Type: application/json'); // Ensure JSON response

// Collect errors for debugging
$errors = [];

// Check if required field is set
if (isset($_POST['collegeName'])) {
    $collegeName = $_POST['collegeName'];
    
    // Get current user's subpage if available, otherwise use default
    $subpage = isset($_SESSION['account']['subpage_assigned']) ? $_SESSION['account']['subpage_assigned'] : 1;
    $pageID = 3;
    // Handle logo upload
    $logoPath = null;


    if (isset($_FILES['collegeLogo']) && $_FILES['collegeLogo']['error'] == 0) {
        $logoFile = $_FILES['collegeLogo'];
        $logoFileName = time() . '_' . basename($logoFile['name']);
        $logoUploadDir = '/WEBSITE-wCMS/CMS-WMSU-Website/uploads/colleges/logos/';
        
        // Create directory if it doesn't exist
        if (!file_exists($logoUploadDir)) {
            mkdir($logoUploadDir, 0777, true);
        }
        
        $logoTargetPath = $logoUploadDir . $logoFileName;
        
        if (move_uploaded_file($logoFile['tmp_name'], $logoTargetPath)) {
            $logoPath = $logoTargetPath;
        } else {
            $errors[] = "Failed to upload logo image.";
        }
    }
    
    // Add college to database
    $collegeAdded = false;
    
    try {
        // Option 1: If you have a dedicated method for adding colleges
        if (method_exists($pagesObj, 'addCollege')) {
            $collegeAdded = $pagesObj->addCollege(
                $collegeName,
                $logoPath,
                $pageID
            );
            $pagesObj->addNewCollegeName($pageID, 'College Profile', 'text', $collegeName, 'carousel-logo-text');

        }         
        if ($collegeAdded) {
            // Refresh college data in session if needed
            if (isset($_SESSION['collegeData'])) {
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
            }
            
            echo json_encode([
                "success" => true,
                "message" => "College department added successfully."
            ]);
            exit;
        } else {
            $errors[] = "Failed to add college department to database.";
        }
    } catch (Exception $e) {
        $errors[] = "Exception: " . $e->getMessage();
    }
    
    // If we reach here, something went wrong
    error_log(json_encode($errors)); // Log errors
    echo json_encode([
        "success" => false, 
        "message" => "Failed to add college department.", 
        "errors" => $errors
    ]);
    exit;
    
} else {
    error_log("Missing required POST field for adding college.");
    echo json_encode([
        "success" => false, 
        "message" => "Missing required field. College name is required."
    ]);
    exit;
}
?>
