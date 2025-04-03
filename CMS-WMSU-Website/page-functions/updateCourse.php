<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login();
$pagesObj = new Pages();

header('Content-Type: application/json');

if (isset($_POST['courseTitle']) && isset($_POST['titleSectionID'])) {
    $courseTitle = $_POST['courseTitle'];
    $titleSectionID = $_POST['titleSectionID'];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Get the original course type from database (more reliable than form ID)
    $originalCourse = $pagesObj->getRowById($titleSectionID);
    $isUndergrad = ($originalCourse['description'] === 'course-header-undergrad');
    $courseType = $isUndergrad ? 'undergrad' : 'grad';

    // Update course title (keep original description)
    $updateTitle = $pagesObj->changeContent(
        $titleSectionID, 
        $subpage, 
        $courseTitle
    );

    if (!$updateTitle) {
        echo json_encode(["success" => false, "message" => "Failed to update course title"]);
        exit;
    }

    // Get course number by counting existing courses of same type
    $allCourses = $pagesObj->fetchSectionsByIndicator('Courses and Programs', 3, $subpage);
    $courseNumber = 1;
    foreach ($allCourses as $course) {
        if ($course['description'] === 'course-header-'.$courseType) {
            if ($course['sectionID'] == $titleSectionID) break;
            $courseNumber++;
        }
    }

    // Process outcomes
    $updateOutcomes = true;
    $errors = [];
    
    if (isset($_POST['outcomes']) && is_array($_POST['outcomes'])) {
        foreach ($_POST['outcomes'] as $outcome) {
            if (isset($outcome['content'])) {
                if (isset($outcome['isNew']) && $outcome['isNew']) {
                    // Add new outcome
                    $newSectionID = $pagesObj->addContent(
                        $subpage,
                        'Courses and Programs',
                        'text',
                        $outcome['content'],
                        null,
                        $courseType . '-course-list-items-' . $courseNumber
                    );
                    
                    if (!$newSectionID) {
                        $errors[] = "Failed to create new outcome";
                        $updateOutcomes = false;
                    }
                } else {
                    // Update existing outcome
                    if (isset($outcome['sectionID'])) {
                        $outcomeUpdate = $pagesObj->changeContent(
                            $outcome['sectionID'],
                            $subpage,
                            $outcome['content']
                        );
                        
                        if (!$outcomeUpdate) {
                            $errors[] = "Failed to update outcome ID: " . $outcome['sectionID'];
                            $updateOutcomes = false;
                        }
                    }
                }
            }
        }
    }

    if ($updateOutcomes) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Some updates failed", "errors" => $errors]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
}
?>