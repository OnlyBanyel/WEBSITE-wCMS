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
        "Bachelor of Science in Computer Science" => [
            "sectionID" => "temp_undergrad_1",
            "outcomes" => [],
            "index" => 1
        ],
        "Bachelor of Business Administration" => [
            "sectionID" => "temp_undergrad_2",
            "outcomes" => [],
            "index" => 2
        ]
    ];
}

if (empty($gradCourses)) {
    $gradCourses = [
        "Master of Science in Information Technology" => [
            "sectionID" => "temp_grad_1",
            "outcomes" => [],
            "index" => 1
        ],
        "Master of Business Administration" => [
            "sectionID" => "temp_grad_2",
            "outcomes" => [],
            "index" => 2
        ]
    ];
}
?>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#BD0F03',
                    primaryLight: '#ee948e',
                    primaryDark: '#8B0000',
                    secondary: '#f5efef',
                    neutral: '#6a6a6a',
                }
            }
        }
    }
</script>
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
    
    /* Ensure outcomes container has proper styling */
    .outcomes-container {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .outcomes-container li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .outcomes-container li input {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Courses & Programs Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the courses and programs offered by your college</p>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 preview-section cursor-pointer" id="previewSection">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-primary">Preview</h2>
            <span class="text-sm text-gray-500">Click to expand/collapse</span>
        </div>
        
        <div class="preview-content" id="previewContent">
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
    </div>

    <!-- Edit Forms Section -->
    <div class="space-y-8">
        <!-- Undergraduate Courses -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Undergraduate Courses</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden courses-item-container">
                        <div class="bg-primary text-white p-4">
                            <h3 class="font-semibold"><?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? 'Add Course' : 'Edit '.$courseName; ?></h3>
                        </div>
                        <div class="p-5">
                            <form action="../page-functions/updateCourse.php" method="POST" class="space-y-4 course-form" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                                    <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                                    <input type="hidden" name="titleSectionID" value="<?php echo $courseData['sectionID']?>">
                                    <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="courseType" value="undergrad">
                                </div>
                                
                                <div class="border-t border-gray-200 my-4"></div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Objectives/Outcomes</label>
                                    <ul class="outcomes-container space-y-3" id="outcomes-undergrad-<?php echo $courseData['index']; ?>">
                                        <?php 
                                        $i = 1; 
                                        if (!empty($courseData["outcomes"])) {
                                            foreach ($courseData["outcomes"] as $outcome) { 
                                        ?>
                                            <li class="flex items-center gap-2">
                                                <input type="text" 
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                    name="outcome_content[]" 
                                                    id="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                                    data-sectionid="<?php echo $outcome['sectionID']?>" 
                                                    value="<?php echo $outcome['content']?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="<?php echo $outcome['sectionID']?>">
                                                <input type="hidden" name="outcome_isnew[]" value="0">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $outcome['sectionID']?>">
                                                    ×
                                                </button>
                                            </li>
                                        <?php 
                                            $i++; 
                                            }
                                        } else {
                                            // Add empty input field if no outcomes exist
                                        ?>
                                            <li class="flex items-center gap-2">
                                                <input type="text" 
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                    name="outcome_content[]" 
                                                    id="<?php echo $courseName?>-outcomes-1" 
                                                    data-sectionid="temp_outcome_<?php echo $courseData['index']; ?>_1" 
                                                    value="">
                                                <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $courseData['index']; ?>_1">
                                                <input type="hidden" name="outcome_isnew[]" value="1">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_outcome_<?php echo $courseData['index']; ?>_1">
                                                    ×
                                                </button>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                
                                <div class="flex justify-between">
                                    <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors" data-course="undergrad-<?php echo $courseData['index']; ?>">Add Outcome</button>
                                    <input type="submit" value="Save Changes" class="submitCourse bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                
                <!-- Add New Undergraduate Course Button -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center">
                    <button id="addNewUndergradCourse" class="p-5 w-full h-full flex flex-col items-center justify-center text-gray-500 hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium">Add New Undergraduate Course</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Graduate Courses -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Graduate Courses</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($gradCourses as $courseName => $courseData) { ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden courses-item-container">
                        <div class="bg-primary text-white p-4">
                            <h3 class="font-semibold"><?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? 'Add Course' : 'Edit '.$courseName; ?></h3>
                        </div>
                        <div class="p-5">
                            <form action="../page-functions/updateCourse.php" method="POST" class="space-y-4 course-form" name="<?php echo $courseName?>-items" id="<?php echo $courseName?>-items">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                                    <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent courseTitle" id="<?php echo $courseName?>" value="<?php echo $courseName ?>">
                                    <input type="hidden" name="titleSectionID" value="<?php echo $courseData['sectionID']?>">
                                    <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="courseType" value="grad">
                                </div>
                                
                                <div class="border-t border-gray-200 my-4"></div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Objectives/Outcomes</label>
                                    <ul class="outcomes-container space-y-3" id="outcomes-grad-<?php echo $courseData['index']; ?>">
                                        <?php 
                                        $i = 1; 
                                        if (!empty($courseData["outcomes"])) {
                                            foreach ($courseData["outcomes"] as $outcome) { 
                                        ?>
                                            <li class="flex items-center gap-2">
                                                <input type="text" 
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                    name="outcome_content[]" 
                                                    id="<?php echo $courseName?>-outcomes-<?php echo $i?>" 
                                                    data-sectionid="<?php echo $outcome['sectionID']?>" 
                                                    value="<?php echo $outcome['content']?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="<?php echo $outcome['sectionID']?>">
                                                <input type="hidden" name="outcome_isnew[]" value="0">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $outcome['sectionID']?>">
                                                    ×
                                                </button>
                                            </li>
                                        <?php 
                                            $i++; 
                                            }
                                        } else {
                                            // Add empty input field if no outcomes exist
                                        ?>
                                            <li class="flex items-center gap-2">
                                                <input type="text" 
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                    name="outcome_content[]" 
                                                    id="<?php echo $courseName?>-outcomes-1" 
                                                    data-sectionid="temp_outcome_grad_<?php echo $courseData['index']; ?>_1" 
                                                    value="">
                                                <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_grad_<?php echo $courseData['index']; ?>_1">
                                                <input type="hidden" name="outcome_isnew[]" value="1">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_outcome_grad_<?php echo $courseData['index']; ?>_1">
                                                    ×
                                                </button>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                
                                <div class="flex justify-between">
                                    <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors" data-course="grad-<?php echo $courseData['index']; ?>">Add Outcome</button>
                                    <input type="submit" value="Save Changes" class="submitCourse bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                
                <!-- Add New Graduate Course Button -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center">
                    <button id="addNewGradCourse" class="p-5 w-full h-full flex flex-col items-center justify-center text-gray-500 hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium">Add New Graduate Course</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview toggle functionality
    document.getElementById('previewSection').addEventListener('click', function() {
        const previewContent = document.getElementById('previewContent');
        previewContent.classList.toggle('hidden');
    });
    
    // Add outcome functionality
    document.querySelectorAll('.add-outcome').forEach(button => {
        button.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course');
            const outcomesList = document.getElementById('outcomes-' + courseId);
            const items = outcomesList.querySelectorAll('li');
            const newIndex = items.length + 1;
            const formName = this.closest('form').getAttribute('id').split('-')[0];
            const tempId = 'temp_new_outcome_' + Date.now();
            
            const newItem = document.createElement('li');
            newItem.className = 'flex items-center gap-2';
            newItem.innerHTML = `
                <input type="text" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                    name="outcome_content[]" 
                    id="${formName}-outcomes-${newIndex}" 
                    data-sectionid="${tempId}" 
                    value="">
                <input type="hidden" name="outcome_sectionid[]" value="${tempId}">
                <input type="hidden" name="outcome_isnew[]" value="1">
                <button type="button" class="remove-outcome btn btn-danger">
                    ×
                </button>
            `;
            
            outcomesList.appendChild(newItem);
            
            // Add event listener to the new remove button
            newItem.querySelector('.remove-outcome').addEventListener('click', function() {
                this.closest('li').remove();
            });
        });
    });
    
    // Remove outcome functionality
    document.querySelectorAll('.remove-outcome').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('li').remove();
        });
    });
    
    // Add new course buttons
    document.getElementById('addNewUndergradCourse').addEventListener('click', function() {
        // Create a form to submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../page-functions/addCourse.php';
        
        // Create hidden inputs
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'courseType';
        typeInput.value = 'undergrad';
        
        // Append inputs to the form
        form.appendChild(typeInput);
        
        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    });
    
    document.getElementById('addNewGradCourse').addEventListener('click', function() {
        // Create a form to submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../page-functions/addCourse.php';
        
        // Create hidden inputs
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'courseType';
        typeInput.value = 'grad';
        
        // Append inputs to the form
        form.appendChild(typeInput);
        
        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    });
    
    // Form submission with AJAX
    document.querySelectorAll('.course-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect all outcomes data
            const outcomes = [];
            this.querySelectorAll('.outcome-input').forEach(input => {
                const sectionId = input.getAttribute('data-sectionid');
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Course ' + (isNewCourse ? 'added' : 'updated') + ' successfully!');
                    // Reload the page to show updated content
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save course.'));
                    console.error(data);
                    // Re-enable the button if there was an error
                    submitButton.disabled = false;
                    submitButton.value = 'Save Changes';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                // Re-enable the button if there was an error
                submitButton.disabled = false;
                submitButton.value = 'Save Changes';
            });
        });
    });
</script>
