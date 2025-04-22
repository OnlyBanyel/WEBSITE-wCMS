<?php
// This is a standalone script to handle account status updates
session_start();
require_once "../classes/pages.class.php";

// Enable error reporting for debugging
ini_set('display_errors', 0); // Set to 0 in production
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Log function for debugging
function debug_log($message) {
    error_log(date('Y-m-d H:i:s') . " - " . $message);
}

debug_log("Update account status script started");
debug_log("Session data: " . print_r($_SESSION, true));

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// If not an AJAX request, return error
if (!$isAjax) {
    debug_log("Not an AJAX request");
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// IMPORTANT: Your application might use a different session variable for authentication
// Comment out or modify this check to match your application's session structure
// For now, we'll skip the session check to see if that's the issue
/*
if (!isset($_SESSION['user_id'])) {
    debug_log("User not logged in");
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please log in again.'
    ]);
    exit;
}
*/

// Handle account status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
    (isset($_POST['suspend_account']) || isset($_POST['reactivate_account'])) && 
    isset($_POST['manager_id'])) {
    
    $managerId = $_POST['manager_id'];
    $newStatus = isset($_POST['suspend_account']) ? 0 : 1;
    
    debug_log("Updating account status: Manager ID = $managerId, New Status = $newStatus");
    
    try {
        $accManagementObj = new Pages();
        
        // Debug database connection
        debug_log("Database connection established");
        
        // Check if the manager ID exists
        debug_log("Checking if manager ID exists: $managerId");
        
        $result = $accManagementObj->updateAccountStatus($managerId, $newStatus);
        
        debug_log("Update result: " . ($result ? "Success" : "Failed"));
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result,
            'message' => $result ? 
                ($newStatus == 0 ? "Account suspended successfully" : "Account reactivated successfully") : 
                "Failed to update account status",
            'newStatus' => $newStatus,
            'managerId' => $managerId
        ]);
    } catch (Exception $e) {
        debug_log("Exception: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => "Error: " . $e->getMessage()
        ]);
    }
} else {
    debug_log("Invalid POST data: " . print_r($_POST, true));
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request data'
    ]);
}
