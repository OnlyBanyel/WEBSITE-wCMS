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

    // Enhance the updateCourse.php file to better handle new courses
    // Add this code after checking if required fields are set

    // Check if this is a new course
    $isNew = isset($_POST['isNew']) && $_POST['isNew'] === '1';

    // If it's a new course, add it instead of updating
    if ($isNew) {
        $courseType = $_POST['courseType']; // 'undergrad' or 'grad'
        
        // Add new course title
        $titleSectionID = $pagesObj->addContent(
            $subpage,
            'Courses and Programs',
            'text',
            $courseTitle,
            null,
            'course-header-' . $courseType
        );
        
        if (!$titleSectionID) {
            echo json_encode(["success" => false, "message" => "Failed to create new course"]);
            exit;
        }
        
        // Determine course number for outcomes
        $allCourses = $pagesObj->fetchSectionsByIndicator('Courses and Programs', 3, $subpage);
        $courseNumber = 0;
        
        foreach ($allCourses as $course) {
            if ($course['description'] === 'course-header-' . $courseType) {
                $courseNumber++;
            }
        }
        
        // Handle outcomes
        $updateOutcomes = true;
        $errors = [];
        
        if (isset($_POST['outcomes'])) {
            // Parse outcomes data
            if (is_string($_POST['outcomes'])) {
                $outcomesData = json_decode($_POST['outcomes'], true);
            } else {
                $outcomesData = $_POST['outcomes'];
            }
            
            if (is_array($outcomesData)) {
                foreach ($outcomesData as $outcome) {
                    if (!isset($outcome['content']) || trim($outcome['content']) === '') {
                        continue; // Skip empty outcomes
                    }
                    
                    $content = trim($outcome['content']);
                    
                    // Add new outcome
                    $newID = $pagesObj->addContent(
                        $subpage,
                        'Courses and Programs',
                        'text',
                        $content,
                        null,
                        $courseType . '-course-list-items-' . $courseNumber
                    );
                    
                    if (!$newID) {
                        $errors[] = "Failed to create outcome: " . $content;
                        $updateOutcomes = false;
                    }
                }
            }
        }
        
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        echo json_encode([
            "success" => true,
            "isNew" => true,
            "courseNumber" => $courseNumber,
            "courseType" => $courseType
        ]);
        exit;
    }

    // Get the original course type
    $originalCourse = $pagesObj->getRowById($titleSectionID);
    if (!$originalCourse) {
        echo json_encode(["success" => false, "message" => "Invalid course ID"]);
        exit;
    }

    $isUndergrad = ($originalCourse['description'] === 'course-header-undergrad');
    $isGrad = ($originalCourse['description'] === 'course-header-grad');

    if ($isUndergrad === $isGrad) {
        echo json_encode(["success" => false, "message" => "Invalid course type"]);
        exit;
    }

    $courseType = $isUndergrad ? 'undergrad' : 'grad';

    // Update course title
    $updateTitle = $pagesObj->changeContent(
        $titleSectionID,
        $subpage,
        $courseTitle
    );

    if (!$updateTitle) {
        echo json_encode(["success" => false, "message" => "Failed to update course title"]);
        exit;
    }

    // Determine course number
    $allCourses = $pagesObj->fetchSectionsByIndicator('Courses and Programs', 3, $subpage);
    $courseNumber = 0;

    foreach ($allCourses as $course) {
        if ($course['description'] === 'course-header-' . $courseType) {
            $courseNumber++;
            if ($course['sectionID'] == $titleSectionID) {
                break;
            }
        }
    }

    if ($courseNumber === 0) {
        echo json_encode(["success" => false, "message" => "Failed to determine course number"]);
        exit;
    }

    // Handle outcomes
    $updateOutcomes = true;
    $errors = [];
    $outcomesData = [];

    // Parse the outcomes data correctly
    if (isset($_POST['outcomes'])) {
        // Handle both array and JSON string formats
        if (is_string($_POST['outcomes'])) {
            $outcomesData = json_decode($_POST['outcomes'], true);
        } else {
            $outcomesData = $_POST['outcomes'];
        }

        if (is_array($outcomesData)) {
            foreach ($outcomesData as $outcome) {
                if (!isset($outcome['content']) || trim($outcome['content']) === '') {
                    $errors[] = "Outcome content cannot be empty";
                    $updateOutcomes = false;
                    continue;
                }

                $content = trim($outcome['content']);
                $isNew = isset($outcome['isNew']) ? (bool)$outcome['isNew'] : false;
                $sectionID = isset($outcome['sectionID']) ? (int)$outcome['sectionID'] : null;

                if ($isNew || $sectionID === null) {
                    // Add new outcome
                    $newID = $pagesObj->addContent(
                        $subpage,
                        'Courses and Programs',
                        'text',
                        $content,
                        null,
                        $courseType . '-course-list-items-' . $courseNumber
                    );

                    if (!$newID) {
                        $errors[] = "Failed to create new outcome: " . $content;
                        $updateOutcomes = false;
                    }
                } else {
                    // Update existing outcome
                    $result = $pagesObj->changeContent($sectionID, $subpage, $content);
                    if (!$result) {
                        $errors[] = "Failed to update outcome ID: $sectionID";
                        $updateOutcomes = false;
                    }
                }
            }
        } else {
            $errors[] = "Invalid outcomes data format";
            $updateOutcomes = false;
        }
    }

    if ($updateOutcomes) {
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        echo json_encode([
            "success" => true,
            "courseNumber" => $courseNumber,
            "courseType" => $courseType
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Some updates failed",
            "errors" => $errors,
            "outcomesData" => $outcomesData // For debugging
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields",
        "receivedData" => $_POST // For debugging
    ]);
}
?>
