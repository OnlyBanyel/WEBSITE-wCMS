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
    
    $logFile = $logDir . '/course_deletions_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $logMessage .= " Data: " . json_encode($data, JSON_PRETTY_PRINT);
    }
    
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
}

// Log the raw request for debugging
logDebug("Received course deletion request", [
    'POST' => $_POST,
    'RAW' => file_get_contents('php://input')
]);

$loginObj = new Login;
$pagesObj = new Pages;

if (isset($_POST['sectionID']) && isset($_POST['courseType'])) {
    $sectionID = $_POST['sectionID'];
    $courseType = $_POST['courseType'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    logDebug("Processing deletion request", [
        'sectionID' => $sectionID,
        'courseType' => $courseType,
        'subpage' => $subpage
    ]);
    
    // First delete all outcomes associated with this course
    $coursesAndPrograms = $_SESSION['collegeData'];
    $courseIndex = null;
    
    // Find the course index
    foreach ($coursesAndPrograms as $item) {
        if ($item['sectionID'] == $sectionID) {
            logDebug("Found course to delete", $item);
            
            // Extract course index from description
            if (preg_match('/-(\d+)$/', $item['description'], $matches)) {
                $courseIndex = $matches[1];
                break;
            } else {
                // If we can't find the index in the description, try to determine it by position
                $count = 0;
                foreach ($coursesAndPrograms as $countItem) {
                    if ($countItem['description'] === 'course-header-' . $courseType && $countItem['sectionID'] <= $sectionID) {
                        $count++;
                    }
                }
                $courseIndex = $count;
                logDebug("Determined course index by position", ['index' => $courseIndex]);
                break;
            }
        }
    }
    
    if ($courseIndex !== null) {
        logDebug("Deleting outcomes for course index", ['courseIndex' => $courseIndex]);
        
        // Delete all outcomes for this course
        $deletedOutcomes = 0;
        foreach ($coursesAndPrograms as $item) {
            if (strpos($item['description'], $courseType . '-course-list-items-' . $courseIndex) !== false) {
                $result = $pagesObj->deleteContent($item['sectionID'], $subpage);
                if ($result) {
                    $deletedOutcomes++;
                    logDebug("Deleted outcome", [
                        'sectionID' => $item['sectionID'],
                        'description' => $item['description']
                    ]);
                } else {
                    logDebug("Failed to delete outcome", [
                        'sectionID' => $item['sectionID'],
                        'description' => $item['description']
                    ]);
                }
            }
        }
        
        logDebug("Deleted outcomes count", ['count' => $deletedOutcomes]);
    } else {
        logDebug("Could not determine course index", ['sectionID' => $sectionID]);
    }
    
    // Then delete the course header
    $result = $pagesObj->deleteContent($sectionID, $subpage);
    
    if ($result) {
        logDebug("Successfully deleted course header", ['sectionID' => $sectionID]);
        
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        echo json_encode([
            "success" => true,
            "message" => "Course and all outcomes deleted successfully"
        ]);
    } else {
        logDebug("Failed to delete course header", ['sectionID' => $sectionID]);
        
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete course"
        ]);
    }
} else {
    logDebug("Missing required parameters", $_POST);
    
    echo json_encode([
        "success" => false,
        "message" => "Missing required parameters"
    ]);
}
?>
