<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

header('Content-Type: application/json');

if (isset($_POST['sectionID']) && isset($_POST['courseType'])) {
    $sectionID = $_POST['sectionID'];
    $courseType = $_POST['courseType'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // First delete all outcomes associated with this course
    $coursesAndPrograms = $_SESSION['collegeData'];
    $courseIndex = null;
    
    // Find the course index
    foreach ($coursesAndPrograms as $item) {
        if ($item['sectionID'] === $sectionID) {
            if (preg_match('/-course-list-items-(\d+)$/', $item['description'], $matches)) {
                $courseIndex = $matches[1];
                break;
            }
        }
    }
    
    if ($courseIndex !== null) {
        // Delete all outcomes for this course
        foreach ($coursesAndPrograms as $item) {
            if (strpos($item['description'], $courseType . '-course-list-items-' . $courseIndex) !== false) {
                $pagesObj->deleteContent($item['sectionID'], $subpage);
            }
        }
    }
    
    // Then delete the course header
    $result = $pagesObj->deleteContent($sectionID, $subpage);
    
    if ($result) {
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        echo json_encode([
            "success" => true,
            "message" => "Course and all outcomes deleted successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete course"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Missing required parameters"
    ]);
}
?>