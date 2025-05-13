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

// Create default courses if none exist
if (empty($undergradCourses)) {
    $undergradCourses = [
        "Change this with course name " => [
            "sectionID" => "temp_undergrad_1",
            "outcomes" => [],
            "index" => 1
        ],
        "Change this with course name" => [
            "sectionID" => "temp_undergrad_2",
            "outcomes" => [],
            "index" => 2
        ]
    ];
}

if (empty($gradCourses)) {
    $gradCourses = [
        "Change this with course name" => [
            "sectionID" => "temp_grad_1",
            "outcomes" => [],
            "index" => 1
        ],
        "Change this with course name " => [
            "sectionID" => "temp_grad_2",
            "outcomes" => [],
            "index" => 2
        ]
    ];
}
?>

<style>
    /* Override Bootstrap's primary color with our red theme */
    .bg-primary,
    .bg-primary.active,
    .bg-primary:not([class*="bg-opacity"]) {
        --tw-bg-opacity: 1 !important;
        --bs-bg-opacity: 1 !important;
        background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
    }
    
    .btn-primary,
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
        border-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
    }
    
    :root {
        --bs-primary: #BD0F03 !important;
        --bs-primary-rgb: 189, 15, 3 !important;
    }
    
    /* Custom styles for preview section */
    .preview-section {
        transition: all 0.3s ease;
    }
    
    .preview-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(189, 15, 3, 0.1), 0 8px 10px -6px rgba(189, 15, 3, 0.1);
    }
    
    .course-card {
        transition: all 0.3s ease;
    }
    
    .course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(189, 15, 3, 0.1), 0 4px 6px -2px rgba(189, 15, 3, 0.05);
    }
    
    /* Remove outcome button styling */
    .remove-outcome {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.375rem;
        background-color: #ef4444;
        color: white;
        transition: background-color 0.2s;
    }
    
    .remove-outcome:hover {
        background-color: #dc2626;
    }
    
    /* Tab styling */
    .tab-nav {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .tab-button {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .tab-button:hover {
        color: #111827;
    }
    
    .tab-button.active {
        color: #BD0F03;
        border-bottom-color: #BD0F03;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    /* Table styling */
    .courses-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .courses-table th {
        text-align: left;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .courses-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }
    
    .courses-table tr:last-child td {
        border-bottom: none;
    }
    
    .courses-table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Outcomes list styling */
    .outcomes-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .outcomes-list li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .outcomes-list li:last-child {
        margin-bottom: 0;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Courses & Programs Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the courses and programs offered by your college</p>
    </div>

    <!-- Main Content with Tabs -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="tab-nav">
            <button class="tab-button active" data-tab="preview">Preview</button>
            <button class="tab-button" data-tab="undergrad">Undergraduate Courses</button>
            <button class="tab-button" data-tab="grad">Graduate Courses</button>
        </div>
        
        <!-- Preview Tab Content -->
        <div id="preview-tab" class="tab-content active">
            <?php if (!empty($coursesAndPrograms)) { ?>
                <!-- Undergraduate Programs Preview -->
                <div class="mb-8">
                    <div class="bg-primary py-3 px-4 rounded-t-lg">
                        <h3 class="text-white font-semibold">Undergraduate Programs</h3>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-b-lg p-4 space-y-3">
                        <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
                            <div class="course-card bg-gray-50 p-4 rounded-lg border-l-4 border-primary">
                                <h4 class="font-bold text-gray-800 mb-2"><?php echo $courseName; ?></h4>
                                <p class="text-sm text-gray-600 mb-2">Program Objectives/Outcomes:</p>
                                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                    <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                                        <li><?php echo $outcome['content']; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <!-- Graduate Programs Preview -->
                <div>
                    <div class="bg-primary py-3 px-4 rounded-t-lg">
                        <h3 class="text-white font-semibold">Graduate Programs</h3>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-b-lg p-4 space-y-3">
                        <?php foreach ($gradCourses as $courseName => $courseData) { ?>
                            <div class="course-card bg-gray-50 p-4 rounded-lg border-l-4 border-primary">
                                <h4 class="font-bold text-gray-800 mb-2"><?php echo $courseName; ?></h4>
                                <p class="text-sm text-gray-600 mb-2">Program Objectives/Outcomes:</p>
                                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                    <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                                        <li><?php echo $outcome['content']; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-600">No courses or programs available. Add content below to see preview.</p>
                </div>
            <?php } ?>
        </div>
        
        <!-- Undergraduate Courses Tab Content -->
        <div id="undergrad-tab" class="tab-content">
            <div class="mb-4 flex justify-end">
                <button id="addNewUndergradCourse" class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Undergraduate Course
                </button>
            </div>
            
            <table class="courses-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Course Title</th>
                        <th width="50%">Program Objectives/Outcomes</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach ($undergradCourses as $courseName => $courseData) { ?>
                        <tr class="courses-item-container">
                            <td><?php echo $i; ?></td>
                            <td>
                                <form action="../page-functions/updateCourse.php" method="POST" class="space-y-4 course-form" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                                    <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                                    <input type="hidden" name="titleSectionID" value="<?php echo $courseData['sectionID']?>">
                                    <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="courseType" value="undergrad">
                            </td>
                            <td>
                                <div class="mb-2 flex justify-between items-center">
                                    <label class="text-sm font-medium text-gray-700">Outcomes</label>
                                    <button type="button" class="add-outcome bg-primary hover:bg-red-700 text-white px-2 py-1 rounded-md text-sm flex items-center gap-1" data-course="undergrad-<?php echo $courseData['index']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add
                                    </button>
                                </div>
                                <ul class="outcomes-list" id="outcomes-undergrad-<?php echo $courseData['index']; ?>">
                                    <?php 
                                    $j = 1; 
                                    if (!empty($courseData["outcomes"])) {
                                        foreach ($courseData["outcomes"] as $outcome) { 
                                    ?>
                                        <li>
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                name="outcome_content[]" 
                                                id="<?php echo $courseName?>-outcomes-<?php echo $j?>" 
                                                data-sectionid="<?php echo $outcome['sectionID']?>" 
                                                value="<?php echo $outcome['content']?>">
                                            <input type="hidden" name="outcome_sectionid[]" value="<?php echo $outcome['sectionID']?>">
                                            <input type="hidden" name="outcome_isnew[]" value="0">
                                            <button type="button" class="remove-outcome" data-sectionid="<?php echo $outcome['sectionID']?>">
                                                ×
                                            </button>
                                        </li>
                                    <?php 
                                        $j++; 
                                        }
                                    } else {
                                        // Add empty input field if no outcomes exist
                                    ?>
                                        <li>
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                name="outcome_content[]" 
                                                id="<?php echo $courseName?>-outcomes-1" 
                                                data-sectionid="temp_outcome_<?php echo $courseData['index']; ?>_1" 
                                                value="">
                                            <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $courseData['index']; ?>_1">
                                            <input type="hidden" name="outcome_isnew[]" value="1">
                                            <button type="button" class="remove-outcome" data-sectionid="temp_outcome_<?php echo $courseData['index']; ?>_1">
                                                ×
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </td>
                            <td>
                                <div class="flex flex-col space-y-2">
                                    <input type="submit" value="Save Changes" class="submitCourse bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm">
                                    <button type="button" class="deleteCourse bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm"
                                            data-sectionid="<?php echo $courseData['sectionID']; ?>"
                                            data-coursetype="undergrad">
                                        Delete Course
                                    </button>
                                </div>
                                </form>
                            </td>
                        </tr>
                    <?php 
                    $i++;
                    } ?>
                </tbody>
            </table>
        </div>
        
        <!-- Graduate Courses Tab Content -->
        <div id="grad-tab" class="tab-content">
            <div class="mb-4 flex justify-end">
                <button id="addNewGradCourse" class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Graduate Course
                </button>
            </div>
            
            <table class="courses-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Course Title</th>
                        <th width="50%">Program Objectives/Outcomes</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach ($gradCourses as $courseName => $courseData) { ?>
                        <tr class="courses-item-container">
                            <td><?php echo $i; ?></td>
                            <td>
                                <form action="../page-functions/updateCourse.php" method="POST" class="space-y-4 course-form" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                                    <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                                    <input type="hidden" name="titleSectionID" value="<?php echo $courseData['sectionID']?>">
                                    <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="courseType" value="grad">
                            </td>
                            <td>
                                <div class="mb-2 flex justify-between items-center">
                                    <label class="text-sm font-medium text-gray-700">Outcomes</label>
                                    <button type="button" class="add-outcome bg-primary hover:bg-red-700 text-white px-2 py-1 rounded-md text-sm flex items-center gap-1" data-course="grad-<?php echo $courseData['index']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add
                                    </button>
                                </div>
                                <ul class="outcomes-list" id="outcomes-grad-<?php echo $courseData['index']; ?>">
                                    <?php 
                                    $j = 1; 
                                    if (!empty($courseData["outcomes"])) {
                                        foreach ($courseData["outcomes"] as $outcome) { 
                                    ?>
                                        <li>
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                name="outcome_content[]" 
                                                id="<?php echo $courseName?>-outcomes-<?php echo $j?>" 
                                                data-sectionid="<?php echo $outcome['sectionID']?>" 
                                                value="<?php echo $outcome['content']?>">
                                            <input type="hidden" name="outcome_sectionid[]" value="<?php echo $outcome['sectionID']?>">
                                            <input type="hidden" name="outcome_isnew[]" value="0">
                                            <button type="button" class="remove-outcome" data-sectionid="<?php echo $outcome['sectionID']?>">
                                                ×
                                            </button>
                                        </li>
                                    <?php 
                                        $j++; 
                                        }
                                    } else {
                                        // Add empty input field if no outcomes exist
                                    ?>
                                        <li>
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                name="outcome_content[]" 
                                                id="<?php echo $courseName?>-outcomes-1" 
                                                data-sectionid="temp_outcome_grad_<?php echo $courseData['index']; ?>_1" 
                                                value="">
                                            <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_grad_<?php echo $courseData['index']; ?>_1">
                                            <input type="hidden" name="outcome_isnew[]" value="1">
                                            <button type="button" class="remove-outcome" data-sectionid="temp_outcome_grad_<?php echo $courseData['index']; ?>_1">
                                                ×
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </td>
                            <td>
                                <div class="flex flex-col space-y-2">
                                    <input type="submit" value="Save Changes" class="submitCourse bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm">
                                    <button type="button" class="deleteCourse bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm"
                                            data-sectionid="<?php echo $courseData['sectionID']; ?>"
                                            data-coursetype="grad">
                                        Delete Course
                                    </button>
                                </div>
                                </form>
                            </td>
                        </tr>
                    <?php 
                    $i++;
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            button.classList.add('active');
            document.getElementById(`${button.dataset.tab}-tab`).classList.add('active');
        });
    });

    // Remove outcome functionality
    document.querySelectorAll('.remove-outcome').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('li').remove();
        });
    });
    
    // Add outcome functionality
    document.querySelectorAll('.add-outcome').forEach(button => {
        button.addEventListener('click', function() {
            const courseType = this.dataset.course;
            const outcomesList = document.getElementById(`outcomes-${courseType}`);
            const outcomeCount = outcomesList.querySelectorAll('li').length + 1;
            const courseIndex = courseType.split('-')[1];
            const isGrad = courseType.startsWith('grad');
            
            const newOutcomeHTML = `
                <li>
                    <input type="text" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                        name="outcome_content[]" 
                        value=""
                        data-sectionid="temp_new_outcome_${outcomeCount}">
                    <input type="hidden" name="outcome_sectionid[]" value="temp_new_outcome_${outcomeCount}">
                    <input type="hidden" name="outcome_isnew[]" value="1">
                    <button type="button" class="remove-outcome" data-sectionid="temp_new_outcome_${outcomeCount}">
                        ×
                    </button>
                </li>
            `;
            
            outcomesList.insertAdjacentHTML('beforeend', newOutcomeHTML);
            
            // Attach event listener to the new remove button
            outcomesList.querySelector(`li:last-child .remove-outcome`).addEventListener('click', function() {
                this.closest('li').remove();
            });
        });
    });
    
    // Add new course buttons
    document.getElementById('addNewUndergradCourse').addEventListener('click', function() {
        addCourse('undergrad');
    });

    document.getElementById('addNewGradCourse').addEventListener('click', function() {
        addCourse('grad');
    });

    function addCourse(courseType) {
        const button = this;
        const originalHTML = button.innerHTML;
        
        // Show loading state
        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Adding Course...
        `;
        button.disabled = true;

        // Make AJAX request
        fetch('../page-functions/addCourse.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `courseType=${courseType}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload the page to show the new course
                location.reload();
            } else {
                showErrorMessage(data.message || 'Failed to add course');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while adding the course');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    }

    // Setup form submit handler for all course forms
    document.querySelectorAll('.course-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect all outcomes data
            const outcomes = [];
            this.querySelectorAll('.outcome-input').forEach(input => {
                const sectionId = input.getAttribute('data-sectionid') || input.closest('li').querySelector('input[name="outcome_sectionid[]"]').value;
                const isNew = sectionId.startsWith('temp_') || sectionId.startsWith('temp_new_outcome_');
                outcomes.push({
                    content: input.value,
                    sectionID: sectionId,
                    isNew: isNew
                });
            });
            
            // Create FormData object
            const formData = new FormData(this);
            formData.append('outcomes', JSON.stringify(outcomes));
            
            // Check if this is a new course
            const isNewCourse = formData.get('isNew') === '1';
            
            // Disable the submit button to prevent double submission
            const submitButton = this.querySelector('input[type="submit"]');
            submitButton.disabled = true;
            submitButton.value = 'Saving...';
            
            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Course ' + (isNewCourse ? 'added' : 'updated') + ' successfully!');
                    
                    // Update the form to mark it as no longer new
                    if (isNewCourse) {
                        this.querySelector('input[name="isNew"]').value = '0';
                    }
                } else {
                    showErrorMessage(data.message || 'Failed to save course');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('An error occurred while saving the course');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.value = 'Save Changes';
            });
        });
    });

    // Course deletion functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteCourse')) {
            const button = e.target;
            const row = button.closest('tr');
            const sectionID = button.dataset.sectionid;
            const courseType = button.dataset.coursetype;
            
            if (confirm('Are you sure you want to delete this course and all its outcomes?')) {
                // Show loading state
                const originalText = button.textContent;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Make AJAX request
                fetch('../page-functions/deleteCourse.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `sectionID=${sectionID}&courseType=${courseType}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Remove the row with animation
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            row.remove();
                            
                            // Show success message
                            showSuccessMessage('Course deleted successfully!');
                        }, 300);
                    } else {
                        alert(data.message || 'Failed to delete course');
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course');
                    button.textContent = originalText;
                    button.disabled = false;
                });
            }
        }
    });
    
    // Helper functions for showing messages
    function showSuccessMessage(message) {
        const successMsg = document.createElement('div');
        successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
        successMsg.textContent = message;
        document.body.appendChild(successMsg);
        
        setTimeout(() => {
            successMsg.remove();
        }, 3000);
    }
    
    function showErrorMessage(message) {
        const errorMsg = document.createElement('div');
        errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
        errorMsg.textContent = message;
        document.body.appendChild(errorMsg);
        
        setTimeout(() => {
            errorMsg.remove();
        }, 3000);
    }
</script>
