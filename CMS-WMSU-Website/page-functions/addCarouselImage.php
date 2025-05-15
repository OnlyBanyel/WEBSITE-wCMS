<?php
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/login.class.php";

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    $response['message'] = 'Unauthorized access';
    echo json_encode($response);
    exit;
}

// Initialize classes
$pagesObj = new Pages();
$loginObj = new Login();

// Get subpage from session
$subpage = $_SESSION['account']['subpage_assigned'];

// Handle adding new carousel image slot
if (isset($_POST['addNewCarouselImage'])) {
    // Add new carousel image slot to database
    $result = $pagesObj->addContent(
        $subpage,
        'College Profile',
        'image',
        '',
        '',
        'carousel-img'
    );
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'New carousel image slot added successfully';
        $response['sectionID'] = $result;
        
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
    } else {
        $response['message'] = 'Failed to add new carousel image slot to database';
    }
} else {
    $response['message'] = 'Invalid request';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
