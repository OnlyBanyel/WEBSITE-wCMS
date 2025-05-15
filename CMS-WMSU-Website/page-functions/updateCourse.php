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
    
    $logFile = $logDir . '/course_updates_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $logMessage .= " Data: " . json_encode($data, JSON_PRETTY_PRINT);
    }
    
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
}

// Log the raw request for debugging
logDebug("Received course update request", [
    'POST' => $_POST,
    'RAW' => file_get_contents('php://input')
]);

$loginObj = new Login();
$pagesObj = new Pages();

if (isset($_POST['courseTitle']) && isset($_POST['titleSectionID'])) {
    $courseTitle = $_POST['courseTitle'];
    $titleSectionID = $_POST['titleSectionID'];
    $subpage = $_SESSION['account']['subpage_assigned'];

    // Check if this is a new course
    $isNew = isset($_POST['isNew']) && ($_POST['isNew'] === '1' || $_POST['isNew'] === 1 || $_POST['isNew'] === true || $_POST['isNew'] === 'true');

    logDebug("Processing course update", [
        'courseTitle' => $courseTitle,
        'titleSectionID' => $titleSectionID,
        'isNew' => $isNew,
        'subpage' => $subpage
    ]);

    // If it's a new course, add it instead of updating
    if ($isNew) {
        $courseType = $_POST['courseType']; // 'undergrad' or 'grad'
        
        logDebug("Adding new course", [
            'courseType' => $courseType
        ]);
        
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
            logDebug("Failed to create new course", [
                'courseTitle' => $courseTitle,
                'courseType' => $courseType
            ]);
            
            echo json_encode(["success" => false, "message" => "Failed to create new course"]);
            exit;
        }
        
        logDebug("Successfully created new course", [
            'titleSectionID' => $titleSectionID
        ]);
        
        // Determine course number for outcomes
        $allCourses = $pagesObj->fetchSectionsByIndicator('Courses and Programs', 3, $subpage);
        $courseNumber = 0;
        
        foreach ($allCourses as $course) {
            if ($course['description'] === 'course-header-' . $courseType) {
                $courseNumber++;
            }
        }
        
        logDebug("Determined course number", [
            'courseNumber' => $courseNumber
        ]);
        
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
            
            logDebug("Processing outcomes for new course", [
                'outcomesData' => $outcomesData
            ]);
            
            if (is_array($outcomesData)) {
                foreach ($outcomesData as $outcome) {
                    if (!isset($outcome['content']) || trim($outcome['content']) === '') {
                        continue; // Skip empty outcomes
                    }
                    
                    $content = trim($outcome['content']);
                    
                    logDebug("Adding outcome to new course", [
                        'content' => $content
                    ]);
                    
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
                        
                        logDebug("Failed to create outcome", [
                            'content' => $content
                        ]);
                    } else {
                        logDebug("Successfully created outcome", [
                            'newID' => $newID,
                            'content' => $content
                        ]);
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
        logDebug("Invalid course ID", [
            'titleSectionID' => $titleSectionID
        ]);
        
        echo json_encode(["success" => false, "message" => "Invalid course ID"]);
        exit;
    }

    $isUndergrad = ($originalCourse['description'] === 'course-header-undergrad');
    $isGrad = ($originalCourse['description'] === 'course-header-grad');

    if ($isUndergrad === $isGrad) {
        logDebug("Invalid course type", [
            'isUndergrad' => $isUndergrad,
            'isGrad' => $isGrad,
            'description' => $originalCourse['description']
        ]);
        
        echo json_encode(["success" => false, "message" => "Invalid course type"]);
        exit;
    }

    $courseType = $isUndergrad ? 'undergrad' : 'grad';
    
    logDebug("Updating existing course", [
        'courseType' => $courseType,
        'originalTitle' => $originalCourse['content'],
        'newTitle' => $courseTitle
    ]);

    // Update course title
    $updateTitle = $pagesObj->changeContent(
        $titleSectionID,
        $subpage,
        $courseTitle
    );

    if (!$updateTitle) {
        logDebug("Failed to update course title", [
            'titleSectionID' => $titleSectionID,
            'courseTitle' => $courseTitle
        ]);
        
        echo json_encode(["success" => false, "message" => "Failed to update course title"]);
        exit;
    }
    
    logDebug("Successfully updated course title", [
        'titleSectionID' => $titleSectionID,
        'courseTitle' => $courseTitle
    ]);

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
        logDebug("Failed to determine course number", [
            'titleSectionID' => $titleSectionID,
            'courseType' => $courseType
        ]);
        
        echo json_encode(["success" => false, "message" => "Failed to determine course number"]);
        exit;
    }
    
    logDebug("Determined course number for existing course", [
        'courseNumber' => $courseNumber
    ]);

    // Handle outcomes
    $updateOutcomes = true;
    $errors = [];
    $outcomesData = [];

    // Parse the outcomes data correctly
    if (isset($_POST['outcome_content']) && is_array($_POST['outcome_content'])) {
        // Handle traditional form submission with arrays
        $outcomeContents = $_POST['outcome_content'];
        $outcomeSectionIds = isset($_POST['outcome_sectionid']) ? $_POST['outcome_sectionid'] : [];
        $outcomeIsNew = isset($_POST['outcome_isnew']) ? $_POST['outcome_isnew'] : [];
        
        for ($i = 0; $i < count($outcomeContents); $i++) {
            $outcomesData[] = [
                'content' => $outcomeContents[$i],
                'sectionID' => isset($outcomeSectionIds[$i]) ? $outcomeSectionIds[$i] : null,
                'isNew' => isset($outcomeIsNew[$i]) ? (bool)$outcomeIsNew[$i] : false
            ];
        }
        
        logDebug("Parsed outcomes from form arrays", $outcomesData);
    } elseif (isset($_POST['outcomes'])) {
        // Handle JSON string format
        $outcomesData = json_decode($_POST['outcomes'], true);
        logDebug("Parsed outcomes from JSON", $outcomesData);
    }

    if (is_array($outcomesData) && !empty($outcomesData)) {
        foreach ($outcomesData as $outcome) {
            if (!isset($outcome['content']) || trim($outcome['content']) === '') {
                continue; // Skip empty outcomes
            }

            $content = trim($outcome['content']);
            $isNew = isset($outcome['isNew']) ? (bool)$outcome['isNew'] : false;
            $sectionID = isset($outcome['sectionID']) ? $outcome['sectionID'] : null;

            logDebug("Processing outcome", [
                'content' => $content,
                'isNew' => $isNew,
                'sectionID' => $sectionID
            ]);

            if ($isNew || $sectionID === null || strpos($sectionID, 'temp_') === 0) {
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
                    logDebug("Failed to create new outcome", [
                        'content' => $content,
                        'courseType' => $courseType,
                        'courseNumber' => $courseNumber
                    ]);
                } else {
                    logDebug("Created new outcome", [
                        'newID' => $newID,
                        'content' => $content
                    ]);
                }
            } else {
                // Update existing outcome
                $result = $pagesObj->changeContent($sectionID, $subpage, $content);
                if (!$result) {
                    $errors[] = "Failed to update outcome ID: $sectionID";
                    $updateOutcomes = false;
                    logDebug("Failed to update outcome", [
                        'sectionID' => $sectionID,
                        'content' => $content
                    ]);
                } else {
                    logDebug("Updated outcome", [
                        'sectionID' => $sectionID,
                        'content' => $content
                    ]);
                }
            }
        }
    } else {
        logDebug("No valid outcomes data found", [
            'outcomesData' => $outcomesData,
            'POST' => $_POST
        ]);
    }

    if ($updateOutcomes) {
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        logDebug("Successfully updated course and outcomes", [
            'courseNumber' => $courseNumber,
            'courseType' => $courseType
        ]);
        
        echo json_encode([
            "success" => true,
            "courseNumber" => $courseNumber,
            "courseType" => $courseType
        ]);
    } else {
        logDebug("Some updates failed", [
            'errors' => $errors
        ]);
        
        echo json_encode([
            "success" => false,
            "message" => "Some updates failed",
            "errors" => $errors
        ]);
    }
} else {
    logDebug("Missing required fields", [
        'receivedData' => $_POST
    ]);
    
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
}
?>
