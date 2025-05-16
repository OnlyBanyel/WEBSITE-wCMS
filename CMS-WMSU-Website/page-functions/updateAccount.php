<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set content type to JSON
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

// Initialize classes
$loginObj = new Login();
$pagesObj = new Pages();

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get subpage from session
$subpage = $_SESSION['account']['subpage_assigned'];

// Handle college name update
if (isset($_POST['collegeName'])) {
    $value = trim($_POST['collegeName']);
    $textID = isset($_POST['textID']) ? $_POST['textID'] : null;
    $isNew = isset($_POST['isNew']) && ($_POST['isNew'] === '1' || $_POST['isNew'] === 1 || $_POST['isNew'] === true || $_POST['isNew'] === 'true');
    
    // Debug information
    error_log("Processing college name update for subpage: " . $subpage);
    error_log("College Name: $value");
    error_log("Text ID: $textID");
    error_log("Is New: " . ($isNew ? 'Yes' : 'No'));

    // Validate input
    if (empty($value)) {
        error_log("Error: College name cannot be empty");
        echo json_encode(['success' => false, 'message' => 'College name cannot be empty']);
        exit;
    }

    try {
        $result = false;
        
        if ($isNew || strpos($textID, 'temp_') === 0) {
            // Add new college name
            error_log("Adding new college name: $value");
            $result = $pagesObj->addContent(
                $subpage,
                'College Profile',
                'text',
                $value,
                null,
                'carousel-logo-text'
            );
            
            if ($result) {
                // Refresh session data
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
                echo json_encode([
                    "success" => true, 
                    "message" => "College name added successfully", 
                    "isNew" => true
                ]);
            } else {
                error_log("Failed to add college name");
                echo json_encode([
                    "success" => false, 
                    "message" => "Failed to add college name"
                ]);
            }
        } else {
            // Update existing college name
            error_log("Updating existing college name: $value, ID: $textID");
            $result = $pagesObj->changeContent($textID, $subpage, $value);
            
            if ($result) {
                // Refresh session data
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
                echo json_encode([
                    "success" => true, 
                    "message" => "College name updated successfully", 
                    "isNew" => false
                ]);
            } else {
                error_log("Failed to update college name");
                echo json_encode([
                    "success" => false, 
                    "message" => "Failed to update college name"
                ]);
            }
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        echo json_encode([
            "success" => false, 
            "message" => "Error: " . $e->getMessage()
        ]);
    }
} else {
    // Debug what was received
    error_log("No college name provided in POST data");
    error_log("POST data: " . json_encode($_POST));
    
    echo json_encode([
        "success" => false, 
        "message" => "No college name provided",
        "post" => $_POST
    ]);
}
?>