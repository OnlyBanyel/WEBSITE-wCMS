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
    $establishedYear = isset($_POST['establishedYear']) ? $_POST['establishedYear'] : null;
    
    // Validate established year format if provided
    if (!empty($establishedYear) && !preg_match('/^\d{4}-\d{4}$/', $establishedYear)) {
        echo json_encode([
            "success" => false, 
            "message" => "Invalid academic year format. Please use YYYY-YYYY format."
        ]);
        exit;
    }
    
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
        // First check if we need to add established_year column to subpages table
        $db = new Database();
        $checkColumnSql = "SHOW COLUMNS FROM subpages LIKE 'established_year'";
        $checkColumnStmt = $db->connect()->prepare($checkColumnSql);
        $checkColumnStmt->execute();
        
        if ($checkColumnStmt->rowCount() == 0) {
            // Add the column if it doesn't exist
            $addColumnSql = "ALTER TABLE subpages ADD COLUMN established_year VARCHAR(20) NULL AFTER isCollege";
            $addColumnStmt = $db->connect()->prepare($addColumnSql);
            $addColumnStmt->execute();
            $errors[] = "Added established_year column to subpages table.";
        }
        
        // Now add the college with the established year
        if (method_exists($pagesObj, 'addCollege')) {
            // Use existing addCollege method
            $collegeAdded = $pagesObj->addCollege(
                $collegeName,
                $logoPath,
                $pageID
            );
            
            // Get the newly created subpage ID
            $sql = "SELECT subpageID FROM subpages WHERE subPageName = :collegeName ORDER BY subpageID DESC LIMIT 1";
            $qry = $db->connect()->prepare($sql);
            $qry->bindParam(':collegeName', $collegeName);
            $qry->execute();
            $result = $qry->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $newSubpageId = $result['subpageID'];
                
                // Update the established_year if provided
                if (!empty($establishedYear)) {
                    $updateSql = "UPDATE subpages SET established_year = :established_year WHERE subpageID = :subpageID";
                    $updateStmt = $db->connect()->prepare($updateSql);
                    $updateStmt->bindParam(':established_year', $establishedYear);
                    $updateStmt->bindParam(':subpageID', $newSubpageId);
                    $updateStmt->execute();
                }
                
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
            
            // Add established year information to the page_sections table too
            if (!empty($establishedYear) && $newSubpageId) {
                $sectionData = [
                    'pageID' => $pageID,
                    'subpage' => $newSubpageId,
                    'indicator' => 'Established Year',
                    'description' => 'established-academic-year',
                    'elemType' => 'text',
                    'content' => $establishedYear,
                    'imagePath' => null
                ];
                
                $insertSql = "INSERT INTO page_sections (pageID, subpage, indicator, description, elemType, content) 
                            VALUES (:pageID, :subpage, :indicator, :description, :elemType, :content)";
                $insertStmt = $db->connect()->prepare($insertSql);
                $insertStmt->bindParam(':pageID', $sectionData['pageID']);
                $insertStmt->bindParam(':subpage', $sectionData['subpage']);
                $insertStmt->bindParam(':indicator', $sectionData['indicator']);
                $insertStmt->bindParam(':description', $sectionData['description']);
                $insertStmt->bindParam(':elemType', $sectionData['elemType']);
                $insertStmt->bindParam(':content', $sectionData['content']);
                $insertStmt->execute();
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
