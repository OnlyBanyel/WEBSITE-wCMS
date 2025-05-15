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

    /* Toast notification styling */
    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
    }

    .toast {
        padding: 0.75rem 1.25rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-width: 250px;
        max-width: 350px;
        animation: slideIn 0.3s ease-out forwards;
    }

    .toast-success {
        background-color: #10b981;
        color: white;
    }

    .toast-error {
        background-color: #ef4444;
        color: white;
    }

    .toast-info {
        background-color: #3b82f6;
        color: white;
    }

    .toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        margin-left: 0.5rem;
        opacity: 0.7;
    }

    .toast-close:hover {
        opacity: 1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .slide-out {
        animation: slideOut 0.3s ease-in forwards;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Toast Container for Notifications -->
    <div id="toast-container" class="toast-container"></div>

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
                                <div class="space-y-4 course-form" data-course-type="undergrad" data-course-index="<?php echo $courseData['index'] ?>">
                                    <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent courseTitle" value="<?php echo $courseName ?>">
                                    <input type="hidden" name="titleSectionID" value="<?php echo $courseData['sectionID']?>">
                                    <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="courseType" value="undergrad">
                                </div>
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
                                    <button type="button" class="save-course bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm">
                                        Save Changes
                                    </button>
                                    <button type="button" class="delete-course bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm"
                                            data-sectionid="<?php echo $courseData['sectionID']; ?>"
                                            data-coursetype="undergrad">
                                        Delete Course
                                    </button>
                                </div>
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
                                <div class="space-y-4 course-form" data-course-type="grad" data-course-index="<?php echo $courseData['index'] ?>">
                                    <input type="text" name="courseTitle" data-titlesectionid="<?php echo $courseData['sectionID']?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent courseTitle" value="<?php echo $courseName ?>">
                                    <input type="hidden" name="titleSectionID" value="<?php echo $courseData['sectionID']?>">
                                    <input type="hidden" name="courseIndex" value="<?php echo $courseData['index'] ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($courseData['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="courseType" value="grad">
                                </div>
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
                                    <button type="button" class="save-course bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm">
                                        Save Changes
                                    </button>
                                    <button type="button" class="delete-course bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-md cursor-pointer transition-colors text-sm"
                                            data-sectionid="<?php echo $courseData['sectionID']; ?>"
                                            data-coursetype="grad">
                                        Delete Course
                                    </button>
                                </div>
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
    // Toast notification system
    const toastContainer = document.getElementById('toast-container');
    
    function showToast(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast-close">&times;</button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto-remove after duration
        const timeout = setTimeout(() => {
            removeToast(toast);
        }, duration);
        
        // Close button functionality
        toast.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(timeout);
            removeToast(toast);
        });
        
        return toast;
    }
    
    function removeToast(toast) {
        toast.classList.add('slide-out');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300); // Match the animation duration
    }
    
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
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-outcome')) {
            const sectionId = e.target.dataset.sectionid;
            
            // If it's a temporary outcome (not saved to DB yet), just remove it from DOM
            if (sectionId.startsWith('temp_')) {
                e.target.closest('li').remove();
                return;
            }
            
            // For existing outcomes, confirm before deletion
            if (confirm('Are you sure you want to remove this outcome?')) {
                const button = e.target;
                const listItem = button.closest('li');
                
                // Show loading state
                button.innerHTML = '...';
                button.disabled = true;
                
                // Send AJAX request to delete the outcome
                fetch('../page-functions/removeItem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `sectionID=${sectionId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the item from DOM with animation
                        listItem.style.opacity = '0';
                        listItem.style.height = '0';
                        listItem.style.transition = 'opacity 0.3s, height 0.3s';
                        
                        setTimeout(() => {
                            listItem.remove();
                            showToast('Outcome removed successfully', 'success');
                        }, 300);
                    } else {
                        showToast(data.message || 'Failed to remove outcome', 'error');
                        button.innerHTML = '×';
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while removing the outcome', 'error');
                    button.innerHTML = '×';
                    button.disabled = false;
                });
            }
        }
    });
    
    // Add outcome functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-outcome')) {
            const courseType = e.target.dataset.course;
            const outcomesList = document.getElementById(`outcomes-${courseType}`);
            const outcomeCount = outcomesList.querySelectorAll('li').length + 1;
            const courseIndex = courseType.split('-')[1];
            const isGrad = courseType.startsWith('grad');
            
            // Generate a unique temporary ID for this new outcome
            const tempId = `temp_new_outcome_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
            
            const newOutcomeHTML = `
                <li>
                    <input type="text" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                        name="outcome_content[]" 
                        value=""
                        data-sectionid="${tempId}">
                    <input type="hidden" name="outcome_sectionid[]" value="${tempId}">
                    <input type="hidden" name="outcome_isnew[]" value="1">
                    <button type="button" class="remove-outcome" data-sectionid="${tempId}">
                        ×
                    </button>
                </li>
            `;
            
            outcomesList.insertAdjacentHTML('beforeend', newOutcomeHTML);
            
            // Focus the new input field
            const newInput = outcomesList.querySelector(`li:last-child input[type="text"]`);
            if (newInput) {
                newInput.focus();
            }
        }
    });
    
    // Add new course buttons
    document.getElementById('addNewUndergradCourse').addEventListener('click', function() {
        addNewCourse('undergrad');
    });

    document.getElementById('addNewGradCourse').addEventListener('click', function() {
        addNewCourse('grad');
    });

    function addNewCourse(courseType) {
        // Show loading state
        const button = courseType === 'undergrad' ? 
            document.getElementById('addNewUndergradCourse') : 
            document.getElementById('addNewGradCourse');
        
        const originalHTML = button.innerHTML;
        
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('New course added successfully!', 'success');
                
                // Get the current page and reload it
                const currentPage = document.querySelector('.dynamic-load.active').dataset.file || 'page-views/courses-offered.php';
                if (typeof loadPage === 'function') {
                    loadPage(currentPage);
                } else {
                    reloadPageContent();
                }
            } else {
                showToast(data.message || 'Failed to add course', 'error');
                
                // Restore button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while adding the course', 'error');
            
            // Restore button state
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    }

    // Save course changes
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('save-course')) {
            const row = e.target.closest('tr');
            const courseForm = row.querySelector('.course-form');
            const courseType = courseForm.dataset.courseType;
            const courseIndex = courseForm.dataset.courseIndex;
            
            // Get form data
            const courseTitle = courseForm.querySelector('input[name="courseTitle"]').value;
            const titleSectionID = courseForm.querySelector('input[name="titleSectionID"]').value;
            const isNew = courseForm.querySelector('input[name="isNew"]').value;
            
            // Collect all outcomes
            const outcomesList = row.querySelector('.outcomes-list');
            const outcomes = [];
            
            outcomesList.querySelectorAll('li').forEach(li => {
                const content = li.querySelector('input[name="outcome_content[]"]').value;
                const sectionId = li.querySelector('input[name="outcome_content[]"]').dataset.sectionid;
                const isNew = sectionId.startsWith('temp_');
                
                outcomes.push({
                    content: content,
                    sectionID: sectionId,
                    isNew: isNew
                });
            });
            
            // Show loading state
            const button = e.target;
            const originalText = button.textContent;
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Saving...
            `;
            button.disabled = true;
            
            // Create form data
            const formData = new FormData();
            formData.append('courseTitle', courseTitle);
            formData.append('titleSectionID', titleSectionID);
            formData.append('isNew', isNew);
            formData.append('courseType', courseType);
            formData.append('courseIndex', courseIndex);
            formData.append('outcomes', JSON.stringify(outcomes));
            
            // Send AJAX request
            fetch('../page-functions/updateCourse.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Course updated successfully!', 'success');
                    
                    // If this was a new course, update the form
                    if (isNew === '1') {
                        courseForm.querySelector('input[name="isNew"]').value = '0';
                    }
                    
                    // Get the current page and reload it
                    const currentPage = document.querySelector('.dynamic-load.active').dataset.file || 'page-views/courses-offered.php';
                    if (typeof loadPage === 'function') {
                        loadPage(currentPage);
                    } else {
                        reloadPageContent();
                    }
                } else {
                    showToast(data.message || 'Failed to save course', 'error');
                    
                    // Restore button state
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while saving the course', 'error');
                
                // Restore button state
                button.textContent = originalText;
                button.disabled = false;
            });
        }
    });

    // Delete course
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-course')) {
            const button = e.target;
            const row = button.closest('tr');
            const sectionID = button.dataset.sectionid;
            const courseType = button.dataset.coursetype;
            
            if (confirm('Are you sure you want to delete this course and all its outcomes?')) {
                // Show loading state
                const originalText = button.textContent;
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                    Deleting...
                `;
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('Course deleted successfully!', 'success');
                        
                        // Get the current page and reload it
                        const currentPage = document.querySelector('.dynamic-load.active').dataset.file || 'page-views/courses-offered.php';
                        if (typeof loadPage === 'function') {
                            loadPage(currentPage);
                        } else {
                            // Fallback animation if we can't reload
                            row.style.opacity = '0';
                            row.style.height = '0';
                            row.style.overflow = 'hidden';
                            row.style.transition = 'opacity 0.3s, height 0.5s';
                            
                            setTimeout(() => {
                                row.remove();
                            }, 500);
                        }
                    } else {
                        showToast(data.message || 'Failed to delete course', 'error');
                        
                        // Restore button state
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while deleting the course', 'error');
                    
                    // Restore button state
                    button.textContent = originalText;
                    button.disabled = false;
                });
            }
        }
    });
    
    // Function to reload the current page content without full page refresh
    function reloadPageContent() {
        // Get the current page URL from the active navigation item
        const currentPage = document.querySelector('.dynamic-load.active').dataset.file || 'page-views/courses-offered.php';
        
        // Show loading toast
        const loadingToast = showToast('Refreshing content...', 'info');
        
        // Use the existing loadPage function from script.js
        if (typeof loadPage === 'function') {
            loadPage(currentPage);
            
            // Remove the loading toast after a short delay
            setTimeout(() => {
                removeToast(loadingToast);
                showToast('Content updated successfully', 'success');
            }, 1000);
        } else {
            // Fallback if loadPage function is not available
            $.ajax({
                url: currentPage,
                type: "GET",
                success: (response) => {
                    $("#main-content-section").html(response);
                    removeToast(loadingToast);
                    showToast('Content updated successfully', 'success');
                    
                    // Reinitialize any necessary components
                    if (typeof initFormHandlers === 'function') {
                        initFormHandlers();
                    }
                },
                error: () => {
                    removeToast(loadingToast);
                    showToast('Failed to refresh content', 'error');
                }
            });
        }
    }
</script>
