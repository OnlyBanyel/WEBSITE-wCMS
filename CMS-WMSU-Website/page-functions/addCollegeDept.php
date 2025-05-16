<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';
require_once '../classes/account.class.php';
require_once '../classes/db_connection.class.php';

// Initialize classes
$loginObj = new Login();
$pagesObj = new Pages();
$accountObj = new Accounts();

header('Content-Type: application/json');

// Set default timezone
date_default_timezone_set('Asia/Manila');

// Improved error handling
function handleError($message, $errors = []) {
    error_log("ERROR: " . $message . " - " . json_encode($errors));
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => $message,
        "errors" => $errors
    ]);
    exit;
}

// Validate request
if (!isset($_POST['collegeName'])) {
    handleError("Missing required field. College name is required.");
}

// Process input
$collegeName = trim($_POST['collegeName']);
$defaultPassword = $_POST['defaultPassword'] ?? 'wmsu123';
$establishedYear = $_POST['establishedYear'] ?? null;

// Input validation
if (empty($collegeName)) {
    handleError("College name cannot be empty");
}

if (!empty($establishedYear) && !preg_match('/^\d{4}-\d{4}$/', $establishedYear)) {
    handleError("Invalid academic year format. Please use YYYY-YYYY format.");
}

// Get current user context
$subpage = $_SESSION['account']['subpage_assigned'] ?? 1;
$pageID = 3;

// File upload handling
$logoPath = null;
$profileImgPath = '/defaults/profile.png';

if (isset($_FILES['collegeLogo']) && $_FILES['collegeLogo']['error'] == 0) {
    $logoFile = $_FILES['collegeLogo'];
    
    // File validation
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024;
    
    if (!in_array($logoFile['type'], $allowedTypes)) {
        handleError("Invalid file type. Only JPG, PNG, and GIF are allowed.");
    }
    
    if ($logoFile['size'] > $maxSize) {
        handleError("File too large. Maximum size is 2MB.");
    }

    // Configure upload paths
    $uploadBase = $_SERVER['DOCUMENT_ROOT'] . '/uploads/colleges/logos/';
    $webAccessiblePath = '/uploads/colleges/logos/';
    
    // Ensure directory exists
    if (!file_exists($uploadBase)) {
        if (!mkdir($uploadBase, 0755, true) && !is_dir($uploadBase)) {
            // Fallback to system temp directory
            $uploadBase = sys_get_temp_dir() . '/college_logos/';
            $webAccessiblePath = '/temp_uploads/';
            if (!file_exists($uploadBase) && !mkdir($uploadBase, 0755, true)) {
                error_log("Failed to create upload directory");
                // Continue without file upload
            }
        }
    }

    // Process file upload
    if (is_dir($uploadBase) && is_writable($uploadBase)) {
        $logoFileName = time() . '_' . preg_replace('/[^\w\.\-]/', '_', $logoFile['name']);
        $logoTargetPath = $uploadBase . $logoFileName;
        
        if (move_uploaded_file($logoFile['tmp_name'], $logoTargetPath)) {
            $logoPath = $webAccessiblePath . $logoFileName;
            $profileImgPath = $logoPath;
        } else {
            error_log("Upload failed: " . json_encode(error_get_last()));
        }
    }
}

// Database operations
try {
    $db = new Database();
    $conn = $db->connect();
    
    if (!$conn) {
        throw new Exception("Database connection failed");
    }
    
    // Start transaction
    $conn->beginTransaction();
    
    // Check if column exists before adding
    $check = $conn->query("SHOW COLUMNS FROM subpages LIKE 'established_year'");
    if ($check->rowCount() == 0) {
        $conn->exec("ALTER TABLE subpages ADD COLUMN established_year VARCHAR(20) NULL AFTER isCollege");
    }
    
    // Add college
    if (!$pagesObj->addCollege($collegeName, $logoPath, $pageID)) {
        throw new Exception("Failed to add college");
    }
    
    // Get new subpage ID
    $stmt = $conn->prepare("SELECT subpageID FROM subpages WHERE subPageName = ? ORDER BY subpageID DESC LIMIT 1");
    $stmt->execute([$collegeName]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        throw new Exception("Failed to retrieve new subpage ID");
    }
    
    $newSubpageId = $result['subpageID'];
    
    // Update established year if provided
    if (!empty($establishedYear)) {
        $stmt = $conn->prepare("UPDATE subpages SET established_year = ? WHERE subpageID = ?");
        $stmt->execute([$establishedYear, $newSubpageId]);
    }
    
    // Add college name to page sections
    if (!$pagesObj->addNewCollegeName($pageID, 'College Profile', 'text', $collegeName, 'carousel-logo-text')) {
        throw new Exception("Failed to add college name to sections");
    }
    
    // Create account for college
    $emailName = preg_replace('/^College of /i', '', $collegeName);
    $emailName = strtolower(preg_replace('/[^a-z0-9]/', '', $emailName)) . '@wmsu.edu.ph';
    
    $accountObj->cleanAccount($emailName, $defaultPassword, 2, $newSubpageId);
    $accountObj->profileImg = $profileImgPath;
    
    if (!$accountObj->addAccount()) {
        throw new Exception("Failed to create college account");
    }
    
    // Add established year to page sections if provided
    if (!empty($establishedYear)) {
        $stmt = $conn->prepare("INSERT INTO page_sections 
                              (pageID, subpage, indicator, description, elemType, content) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $pageID,
            $newSubpageId,
            'Established Year',
            'established-academic-year',
            'text',
            $establishedYear
        ]);
    }
    
    // Commit transaction
    $conn->commit();
    
    // Update session data
    if (isset($_SESSION['collegeData'])) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    }
    
    // Success response
    echo json_encode([
        "success" => true,
        "message" => "College department and content manager account added successfully.",
        "collegeId" => $newSubpageId
    ]);
    
} catch (PDOException $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    handleError("Database error occurred", ["database_error" => $e->getMessage()]);
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    handleError("Failed to add college department", ["exception" => $e->getMessage()]);
}
?>