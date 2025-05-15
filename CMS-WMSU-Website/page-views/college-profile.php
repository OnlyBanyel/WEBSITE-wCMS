<?php
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/element_styler.class.php";
$collegeProfileObj = new Pages;
$styler = new ElementStyler();

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
    
    /* Style for elements being edited */
    .style-editing {
        outline: 2px dashed #BD0F03 !important;
        position: relative;
    }
    
    .style-editing::after {
        content: "Editing";
        position: absolute;
        top: -20px;
        right: 0;
        background-color: #BD0F03;
        color: white;
        padding: 2px 6px;
        font-size: 10px;
        border-radius: 3px;
        z-index: 100;
    }
    
    /* Style for styleable elements when in edit mode */
    body.style-edit-mode .styleable {
        cursor: pointer;
        position: relative;
    }
    
    body.style-edit-mode .styleable:hover {
        outline: 2px dotted #BD0F03;
    }
    
    body.style-edit-mode .styleable:hover::after {
        content: "Click to edit";
        position: absolute;
        top: -20px;
        right: 0;
        background-color: #BD0F03;
        color: white;
        padding: 2px 6px;
        font-size: 10px;
        border-radius: 3px;
        z-index: 100;
    }
    
    /* Tab styles */
    .tab-nav {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1rem;
    }
    
    .tab-button {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .tab-button:hover {
        color: #4b5563;
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
    
    /* Table styles */
    .profile-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .profile-table th {
        text-align: left;
        padding: 0.75rem;
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .profile-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    .profile-table tr:last-child td {
        border-bottom: none;
    }
    
    .profile-table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Image preview container */
    .image-preview {
        width: 100px;
        height: 60px;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 0.375rem;
    }
    
    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8 styleable" data-section-id="page_header" data-element-name="Page Header">
        <h1 class="text-3xl font-bold text-gray-800">College Profile Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage your college profile information</p>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 preview-section cursor-pointer" id="previewSection">
        <div class="flex justify-between items-center mb-4 styleable" data-section-id="preview_header" data-element-name="Preview Header">
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
                            <h3 class="text-xl font-bold text-center text-gray-800 styleable <?php echo $styler->getElementClassString($collegeName[0]['sectionID']); ?>" data-section-id="<?php echo $collegeName[0]['sectionID']; ?>" data-element-name="College Name Display">
                                <?php echo $collegeName[0]['content']; ?>
                            </h3>
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
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg styleable" data-section-id="empty_preview" data-element-name="Empty Preview">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-600">No profile content available. Add content below to see preview.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Tabbed Interface -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <div class="tab-button active" data-tab="college-title">College Title</div>
            <div class="tab-button" data-tab="college-logo">College Logo</div>
            <div class="tab-button" data-tab="carousel-images">Carousel Images</div>
        </div>
        
        <!-- Tab Content -->
        <div class="p-5">
            <!-- College Title Tab -->
            <div class="tab-content active" id="college-title-tab">
                <form action="../page-functions/updateCollegeName.php" method="POST" id="collegeNameForm" class="space-y-4">
                    <table class="profile-table">
                        <thead>
                            <tr>
                                <th>College Name</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="collegeName" class="styleable <?php echo $styler->getElementClassString($collegeName[0]['sectionID']); ?> w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="collegeName" value="<?php echo $collegeName[0]['content']; ?>" data-section-id="<?php echo $collegeName[0]['sectionID']; ?>" data-element-name="College Name">
                                    <input type="hidden" name="textID" value="<?php echo $collegeName[0]['sectionID']; ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($collegeName[0]['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                </td>
                                <td>
                                    <input type="submit" value="Save Changes" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors styleable w-full">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <!-- College Logo Tab -->
            <div class="tab-content" id="college-logo-tab">
                <form action="../page-functions/uploadLogo.php" method="POST" id="logoForm" enctype="multipart/form-data" class="space-y-4">
                    <table class="profile-table">
                        <thead>
                            <tr>
                                <th>Current Logo</th>
                                <th>Upload New Logo</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
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
                                </td>
                                <td>
                                    <div class="relative flex-1">
                                        <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="logoImage" id="logoImage" accept="image/*">
                                        <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                                            <span class="file-name">Choose a file...</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="submit" name="submitLogo" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors w-full" value="Upload">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <!-- Carousel Images Tab -->
            <div class="tab-content" id="carousel-images-tab">
                <table class="profile-table">
                    <thead>
                        <tr>
                            <th width="120">Preview</th>
                            <th>Image</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carouselImgs as $index => $img) { ?>
                            <tr>
                                <td>
                                    <div class="image-preview">
                                        <?php if (!empty($img['imagePath'])) { ?>
                                            <img src="<?php echo $img['imagePath']; ?>" alt="Carousel Image <?php echo $index + 1; ?>">
                                        <?php } else { ?>
                                            <div class="text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <form action="../page-functions/uploadProfileImgs.php" method="POST" id="carouselForm-<?php echo $img['sectionID']; ?>" enctype="multipart/form-data" class="space-y-4">
                                        <input type="hidden" name="imageIndex" value="<?php echo $img['sectionID']; ?>">
                                        <input type="hidden" name="isNew" value="<?php echo empty($img['imagePath']) || strpos($img['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                        <div class="relative">
                                            <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="logoImage" id="carouselImage-<?php echo $index; ?>" accept="image/*">
                                            <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                                                <span class="file-name">Choose a file...</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <button type="submit" form="carouselForm-<?php echo $img['sectionID']; ?>" name="submitImg" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md cursor-pointer transition-colors flex-1 text-sm">
                                            Upload
                                        </button>
                                        <button type="button" class="deleteCarouselImage bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md cursor-pointer transition-colors flex-1 text-sm"
                                                data-sectionid="<?php echo $img['sectionID']; ?>"
                                                data-isnew="<?php echo empty($img['imagePath']) || strpos($img['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <button id="addNewCarouselImage" class="p-3 w-full flex items-center justify-center text-gray-500 hover:text-primary transition-colors border-2 border-dashed border-gray-300 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="font-medium">Add New Carousel Image</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
    
    // Tab functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });

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
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = 'Uploading...';
            
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
                    submitButton.innerHTML = 'Upload';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                // Re-enable the button if there was an error
                submitButton.disabled = false;
                submitButton.innerHTML = 'Upload';
            });
        });
    });

    // Add new carousel image functionality with AJAX
    document.getElementById('addNewCarouselImage').addEventListener('click', function() {
        const button = this;
        const originalHTML = button.innerHTML;
        
        // Show loading state
        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span class="font-medium">Adding Image...</span>
        `;
        button.disabled = true;

        // Make AJAX request
        fetch('../page-functions/addCarouselImage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'addNewCarouselImage=1'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload the page to show the new carousel image slot
                window.location.reload();
            } else {
                // Show error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                errorMsg.textContent = data.message || 'Failed to add carousel image';
                document.body.appendChild(errorMsg);
                
                // Remove message after 3 seconds
                setTimeout(() => {
                    errorMsg.remove();
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = document.createElement('div');
            errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
            errorMsg.textContent = 'An error occurred while adding the carousel image';
            document.body.appendChild(errorMsg);
            
            // Remove message after 3 seconds
            setTimeout(() => {
                errorMsg.remove();
            }, 3000);
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    });

    // Carousel image deletion functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteCarouselImage')) {
            const button = e.target;
            const row = button.closest('tr');
            const sectionID = button.dataset.sectionid;
            const isNew = button.dataset.isnew === '1';
            
            if (confirm('Are you sure you want to delete this carousel image?')) {
                // Show loading state
                const originalText = button.textContent;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Make AJAX request
                fetch('../page-functions/uploadProfileImgs.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `deleteCarouselImage=1&sectionID=${sectionID}&isNew=${isNew}`
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
                            const successMsg = document.createElement('div');
                            successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                            successMsg.textContent = 'Carousel image deleted successfully!';
                            document.body.appendChild(successMsg);
                            
                            setTimeout(() => {
                                successMsg.remove();
                            }, 3000);
                        }, 300);
                    } else {
                        alert(data.message || 'Failed to delete carousel image');
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the carousel image');
                    button.textContent = originalText;
                    button.disabled = false;
                });
            }
        }
    });
</script>