<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';
require_once '../classes/account.class.php';
require_once '../classes/db_connection.class.php';

$loginObj = new Login();
$pagesObj = new Pages();
$accountObj = new Accounts();

header('Content-Type: application/json');

// Collect errors for debugging
$errors = [];

// Check if required field is set
if (isset($_POST['collegeName'])) {
    $collegeName = $_POST['collegeName'];
    $defaultPassword = isset($_POST['defaultPassword']) ? $_POST['defaultPassword'] : 'wmsu123';
    $establishedYear = isset($_POST['establishedYear']) ? $_POST['establishedYear'] : null;
    
    // Validate established year format
    if (!empty($establishedYear) && !preg_match('/^\d{4}-\d{4}$/', $establishedYear)) {
        echo json_encode([
            "success" => false, 
            "message" => "Invalid academic year format. Please use YYYY-YYYY format."
        ]);
        exit;
    }
    
    // Get current user's subpage
    $subpage = isset($_SESSION['account']['subpage_assigned']) ? $_SESSION['account']['subpage_assigned'] : 1;
    $pageID = 3;
    
    // Handle logo upload with improved error handling
    $logoPath = null;
    $profileImgPath = '/defaults/profile.png'; // Default profile image path

    if (isset($_FILES['collegeLogo']) && $_FILES['collegeLogo']['error'] == 0) {
        $logoFile = $_FILES['collegeLogo'];
        
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($logoFile['type'], $allowedTypes)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        } elseif ($logoFile['size'] > $maxSize) {
            $errors[] = "File too large. Maximum size is 2MB.";
        } else {
            $logoFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $logoFile['name']);
            $logoUploadDir = $_SERVER['DOCUMENT_ROOT'] . '/CMS-WMSU-Website/uploads/colleges/logos/';
            
            // Create directory if needed
            if (!file_exists($logoUploadDir)) {
                if (!mkdir($logoUploadDir, 0755, true)) {
                    $errors[] = "Could not create logo upload directory.";
                }
            }
            
            if (empty($errors)) {
                $logoTargetPath = $logoUploadDir . $logoFileName;
                
                if (move_uploaded_file($logoFile['tmp_name'], $logoTargetPath)) {
                    $logoPath = '/CMS-WMSU-Website/uploads/colleges/logos/' . $logoFileName;
                    $profileImgPath = $logoPath; // Use same image for profile
                } else {
                    $errors[] = "Failed to move uploaded file.";
                }
            }
        }
    }
    
    // Add college to database
    $collegeAdded = false;
    $newSubpageId = null;
    
    try {
        $db = new Database();
        $conn = $db->connect();
        
        if (!$conn) {
            throw new Exception("Database connection failed");
        }
        
        // Start transaction
        $conn->beginTransaction();
        
        // Check for established_year column
        $checkColumnStmt = $conn->prepare("SHOW COLUMNS FROM subpages LIKE 'established_year'");
        $checkColumnStmt->execute();
        
        if ($checkColumnStmt->rowCount() == 0) {
            $conn->exec("ALTER TABLE subpages ADD COLUMN established_year VARCHAR(20) NULL AFTER isCollege");
        }
        
        // Add college
        if (method_exists($pagesObj, 'addCollege')) {
            $collegeAdded = $pagesObj->addCollege($collegeName, $logoPath, $pageID);
            
            // Get new subpage ID
            $sql = "SELECT subpageID FROM subpages WHERE subPageName = :collegeName ORDER BY subpageID DESC LIMIT 1";
            $qry = $conn->prepare($sql);
            $qry->bindParam(':collegeName', $collegeName);
            $qry->execute();
            $result = $qry->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $newSubpageId = $result['subpageID'];
                
                // Update established_year if provided
                if (!empty($establishedYear)) {
                    $updateSql = "UPDATE subpages SET established_year = :established_year WHERE subpageID = :subpageID";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bindParam(':established_year', $establishedYear);
                    $updateStmt->bindParam(':subpageID', $newSubpageId);
                    $updateStmt->execute();
                }
                
                $pagesObj->addNewCollegeName($pageID, 'College Profile', 'text', $collegeName, 'carousel-logo-text');
                
                // Create account for new college
                $emailName = $collegeName;
                if (stripos($collegeName, 'College of ') === 0) {
                    $emailName = substr($collegeName, 11);
                }
                
                $emailName = strtolower(preg_replace('/[^a-z0-9]/i', '', $emailName));
                $email = $emailName . '@wmsu.edu.ph';
                
                // Create account with profile image
                $accountObj->cleanAccount($email, $defaultPassword, 2, $newSubpageId);
                
                // Set profile image path before adding account
                $accountObj->profileImg = $profileImgPath;
                
                if ($accountObj->addAccount()) {
                    $collegeAdded = true;
                } else {
                    $errors[] = "Failed to create college account.";
                }
            } else {
                $errors[] = "Failed to retrieve new subpage ID.";
            }
        }
        
        if ($collegeAdded) {
            // Refresh session data
            if (isset($_SESSION['collegeData'])) {
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
            }
            
            // Add established year to page_sections
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
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bindParam(':pageID', $sectionData['pageID']);
                $insertStmt->bindParam(':subpage', $sectionData['subpage']);
                $insertStmt->bindParam(':indicator', $sectionData['indicator']);
                $insertStmt->bindParam(':description', $sectionData['description']);
                $insertStmt->bindParam(':elemType', $sectionData['elemType']);
                $insertStmt->bindParam(':content', $sectionData['content']);
                $insertStmt->execute();
            }
            
            $conn->commit();
            
            echo json_encode([
                "success" => true,
                "message" => "College department and content manager account added successfully."
            ]);
            exit;
        } else {
            $conn->rollBack();
            $errors[] = "Failed to add college department to database.";
        }
    } catch (Exception $e) {
        if (isset($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        $errors[] = "Exception: " . $e->getMessage();
    }
    
    // Error response
    error_log(json_encode($errors));
    echo json_encode([
        "success" => false, 
        "message" => "Failed to add college department.", 
        "errors" => $errors
    ]);
    exit;
    
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Missing required field. College name is required."
    ]);
    exit;
}
?>