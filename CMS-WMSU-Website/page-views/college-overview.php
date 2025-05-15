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

// Debug image data
// echo "<pre>Image Data: " . print_r($genInfoImgs, true) . "</pre>";

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
    
    /* Tab styles */
    .tab-nav {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1rem;
        overflow-x: auto;
    }
    
    .tab-button {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
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
    .overview-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .overview-table th {
        text-align: left;
        padding: 0.75rem;
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .overview-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    .overview-table tr:last-child td {
        border-bottom: none;
    }
    
    .overview-table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Image preview container */
    .image-preview {
        width: 100%;
        height: 200px;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
    }
    
    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    /* Toast notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        padding: 12px 20px;
        border-radius: 4px;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-width: 300px;
        max-width: 450px;
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
    
    .toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        margin-left: 10px;
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
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8 styleable" data-section-id="page_header" data-element-name="Page Header">
        <h1 class="text-3xl font-bold text-gray-800">College Overview Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the college overview section of your website</p>
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
            <p class="text-sm text-blue-700"><strong>Note:</strong> Changes you make will be saved when you click the "Save Changes" button.</p>
        </div>
    </div>

    <!-- Universal Preview Section -->
    
    <!-- Toast Container for Notifications -->
    <div class="toast-container" id="toast-container"></div>
    
    <!-- Tabbed Interface -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <div class="tab-button active" data-tab="college-goals">College Goals</div>
            <div class="tab-button" data-tab="college-mission">College Mission</div>
            <div class="tab-button" data-tab="college-vision">College Vision</div>
            <div class="tab-button" data-tab="overview-image">Overview Image</div>
        </div>
        
        <!-- Tab Content -->
        <div class="p-5">
            <!-- College Goals Tab -->
            <div class="tab-content active" id="college-goals-tab">
                <?php 
                $q = 0; // College Goals index
                $titleContent = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['content'] : '';
                $titleSectionID = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['sectionID'] : 'temp_title_'.$q;
                $headContent = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['content'] : '';
                $headSectionID = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['sectionID'] : 'temp_head_'.$q;
                $listItems = isset($genInfoBackLists[$q]) ? $genInfoBackLists[$q] : [];
                
                if (empty($titleContent)) {
                    $titleContent = 'College Goals';
                }
                
                $listType = 'CG-list-item';
                ?>
                
                <form action="#" method="POST" class="space-y-4 overview-form" name="<?php echo $titleContent; ?>-overviewItems" id="<?php echo $titleContent; ?>-overviewItems" data-section-type="goals" data-section-index="<?php echo $q; ?>">
                    <table class="overview-table">
                        <thead>
                            <tr>
                                <th colspan="2">College Goals Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200" class="font-medium">Section Title</td>
                                <td>
                                    <input type="text" name="overviewTitle" data-overviewsectionid="<?php echo $titleSectionID; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overviewTitle styleable <?php echo $styler->getElementClassString($titleSectionID); ?>" id="<?php echo $titleContent; ?>" value="<?php echo $titleContent; ?>" data-section-id="<?php echo $titleSectionID; ?>" data-element-name="Section Title Input">
                                    <input type="hidden" name="overviewSectionID" value="<?php echo $titleSectionID; ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($titleSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="sectionType" value="<?php echo $q; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium">Section Content</td>
                                <td>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overview-top-content styleable <?php echo $styler->getElementClassString($headSectionID); ?>" name="overviewTopContent" id="overview-top-content-<?php echo $q; ?>" data-sectionid="<?php echo $headSectionID; ?>" value="<?php echo $headContent; ?>" data-section-id="<?php echo $headSectionID; ?>" data-element-name="Section Content Input" data-section-type="goals">
                                    <input type="hidden" name="topContentSectionID" value="<?php echo $headSectionID; ?>">
                                    <input type="hidden" name="topContentIsNew" value="<?php echo strpos($headSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium align-top pt-4">Outcomes</td>
                                <td>
                                    <ul class="outcomes-list space-y-3" id="outcomes-list-<?php echo $q; ?>" data-section-type="goals">
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
                                                    data-element-name="Outcome Input"
                                                    data-section-type="goals"
                                                    data-outcome-type="<?php echo $listType; ?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="<?php echo $item['sectionID']; ?>">
                                                <input type="hidden" name="outcome_isnew[]" value="0">
                                                <input type="hidden" name="outcome_type[]" value="<?php echo $listType; ?>">
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
                                                    data-element-name="Outcome Input"
                                                    data-section-type="goals"
                                                    data-outcome-type="<?php echo $listType; ?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $q; ?>_1">
                                                <input type="hidden" name="outcome_isnew[]" value="1">
                                                <input type="hidden" name="outcome_type[]" value="<?php echo $listType; ?>">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_outcome_<?php echo $q; ?>_1">
                                                    ×
                                                </button>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="mt-3">
                                        <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors styleable" data-section="<?php echo $q; ?>" data-type="<?php echo $listType; ?>" data-section-type="goals">Add Outcome</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <button type="button" class="save-section bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors styleable">Save Changes</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <!-- College Mission Tab -->
            <div class="tab-content" id="college-mission-tab">
                <?php 
                $q = 1; // College Mission index
                $titleContent = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['content'] : '';
                $titleSectionID = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['sectionID'] : 'temp_title_'.$q;
                $headContent = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['content'] : '';
                $headSectionID = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['sectionID'] : 'temp_head_'.$q;
                $listItems = isset($genInfoBackLists[$q]) ? $genInfoBackLists[$q] : [];
                
                if (empty($titleContent)) {
                    $titleContent = 'College Mission';
                }
                
                $listType = 'CM-list-item';
                ?>
                
                <form action="#" method="POST" class="space-y-4 overview-form" name="<?php echo $titleContent; ?>-overviewItems" id="<?php echo $titleContent; ?>-overviewItems" data-section-type="mission" data-section-index="<?php echo $q; ?>">
                    <table class="overview-table">
                        <thead>
                            <tr>
                                <th colspan="2">College Mission Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200" class="font-medium">Section Title</td>
                                <td>
                                    <input type="text" name="overviewTitle" data-overviewsectionid="<?php echo $titleSectionID; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overviewTitle styleable <?php echo $styler->getElementClassString($titleSectionID); ?>" id="<?php echo $titleContent; ?>" value="<?php echo $titleContent; ?>" data-section-id="<?php echo $titleSectionID; ?>" data-element-name="Section Title Input">
                                    <input type="hidden" name="overviewSectionID" value="<?php echo $titleSectionID; ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($titleSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="sectionType" value="<?php echo $q; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium">Section Content</td>
                                <td>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overview-top-content styleable <?php echo $styler->getElementClassString($headSectionID); ?>" name="overviewTopContent" id="overview-top-content-<?php echo $q; ?>" data-sectionid="<?php echo $headSectionID; ?>" value="<?php echo $headContent; ?>" data-section-id="<?php echo $headSectionID; ?>" data-element-name="Section Content Input" data-section-type="mission">
                                    <input type="hidden" name="topContentSectionID" value="<?php echo $headSectionID; ?>">
                                    <input type="hidden" name="topContentIsNew" value="<?php echo strpos($headSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium align-top pt-4">Outcomes</td>
                                <td>
                                    <ul class="outcomes-list space-y-3" id="outcomes-list-<?php echo $q; ?>" data-section-type="mission">
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
                                                    data-element-name="Outcome Input"
                                                    data-section-type="mission"
                                                    data-outcome-type="<?php echo $listType; ?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="<?php echo $item['sectionID']; ?>">
                                                <input type="hidden" name="outcome_isnew[]" value="0">
                                                <input type="hidden" name="outcome_type[]" value="<?php echo $listType; ?>">
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
                                                    data-element-name="Outcome Input"
                                                    data-section-type="mission"
                                                    data-outcome-type="<?php echo $listType; ?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $q; ?>_1">
                                                <input type="hidden" name="outcome_isnew[]" value="1">
                                                <input type="hidden" name="outcome_type[]" value="<?php echo $listType; ?>">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_outcome_<?php echo $q; ?>_1">
                                                    ×
                                                </button>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="mt-3">
                                        <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors styleable" data-section="<?php echo $q; ?>" data-type="<?php echo $listType; ?>" data-section-type="mission">Add Outcome</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <button type="button" class="save-section bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors styleable">Save Changes</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <!-- College Vision Tab -->
            <div class="tab-content" id="college-vision-tab">
                <?php 
                $q = 2; // College Vision index
                $titleContent = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['content'] : '';
                $titleSectionID = isset($genInfoTitles[$q]) ? $genInfoTitles[$q]['sectionID'] : 'temp_title_'.$q;
                $headContent = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['content'] : '';
                $headSectionID = isset($genInfoBackHead[$q]) ? $genInfoBackHead[$q]['sectionID'] : 'temp_head_'.$q;
                $listItems = isset($genInfoBackLists[$q]) ? $genInfoBackLists[$q] : [];
                
                if (empty($titleContent)) {
                    $titleContent = 'College Vision';
                }
                
                $listType = 'CV-list-item';
                ?>
                
                <form action="#" method="POST" class="space-y-4 overview-form" name="<?php echo $titleContent; ?>-overviewItems" id="<?php echo $titleContent; ?>-overviewItems" data-section-type="vision" data-section-index="<?php echo $q; ?>">
                    <table class="overview-table">
                        <thead>
                            <tr>
                                <th colspan="2">College Vision Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200" class="font-medium">Section Title</td>
                                <td>
                                    <input type="text" name="overviewTitle" data-overviewsectionid="<?php echo $titleSectionID; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overviewTitle styleable <?php echo $styler->getElementClassString($titleSectionID); ?>" id="<?php echo $titleContent; ?>" value="<?php echo $titleContent; ?>" data-section-id="<?php echo $titleSectionID; ?>" data-element-name="Section Title Input">
                                    <input type="hidden" name="overviewSectionID" value="<?php echo $titleSectionID; ?>">
                                    <input type="hidden" name="isNew" value="<?php echo strpos($titleSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                    <input type="hidden" name="sectionType" value="<?php echo $q; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium">Section Content</td>
                                <td>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent overview-top-content styleable <?php echo $styler->getElementClassString($headSectionID); ?>" name="overviewTopContent" id="overview-top-content-<?php echo $q; ?>" data-sectionid="<?php echo $headSectionID; ?>" value="<?php echo $headContent; ?>" data-section-id="<?php echo $headSectionID; ?>" data-element-name="Section Content Input" data-section-type="vision">
                                    <input type="hidden" name="topContentSectionID" value="<?php echo $headSectionID; ?>">
                                    <input type="hidden" name="topContentIsNew" value="<?php echo strpos($headSectionID, 'temp_') === 0 ? '1' : '0'; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium align-top pt-4">Outcomes</td>
                                <td>
                                    <ul class="outcomes-list space-y-3" id="outcomes-list-<?php echo $q; ?>" data-section-type="vision">
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
                                                    data-element-name="Outcome Input"
                                                    data-section-type="vision"
                                                    data-outcome-type="<?php echo $listType; ?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="<?php echo $item['sectionID']; ?>">
                                                <input type="hidden" name="outcome_isnew[]" value="0">
                                                <input type="hidden" name="outcome_type[]" value="<?php echo $listType; ?>">
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
                                                    data-element-name="Outcome Input"
                                                    data-section-type="vision"
                                                    data-outcome-type="<?php echo $listType; ?>">
                                                <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $q; ?>_1">
                                                <input type="hidden" name="outcome_isnew[]" value="1">
                                                <input type="hidden" name="outcome_type[]" value="<?php echo $listType; ?>">
                                                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="temp_outcome_<?php echo $q; ?>_1">
                                                    ×
                                                </button>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="mt-3">
                                        <button type="button" class="add-outcome bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors styleable" data-section="<?php echo $q; ?>" data-type="<?php echo $listType; ?>" data-section-type="vision">Add Outcome</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <button type="button" class="save-section bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors styleable">Save Changes</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <!-- Overview Image Tab -->
            <div class="tab-content" id="overview-image-tab">
                <form action="../page-functions/uploadOverviewImg.php" method="POST" id="overviewImg-<?php echo isset($genInfoImgs[1]) ? $genInfoImgs[1]['sectionID'] : 'temp_img'; ?>" enctype="multipart/form-data" class="space-y-4">
                    <table class="overview-table">
                        <thead>
                            <tr>
                                <th colspan="2">Overview Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200" class="font-medium">Current Image</td>
                                <td>
                                    <div class="image-preview">
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
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium">Upload New Image</td>
                                <td>
                                    <div class="relative flex-1">
                                        <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="overviewImg" id="overviewImg-<?php echo isset($genInfoImgs[1]) ? $genInfoImgs[1]['sectionID'] : 'temp_img'; ?>" accept="image/*">
                                        <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                                            <span class="file-name">Choose a file...</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <input type="submit" name="submitImg" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors" value="Upload">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Save All Changes Button -->
<?php include_once "../components/save-all-button.php"; ?>

<script>
    // Toast notification system
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div>${message}</div>
            <button class="toast-close">&times;</button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto-remove after 5 seconds
        const timeout = setTimeout(() => {
            removeToast(toast);
        }, 5000);
        
        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(timeout);
            removeToast(toast);
        });
    }
    
    function removeToast(toast) {
        toast.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
    
    // File input display
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose a file...';
            this.parentElement.querySelector('.file-name').textContent = fileName;
        });
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
    
    // Add outcome functionality
    document.querySelectorAll('.add-outcome').forEach(button => {
        // Remove any existing event listeners to prevent duplicates
        button.removeEventListener('click', addOutcomeHandler);
        // Add the event listener
        button.addEventListener('click', addOutcomeHandler);
    });

    // Define the handler function separately to avoid duplicates
    function addOutcomeHandler(event) {
        // Prevent default behavior and stop propagation
        event.preventDefault();
        event.stopPropagation();
        
        const form = this.closest('.overview-form');
        const outcomesList = form.querySelector('.outcomes-list');
        const formName = form.getAttribute('name');
        const nextIndex = outcomesList.querySelectorAll('li').length + 1;
        const sectionType = this.getAttribute('data-type');
        const sectionCategory = this.getAttribute('data-section-type');
        
        // Generate a temporary ID for new items
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
                   data-section-type="${sectionCategory}"
                   data-outcome-type="${sectionType}"
                   value="">
            <input type="hidden" name="outcome_sectionid[]" value="${tempSectionID}">
            <input type="hidden" name="outcome_isnew[]" value="1">
            <input type="hidden" name="outcome_type[]" value="${sectionType}">
            <button type="button" class="remove-outcome btn btn-danger" data-sectionid="${tempSectionID}">×</button>
        `;
        
        outcomesList.appendChild(newOutcome);
        
        // Add event listener to the new remove button
        newOutcome.querySelector('.remove-outcome').addEventListener('click', function() {
            this.closest('li').remove();
        });
        
        // Log to confirm only one item was added
        console.log('Added new outcome item');
    }
    
    // Remove outcome functionality
    document.querySelectorAll('.remove-outcome').forEach(button => {
        button.addEventListener('click', function() {
            const sectionID = this.getAttribute('data-sectionid');
            const listItem = this.closest('li');
            
            // If this is a temporary item (not yet saved to database), just remove it
            if (sectionID.startsWith('temp_')) {
                listItem.remove();
                return;
            }
            
            // Otherwise, send AJAX request to delete from database
            if (confirm('Are you sure you want to delete this item?')) {
                fetch('../page-functions/removeItem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        sectionID: sectionID
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the item from the DOM
                        listItem.remove();
                        
                        // Show success message
                        showToast('Item deleted successfully', 'success');
                    } else {
                        // Show error message
                        showToast('Error: ' + (data.message || 'Failed to delete item'), 'error');
                        console.error('Delete error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while deleting the item. Please try again.', 'error');
                });
            }
        });
    });
    
    // Save section functionality
    document.querySelectorAll('.save-section').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            const formData = new FormData(form);
            const sectionType = form.getAttribute('data-section-type');
            
            // Add section type to form data
            formData.append('section_type', sectionType);
            
            // Show saving indicator
            const originalText = this.textContent;
            this.textContent = 'Saving...';
            this.disabled = true;
            
            // Send AJAX request to save the data
            fetch('../page-functions/updateOverviewItem.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = 'Saved!';
                    
                    // Update any temporary IDs with new permanent IDs
                    if (data.newItems) {
                        data.newItems.forEach(item => {
                            const input = form.querySelector(`input[data-sectionid="${item.tempId}"]`);
                            if (input) {
                                input.setAttribute('data-sectionid', item.newId);
                                const hiddenInput = input.nextElementSibling;
                                if (hiddenInput) {
                                    hiddenInput.value = item.newId;
                                }
                            }
                        });
                    }
                    
                    showToast('Changes saved successfully', 'success');
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.disabled = false;
                    }, 1000);
                } else {
                    this.textContent = 'Error!';
                    console.error('Save error:', data);
                    showToast('Error: ' + (data.message || 'Failed to save changes'), 'error');
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.disabled = false;
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.textContent = 'Error!';
                showToast('An error occurred while saving. Please try again.', 'error');
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                this.textContent = 'Error!';
                showToast('An error occurred while saving. Please try again.', 'error');
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 1000);
            });
        });
    });
    
    // Initialize input change listeners
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('input', function() {
            // Mark form as having unsaved changes
            const form = this.closest('form');
            if (form) {
                form.classList.add('has-changes');
            }
        });
    });
    
    // Handle form submission for image uploads
    document.querySelector('form[enctype="multipart/form-data"]')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const fileInput = this.querySelector('input[type="file"]');
        if (fileInput && fileInput.files.length === 0) {
            showToast('Please select a file to upload', 'error');
            return;
        }
        
        const formData = new FormData(this);
        
        // Show loading indicator
        const submitButton = this.querySelector('input[type="submit"]');
        const originalText = submitButton.value;
        submitButton.value = 'Uploading...';
        submitButton.disabled = true;
        
        fetch('../page-functions/uploadOverviewImg.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Image uploaded successfully', 'success');
                
                // Update the image preview
                const imagePreview = this.querySelector('.image-preview');
                if (imagePreview) {
                    imagePreview.innerHTML = `<img src="${data.newPath}" alt="Overview Image" class="max-w-full max-h-full object-contain">`;
                }
                
                // Reset the file input
                fileInput.value = '';
                this.querySelector('.file-name').textContent = 'Choose a file...';
                
                // Reload the page to reflect changes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast('Error: ' + (data.message || 'Failed to upload image'), 'error');
            }
            
            submitButton.value = originalText;
            submitButton.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while uploading. Please try again.', 'error');
            
            submitButton.value = originalText;
            submitButton.disabled = false;
        });
    });
</script>
