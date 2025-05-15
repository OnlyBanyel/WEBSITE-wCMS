<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create a log file for this specific script
$logFile = __DIR__ . '/../logs/college_name_update_' . date('Y-m-d') . '.log';

// Function to log messages to our custom log file
function custom_log($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    
    // Also log to PHP error log
    error_log($message);
    
    // Create directory if it doesn't exist
    $dir = dirname($logFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Append to log file
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Log request information
custom_log('updateCollegeName.php accessed with method: ' . $_SERVER['REQUEST_METHOD']);
custom_log('POST data: ' . print_r($_POST, true));
custom_log('Raw POST data: ' . file_get_contents('php://input'));

// Set headers to prevent caching
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

// Add CORS headers if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

// Initialize classes
$loginObj = new Login();
$pagesObj = new Pages();

// Headers already set above

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get subpage from session
$subpage = $_SESSION['account']['subpage_assigned'];

// Log incoming data for debugging
custom_log('updateCollegeName.php - POST data: ' . print_r($_POST, true));

// Check if college name is provided
if (isset($_POST['collegeName'])) {
    $value = trim($_POST['collegeName']);
    $textID = isset($_POST['textID']) ? $_POST['textID'] : null;
    $isNew = isset($_POST['isNew']) && ($_POST['isNew'] === '1' || $_POST['isNew'] === 1 || $_POST['isNew'] === true || $_POST['isNew'] === 'true');
    
    // Log the values for debugging
    custom_log("College Name: $value");
    custom_log("Text ID: $textID");
    custom_log("Is New: " . ($isNew ? 'Yes' : 'No'));
    
    // Validate input
    if (empty($value)) {
        custom_log("Error: College name cannot be empty");
        echo json_encode(['success' => false, 'message' => 'College name cannot be empty']);
        exit;
    }
    
    try {
        $result = false;
        
        if ($isNew || strpos($textID, 'temp_') === 0) {
            // Add new college name
            custom_log("Adding new college name: $value");
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
                echo json_encode(["success" => true, "message" => "College name added successfully", "isNew" => true]);
            } else {
                custom_log("Failed to add college name");
                echo json_encode(["success" => false, "message" => "Failed to add college name"]);
            }
        } else {
            // Update existing college name
            custom_log("Updating existing college name: $value, ID: $textID");
            $result = $pagesObj->changeContent($textID, $subpage, $value);
            
            if ($result) {
                // Refresh session data
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
                echo json_encode(["success" => true, "message" => "College name updated successfully", "isNew" => false]);
            } else {
                custom_log("Failed to update college name");
                echo json_encode(["success" => false, "message" => "Failed to update college name"]);
            }
        }
    } catch (Exception $e) {
        custom_log("Exception: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    custom_log("No college name provided in POST data");
    echo json_encode(["success" => false, "message" => "No college name provided"]);
}
?>
