<?php
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/login.class.php";

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize classes
$pagesObj = new Pages();
$loginObj = new Login();

// Get the current page and form data
$page = isset($_POST['page']) ? $_POST['page'] : '';
$formData = isset($_POST['form_data']) ? json_decode($_POST['form_data'], true) : [];

// Process form data based on the page
$success = true;
$message = '';
$errors = [];

try {
    switch ($page) {
        case 'college-overview.php':
            list($success, $message, $errors) = saveCollegeOverviewChanges($formData, $pagesObj, $loginObj);
            break;
            
        case 'college-profile.php':
            list($success, $message, $errors) = saveCollegeProfileChanges($formData, $pagesObj, $loginObj);
            break;
            
        case 'courses-offered.php':
            list($success, $message, $errors) = saveCoursesOfferedChanges($formData, $pagesObj, $loginObj);
            break;
            
        case 'departments.php':
            list($success, $message, $errors) = saveDepartmentsChanges($formData, $pagesObj, $loginObj);
            break;
            
        case 'shs.php':
            list($success, $message, $errors) = saveSHSChanges($formData, $pagesObj, $loginObj);
            break;
            
        default:
            $success = false;
            $message = 'Unknown page type';
            break;
    }
    
    // Clear preview data if successful
    if ($success) {
        unset($_SESSION['preview_data']);
    }
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
}

// Return the result
header('Content-Type: application/json');
echo json_encode([
    'success' => $success,
    'message' => $message,
    'errors' => $errors
]);
exit;

/**
 * Save changes for College Overview page
 */
function saveCollegeOverviewChanges($formData, $pagesObj, $loginObj) {
    $success = true;
    $message = 'All changes saved successfully';
    $errors = [];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Process each form
    foreach ($formData as $formId => $data) {
        if (strpos($formId, 'overviewItems') !== false) {
            // Save overview title
            if (isset($data['overviewTitle']) && isset($data['overviewSectionID'])) {
                $isNew = isset($data['isNew']) && $data['isNew'] === '1';
                $sectionID = $data['overviewSectionID'];
                
                if ($isNew || strpos($sectionID, 'temp_') === 0) {
                    // Add new title
                    $result = $pagesObj->addContent(
                        $subpage,
                        'College Overview',
                        'text',
                        $data['overviewTitle'],
                        null,
                        'geninfo-front-title'
                    );
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to add overview title: " . $data['overviewTitle'];
                    }
                } else {
                    // Update existing title
                    $result = $pagesObj->changeContent($sectionID, $subpage, $data['overviewTitle']);
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to update overview title (ID: $sectionID)";
                    }
                }
            }
            
            // Save overview content
            if (isset($data['overviewTopContent']) && isset($data['topContentSectionID'])) {
                $isNew = isset($data['topContentIsNew']) && $data['topContentIsNew'] === '1';
                $sectionID = $data['topContentSectionID'];
                
                if ($isNew || strpos($sectionID, 'temp_') === 0) {
                    // Add new content
                    $result = $pagesObj->addContent(
                        $subpage,
                        'College Overview',
                        'text',
                        $data['overviewTopContent'],
                        null,
                        'geninfo-back-head'
                    );
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to add overview content";
                    }
                } else {
                    // Update existing content
                    $result = $pagesObj->changeContent($sectionID, $subpage, $data['overviewTopContent']);
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to update overview content (ID: $sectionID)";
                    }
                }
            }
            
            // Save outcomes
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $isNew = isset($item['isNew']) ? $item['isNew'] : (strpos($item['sectionID'], 'temp_') === 0);
                    
                    if ($isNew) {
                        // Add new outcome
                        $result = $pagesObj->addContent(
                            $subpage,
                            'College Overview',
                            'text',
                            $item['content'],
                            null,
                            'CG-list-item'
                        );
                        
                        if (!$result) {
                            $success = false;
                            $errors[] = "Failed to add outcome: " . $item['content'];
                        }
                    } else {
                        // Update existing outcome
                        $result = $pagesObj->changeContent($item['sectionID'], $subpage, $item['content']);
                        
                        if (!$result) {
                            $success = false;
                            $errors[] = "Failed to update outcome (ID: " . $item['sectionID'] . ")";
                        }
                    }
                }
            }
        }
    }
    
    // Refresh session data
    if ($success) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    }
    
    return [$success, $message, $errors];
}

/**
 * Save changes for College Profile page
 */
function saveCollegeProfileChanges($formData, $pagesObj, $loginObj) {
    $success = true;
    $message = 'All changes saved successfully';
    $errors = [];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Process college name form
    if (isset($formData['collegeNameForm'])) {
        $data = $formData['collegeNameForm'];
        
        if (isset($data['collegeName']) && isset($data['textID'])) {
            $isNew = isset($data['isNew']) && $data['isNew'] === '1';
            
            if ($isNew) {
                // Add new college name
                $result = $pagesObj->addContent(
                    $subpage,
                    'College Profile',
                    'text',
                    $data['collegeName'],
                    null,
                    'carousel-logo-text'
                );
            } else {
                // Update existing college name
                $result = $pagesObj->changeContent($data['textID'], $subpage, $data['collegeName']);
            }
            
            if (!$result) {
                $success = false;
                $errors[] = "Failed to " . ($isNew ? "add" : "update") . " college name";
            }
        }
    }
    
    // Refresh session data
    if ($success) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    }
    
    return [$success, $message, $errors];
}

/**
 * Save changes for Courses Offered page
 */
function saveCoursesOfferedChanges($formData, $pagesObj, $loginObj) {
    $success = true;
    $message = 'All changes saved successfully';
    $errors = [];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Process each course form
    foreach ($formData as $formId => $data) {
        if (strpos($formId, '-items') !== false) {
            // Save course title
            if (isset($data['courseTitle']) && isset($data['titleSectionID'])) {
                $isNew = isset($data['isNew']) && $data['isNew'] === '1';
                $courseType = $data['courseType'] ?? 'undergrad';
                
                if ($isNew) {
                    // Add new course
                    $result = $pagesObj->addContent(
                        $subpage,
                        'Courses and Programs',
                        'text',
                        $data['courseTitle'],
                        null,
                        'course-header-' . $courseType
                    );
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to add course: " . $data['courseTitle'];
                        continue;
                    }
                    
                    $titleSectionID = $result;
                } else {
                    // Update existing course
                    $result = $pagesObj->changeContent($data['titleSectionID'], $subpage, $data['courseTitle']);
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to update course title (ID: " . $data['titleSectionID'] . ")";
                    }
                    
                    $titleSectionID = $data['titleSectionID'];
                }
                
                // Save outcomes
                if (isset($data['items'])) {
                    // Determine course number
                    $courseNumber = $data['courseIndex'] ?? 1;
                    
                    foreach ($data['items'] as $item) {
                        $isNewOutcome = isset($item['isNew']) ? $item['isNew'] : (strpos($item['sectionID'], 'temp_') === 0);
                        
                        if ($isNewOutcome) {
                            // Add new outcome
                            $result = $pagesObj->addContent(
                                $subpage,
                                'Courses and Programs',
                                'text',
                                $item['content'],
                                null,
                                $courseType . '-course-list-items-' . $courseNumber
                            );
                            
                            if (!$result) {
                                $success = false;
                                $errors[] = "Failed to add outcome: " . $item['content'];
                            }
                        } else {
                            // Update existing outcome
                            $result = $pagesObj->changeContent($item['sectionID'], $subpage, $item['content']);
                            
                            if (!$result) {
                                $success = false;
                                $errors[] = "Failed to update outcome (ID: " . $item['sectionID'] . ")";
                            }
                        }
                    }
                }
            }
        }
    }
    
    // Refresh session data
    if ($success) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    }
    
    return [$success, $message, $errors];
}

/**
 * Save changes for Departments page
 */
function saveDepartmentsChanges($formData, $pagesObj, $loginObj) {
    $success = true;
    $message = 'All changes saved successfully';
    $errors = [];
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Process each department form
    foreach ($formData as $formId => $data) {
        if (strpos($formId, 'departmentForm-') !== false) {
            // Save department name
            if (isset($data['deptName']) && isset($data['textID'])) {
                $isNew = isset($data['isNew']) && $data['isNew'] === '1';
                
                if ($isNew) {
                    // Add new department
                    $result = $pagesObj->addContent(
                        $subpage,
                        'Departments',
                        'text',
                        $data['deptName'],
                        null,
                        'department-name'
                    );
                } else {
                    // Update existing department
                    $result = $pagesObj->changeContent($data['textID'], $subpage, $data['deptName']);
                }
                
                if (!$result) {
                    $success = false;
                    $errors[] = "Failed to " . ($isNew ? "add" : "update") . " department: " . $data['deptName'];
                }
            }
        }
    }
    
    // Refresh session data
    if ($success) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    }
    
    return [$success, $message, $errors];
}

/**
 * Save changes for SHS page
 */
function saveSHSChanges($formData, $pagesObj, $loginObj) {
    $success = true;
    $message = 'All changes saved successfully';
    $errors = [];
    $subpage = 31; // SHS subpage ID
    
    // Process each strand form
    foreach ($formData as $formId => $data) {
        if (strpos($formId, 'updateStrandForm-') !== false) {
            $strandID = str_replace('updateStrandForm-', '', $formId);
            $isNew = isset($data['isNew']) && $data['isNew'] === '1';
            
            // Save strand name
            if (isset($data['strandName'])) {
                if ($isNew) {
                    // Add new strand
                    $result = $pagesObj->addContent(
                        $subpage,
                        'Strand',
                        'text',
                        $data['strandName'],
                        null,
                        'strand-name'
                    );
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to add strand: " . $data['strandName'];
                        continue;
                    }
                    
                    $strandID = $result;
                } else {
                    // Update existing strand
                    $result = $pagesObj->changeContent($strandID, $subpage, $data['strandName']);
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to update strand name (ID: $strandID)";
                    }
                }
            }
            
            // Save strand description
            if (isset($data['strandDesc']) && isset($data['descID'])) {
                $descID = $data['descID'];
                $isNewDesc = strpos($descID, 'temp_') === 0;
                
                if ($isNewDesc) {
                    // Add new description
                    $result = $pagesObj->addContent(
                        $subpage,
                        'Strand',
                        'text',
                        $data['strandDesc'],
                        null,
                        'strand-desc'
                    );
                } else {
                    // Update existing description
                    $result = $pagesObj->changeContent($descID, $subpage, $data['strandDesc']);
                }
                
                if (!$result) {
                    $success = false;
                    $errors[] = "Failed to " . ($isNewDesc ? "add" : "update") . " strand description";
                }
            }
            
            // Save strand end description
            if (isset($data['strandEndDesc']) && isset($data['endDescID'])) {
                $endDescID = $data['endDescID'];
                $isNewEndDesc = strpos($endDescID, 'temp_') === 0;
                
                if ($isNewEndDesc) {
                    // Add new end description
                    $result = $pagesObj->addContent(
                        $subpage,
                        'Strand',
                        'text',
                        $data['strandEndDesc'],
                        null,
                        'strand-desc-end'
                    );
                } else {
                    // Update existing end description
                    $result = $pagesObj->changeContent($endDescID, $subpage, $data['strandEndDesc']);
                }
                
                if (!$result) {
                    $success = false;
                    $errors[] = "Failed to " . ($isNewEndDesc ? "add" : "update") . " strand end description";
                }
            }
            
            // Save outcomes
            if (isset($data['items'])) {
                foreach ($data['items'] as $index => $item) {
                    $isNewOutcome = isset($item['isNew']) ? $item['isNew'] : (strpos($item['sectionID'], 'temp_') === 0);
                    
                    if ($isNewOutcome) {
                        // Add new outcome
                        $result = $pagesObj->addContent(
                            $subpage,
                            'Strand',
                            'text',
                            $item['content'],
                            null,
                            'strand-item-' . ($index + 1)
                        );
                    } else {
                        // Update existing outcome
                        $result = $pagesObj->changeContent($item['sectionID'], $subpage, $item['content']);
                    }
                    
                    if (!$result) {
                        $success = false;
                        $errors[] = "Failed to " . ($isNewOutcome ? "add" : "update") . " strand outcome";
                    }
                }
            }
        }
    }
    
    // Refresh session data if needed
    if ($success && isset($_SESSION['collegeData'])) {
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    }
    
    return [$success, $message, $errors];
}
?>
