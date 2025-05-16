<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';
require_once '../classes/account.class.php';

// Initialize classes
$loginObj = new Login();
$pagesObj = new Pages();
$accountObj = new Accounts();

header('Content-Type: application/json');

// Set default timezone
date_default_timezone_set('Asia/Manila');

// Validate request
if (!isset($_POST['collegeName'])) {
    echo json_encode(["success" => false, "message" => "Missing required field. College name is required."]);
    exit;
}

// Process input
$collegeName = trim($_POST['collegeName']);
$defaultPassword = $_POST['defaultPassword'] ?? 'wmsu123';
$establishedYear = $_POST['establishedYear'] ?? null;

// Input validation
if (empty($collegeName)) {
    echo json_encode(["success" => false, "message" => "College name cannot be empty"]);
    exit;
}

if (!empty($establishedYear) && !preg_match('/^\d{4}-\d{4}$/', $establishedYear)) {
    echo json_encode(["success" => false, "message" => "Invalid academic year format. Please use YYYY-YYYY format."]);
    exit;
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
        echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, PNG, and GIF are allowed."]);
        exit;
    }
    
    if ($logoFile['size'] > $maxSize) {
        echo json_encode(["success" => false, "message" => "File too large. Maximum size is 2MB."]);
        exit;
    }

    // Configure upload paths
    $uploadBase = $_SERVER['DOCUMENT_ROOT'] . '/CMS-WMSU-Website/uploads/colleges/logos/';
    $webAccessiblePath = '/CMS-WMSU-Website/uploads/colleges/logos/';
    
    // Ensure directory exists
    if (!file_exists($uploadBase)) {
        mkdir($uploadBase, 0755, true);
    }

    // Process file upload
    $logoFileName = time() . '_' . preg_replace('/[^\w\.\-]/', '_', $logoFile['name']);
    $logoTargetPath = $uploadBase . $logoFileName;
    
    if (move_uploaded_file($logoFile['tmp_name'], $logoTargetPath)) {
        $logoPath = $webAccessiblePath . $logoFileName;
        $profileImgPath = $logoPath;
    }
}

// Add college
if (!$pagesObj->addCollege($collegeName, $logoPath, $pageID)) {
    echo json_encode(["success" => false, "message" => "Failed to add college"]);
    exit;
}

// Get new subpage ID
$newSubpageId = $pagesObj->getLastInsertedCollegeId($collegeName);
if (!$newSubpageId) {
    echo json_encode(["success" => false, "message" => "Failed to retrieve new subpage ID"]);
    exit;
}

// Update established year if provided
if (!empty($establishedYear)) {
    $pagesObj->updateEstablishedYear($newSubpageId, $establishedYear);
}

// Add college name to page sections
if (!$pagesObj->addNewCollegeName($pageID, 'College Profile', 'text', $collegeName, 'carousel-logo-text')) {
    echo json_encode(["success" => false, "message" => "Failed to add college name to sections"]);
    exit;
}

// Create account for college
$emailName = preg_replace('/^College of /i', '', $collegeName);
$emailName = strtolower(preg_replace('/[^a-z0-9]/', '', $emailName)) . '@wmsu.edu.ph';

$accountObj->cleanAccount($emailName, $defaultPassword, 2, $newSubpageId);
$accountObj->profileImg = $profileImgPath;

if (!$accountObj->addAccount()) {
    echo json_encode(["success" => false, "message" => "Failed to create college account"]);
    exit;
}

// Add established year to page sections if provided
if (!empty($establishedYear)) {
    $pagesObj->insertSectionContent($establishedYear, 'Established Year', 'established-academic-year', $pageID, $newSubpageId);
}

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
exit;