<?php
session_start();
require_once '../classes/login.class.php';
require_once '../classes/pages.class.php';

// Initialize classes
$loginObj = new Login();
$pagesObj = new Pages();

// Set content type to JSON
header('Content-Type: application/json');

// Log incoming data for debugging
error_log('uploadProfileImgs.php - POST data: ' . print_r($_POST, true));
error_log('uploadProfileImgs.php - FILES data: ' . print_r($_FILES, true));

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    error_log('User not logged in');
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get subpage from session
$subpage = $_SESSION['account']['subpage_assigned'];
error_log("Subpage: $subpage");

// Check if this is a delete request
if (isset($_POST['deleteCarouselImage'])) {
    $sectionID = $_POST['sectionID'] ?? null;
    $isNew = $_POST['isNew'] ?? false;
    
    error_log("Delete request - Section ID: $sectionID, Is New: " . ($isNew ? 'Yes' : 'No'));
    
    if (!$sectionID) {
        echo json_encode([
            "success" => false,
            "message" => "Missing section ID"
        ]);
        exit;
    }
    
    // If it's a new (temporary) image that hasn't been saved to DB yet
    if ($isNew) {
        echo json_encode([
            "success" => true,
            "message" => "Temporary image removed"
        ]);
        exit;
    }
    
    // Delete the image from database
    $result = $pagesObj->deleteItem($sectionID, $subpage);
    
    if ($result) {
        // Refresh session data
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
        
        echo json_encode([
            "success" => true,
            "message" => "Carousel image deleted successfully"
        ]);
    } else {
        error_log("Failed to delete carousel image with ID: $sectionID");
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete carousel image"
        ]);
    }
    exit;
}

// Handle image upload
if (isset($_FILES['carouselImage']) && $_FILES['carouselImage']['error'] == 0) {
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $filename = $_FILES['carouselImage']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    error_log("Processing image upload: $filename");
    
    if (!in_array(strtolower($ext), $allowed)) {
        error_log("Invalid file format: $ext");
        echo json_encode([
            "success" => false,
            "message" => "Invalid file format. Allowed formats: " . implode(', ', $allowed)
        ]);
        exit;
    }
    
    $imageIndex = $_POST['imageIndex'] ?? null;
    $isNew = isset($_POST['isNew']) ? $_POST['isNew'] : '0';
    
    error_log("Image Index: $imageIndex, Is New: $isNew");
    
    if (!$imageIndex) {
        error_log("Missing image index");
        echo json_encode([
            "success" => false,
            "message" => "Missing image index"
        ]);
        exit;
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = '../../imgs/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        error_log("Created upload directory: $uploadDir");
    }
    
    // Generate a unique filename with the same format as college-overview
    $newFilename = 'carouselImg_' . uniqid('', true) . '.' . $ext;
    $destPath = $uploadDir . $newFilename;
    
    error_log("Destination path: $destPath");
    
    if (move_uploaded_file($_FILES['carouselImage']['tmp_name'], $destPath)) {
        error_log("File uploaded successfully");
        
        // Update the database with the new image path using the full path format
        $webPath = '/WEBSITE-wCMS/imgs/' . $newFilename;
        
        error_log("Web path for database: $webPath");
        
        if ($isNew == '1') {
            // Add new carousel image
            error_log("Adding new carousel image");
            $result = $pagesObj->addContent(
                $subpage,
                'College Profile',
                'image',
                '',
                $webPath,
                'carousel-img'
            );
            
            if ($result) {
                // Refresh session data
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
                
                echo json_encode([
                    "success" => true,
                    "message" => "New carousel image added successfully",
                    "newPath" => $webPath
                ]);
            } else {
                error_log("Failed to add new carousel image to database");
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to add new carousel image to database"
                ]);
            }
        } else {
            // Update existing carousel image
            error_log("Updating existing carousel image with ID: $imageIndex");
            $result = $pagesObj->uploadProfileImgs($webPath, $imageIndex, $subpage);
            
            if ($result) {
                // Refresh session data
                $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);
                
                echo json_encode([
                    "success" => true,
                    "message" => "Carousel image updated successfully",
                    "newPath" => $webPath
                ]);
            } else {
                error_log("Failed to update carousel image in database");
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update carousel image in database"
                ]);
            }
        }
    } else {
        error_log("Failed to move uploaded file");
        echo json_encode([
            "success" => false,
            "message" => "Failed to upload image. Server error."
        ]);
    }
} else {
    $errorCode = isset($_FILES['carouselImage']) ? $_FILES['carouselImage']['error'] : 'No file uploaded';
    error_log("Upload error: $errorCode");
    echo json_encode([
        "success" => false,
        "message" => "No image file received or upload error occurred: " . $errorCode
    ]);
}
?>
