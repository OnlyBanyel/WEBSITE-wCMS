<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

header('Content-Type: application/json');

// Check if we're adding a new department
if (isset($_POST['addNewDepartment'])) {
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Add a new department with default values
    $newDeptContent = "New Department";
    $newDeptID = $pagesObj->addContent(
        $subpage,
        'Departments',
        'text',
        $newDeptContent,
        null,
        'department-name'
    );
    
    if ($newDeptID) {
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        // Return JSON response instead of redirecting
        echo json_encode([
            "success" => true,
            "message" => "New department added successfully",
            "redirect" => "../page-views/departments.php"
        ]);
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add new department."]);
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
?>
