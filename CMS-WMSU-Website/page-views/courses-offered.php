<?php 
session_start();
require_once "../classes/pages.class.php";
$collegeOffered = new Pages;

foreach($_SESSION['collegeData'] as $data){
    if ($data['indicator'] == 'Courses and Programs'){
        $coursesAndPrograms[] = $data;
    }
}

foreach ($coursesAndPrograms as $item) {
    // Store Program Headers
    if ($item["description"] == "program-header") {
        $programHeaders[] = $item['content'];
    }

    // ✅ Identify Course Type (Undergrad or Grad)
    $isUndergrad = $item["description"] === "course-header-undergrad";
    $isGrad = $item["description"] === "course-header-grad";

    // ✅ Store Course Headers & Reset Properly, Including Section ID
    if ($isUndergrad) {
        $currentUndergrad = $item['content'];
        $undergradCourses[$currentUndergrad] = [
            "sectionID" => $item["sectionID"], // Store sectionID for the course
            "outcomes" => []
        ];
    } elseif ($isGrad) {
        $currentGrad = $item['content'];
        $gradCourses[$currentGrad] = [
            "sectionID" => $item["sectionID"], // Store sectionID for the course
            "outcomes" => []
        ];
    }

    // ✅ Ensure Outcomes Are Stored Under Correct Course
    if (isset($currentUndergrad) && preg_match('/undergrad-course-list-items-\d+$/', $item["description"])) {
        $undergradCourses[$currentUndergrad]["outcomes"][] = [
            "content" => $item['content'],
            "sectionID" => $item["sectionID"] // Include sectionID for each outcome
        ];
    }

    if (isset($currentGrad) && preg_match('/grad-course-list-items-\d+$/', $item["description"])) {
        $gradCourses[$currentGrad]["outcomes"][] = [
            "content" => $item['content'],
            "sectionID" => $item["sectionID"] // Include sectionID for each outcome
        ];
    }
}


?>

<div class="courses-offered-container">
    <h2>Undergraduate Courses</h2>
    <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo md5($courseName); ?>">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse" data-coreui-target="#collapse<?php echo md5($courseName); ?>" aria-expanded="false" aria-controls="collapse<?php echo md5($courseName); ?>">
                    <?php echo $courseName ?>
                </button>
            </h2>
            <div id="collapse<?php echo md5($courseName); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo md5($courseName); ?>" data-coreui-parent="#undergradAccordion">
                <div class="accordion-body">
                    <p><strong>Program Objectives/Outcomes:</strong></p>
                    <ul>
                        <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                            <li><?php echo $outcome['content']; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
    <h2>Graduate Courses</h2>
    <?php foreach ($gradCourses as $courseName => $courseData) { ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo md5($courseName); ?>">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse" data-coreui-target="#collapse<?php echo md5($courseName); ?>" aria-expanded="false" aria-controls="collapse<?php echo md5($courseName); ?>">
                    <?php echo $courseName ?>
                </button>
            </h2>
            <div id="collapse<?php echo md5($courseName); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo md5($courseName); ?>" data-coreui-parent="#undergradAccordion">
                <div class="accordion-body">
                    <p><strong>Program Objectives/Outcomes:</strong></p>
                    <ul>
                        <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                            <li><?php echo $outcome['content']; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>

    <h2>Undergraduate Courses</h2>
    <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
        <div class="courses-item-container">
            <form action="" method="POST" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                <input type="text" name="courseTitle" data-titleSectionID="<?php echo $courseData['sectionID']?>" class="courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                <div class="divider-line"></div>
                <p><strong>Program Objectives/Outcomes:</strong></p>
                <ul>
                    <div class="outcomes-container">
                        <?php $i = 1; ?>
                        <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                            <li>
                                <input type="text" 
                                       name="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                       id="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                       data-sectionID="<?php echo $outcome['sectionID']?>" 
                                       value="<?php echo $outcome['content']?>">
                            </li>
                        <?php $i++;} ?>
                    </div>
                </ul>
                <div class="btn-container">
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
                <div class="divider-line"></div>
                <p><strong>Program Objectives/Outcomes:</strong></p>
                <ul>
                    <div class="outcomes-container">
                        <?php $i = 1; ?>
                        <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                            <li>
                                <input type="text" 
                                       name="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                       id="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                       data-sectionID="<?php echo $outcome['sectionID']?>" 
                                       value="<?php echo $outcome['content']?>">
                            </li>
                        <?php $i++;} ?>
                    </div>
                </ul>
                <div class="btn-container">
                    <input type="submit" value="Submit" class="submitCourse btn btn-success">
                </div>
            </form>
        </div>
    <?php } ?>

</div>
