<?php 
session_start();
require_once "../classes/pages.class.php";
$shsObj = new Pages;

// Fetch all strand data using the same pattern as college-overview.php
$strandsData = [];

// Use the same subpage ID for SHS (31)
$subpage = 31;

// Fetch all sections with indicator 'Strand'
$strandsItemSQL = "
    SELECT * FROM page_sections 
    WHERE subpage = $subpage 
    AND indicator = 'Strand'
    ORDER BY sectionID ASC
";

$strandsData = $shsObj->execQuery($strandsItemSQL);

// Process strand data
$strands = [];
$currentStrand = null;

foreach ($strandsData as $data) {
    if ($data["description"] == "strand-name") {
        $currentStrand = $data["content"];
        $strands[$currentStrand] = [
            "name" => $data["content"],
            "sectionID" => $data["sectionID"],
            "desc" => "",
            "desc_sectionID" => "",
            "end_desc" => "",
            "end_desc_sectionID" => "",
            "outcomes" => []
        ];
    }
    
    if ($data["description"] == "strand-desc" && isset($currentStrand)) {
        $strands[$currentStrand]["desc"] = $data["content"];
        $strands[$currentStrand]["desc_sectionID"] = $data["sectionID"];
    }
    
    if ($data["description"] == "strand-desc-end" && isset($currentStrand)) {
        $strands[$currentStrand]["end_desc"] = $data["content"];
        $strands[$currentStrand]["end_desc_sectionID"] = $data["sectionID"];
    }
    
    if (preg_match('/^strand-item-\d+$/', $data["description"]) && isset($currentStrand)) {
        $strands[$currentStrand]["outcomes"][] = [
            "content" => $data["content"],
            "sectionID" => $data["sectionID"]
        ];
    }
}

// Create default strand if none exists
if (empty($strands)) {
    $strands["STEM"] = [
        "name" => "STEM",
        "sectionID" => "temp_strand_name",
        "desc" => "",
        "desc_sectionID" => "temp_strand_desc",
        "end_desc" => "",
        "end_desc_sectionID" => "temp_strand_end_desc",
        "outcomes" => [
            ["content" => "", "sectionID" => "temp_strand_item_1"]
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
    
    /* Strand card styling */
    .strand-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-top: 5px solid #BD0F03;
        transition: all 0.3s ease;
    }
    
    .strand-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* Remove outcome button styling */
    .remove-outcome, .remove-strand {
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
    
    .remove-outcome:hover, .remove-strand:hover {
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
    
    /* Collapsible sections */
    .collapsible-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .collapsible-content.expanded {
        max-height: 2000px;
    }
    
    .collapse-toggle {
        cursor: pointer;
    }
    
    .collapse-icon {
        transition: transform 0.3s ease;
    }
    
    .collapse-icon.rotated {
        transform: rotate(180deg);
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Senior High School Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the Senior High School strands and content</p>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 preview-section cursor-pointer" id="previewSection">
        <div class="flex justify-between items-center mb-4 collapse-toggle" data-target="previewContent">
            <h2 class="text-xl font-semibold text-primary">Preview</h2>
            <span class="collapse-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </div>
        
        <div class="collapsible-content" id="previewContent">
            <?php if (!empty($strands)) { ?>
                <div class="space-y-6">
                    <?php foreach ($strands as $strandName => $strand) { ?>
                        <div class="strand-card">
                            <div class="strand-title">
                                <h3 class="text-lg font-bold text-primary"><?php echo $strand['name']; ?></h3>
                            </div>
                            <p class="text-gray-700 my-3"><?php echo $strand['desc']; ?></p>
                            
                            <h4 class="font-medium text-gray-800 mb-2">Core Subjects Include:</h4>
                            <ul class="space-y-2 pl-5 list-disc text-gray-600">
                                <?php foreach ($strand['outcomes'] as $outcome) { ?>
                                    <li><?php echo $outcome['content']; ?></li>
                                <?php } ?>
                            </ul>
                            
                            <p class="mt-3 text-gray-700"><?php echo $strand['end_desc']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-600">No strands available. Add strands below to see preview.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Add New Strand Button -->
    <div class="mb-6">
        <button id="addNewStrand" class="bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add New Strand
        </button>
    </div>

    <!-- Strands Management Section -->
    <div id="strandsContainer" class="space-y-6">
        <?php foreach ($strands as $strandName => $strand) { ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden strand-form-container" data-strand-id="<?php echo $strand['sectionID']; ?>">
                <div class="bg-primary text-white p-4 flex justify-between items-center collapse-toggle" data-target="strand-content-<?php echo $strand['sectionID']; ?>">
                    <h3 class="font-semibold"><?php echo $strand['name']; ?></h3>
                    <div class="flex items-center">
                        <button type="button" class="remove-strand mr-3" data-strand-id="<?php echo $strand['sectionID']; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <span class="collapse-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </div>
                
                <div class="collapsible-content expanded" id="strand-content-<?php echo $strand['sectionID']; ?>">
                    <div class="p-5">
                        <form action="../page-functions/addStrandItem.php" method="POST" class="space-y-4 strand-form">
                            <input type="hidden" name="subpage" value="31">
                            <input type="hidden" name="strandID" value="<?php echo $strand['sectionID']; ?>">
                            <input type="hidden" name="descID" value="<?php echo $strand['desc_sectionID']; ?>">
                            <input type="hidden" name="endDescID" value="<?php echo $strand['end_desc_sectionID']; ?>">
                            <input type="hidden" name="isNew" value="<?php echo strpos($strand['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand Name</label>
                                <input type="text" name="strandName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo $strand['name']; ?>" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand Description</label>
                                <textarea name="strandDesc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo $strand['desc']; ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Core Subjects</label>
                                <ul class="outcomes-list space-y-3" id="outcomes-list-<?php echo $strand['sectionID']; ?>">
                                    <?php 
                                    if (!empty($strand['outcomes'])) {
                                        foreach ($strand['outcomes'] as $index => $outcome) { 
                                    ?>
                                        <li class="flex items-center gap-2">
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                name="outcome_content[]" 
                                                value="<?php echo $outcome['content']; ?>">
                                            <input type="hidden" name="outcome_sectionid[]" value="<?php echo $outcome['sectionID']; ?>">
                                            <input type="hidden" name="outcome_isnew[]" value="<?php echo strpos($outcome['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                                            <button type="button" class="remove-outcome" data-sectionid="<?php echo $outcome['sectionID']; ?>">
                                                ×
                                            </button>
                                        </li>
                                    <?php 
                                        }
                                    } else {
                                        // Add empty input field if no items exist
                                    ?>
                                        <li class="flex items-center gap-2">
                                            <input type="text" 
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                                name="outcome_content[]" 
                                                value="">
                                            <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_<?php echo $strand['sectionID']; ?>_1">
                                            <input type="hidden" name="outcome_isnew[]" value="1">
                                            <button type="button" class="remove-outcome" data-sectionid="temp_outcome_<?php echo $strand['sectionID']; ?>_1">
                                                ×
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <button type="button" class="add-outcome mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-md text-sm transition-colors" data-strand-id="<?php echo $strand['sectionID']; ?>">
                                    + Add Subject
                                </button>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Closing Description</label>
                                <textarea name="strandEndDesc" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo $strand['end_desc']; ?></textarea>
                            </div>
                            
                            <div class="flex justify-end">
                                <input type="submit" value="Save Changes" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle collapsible sections
        document.querySelectorAll('.collapse-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.collapse-icon');
                
                if (targetContent) {
                    targetContent.classList.toggle('expanded');
                    if (icon) {
                        icon.classList.toggle('rotated');
                    }
                }
            });
        });
        
        // Add new outcome/subject
        document.querySelectorAll('.add-outcome').forEach(button => {
            button.addEventListener('click', function() {
                const strandId = this.getAttribute('data-strand-id');
                const outcomesList = document.getElementById(`outcomes-list-${strandId}`);
                const outcomeCount = outcomesList.querySelectorAll('li').length + 1;
                
                const newOutcome = document.createElement('li');
                newOutcome.className = 'flex items-center gap-2';
                newOutcome.innerHTML = `
                    <input type="text" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                        name="outcome_content[]" 
                        value="">
                    <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_${strandId}_${outcomeCount}">
                    <input type="hidden" name="outcome_isnew[]" value="1">
                    <button type="button" class="remove-outcome" data-sectionid="temp_outcome_${strandId}_${outcomeCount}">
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
        
        // Remove outcome/subject
        document.querySelectorAll('.remove-outcome').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('li').remove();
            });
        });
        
        // Add new strand
        document.getElementById('addNewStrand').addEventListener('click', function() {
            const strandId = 'temp_strand_' + Date.now();
            const strandsContainer = document.getElementById('strandsContainer');
            
            const newStrandForm = document.createElement('div');
            newStrandForm.className = 'bg-white rounded-lg shadow-md overflow-hidden strand-form-container';
            newStrandForm.setAttribute('data-strand-id', strandId);
            
            newStrandForm.innerHTML = `
                <div class="bg-primary text-white p-4 flex justify-between items-center collapse-toggle" data-target="strand-content-${strandId}">
                    <h3 class="font-semibold">New Strand</h3>
                    <div class="flex items-center">
                        <button type="button" class="remove-strand mr-3" data-strand-id="${strandId}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <span class="collapse-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </div>
                
                <div class="collapsible-content expanded" id="strand-content-${strandId}">
                    <div class="p-5">
                        <form action="../page-functions/addStrandItem.php" method="POST" class="space-y-4 strand-form">
                            <input type="hidden" name="subpage" value="31">
                            <input type="hidden" name="strandID" value="${strandId}">
                            <input type="hidden" name="descID" value="temp_desc_${strandId}">
                            <input type="hidden" name="endDescID" value="temp_end_desc_${strandId}">
                            <input type="hidden" name="isNew" value="1">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand Name</label>
                                <input type="text" name="strandName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" value="" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand Description</label>
                                <textarea name="strandDesc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Core Subjects</label>
                                <ul class="outcomes-list space-y-3" id="outcomes-list-${strandId}">
                                    <li class="flex items-center gap-2">
                                        <input type="text" 
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                                            name="outcome_content[]" 
                                            value="">
                                        <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_${strandId}_1">
                                        <input type="hidden" name="outcome_isnew[]" value="1">
                                        <button type="button" class="remove-outcome" data-sectionid="temp_outcome_${strandId}_1">
                                            ×
                                        </button>
                                    </li>
                                </ul>
                                <button type="button" class="add-outcome mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-md text-sm transition-colors" data-strand-id="${strandId}">
                                    + Add Subject
                                </button>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Closing Description</label>
                                <textarea name="strandEndDesc" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>
                            
                            <div class="flex justify-end">
                                <input type="submit" value="Save Changes" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md cursor-pointer transition-colors">
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            strandsContainer.appendChild(newStrandForm);
            
            // Add event listeners to the new elements
            const newToggle = newStrandForm.querySelector('.collapse-toggle');
            newToggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.collapse-icon');
                
                if (targetContent) {
                    targetContent.classList.toggle('expanded');
                    if (icon) {
                        icon.classList.toggle('rotated');
                    }
                }
            });
            
            const newAddOutcome = newStrandForm.querySelector('.add-outcome');
            newAddOutcome.addEventListener('click', function() {
                const strandId = this.getAttribute('data-strand-id');
                const outcomesList = document.getElementById(`outcomes-list-${strandId}`);
                const outcomeCount = outcomesList.querySelectorAll('li').length + 1;
                
                const newOutcome = document.createElement('li');
                newOutcome.className = 'flex items-center gap-2';
                newOutcome.innerHTML = `
                    <input type="text" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent outcome-input"
                        name="outcome_content[]" 
                        value="">
                    <input type="hidden" name="outcome_sectionid[]" value="temp_outcome_${strandId}_${outcomeCount}">
                    <input type="hidden" name="outcome_isnew[]" value="1">
                    <button type="button" class="remove-outcome" data-sectionid="temp_outcome_${strandId}_${outcomeCount}">
                        ×
                    </button>
                `;
                
                outcomesList.appendChild(newOutcome);
                
                // Add event listener to the new remove button
                newOutcome.querySelector('.remove-outcome').addEventListener('click', function() {
                    this.closest('li').remove();
                });
            });
            
            newStrandForm.querySelectorAll('.remove-outcome').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('li').remove();
                });
            });
            
            const newRemoveStrand = newStrandForm.querySelector('.remove-strand');
            newRemoveStrand.addEventListener('click', function() {
                const strandId = this.getAttribute('data-strand-id');
                const strandContainer = document.querySelector(`.strand-form-container[data-strand-id="${strandId}"]`);
                if (strandContainer) {
                    strandContainer.remove();
                }
            });
        });
        
        // Remove strand
        document.querySelectorAll('.remove-strand').forEach(button => {
            button.addEventListener('click', function() {
                const strandId = this.getAttribute('data-strand-id');
                const strandContainer = document.querySelector(`.strand-form-container[data-strand-id="${strandId}"]`);
                
                if (strandContainer) {
                    if (confirm('Are you sure you want to delete this strand? This action cannot be undone.')) {
                        // If it's not a temporary ID (new strand), send delete request to server
                        if (!strandId.startsWith('temp_')) {
                            fetch('../page-functions/removeItem.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `sectionID=${strandId}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    strandContainer.remove();
                                    alert('Strand deleted successfully!');
                                } else {
                                    alert('Error: ' + (data.message || 'Failed to delete strand.'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
                            });
                        } else {
                            // For new strands that haven't been saved yet, just remove from DOM
                            strandContainer.remove();
                        }
                    }
                }
            });
        });
        
        // Form submission with AJAX
        document.querySelectorAll('.strand-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Disable the submit button to prevent double submission
                const submitButton = this.querySelector('input[type="submit"]');
                submitButton.disabled = true;
                submitButton.value = 'Saving...';
                
                // Create FormData object
                const formData = new FormData(this);
                
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
    });
</script>
