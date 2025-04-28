<?php
session_start();
require_once "../../classes/pages.class.php";

$accManagementObj = new Pages;

// Fetch content managers
$contentManage = $accManagementObj->fetchContentManagers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Manager Accounts</title>
    <style>
        /* File input styling */
        .custom-file-input {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .custom-file-input input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 10;
        }
        
        .custom-file-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            color: #4b5563;
            transition: all 0.2s ease;
        }
        
        .custom-file-input:hover .custom-file-label {
            border-color: #9ca3af;
        }
        
        /* Card hover effect */
        .college-card {
            transition: all 0.3s ease;
        }
        
        .college-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(189, 15, 3, 0.1), 0 8px 10px -6px rgba(189, 15, 3, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-center mb-10">Academic Management</h1>
        
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span><?php echo $_SESSION['success_msg']; ?></span>
                </div>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error_msg'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?php echo $_SESSION['error_msg']; ?></span>
                </div>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>
        
        <!-- Add College Department Section -->
        <div class="mb-12 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary text-white p-4">
                <h2 class="text-xl font-semibold">Add New College Department</h2>
            </div>
            <div class="p-6">
                <form id="addCollegeForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Department Information -->
                        <div class="space-y-4">
                            <div>
                                <label for="collegeName" class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                                <input type="text" id="collegeName" name="collegeName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter department name" required>
                            </div>                                              
                        </div>
                        
                        <!-- Department Logo Upload -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department Logo</label>
                                <div class="border border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <div class="mb-4">
                                        <img id="logoPreview" src="../../imgs/default-dept-img.png" alt="Logo Preview" class="mx-auto h-32 w-32 object-contain">
                                    </div>
                                    
                                    <div class="custom-file-input">
                                        <input type="file" name="collegeLogo" id="collegeLogo" accept="image/*" onchange="previewLogo(this)">
                                        <div class="custom-file-label">
                                            <span id="fileNameDisplay">Choose logo file...</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <p class="mt-2 text-xs text-gray-500">Recommended: Square image, 512x512px or larger</p>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" onclick="addCollege()" class="px-6 py-3 bg-primary hover:bg-primaryDark text-white font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Department
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        
        <!-- Content Manager Accounts Section -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Content Manager Accounts</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(!empty($contentManage)): ?>
                <?php foreach($contentManage as $manager): ?>
                    <div class="transform transition duration-300 hover:-translate-y-2 hover:shadow-lg">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
                            <div class="bg-gray-100 p-6 border-b-2 border-gray-200 text-center">
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-3 border-white shadow-md">
                                    <?php if(!empty($manager['profileImg'])): ?>
                                        <img src="<?php echo htmlspecialchars($manager['profileImg']); ?>" class="w-full h-full object-cover" alt="Profile Image">
                                    <?php else: ?>
                                        <img src="/WEBSITE-wCMS/imgs/profiles/default-profile.png" class="w-full h-full object-cover" alt="Default Profile">
                                    <?php endif; ?>
                                </div>
                                <h5 class="text-xl font-semibold">
                                    <?php echo htmlspecialchars($manager['firstName'] . ' ' . $manager['lastName']); ?>
                                </h5>
                                
                                <?php if(isset($manager['status']) && $manager['status'] == 0): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Suspended
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Active
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="p-6 flex-grow">
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-700"><?php echo htmlspecialchars($manager['email']); ?></span>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="px-3 py-1 text-xs font-semibold text-white bg-cyan-600 rounded-full">
                                        <?php 
                                        echo isset($manager['roleName']) ? htmlspecialchars($manager['roleName']) : 'Role ID: ' . htmlspecialchars($manager['role_id']); 
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded-full">
                                        <?php 
                                        echo isset($manager['pageName']) ? htmlspecialchars($manager['pageName']) : 'Page ID: ' . htmlspecialchars($manager['pageID']); 
                                        ?>
                                    </span>
                                </div>
                                
                                <?php if(!empty($manager['subpage_assigned']) || !empty($manager['subPageName'])): ?>
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                    </svg>
                                    <span class="px-3 py-1 text-xs font-semibold text-white bg-amber-500 rounded-full">
                                        <?php 
                                        echo isset($manager['subPageName']) ? htmlspecialchars($manager['subPageName']) : htmlspecialchars($manager['subpage_assigned']); 
                                        ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                                <?php if(isset($manager['status']) && $manager['status'] == 0): ?>
                                    <form method="POST" class="inline status-form">
                                        <input type="hidden" name="manager_id" value="<?php echo $manager['id']; ?>">
                                        <input type="hidden" name="reactivate_account" value="1">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Reactivate Account
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" class="inline status-form">
                                        <input type="hidden" name="manager_id" value="<?php echo $manager['id']; ?>">
                                        <input type="hidden" name="suspend_account" value="1">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            Suspend Account
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3">
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded" role="alert">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>No content manager accounts found.</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Preview logo image
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                document.getElementById('fileNameDisplay').textContent = fileName;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Preview banner image
        function previewBanner(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                document.getElementById('bannerFileNameDisplay').textContent = fileName;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bannerPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Add College function (to be implemented by the user)
        // Add College function
        function addCollege() {
    // Get form data
    const form = document.getElementById('addCollegeForm');
    const collegeName = document.getElementById('collegeName').value;
    
    // Basic validation
    if (!collegeName) {
        alert('Please enter a college department name');
        return;
    }
    
    // Show loading state
    const submitButton = form.querySelector('button[type="button"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    `;
    submitButton.disabled = true;
    
    // Use jQuery AJAX
    $.ajax({
        url: '../page-functions/addCollegeDept.php',
        type: 'POST',
        data: new FormData(form),
        processData: false,
        contentType: false,
        success: function(data) {
            // Reset button state
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
            
            if (data.success) {
                // Show success message
                const successMessage = $(`
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>${data.message || 'College department added successfully!'}</span>
                        </div>
                    </div>
                `);
                
                // Insert success message before the form
                $(form).before(successMessage);
                
                // Remove success message after 5 seconds
                setTimeout(() => {
                    successMessage.fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
                
                // Reset form
                form.reset();
                $('#logoPreview').attr('src', '../../imgs/profiles/default-profile.png');
                $('#fileNameDisplay').text('Choose logo file...');
                
                // Reload the page after a short delay to show the new department
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                // Show error message
                alert(data.message || 'An error occurred while adding the college department.');
                console.error('Errors:', data.errors);
            }
        },
        error: function(xhr, status, error) {
            // Reset button state
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
            
            console.error('AJAX Error:', status, error);
            alert('An error occurred. Please try again.');
        }
    });
}
    </script>
</body>
</html>