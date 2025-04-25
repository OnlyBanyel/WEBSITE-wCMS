<?php
session_start();
require_once '../classes/pages.class.php';

$pagesObj = new Pages;
$departments = [];
$collegeOverview = [];
$genInfoImgs = [];

foreach ($_SESSION['collegeData'] as $data) {
    if ($data['indicator'] == "Departments"){
        $departments[] = $data;
    }
    if ($data['indicator'] == "College Overview"){
        $collegeOverview[] = $data;
    }
}

foreach ($collegeOverview as $imgs){
    if ($imgs['description'] == 'geninfo-front-img'){
        $genInfoImgs[] = $imgs;
    }
}

// Create default departments if none exist
if (empty($departments)) {
    $defaultDepartments = [
        ['content' => 'Department of Computer Science', 'sectionID' => 'temp_dept_1'],
        ['content' => 'Department of Engineering', 'sectionID' => 'temp_dept_2'],
        ['content' => 'Department of Business', 'sectionID' => 'temp_dept_3']
    ];
    $departments = $defaultDepartments;
}

// Create default images if none exist
if (empty($genInfoImgs)) {
    $defaultImages = [
        ['imagePath' => '', 'sectionID' => 'temp_img_1'],
        ['imagePath' => '', 'sectionID' => 'temp_img_2'],
        ['imagePath' => '', 'sectionID' => 'temp_img_3']
    ];
    $genInfoImgs = $defaultImages;
}

// Ensure we have enough images for all departments
while (count($genInfoImgs) < count($departments)) {
    $genInfoImgs[] = ['imagePath' => '', 'sectionID' => 'temp_img_' . (count($genInfoImgs) + 1)];
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
    
    /* Department card hover effect */
    .dept-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    .dept-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(189, 15, 3, 0.2), 0 10px 10px -5px rgba(189, 15, 3, 0.1);
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Departments Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the departments in your college</p>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 cursor-pointer" id="previewSection">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-primary">Departments Preview</h2>
            <span class="text-sm text-gray-500">Click to expand/collapse</span>
        </div>
        
        <div class="preview-content" id="previewContent">
            <?php if (!empty($departments)) { ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $i = 0;
                    foreach ($departments as $items) { ?>
                        <div class="dept-card rounded-lg overflow-hidden shadow-md h-48 relative">
                            <div class="absolute inset-0 bg-primary/70" style="background: linear-gradient(rgba(189, 15, 3, 0.7), rgba(189, 15, 3, 0.7)), url('<?php echo !empty($genInfoImgs[$i]['imagePath']) ? $genInfoImgs[$i]['imagePath'] : ''; ?>') no-repeat center center; background-size: cover;"></div>
                            <div class="absolute inset-0 flex items-center justify-center p-4">
                                <h3 class="text-xl font-bold text-white text-center"><?php echo $items['content']; ?></h3>
                            </div>
                        </div>
                    <?php $i++; } ?>
                </div>
            <?php } else { ?>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-600">No departments available. Add departments below to see preview.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Edit Forms Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php 
        $i = 0;
        foreach ($departments as $items) { ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-primary text-white p-4">
                    <h3 class="font-semibold"><?php echo strpos($items['sectionID'], 'temp_') === 0 ? 'Add Department' : 'Edit '.$items['content']; ?></h3>
                </div>
                <div class="p-5">
                    <form action="../page-functions/uploadDeptImgs.php" method="POST" class="departmentForm space-y-4" id="departmentForm-<?php echo isset($genInfoImgs[$i]) ? $genInfoImgs[$i]['sectionID'] : 'temp_'.$i; ?>" enctype="multipart/form-data">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                            <input type="text" name="deptName" class="deptName w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="deptName" data-textid="<?php echo $items['sectionID']?>" value="<?php echo $items['content']?>">
                            <input type="hidden" name="textID" value="<?php echo $items['sectionID']?>">
                            <input type="hidden" name="isNew" value="<?php echo strpos($items['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                        </div>
                        
                        <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 h-40 flex items-center justify-center">
                            <?php if (!empty($genInfoImgs[$i]['imagePath'])) { ?>
                                <img src="<?php echo $genInfoImgs[$i]['imagePath'] ?>" alt="Department Image" class="max-w-full max-h-full object-contain">
                            <?php } else { ?>
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-2 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500">No image uploaded</p>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="relative flex-1 mr-4">
                                <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="deptImg" id="deptImg-<?php echo isset($genInfoImgs[$i]) ? $genInfoImgs[$i]['sectionID'] : 'temp_'.$i; ?>" accept="image/*">
                                <input type="hidden" name="sectionID" value="<?php echo isset($genInfoImgs[$i]) ? $genInfoImgs[$i]['sectionID'] : 'temp_img_'.$i; ?>">
                                <input type="hidden" name="imgIsNew" value="<?php echo (!isset($genInfoImgs[$i]) || empty($genInfoImgs[$i]['imagePath'])) ? '1' : '0'; ?>">
                                <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                                    <span class="file-name">Choose a new image...</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <input type="submit" id="changeDeptImg" class="changeDeptImg bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        <?php $i++; } ?>
        
        <!-- Add New Department Button -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center">
            <button id="addNewDepartment" class="p-5 w-full h-full flex flex-col items-center justify-center text-gray-500 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="font-medium">Add New Department</span>
            </button>
        </div>
    </div>
</div>

<script>
    // File input display
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose a file...';
            this.parentElement.querySelector('.file-name').textContent = fileName;
        });
    });

    // Preview toggle functionality
    document.getElementById('previewSection').addEventListener('click', function() {
        const previewContent = document.getElementById('previewContent');
        previewContent.classList.toggle('hidden');
    });
    
    // Add new department functionality
    document.getElementById('addNewDepartment').addEventListener('click', function() {
        // Create a form to submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../page-functions/addDepartment.php';
        
        // Create a hidden input for the new department
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'addNewDepartment';
        input.value = '1';
        
        // Append the input to the form
        form.appendChild(input);
        
        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    });
</script>
