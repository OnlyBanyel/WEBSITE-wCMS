<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

$loginObj = new Login;
$pagesObj = new Pages;

if (isset($_POST['addNewCarouselImage'])) {
    $subpage = $_SESSION['account']['subpage_assigned'];
    
    // Add a new empty carousel image entry
    $result = $pagesObj->addContent(
        $subpage,
        'College Profile',
        'image',
        null,
        '', // Empty path for new image
        'carousel-img'
    );
    
    if ($result['success']) {
        // Refresh session data
        unset($_SESSION['collegeData']);
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        // Count how many carousel images we have now
        $carouselCount = 0;
        foreach ($_SESSION['collegeData'] as $data) {
            if ($data['indicator'] == 'College Profile' && $data['description'] == 'carousel-img') {
                $carouselCount++;
            }
        }
        
        echo json_encode([
            "success" => true,
            "message" => "New carousel image slot added successfully",
            "newIndex" => $carouselCount,
            "sectionID" => $result['sectionID'] // Return the new section ID
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to add new carousel image slot"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request"
    ]);
}