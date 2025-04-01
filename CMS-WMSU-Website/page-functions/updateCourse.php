<?php
session_start();
require_once '../classes/login.class.php'; // Login object for college data fetching
require_once '../classes/pages.class.php'; // Pages object for course data handling

$loginObj = new Login(); // Instantiate the Login object
$pagesObj = new Pages(); // Instantiate the Pages object

// Check if courseTitle, titleSectionID, and outcomes are set
if (isset($_POST['courseTitle']) && isset($_POST['outcomes']) && isset($_POST['titleSectionID'])) {
    $courseTitle = $_POST['courseTitle'];  // Get the course title
    $titleSectionID = $_POST['titleSectionID']; // Get the sectionID for the course title
    $subpage = $_SESSION['account']['subpage_assigned']; // Get the subpage assigned to the user

    // Ensure outcomes is an array
    $outcomes = is_array($_POST['outcomes']) ? $_POST['outcomes'] : [];

    // Update the course title with the provided course title and sectionID
    $updateTitle = $pagesObj->changeContent($titleSectionID, $subpage, $courseTitle);

    // Initialize outcome update flag
    $updateOutcomes = true;

    // Loop through outcomes to update each one with content and sectionID
    foreach ($outcomes as $outcome) {
        // Ensure that each outcome contains both content and sectionID
        if (isset($outcome['content']) && isset($outcome['sectionID'])) {
            // Update outcome using the sectionID and the new content
            $updateOutcomes = $pagesObj->changeContent($outcome['sectionID'], $subpage, $outcome['content']);
        } else {
            $updateOutcomes = false; // If any outcome is missing required data, stop the update
            break;
        }
    }

    // If both the title and outcomes are updated successfully
    if ($updateTitle && $updateOutcomes) {
        // Optionally, update course data in session
        $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage);

        // Send a success response
        echo json_encode(["success" => true]);
    } else {
        // Send an error response if something went wrong
        echo json_encode(["success" => false, "message" => "Failed to update course data."]);
    }
} else {
    // Send an error response if the necessary data is missing
    echo json_encode(["success" => false, "message" => "Missing course title, sectionID, or outcomes."]);
}
?>
