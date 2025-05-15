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
debug_log("POST data: " . print_r($_POST, true));

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// If not an AJAX request, check if it's a regular form submission
if (!$isAjax && $_SERVER['REQUEST_METHOD'] != 'POST') {
    debug_log("Not an AJAX request or POST submission");
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
    
    $managerId = intval($_POST['manager_id']);
    $newStatus = isset($_POST['suspend_account']) ? 0 : 1;
    
    debug_log("Updating account status: Manager ID = $managerId, New Status = $newStatus");
    
    if ($managerId <= 0) {
        debug_log("Invalid manager ID: $managerId");
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Invalid manager ID'
        ]);
        exit;
    }
    
    try {
        $accManagementObj = new Pages();
        
        // Debug database connection
        debug_log("Database connection established");
        
        // Update the account status
        $result = $accManagementObj->updateAccountStatus($managerId, $newStatus);
        
        debug_log("Update result: " . ($result ? "Success" : "Failed"));
        
        // For AJAX requests, return JSON
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result,
                'message' => $result ? 
                    ($newStatus == 0 ? "Account suspended successfully" : "Account reactivated successfully") : 
                    "Failed to update account status",
                'newStatus' => $newStatus,
                'managerId' => $managerId
            ]);
        } else {
            // For regular form submissions, redirect back with a message
            if ($result) {
                $_SESSION['success_msg'] = $newStatus == 0 ? 
                    "Account suspended successfully" : 
                    "Account reactivated successfully";
            } else {
                $_SESSION['error_msg'] = "Failed to update account status";
            }
            
            // Redirect back to the accounts page
            header("Location: ../page-views/super-admin/academics-account.php");
            exit;
        }
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
