<?php 
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/element_styler.class.php";
?>
<meta name="current-page" content="college-overview.php">
<?php
$collegeProfileObj = new Pages;
$styler = new ElementStyler();
$collegeOverview = [];

foreach($_SESSION['collegeData'] as $data){
    if ($data['indicator'] == 'College Overview'){
        $collegeOverview[] = $data;
    }
}

// Initialize empty arrays to prevent undefined variable errors
$genInfoBackHead = [];
$genInfoTitles = [];
$genInfoImgs = [];
$genInfoBackCGList = [];
$genInfoBackCMList = [];
$genInfoBackCVList = [];

foreach ($collegeOverview as $data){
    if ($data["description"] == "geninfo-front-img") {
        $genInfoImgs[] = $data;
    }
    if ($data['description'] == 'geninfo-back-head'){
        $genInfoBackHead[] = $data;
    }
    if ($data['description'] == 'geninfo-front-title'){
        $genInfoTitles[] = $data;
    }
    if ($data["description"] == "CG-list-item" ) {
        $genInfoBackCGList[] = $data;
    }
    if ($data["description"] == "CM-list-item") {
        $genInfoBackCMList[] = $data;
    }
    if ($data["description"] == "CV-list-item") {
        $genInfoBackCVList[] = $data;
    }
}

// Create default lists if empty
$genInfoBackLists = [
    0 => $genInfoBackCGList,
    1 => $genInfoBackCMList,
    2 => $genInfoBackCVList
];

// Create default titles and headers if none exist
if (empty($genInfoTitles)) {
    $genInfoTitles = [
        ['content' => 'College Goals', 'sectionID' => 'temp_cg'],
        ['content' => 'College Mission', 'sectionID' => 'temp_cm'],
        ['content' => 'College Vision', 'sectionID' => 'temp_cv']
    ];
}

if (empty($genInfoBackHead)) {
    $genInfoBackHead = [
        ['content' => ' ', 'sectionID' => 'temp_cg_head'],
        ['content' => ' ', 'sectionID' => 'temp_cm_head'],
        ['content' => ' ', 'sectionID' => 'temp_cv_head']
    ];
}

// Create a default image if none exists
if (empty($genInfoImgs)) {
    $genInfoImgs = [
        ['imagePath' => '', 'sectionID' => 'temp_img_0'],
        ['imagePath' => '', 'sectionID' => 'temp_img_1']
    ];
}

// Set the current page for the preview component
$previewPage = 'college-overview';
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
    
    /* Ensure outcomes list has proper styling */
    .outcomes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .outcomes-list li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .outcomes-list li input {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
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
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8 styleable" data-section-id="page_header" data-element-name="Page Header">
        <h1 class="text-3xl font-bold text-gray-800">College Overview Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the college overview section of your website</p>
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
            <p class="text-sm text-blue-700"><strong>Note:</strong> The preview below shows how your content will appear on the actual website. Changes you make will be reflected in real-time.</p>
        </div>
    </div>

    <!-- Universal Preview Section -->
    <?php include_once "../components/universal-preview.php"; ?>

    <!-- Edit Forms Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Overview Items -->
        <div class="space-y-6">
            <?php for ($q = 0; $q < 3; $q++) { 
                $titleContent = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['content'] : '';
                $titleSectionID = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['sectionID'] : 'temp_title_'.$q;
                $headContent = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['content'] : '';
                $headSectionID = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['sectionID'] : 'temp_head_'.$q;
                $listItems = isset($genInfoBackLists[$q]) ? $genInfoBackLists[$q] : [];
                
                $sectionNames = ['College Goals', 'College Mission', 'College Vision'];
                if (empty($titleContent)) {
                    $titleContent = $sectionNames[$q];
                }
                
                $listTypes = ['CG-list-item', 'CM-list-item', 'CV-list-item'];
            ?> 
                <div class="bg-white rounded-lg shadow-md overflow-hidden styleable" data-section-id="form_container_<?php echo $q; ?>" data-element-name="Form Container <?php echo $sectionNames[$q]; ?>">
                    <div class="bg-primary text-white p-4 styleable" data-section-id="form_header_<?php echo $q; ?>" data-element-name="Form Header <?php echo $sectionNames[$q]; ?>">
                        <h3 class="font-semibold"><?php echo !empty($titleContent) ? 'Edit '.$titleContent : 'Add '.$sectionNames[$q]; ?></h3>
                    </div>
                    <div class="p-5 styleable" data-section-id="form_body_<?php echo $q; ?>" data-element-name="Form Body <?php echo $sectionNames[$q]; ?>">
                        <form action="#" method="POST" class="space-y-4 overview-form" name="<?php echo $titleContent; ?>-overviewItems" id="<?php echo $titleContent; ?>-overviewItems" data-preview="true">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section Title</label>
                                <input type="text" name="overviewTitle" data-overviewsectionid="<?php echo $titleSectionID; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overviewTitle styleable <?php echo $styler->getElementClassString($titleSectionID); ?>" id="<?php echo $titleContent; ?>" value="<?php echo $titleContent; ?>" data-section-id="<?php echo $titleSectionID; ?>" data-element-name="Section Title Input">
                                <input type="hidden" name="overviewSectionID" value="<?php echo $titleSectionID; ?>">
                                <input type="hidden" name="isNew" value="<?php echo strpos($titleSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                <input type="hidden" name="sectionType" value="<?php echo $q; ?>">
                            </div>
                            
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section Content</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overview-top-content styleable <?php echo $styler->getElementClassString($headSectionID); ?>" name="overviewTopContent" id="overview-top-content-<?php echo $q; ?>" data-sectionid="<?php echo $headSectionID; ?>" value="<?php echo $headContent; ?>" data-section-id="<?php echo $headSectionID; ?>" data-element-name="Section Content Input">
                                <input type="hidden" name="topContentSectionID" value="<?php echo $headSectionID; ?>">
                                <input type="hidden" name="topContentIsNew" value="<?php echo strpos($headSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Outcomes</label>
                                <ul class="outcomes-list space-y-3" id="outcomes-list-<?php echo $q; ?>">
                                    <?php 
                                    $i = 1; 
                                    if (!empty($listItems)) {
                                        foreach ($listItems as $item) { 
                                    ?>
                                        <li class="flex items-center gap-2">
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input styleable <?php echo $styler->getElementClassString($item['sectionID']); ?>"
                                                name="outcome_content[]" 
                                                id="<?php echo $titleContent; ?>-<?php echo $i; ?>-outcomes" 
                                                data-sectionid="<?php echo $item['sectionID']; ?>" 
                                                value="<?php echo $item['content']; ?>"
                                                data-section-id="<?php echo $item['sectionID']; ?>" 
                                                data-element-name="Outcome Input">
                                            <input type="hidden" name="outcome_sectionid[]" value="<?php echo $item['sectionID']; ?>">
                                            <input type="hidden" name="outcome_isnew[]" value="0">
                                            <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $item['sectionID']; ?>">
                                                ×
                                            </button>
                                        </li>
                                    <?php 
                                        $i++; 
                                        }
                                    } else {
                                        // Add empty input field if no items exist
                                    ?>
                                        <li class="flex items-center gap-2">
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input styleable"
                                                name="outcome_content[]" 
                                                id="<?php echo $titleContent; ?>-1-outcomes" 
                                                data-sectionid="temp_outcome_<?php echo $q; ?>_1" 
                                                value=""
                                                data-section-id="temp_outcome_<?php echo $q; ?>_1" 
                                                data-element-name="Outcome Input">
                                            <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $q; ?>_1">
                                            <input type="hidden" name="outcome_isnew[]" value="1">
                                            <input type="hidden" name="outcome_type[]" value="<?php echo $listTypes[$q]; ?>">
                                            <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_outcome_<?php echo $q; ?>_1">
                                                ×
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            
                            <div class="flex justify-between">
                                <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors styleable" data-section="<?php echo $q; ?>" data-type="<?php echo $listTypes[$q]; ?>">Add Outcome</button>
                                <button type="button" class="save-section bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors styleable">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Image Section -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-primary text-white p-4">
                    <h3 class="font-semibold"><?php echo !empty($genInfoImgs) && !empty($genInfoImgs[1]['imagePath']) ? 'Change Overview Image' : 'Add Overview Image'; ?></h3>
                </div>
                <div class="p-5">
                    <form action="../page-functions/uploadOverviewImg.php" method="POST" id="overviewImg-<?php echo isset($genInfoImgs[1]) ? $genInfoImgs[1]['sectionID'] : 'temp_img'; ?>" enctype="multipart/form-data" class="space-y-4">
                        <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 h-64 flex items-center justify-center">
                            <?php if (isset($genInfoImgs[1]) && !empty($genInfoImgs[1]['imagePath'])) { ?>
                                <img src="<?php echo $genInfoImgs[1]['imagePath']; ?>" alt="Overview Image" class="max-w-full max-h-full object-contain">
                            <?php } else { ?>
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-2 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500">No image uploaded</p>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <input type="hidden" name="imageIndex" value="<?php echo isset($genInfoImgs[1]) ? $genInfoImgs[1]['sectionID'] : 'temp_img'; ?>">
                        <input type="hidden" name="isNew" value="<?php echo (!isset($genInfoImgs[1]) || strpos($genInfoImgs[1]['sectionID'], 'temp_') === 0) ? '1' : '0'; ?>">
                        
                        <div class="flex items-center justify-between">
                            <div class="relative flex-1 mr-4">
                                <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="overviewImg" id="overviewImg-<?php echo isset($genInfoImgs[1]) ? $genInfoImgs[1]['sectionID'] : 'temp_img'; ?>" accept="image/*">
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
            </div>
        </div>
    </div>
</div>

<!-- Include Save All Changes Button -->
<?php include_once "../components/save-all-button.php"; ?>

<script>
    // File input display
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose a file...';
            this.parentElement.querySelector('.file-name').textContent = fileName;
        });
    });
    
    // Add outcome functionality
    document.querySelectorAll('.add-outcome').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.overview-form');
            const outcomesList = form.querySelector('.outcomes-list');
            const formName = form.getAttribute('name');
            const nextIndex = outcomesList.querySelectorAll('li').length + 1;
            
            // Generate a temporary ID for new items (negative number)
            const tempSectionID = 'temp_outcome_' + Math.floor(Math.random() * 1000000);
            
            const newOutcome = document.createElement('li');
            newOutcome.className = 'flex items-center gap-2';
            newOutcome.innerHTML = `
                <input type="text" 
                       name="outcome_content[]" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                       id="${formName}-${nextIndex}-outcomes" 
                       data-sectionid="${tempSectionID}" 
                       data-is-new="true" 
                       value="">
                <input type="hidden" name="outcome_sectionid[]" value="${tempSectionID}">
                <input type="hidden" name="outcome_isnew[]" value="1">
                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="${tempSectionID}">×</button>
            `;
            
            outcomesList.appendChild(newOutcome);
            
            // Add event listener to the new remove button
            newOutcome.querySelector('.remove-outcome').addEventListener('click', function() {
                this.closest('li').remove();
                updatePreview();
            });
            
            // Update preview
            updatePreview();
        });
    });
    
    // Remove outcome functionality
    document.querySelectorAll('.remove-outcome').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('li').remove();
            updatePreview();
        });
    });
    
    // Save section functionality
    document.querySelectorAll('.save-section').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            const formData = new FormData(form);
            
            // Update the preview first
            updatePreview();
            
            // Show saving indicator
            const originalText = this.textContent;
            this.textContent = 'Saving...';
            this.disabled = true;
            
            // Simulate saving (just update preview in this case)
            setTimeout(() => {
                this.textContent = 'Saved!';
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 1000);
            }, 500);
        });
    });
    
    // Initialize input change listeners
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('input', function() {
            markUnsavedChanges();
        });
    });
</script>
