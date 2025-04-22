<?php
session_start();
require_once '../classes/account.class.php';

// Set content type to JSON
header('Content-Type: application/json');

// Initialize Accounts class
$accountsObj = new Accounts();

// Handle profile picture update
if (isset($_POST['formType']) && $_POST['formType'] === 'profilePicture' && isset($_FILES['profilePicture'])) {
    $file = $_FILES['profilePicture'];
    $userId = $_SESSION['account']['id']; // Get current user ID from session
    
    // Debug information
    error_log("Processing profile picture upload for user ID: " . $userId);
    error_log("File info: " . json_encode($file));
    
    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if ($file['error'] !== 0) {
        echo json_encode(["success" => false, "message" => "Upload error: " . $file['error']]);
        exit;
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Invalid file type. Please upload JPG, PNG or GIF."]);
        exit;
    }
    
    if ($file['size'] > $maxSize) {
        echo json_encode(["success" => false, "message" => "File too large. Maximum size is 5MB."]);
        exit;
    }
    
    // Use the same upload directory structure as your other image uploads
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-wCMS/imgs/profiles/";
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename using the same pattern as your other uploads
    $fileName = uniqid("profile_", true) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destPath)) {
        // Create web-accessible path
        $relativePath = "/WEBSITE-wCMS/imgs/profiles/" . $fileName;
        
        // Update database
        $updateResult = $accountsObj->updateProfilePicture($userId, $relativePath);
        
        if ($updateResult) {
            // Update session
            $_SESSION['account']['profileImg'] = $relativePath;
            
            echo json_encode([
                "success" => true, 
                "message" => "Profile picture updated successfully.", 
                "newPath" => $relativePath
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update profile picture in database."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload file to " . $destPath]);
    }
}
// Handle personal information update
else if (isset($_POST['formType']) && $_POST['formType'] === 'personalInfo') {
    $userId = $_SESSION['account']['id'];
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    
    $updateResult = $accountsObj->updatePersonalInfo($userId, $firstName, $lastName);
    
    if ($updateResult) {
        // Update session
        $_SESSION['account']['firstName'] = $firstName;
        $_SESSION['account']['lastName'] = $lastName;
        echo json_encode(["success" => true, "message" => "Personal information updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update personal information."]);
    }
}
// Handle email update
else if (isset($_POST['formType']) && $_POST['formType'] === 'email') {
    $userId = $_SESSION['account']['id'];
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    // Check if email is already in use
    if ($accountsObj->isEmailTaken($email, $userId)) {
        echo json_encode(["success" => false, "message" => "Email is already in use by another account."]);
        exit;
    }
    
    $updateResult = $accountsObj->updateEmail($userId, $email);
    
    if ($updateResult) {
        // Update session
        $_SESSION['account']['email'] = $email;
        echo json_encode(["success" => true, "message" => "Email updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update email."]);
    }
}
// Handle password update
else if (isset($_POST['formType']) && $_POST['formType'] === 'password') {
    $userId = $_SESSION['account']['id'];
    $currentPassword = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : '';
    $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    
    // Verify current password
    if (!$accountsObj->verifyPassword($userId, $currentPassword)) {
        echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
        exit;
    }
    
    // Check if new passwords match
    if ($newPassword !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "New passwords do not match."]);
        exit;
    }
    
    // Validate password strength
    if (strlen($newPassword) < 8) {
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters long."]);
        exit;
    }
    
    // Update password
    $updateResult = $accountsObj->updatePassword($userId, $newPassword);
    
    if ($updateResult) {
        echo json_encode(["success" => true, "message" => "Password updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update password."]);
    }
}
else {
    // Debug what was received
    error_log("POST data: " . json_encode($_POST));
    error_log("FILES data: " . json_encode($_FILES));
    
    echo json_encode([
        "success" => false, 
        "message" => "Invalid request or missing data.",
        "post" => $_POST,
        "files" => isset($_FILES) ? "Files present" : "No files"
    ]);
}
?>