<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';
require_once '../classes/account.class.php';

$loginObj = new Login();
$pagesObj = new Pages();
$accountObj = new Accounts();

header('Content-Type: application/json'); // Ensure JSON response

// Collect errors for debugging
$errors = [];

// Check if required field is set
if (isset($_POST['collegeName'])) {
    $collegeName = $_POST['collegeName'];
    $defaultPassword = isset($_POST['defaultPassword']) ? $_POST['defaultPassword'] : 'wmsu123';
    
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
    $newSubpageId = null;
    
    try {
        // Option 1: If you have a dedicated method for adding colleges
        if (method_exists($pagesObj, 'addCollege')) {
            $collegeAdded = $pagesObj->addCollege(
                $collegeName,
                $logoPath,
                $pageID
            );
            
            // Get the newly created subpage ID
            $sql = "SELECT subpageID FROM subpages WHERE subPageName = :collegeName ORDER BY subpageID DESC LIMIT 1";
            $db = new Database();
            $qry = $db->connect()->prepare($sql);
            $qry->bindParam(':collegeName', $collegeName);
            $qry->execute();
            $result = $qry->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $newSubpageId = $result['subpageID'];
                $pagesObj->addNewCollegeName($pageID, 'College Profile', 'text', $collegeName, 'carousel-logo-text');
                
                // Create account for the new college department
                // Generate email from college name
                $emailName = $collegeName;
                if (stripos($collegeName, 'College of ') === 0) {
                    $emailName = substr($collegeName, 11);
                }
                
                // Remove spaces and special characters
                $emailName = strtolower(preg_replace('/[^a-z0-9]/i', '', $emailName));
                $email = $emailName . '@wmsu.edu.ph';
                
                // Create the account
                $accountObj->cleanAccount($email, $defaultPassword, 2, $newSubpageId);
                $accountObj->addAccount();
                
                $collegeAdded = true;
            } else {
                $errors[] = "Failed to retrieve new subpage ID.";
            }
        }         
        
        if ($collegeAdded) {
            // Refresh college data in session if needed
            if (isset($_SESSION['collegeData'])) {
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
            }
            
            echo json_encode([
                "success" => true,
                "message" => "College department and content manager account added successfully."
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
