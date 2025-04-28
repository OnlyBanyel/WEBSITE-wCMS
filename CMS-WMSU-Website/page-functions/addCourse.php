<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

header('Content-Type: application/json');

// Check if we're adding a new course
if (isset($_POST['courseType'])) {
    $courseType = $_POST['courseType'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Get the current highest index for this course type
    $highestIndex = 0;
    foreach ($_SESSION['collegeData'] as $data) {
        if ($data['indicator'] === 'Courses and Programs' && 
            strpos($data['description'], 'course-header-' . $courseType) !== false) {
            $highestIndex++;
        }
    }
    
    $newIndex = $highestIndex + 1;
    
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
        // Add a default empty outcome
        $defaultOutcome = $pagesObj->addContent(
            $subpage,
            'Courses and Programs',
            'text',
            'Program outcome description',
            null,
            $courseType . '-course-list-items-' . $newIndex
        );
        
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
        echo json_encode(["success" => false, "message" => "Failed to add new course."]);
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
?>