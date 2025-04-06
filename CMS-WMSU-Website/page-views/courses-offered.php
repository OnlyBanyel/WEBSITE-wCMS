<?php 
session_start();
require_once "../classes/pages.class.php";
$collegeOffered = new Pages;

$coursesAndPrograms = [];
$programHeaders = [];
$undergradCourses = [];
$gradCourses = [];

// Filter courses and programs from session data
foreach ($_SESSION['collegeData'] as $data) {
    if ($data['indicator'] === 'Courses and Programs') {
        $coursesAndPrograms[] = $data;
    }
}

// Process courses and outcomes
$undergradIndex = 1;
$gradIndex = 1;

// First pass: Collect course headers
foreach ($coursesAndPrograms as $item) {
    if ($item["description"] === "program-header") {
        $programHeaders[] = $item['content'];
    }

    if ($item["description"] === "course-header-undergrad") {
        $undergradCourses[$item['content']] = [
            "sectionID" => $item["sectionID"],
            "outcomes" => [],
            "index" => $undergradIndex
        ];
        $undergradIndex++;
    } elseif ($item["description"] === "course-header-grad") {
        $gradCourses[$item['content']] = [
            "sectionID" => $item["sectionID"],
            "outcomes" => [],
            "index" => $gradIndex
        ];
        $gradIndex++;
    }
}

// Second pass: Assign outcomes to correct courses
foreach ($coursesAndPrograms as $item) {
    // Match undergrad outcomes
    if (preg_match('/undergrad-course-list-items-(\d+)$/', $item["description"], $matches)) {
        $courseNum = $matches[1];
        foreach ($undergradCourses as &$course) {
            if ($course['index'] == $courseNum) {
                $course["outcomes"][] = [
                    "content" => $item['content'],
                    "sectionID" => $item["sectionID"]
                ];
                break;
            }
        }
    }
    // Match grad outcomes
    elseif (preg_match('/grad-course-list-items-(\d+)$/', $item["description"], $matches)) {
        $courseNum = $matches[1];
        foreach ($gradCourses as &$course) {
            if ($course['index'] == $courseNum) {
                $course["outcomes"][] = [
                    "content" => $item['content'],
                    "sectionID" => $item["sectionID"]
                ];
                break;
            }
        }
    }
}
?>

<div class="courses-offered-container">
    <h2>Undergraduate Courses</h2>
    <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
        <div class="courses-item-container">
            <form action="" method="POST" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                <div class="divider-line"></div>
                <p><strong>Program Objectives/Outcomes:</strong></p>
                <ul class="outcomes-container">
                    <?php $i = 1; ?>
                    <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                        <li>
                            <input type="text" 
                                name="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                id="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                data-sectionid="<?php echo $outcome['sectionID']?>" 
                                value="<?php echo $outcome['content']?>">
                            <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $outcome['sectionID']?>">×</button>
                        </li>
                    <?php $i++; } ?>
                </ul>
                <div class="btn-container">
                    <button type="button" class="add-outcome btn btn-primary">Add Outcome</button>
                    <input type="submit" value="Submit" class="submitCourse btn btn-success">
                </div>
            </form>
        </div>
    <?php } ?>

    <h2>Graduate Courses</h2>
    <?php foreach ($gradCourses as $courseName => $courseData) { ?>
        <div class="courses-item-container">
            <form action="" method="POST" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                <input type="text" name="courseTitle" data-titleSectionID="<?php echo $courseData['sectionID']?>" class="courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                <div class="divider-line"></div>
                <p><strong>Program Objectives/Outcomes:</strong></p>
                <ul class="outcomes-container">
                    <?php $i = 1; ?>
                    <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                        <li>
                            <input type="text" 
                                   name="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                   id="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                   data-sectionID="<?php echo $outcome['sectionID']?>" 
                                   value="<?php echo $outcome['content']?>">
                            <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $outcome['sectionID']?>">×</button>
                        </li>
                    <?php $i++; } ?>
                </ul>
                <div class="btn-container">
                    <button type="button" class="add-outcome btn btn-primary">Add Outcome</button>
                    <input type="submit" value="Submit" class="submitCourse btn btn-success">
                </div>
            </form>
        </div>
    <?php } ?>
</div>