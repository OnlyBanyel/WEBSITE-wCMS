<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for AJAX responses
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Custom logging function
function logDebug($message, $data = null) {
    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/course_additions_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $logMessage .= " Data: " . json_encode($data, JSON_PRETTY_PRINT);
    }
    
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
}

// Log the raw request for debugging
logDebug("Received add course request", [
    'POST' => $_POST,
    'RAW' => file_get_contents('php://input')
]);

$loginObj = new Login;
$pagesObj = new Pages;

// Check if we're adding a new course
if (isset($_POST['courseType'])) {
    $courseType = $_POST['courseType'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    logDebug("Processing add course request", [
        'courseType' => $courseType,
        'subpage' => $subpage
    ]);
    
    // Get the current highest index for this course type
    $highestIndex = 0;
    foreach ($_SESSION['collegeData'] as $data) {
        if ($data['indicator'] === 'Courses and Programs' && 
            strpos($data['description'], 'course-header-' . $courseType) !== false) {
            $highestIndex++;
        }
    }
    
    $newIndex = $highestIndex + 1;
    
    logDebug("Determined new course index", [
        'highestIndex' => $highestIndex,
        'newIndex' => $newIndex
    ]);
    
    // Default course name based on type
    $newCourseName = ($courseType === 'undergrad') 
        ? "New Undergraduate Program" 
        : "New Graduate Program";
    
    // Add the new course header
    $newCourseID = $pagesObj->addContent(
        $subpage,
        'Courses and Programs',
        'text',
        $newCourseName,
        null,
        'course-header-' . $courseType
    );
    
    if ($newCourseID) {
        logDebug("Successfully added new course header", [
            'newCourseID' => $newCourseID,
            'courseName' => $newCourseName
        ]);
        
        // Add a default empty outcome
        $defaultOutcome = $pagesObj->addContent(
            $subpage,
            'Courses and Programs',
            'text',
            'Program outcome description',
            null,
            $courseType . '-course-list-items-' . $newIndex
        );
        
        logDebug("Added default outcome", [
            'outcomeID' => $defaultOutcome,
            'courseIndex' => $newIndex
        ]);
        
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        echo json_encode([
            "success" => true,
            "message" => "New course added successfully",
            "newCourseID" => $newCourseID,
            "newIndex" => $newIndex
        ]);
        exit;
    } else {
        logDebug("Failed to add new course header", [
            'courseName' => $newCourseName
        ]);
        
        echo json_encode(["success" => false, "message" => "Failed to add new course."]);
        exit;
    }
}

logDebug("Invalid request - missing courseType", $_POST);
echo json_encode(["success" => false, "message" => "Invalid request."]);
?>
