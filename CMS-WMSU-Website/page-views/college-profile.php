<?php
session_start();
require_once "../classes/pages.class.php";
$collegeProfileObj = new Pages;

// Initialize arrays to prevent undefined variable errors
$collegeProfile = [];
$carouselLogo = [];
$carouselImgs = [];
$collegeName = [];

// Extract data from session
foreach($_SESSION['collegeData'] as $data){
    if ($data['indicator'] == 'College Profile'){
        $collegeProfile[] = $data;
    }
}

// Process college profile data
foreach ($collegeProfile as $data){
    if ($data['description'] == 'carousel-logo'){
        $carouselLogo[] = $data;
    }
    if ($data['description'] == 'carousel-img'){
        $carouselImgs[] = $data;
    }
    if ($data['description'] == 'carousel-logo-text'){
        $collegeName[] = $data;
    }
}

// Create default values if none exist
if (empty($carouselLogo)) {
    $carouselLogo = [['imagePath' => '', 'sectionID' => 'temp_logo']];
}

if (empty($carouselImgs)) {
    $carouselImgs = [
        ['imagePath' => '', 'sectionID' => 'temp_img_1'],
        ['imagePath' => '', 'sectionID' => 'temp_img_2'],
        ['imagePath' => '', 'sectionID' => 'temp_img_3']
    ];
}

if (empty($collegeName)) {
    $collegeName = [['content' => 'College Name', 'sectionID' => 'temp_college_name']];
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
    
    /* Carousel fade animation */
    .carousel-item.active {
        animation: fadeIn 1.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">College Profile Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage your college profile information</p>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 preview-section cursor-pointer" id="previewSection">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-primary">Preview</h2>
            <span class="text-sm text-gray-500">Click to expand/collapse</span>
        </div>
        
        <div class="preview-content" id="previewContent">
            <?php if (!empty($collegeProfile)) { ?>
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/3">
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <?php if (!empty($carouselLogo[0]['imagePath'])) { ?>
                                <img src="<?php echo $carouselLogo[0]['imagePath']; ?>" alt="College Logo" class="w-full h-auto object-contain mb-4">
                            <?php } else { ?>
                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center mb-4">
                                    <p class="text-gray-600">No logo available</p>
                                </div>
                            <?php } ?>
                            <h3 class="text-xl font-bold text-center text-gray-800"><?php echo $collegeName[0]['content']; ?></h3>
                        </div>
                    </div>
                    <div class="md:w-2/3">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-lg overflow-hidden shadow-md">
                                <?php 
                                $active = "active";
                                foreach ($carouselImgs as $index => $img) { 
                                ?>
                                    <div class="carousel-item <?php echo $active; ?>">
                                        <?php if (!empty($img['imagePath'])) { ?>
                                            <img src="<?php echo $img['imagePath']; ?>" class="d-block w-100 h-64 object-cover" alt="College Image">
                                        <?php } else { ?>
                                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                                <p class="text-gray-600">No image available</p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php 
                                    $active = "";
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-600">No profile content available. Add content below to see preview.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Edit Forms Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- College Name -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary text-white p-4">
                <h3 class="font-semibold">Edit College Name</h3>
            </div>
            <div class="p-5">
                <form action="../page-functions/updateCollegeName.php" method="POST" id="collegeNameForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">College Name</label>
                        <input type="text" name="collegeName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="collegeName" value="<?php echo $collegeName[0]['content']; ?>">
                        <input type="hidden" name="textID" value="<?php echo $collegeName[0]['sectionID']; ?>">
                        <input type="hidden" name="isNew" value="<?php echo strpos($collegeName[0]['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                    </div>
                    
                    <div class="flex justify-end">
                        <input type="submit" value="Save Changes" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors">
                    </div>
                </form>
            </div>
        </div>

        <!-- College Logo -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-primary text-white p-4">
                <h3 class="font-semibold"><?php echo !empty($carouselLogo[0]['imagePath']) ? 'Change College Logo' : 'Add College Logo'; ?></h3>
            </div>
            <div class="p-5">
                <form action="../page-functions/uploadLogo.php" method="POST" id="logoForm" enctype="multipart/form-data" class="space-y-4">
                    <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 h-40 flex items-center justify-center">
                        <?php if (!empty($carouselLogo[0]['imagePath'])) { ?>
                            <img src="<?php echo $carouselLogo[0]['imagePath']; ?>" alt="College Logo" class="max-w-full max-h-full object-contain">
                        <?php } else { ?>
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-2 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-500">No logo uploaded</p>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <input type="hidden" name="isNew" value="<?php echo empty($carouselLogo[0]['imagePath']) || strpos($carouselLogo[0]['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                    
                    <div class="flex items-center justify-between">
                        <div class="relative flex-1 mr-4">
                            <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="logoImage" id="logoImage" accept="image/*">
                            <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                                <span class="file-name">Choose a file...</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <input type="submit" name="submitLogo" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors" value="Upload">
                    </div>
                </form>
            </div>
        </div>

        <!-- Carousel Images -->
        <?php foreach ($carouselImgs as $index => $img) { ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-primary text-white p-4">
                    <h3 class="font-semibold"><?php echo !empty($img['imagePath']) ? 'Change Carousel Image ' . ($index + 1) : 'Add Carousel Image ' . ($index + 1); ?></h3>
                </div>
                <div class="p-5">
                    <form action="../page-functions/uploadProfileImgs.php" method="POST" id="carouselForm-<?php echo $img['sectionID']; ?>" enctype="multipart/form-data" class="space-y-4">
                        <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 h-48 flex items-center justify-center">
                            <?php if (!empty($img['imagePath'])) { ?>
                                <img src="<?php echo $img['imagePath']; ?>" alt="Carousel Image <?php echo $index + 1; ?>" class="max-w-full max-h-full object-contain">
                            <?php } else { ?>
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-2 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500">No image uploaded</p>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <input type="hidden" name="imageIndex" value="<?php echo $img['sectionID']; ?>">
                        <input type="hidden" name="isNew" value="<?php echo empty($img['imagePath']) || strpos($img['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                        
                        <div class="flex items-center justify-between">
                            <div class="relative flex-1 mr-4">
                                <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="logoImage" id="carouselImage-<?php echo $index; ?>" accept="image/*">
                                <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                                    <span class="file-name">Choose a file...</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <input type="submit" name="submitImg" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors" value="Upload">
                    </div>
                </form>
            </div>
        <?php } ?>
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
    
    // Update the form submission script for college name to properly handle new items
    // Replace the existing script with this enhanced version

    // Form submission with AJAX
    document.getElementById('collegeNameForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isNew = formData.get('isNew') === '1';
        
        // Disable the submit button to prevent double submission
        const submitButton = this.querySelector('input[type="submit"]');
        submitButton.disabled = true;
        submitButton.value = 'Saving...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('College name ' + (isNew ? 'added' : 'updated') + ' successfully!');
                // Reload the page to show updated content
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update college name.'));
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

    // Logo form submission
    document.getElementById('logoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Disable the submit button to prevent double submission
        const submitButton = this.querySelector('input[type="submit"]');
        submitButton.disabled = true;
        submitButton.value = 'Uploading...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logo updated successfully!');
                // Reload the page to show updated content
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update logo.'));
                console.error(data);
                // Re-enable the button if there was an error
                submitButton.disabled = false;
                submitButton.value = 'Upload';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            // Re-enable the button if there was an error
            submitButton.disabled = false;
            submitButton.value = 'Upload';
        });
    });

    // Carousel image forms submission
    document.querySelectorAll('form[id^="carouselForm-"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Disable the submit button to prevent double submission
            const submitButton = this.querySelector('input[type="submit"]');
            submitButton.disabled = true;
            submitButton.value = 'Uploading...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Image updated successfully!');
                    // Reload the page to show updated content
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update image.'));
                    console.error(data);
                    // Re-enable the button if there was an error
                    submitButton.disabled = false;
                    submitButton.value = 'Upload';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                // Re-enable the button if there was an error
                submitButton.disabled = false;
                submitButton.value = 'Upload';
            });
        });
    });
</script>
