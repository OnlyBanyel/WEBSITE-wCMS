<?php 
session_start();
require_once "../classes/pages.class.php";
$collegeProfileObj = new Pages;
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
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">College Overview Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the college overview section of your website</p>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 preview-section cursor-pointer" id="previewSection">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-primary">Preview</h2>
            <span class="text-sm text-gray-500">Click to expand/collapse</span>
        </div>
        
<div class="preview-content" id="previewContent">
    <?php if (!empty($collegeOverview)) { ?>
        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/2 space-y-6">
                <?php for ($i = 0; $i < count($genInfoBackHead); $i ++) { ?>
                    <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-primary">
                        <h4 class="text-lg font-bold text-primary mb-2 styleable <?php echo isset($genInfoTitles[$i]['styles']) ? implode(' ', json_decode($genInfoTitles[$i]['styles'], true) ?? []) : ''; ?>" 
                            data-section-id="<?php echo $genInfoTitles[$i]['sectionID']; ?>" 
                            data-element-name="Title: <?php echo htmlspecialchars($genInfoTitles[$i]['content']); ?>">
                            <?php echo $genInfoTitles[$i]['content'] ?>
                        </h4>
                        <p class="text-gray-700 mb-3 styleable <?php echo isset($genInfoBackHead[$i]['styles']) ? implode(' ', json_decode($genInfoBackHead[$i]['styles'], true) ?? []) : ''; ?>" 
                           data-section-id="<?php echo $genInfoBackHead[$i]['sectionID']; ?>" 
                           data-element-name="Description: <?php echo substr(htmlspecialchars($genInfoBackHead[$i]['content']), 0, 30) . '...'; ?>">
                            <?php echo $genInfoBackHead[$i]['content']?>
                        </p>
                        <ul class="space-y-2 pl-5 list-disc text-gray-600">
                            <?php foreach ($genInfoBackLists[$i] as $item) { ?>
                                <li class="styleable <?php echo isset($item['styles']) ? implode(' ', json_decode($item['styles'], true) ?? []) : ''; ?>" 
                                    data-section-id="<?php echo $item['sectionID']; ?>" 
                                    data-element-name="List Item: <?php echo substr(htmlspecialchars($item['content']), 0, 30) . '...'; ?>">
                                    <?php echo $item['content']; ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="md:w-1/2">
                <div class="rounded-lg overflow-hidden shadow-md">
                    <?php if (isset($genInfoImgs) && !empty($genInfoImgs) && !empty($genInfoImgs[1]['imagePath'])) { ?>
                        <img src="<?php echo $genInfoImgs[1]['imagePath']; ?>" alt="Overview Image" class="max-w-full max-h-full object-contain styleable" 
                             data-section-id="<?php echo $genInfoImgs[1]['sectionID']; ?>" 
                             data-element-name="Overview Image">
                    <?php } else { ?>
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <p class="text-gray-600">No image available</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600">No overview content available. Add content below to see preview.</p>
        </div>
    <?php } ?>
</div>
    </div>

    <!-- Edit Forms Section -->
    <div class="flex flex-col justify-between gap-2">
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
                                <img src="<?php echo $genInfoImgs[1]['imagePath']; ?>" alt="Overview Image" class="max-w-full max-h-full object-contain styleable" 
                                     data-section-id="<?php echo $genInfoImgs[1]['sectionID']; ?>" 
                                     data-element-name="Overview Image">
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
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-primary text-white p-4">
                        <h3 class="font-semibold"><?php echo !empty($titleContent) ? 'Edit '.$titleContent : 'Add '.$sectionNames[$q]; ?></h3>
                    </div>
                    <div class="p-5">
                        <form action="../page-functions/updateOverviewItem.php" method="POST" class="space-y-4 overview-form" name="<?php echo $titleContent; ?>-overviewItems" id="<?php echo $titleContent; ?>-overviewItems">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section Title</label>
                                <input type="text" name="overviewTitle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overviewTitle styleable <?php echo isset($genInfoTitles[$q]['styles']) ? implode(' ', json_decode($genInfoTitles[$q]['styles'], true) ?? []) : ''; ?>" id="<?php echo $titleContent; ?>" value="<?php echo $titleContent; ?>" data-section-id="<?php echo $titleSectionID; ?>" data-element-name="Title Input: <?php echo htmlspecialchars($titleContent); ?>">
                                <input type="hidden" name="overviewSectionID" value="<?php echo $titleSectionID; ?>">
                                <input type="hidden" name="isNew" value="<?php echo strpos($titleSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                <input type="hidden" name="sectionType" value="<?php echo $q; ?>">
                            </div>
                            
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section Content</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overview-top-content styleable" name="overviewTopContent" id="overview-top-content-<?php echo $q; ?>" data-sectionid="<?php echo $headSectionID; ?>" value="<?php echo $headContent; ?>" data-section-id="<?php echo $headSectionID; ?>" data-element-name="Content: <?php echo substr(htmlspecialchars($headContent), 0, 30) . '...'; ?>">
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
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input styleable"
                                                name="outcome_content[]" 
                                                id="<?php echo $titleContent; ?>-<?php echo $i; ?>-outcomes" 
                                                data-sectionid="<?php echo $item['sectionID']; ?>" 
                                                value="<?php echo $item['content']; ?>"
                                                data-section-id="<?php echo $item['sectionID']; ?>" 
                                                data-element-name="Outcome: <?php echo substr(htmlspecialchars($item['content']), 0, 30) . '...'; ?>">
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
                                                data-element-name="New Outcome">
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
                                <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors" data-section="<?php echo $q; ?>" data-type="<?php echo $listTypes[$q]; ?>">Add Outcome</button>
                                <input type="submit" value="Save Changes" class="submitCourse bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors">
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
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
    
    // Remove outcome functionality
    document.querySelectorAll('.remove-outcome').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('li').remove();
        });
    });
    
    // Add outcome functionality
    document.querySelectorAll('.add-outcome').forEach(button => {
        button.addEventListener('click', function() {
            const section = this.dataset.section;
            const type = this.dataset.type;
            const outcomesList = document.getElementById('outcomes-list-' + section);
            const itemCount = outcomesList.querySelectorAll('li').length + 1;
            const titleContent = this.closest('form').querySelector('.overviewTitle').value;
            
            const newOutcome = document.createElement('li');
            newOutcome.className = 'flex items-center gap-2';
            newOutcome.innerHTML = `
                <input type="text" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input styleable"
                    name="outcome_content[]" 
                    id="${titleContent}-${itemCount}-outcomes" 
                    data-sectionid="temp_new_outcome_${section}_${itemCount}" 
                    value=""
                    data-section-id="temp_new_outcome_${section}_${itemCount}" 
                    data-element-name="New Outcome">
                <input type="hidden" name="outcome_sectionid[]" value="temp_new_outcome_${section}_${itemCount}">
                <input type="hidden" name="outcome_isnew[]" value="1">
                <input type="hidden" name="outcome_type[]" value="${type}">
                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_new_outcome_${section}_${itemCount}">
                    ×
                </button>
            `;
            
            outcomesList.appendChild(newOutcome);
            
            // Add event listener to the new remove button
            newOutcome.querySelector('.remove-outcome').addEventListener('click', function() {
                this.closest('li').remove();
            });
        });
    });
    
    // Form submission with AJAX
    document.querySelectorAll('.overview-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect all outcomes data
            const outcomes = [];
            this.querySelectorAll('.outcome-input').forEach(input => {
                const sectionId = input.getAttribute('data-sectionid');
                const isNew = sectionId.startsWith('temp_');
                outcomes.push({
                    content: input.value,
                    sectionID: sectionId,
                    isNew: isNew
                });
            });
            
            // Create FormData object
            const formData = new FormData(this);
            formData.append('outcomes', JSON.stringify(outcomes));
            
            // Check if this is a new item or an edit
            const titleSectionID = this.querySelector('input[name="overviewSectionID"]').value;
            const isNewItem = titleSectionID.startsWith('temp_');
            formData.append('isNewItem', isNewItem ? '1' : '0');
            
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
                    alert('Changes saved successfully!');
                    // Reload the page to show updated content
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save changes.'));
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
