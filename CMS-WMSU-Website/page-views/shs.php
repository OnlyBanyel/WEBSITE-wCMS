<?php 
// No need for session_start() as it's already handled in dashboard.php
require_once "../classes/pages.class.php";
$shsObj = new Pages;

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
$processedGroups = []; // Track processed group IDs

foreach ($strandsData as $data) {
    // Check if this is a strand name
    if ($data["description"] == "strand-name") {
        $currentStrand = $data["content"];
        $groupId = isset($data["group_id"]) ? $data["group_id"] : null;
        
        // Skip if we've already processed this group
        if ($groupId && in_array($groupId, $processedGroups)) {
            continue;
        }
        
        // Add to processed groups if we have a group ID
        if ($groupId) {
            $processedGroups[] = $groupId;
        }
        
        $strands[$currentStrand] = [
            "name" => $data["content"],
            "sectionID" => $data["sectionID"],
            "desc" => "",
            "desc_sectionID" => "",
            "end_desc" => "",
            "end_desc_sectionID" => "",
            "group_id" => $groupId,
            "outcomes" => []
        ];
    }
    
    // Only process if we have a current strand
    if (isset($currentStrand)) {
        $groupId = isset($data["group_id"]) ? $data["group_id"] : null;
        $currentGroupId = isset($strands[$currentStrand]["group_id"]) ? $strands[$currentStrand]["group_id"] : null;
        
        // Only process items that belong to the current strand's group
        if ($groupId && $currentGroupId && $groupId !== $currentGroupId) {
            continue;
        }
        
        if ($data["description"] == "strand-desc") {
            $strands[$currentStrand]["desc"] = $data["content"];
            $strands[$currentStrand]["desc_sectionID"] = $data["sectionID"];
        }
        
        if ($data["description"] == "strand-desc-end") {
            $strands[$currentStrand]["end_desc"] = $data["content"];
            $strands[$currentStrand]["end_desc_sectionID"] = $data["sectionID"];
        }
        
        if (preg_match('/^strand-item-\d+$/', $data["description"])) {
            $strands[$currentStrand]["outcomes"][] = [
                "content" => $data["content"],
                "sectionID" => $data["sectionID"]
            ];
        }
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
        "group_id" => null,
        "outcomes" => [
            ["content" => "", "sectionID" => "temp_strand_item_1"]
        ]
    ];
}

// Debug log
error_log("Processed strands: " . print_r(array_keys($strands), true));
?>

<!-- No need for HTML, head, body tags as this is loaded into dashboard.php -->
<style>
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
    
    /* Tab styling */
    .tabs-container {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    .tab {
        display: inline-block;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .tab:hover {
        color: #BD0F03;
    }
    
    .tab.active {
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
    table.form-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }
    
    table.form-table th {
        background-color: #f3f4f6;
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border: 1px solid #e5e7eb;
    }
    
    table.form-table td {
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    table.form-table input[type="text"], 
    table.form-table textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
    }
    
    table.form-table textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    /* Section styling */
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    /* Button styling */
    .btn-primary {
        background-color: #2563eb;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.2s;
    }
    
    .btn-primary:hover {
        background-color: #1d4ed8;
    }
    
    .btn-danger {
        background-color: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.2s;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
    }
    
    .btn-success {
        background-color: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.2s;
    }
    
    .btn-success:hover {
        background-color: #059669;
    }
    
    /* Empty state styling */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        background-color: #f9fafb;
        border-radius: 0.5rem;
        border: 1px dashed #d1d5db;
    }
    
    .empty-state svg {
        width: 4rem;
        height: 4rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Senior High School Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the Senior High School strands and content</p>
    </div>

    <!-- Tabs: Preview and Management -->
    <div class="bg-white rounded-xl shadow-md mb-8">
        <div class="tabs-container">
            <div class="tab active" data-tab="preview">Preview</div>
            <div class="tab" data-tab="management">Strand Management</div>
        </div>
        
        <!-- Preview Tab Content -->
        <div class="tab-content active p-6" id="preview-tab">
            <?php if (!empty($strands)) { ?>
                <div class="space-y-6">
                    <?php foreach ($strands as $strandName => $strand) { ?>
                        <div class="strand-card">
                            <div class="strand-title">
                                <h3 class="text-lg font-bold text-primary styleable <?php echo isset($strand['styles']) ? implode(' ', json_decode($strand['styles'], true) ?? []) : ''; ?>"
                            data-section-id="<?php echo $strand['sectionID']; ?>" 
                            data-element-name="Strand: <?php echo htmlspecialchars($strand['name']); ?>">
                                    <?php echo $strand['name']; ?>
                                </h3>
                            </div>
                            <p class="text-gray-700 my-3 <?php echo isset($strand['desc_styles']) ? implode(' ', json_decode($strand['desc_styles'], true) ?? []) : ''; ?>">
                                <?php echo $strand['desc']; ?>
                            </p>
                            
                            <h4 class="font-medium text-gray-800 mb-2">Core Subjects Include:</h4>
                            <ul class="space-y-2 pl-5 list-disc text-gray-600">
                                <?php foreach ($strand['outcomes'] as $outcome) { ?>
                                    <li class="<?php echo isset($outcome['styles']) ? implode(' ', json_decode($outcome['styles'], true) ?? []) : ''; ?>">
                                        <?php echo $outcome['content']; ?>
                                    </li>
                                <?php } ?>
                            </ul>
                            
                            <p class="mt-3 text-gray-700 <?php echo isset($strand['end_desc_styles']) ? implode(' ', json_decode($strand['end_desc_styles'], true) ?? []) : ''; ?>">
                                <?php echo $strand['end_desc']; ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-600">No strands available. Add strands below to see preview.</p>
                </div>
            <?php } ?>
        </div>
        
        <!-- Management Tab Content -->
        <div class="tab-content p-6" id="management-tab">
            <!-- Add New Strand Button -->
            <div class="mb-6">
                <button id="addNewStrand" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add New Strand
                </button>
            </div>
            
            <?php if (!empty($strands)) { ?>
                <!-- Strand Tabs -->
                <div class="tabs-container strand-tabs">
                    <?php 
                    $firstStrand = true;
                    foreach ($strands as $strandName => $strand) { 
                    ?>
                        <div class="tab <?php echo $firstStrand ? 'active' : ''; ?>" data-strand-tab="strand-<?php echo $strand['sectionID']; ?>">
                            <?php echo $strand['name']; ?>
                        </div>
                    <?php 
                        $firstStrand = false;
                    } 
                    ?>
                </div>
                
                <!-- Strand Tab Contents -->
                <?php 
                $firstStrand = true;
                foreach ($strands as $strandName => $strand) { 
                ?>
                    <div class="tab-content <?php echo $firstStrand ? 'active' : ''; ?>" id="strand-<?php echo $strand['sectionID']; ?>-content">
                        <div class="mb-4 flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Edit Strand: <?php echo $strand['name']; ?></h2>
                            <button type="button" class="btn-danger remove-strand" data-strand-id="<?php echo $strand['sectionID']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete Strand
                            </button>
                        </div>
                        
                        <form action="" method="POST" class="strand-form" id="updateStrandForm-<?php echo $strand['sectionID']; ?>">
                            <input type="hidden" name="subpage" value="31">
                            <input type="hidden" name="strandID" value="<?php echo $strand['sectionID']; ?>">
                            <input type="hidden" name="descID" value="<?php echo $strand['desc_sectionID']; ?>">
                            <input type="hidden" name="endDescID" value="<?php echo $strand['end_desc_sectionID']; ?>">
                            <input type="hidden" name="isNew" value="<?php echo strpos($strand['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                            <input type="hidden" name="groupId" value="<?php echo $strand['group_id']; ?>">
                            
                            <!-- Strand Name Section -->
                            <div class="section-title">Strand Information</div>
                            <table class="form-table">
                                <tr>
                                    <th width="20%">Strand Name</th>
                                    <td width="80%">
                                        <input type="text" name="strandName" class="styleable <?php echo isset($strand['styles']) ? implode(' ', json_decode($strand['styles'], true) ?? []) : ''; ?>" 
                                            value="<?php echo $strand['name']; ?>" required 
                                            data-section-id="<?php echo $strand['sectionID']; ?>" 
                                            data-element-name="Strand Name: <?php echo htmlspecialchars($strand['name']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Strand Description</th>
                                    <td>
                                        <textarea name="strandDesc" class="styleable" required 
                                            data-section-id="<?php echo $strand['desc_sectionID']; ?>" 
                                            data-element-name="Strand Description"><?php echo $strand['desc']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Strand End Description</th>
                                    <td>
                                        <textarea name="strandEndDesc" class="styleable" required 
                                            data-section-id="<?php echo $strand['end_desc_sectionID']; ?>" 
                                            data-element-name="Strand End Description"><?php echo $strand['end_desc']; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Core Subjects Section -->
                            <div class="section-title">Core Subjects</div>
                            <div class="mb-4">
                                <button type="button" class="btn-success add-outcome">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Subject
                                </button>
                            </div>
                            
                            <table class="form-table outcomes-table">
                                <thead>
                                    <tr>
                                        <th width="80%">Subject Name</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="outcomes-list" id="outcome-list-<?php echo $strand['sectionID']; ?>">
                                    <?php if (!empty($strand['outcomes'])) { ?>
                                        <?php foreach ($strand['outcomes'] as $index => $outcome) { ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="outcomeContent[]" class="outcome-input styleable" 
                                                        value="<?php echo $outcome['content']; ?>" 
                                                        data-sectionid="<?php echo $outcome['sectionID']; ?>" 
                                                        data-section-id="<?php echo $outcome['sectionID']; ?>" 
                                                        data-element-name="Outcome: <?php echo substr(htmlspecialchars($outcome['content']), 0, 30) . '...'; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="remove-outcome" data-sectionid="<?php echo $outcome['sectionID']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr class="empty-row">
                                            <td colspan="2" class="text-center text-gray-500">
                                                No subjects added yet. Click "Add Subject" to add one.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                            <!-- Submit Button -->
                            <div class="mt-6 text-right">
                                <button type="submit" class="btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Update Strand
                                </button>
                            </div>
                        </form>
                    </div>
                <?php 
                    $firstStrand = false;
                } 
                ?>
            <?php } else { ?>
                <!-- Empty state when no strands exist -->
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p>No strands available. Click "Add New Strand" to create your first strand.</p>
                    <button id="addNewStrandEmpty" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add New Strand
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal for adding new strand -->
<div id="addStrandModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Strand</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addStrandForm" method="POST">
          <input type="hidden" name="subpage" value="31">
          <input type="hidden" name="isNew" value="1">
          
          <!-- Strand Information Section -->
          <div class="section-title">Strand Information</div>
          <table class="form-table">
            <tr>
              <th width="20%">Strand Name</th>
              <td width="80%">
                <input type="text" name="strandName" required>
              </td>
            </tr>
            <tr>
              <th>Strand Description</th>
              <td>
                <textarea name="strandDesc" required></textarea>
              </td>
            </tr>
            <tr>
              <th>Strand End Description</th>
              <td>
                <textarea name="strandEndDesc" required></textarea>
              </td>
            </tr>
          </table>
          
          <!-- Core Subjects Section -->
          <div class="section-title">Core Subjects</div>
          <div class="mb-4">
            <button type="button" class="btn-success add-outcome" data-target="new">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Add Subject
            </button>
          </div>
          
          <table class="form-table outcomes-table">
            <thead>
              <tr>
                <th width="80%">Subject Name</th>
                <th width="20%">Actions</th>
              </tr>
            </thead>
            <tbody class="outcomes-list" id="outcome-list-new">
              <tr class="empty-row">
                <td colspan="2" class="text-center text-gray-500">
                  No subjects added yet. Click "Add Subject" to add one.
                </td>
              </tr>
            </tbody>
          </table>
          
          <!-- Submit Button -->
          <div class="mt-6 text-right">
            <button type="submit" class="btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Add Strand
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Tab switching functionality
  $('.tab[data-tab]').click(function() {
    const tabId = $(this).data('tab');
    
    // Switch main tabs (Preview/Management)
    $('.tab[data-tab]').removeClass('active');
    $(this).addClass('active');
    $('.tab-content').removeClass('active');
    $(`#${tabId}-tab`).addClass('active');
    
    // If switching to management tab, ensure the first strand tab is active
    if (tabId === 'management') {
      const firstStrandTab = $('.tab[data-strand-tab]').first();
      if (firstStrandTab.length) {
        firstStrandTab.click();
      }
    }
  });
  
  // Strand tab switching functionality
  $('.tab[data-strand-tab]').click(function() {
    const strandId = $(this).data('strand-tab');
    
    // Switch strand tabs
    $('.tab[data-strand-tab]').removeClass('active');
    $(this).addClass('active');
    $('.tab-content[id$="-content"]').removeClass('active');
    $(`#${strandId}-content`).addClass('active');
  });

  // Add event listener for the "Add New Strand" buttons
  $('#addNewStrand, #addNewStrandEmpty').click(function() {
    // Open the modal for adding a new strand
    $('#addStrandModal').modal('show');
  });

  // Handle form submission for adding new strand
  $(document).on('submit', '#addStrandForm', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    
    // Collect outcomes properly
    $('#outcome-list-new tr:not(.empty-row)').each(function(index) {
        formData.append('outcome_content[]', $(this).find('input').val());
    });
    
    $.ajax({
        url: '../page-functions/addStrandItem.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error);
            console.error(xhr.responseText);
        }
    });
  });

  // Handle form submission for updating a strand
  $(document).on('submit', 'form[id^="updateStrandForm-"]', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    var strandID = $(this).find('input[name="strandID"]').val();
    var groupId = $(this).find('input[name="groupId"]').val();

    // Collect outcomes
    $(this).find('.outcomes-list tr:not(.empty-row)').each(function(index) {
        var input = $(this).find('input.outcome-input');
        formData.append('outcome_content[]', input.val());
        formData.append('outcome_sectionid[]', input.data('sectionid') || '');
        formData.append('outcome_isnew[]', input.data('is-new') ? '1' : '0');
    });

    // Add group ID to form data
    formData.append('groupId', groupId);

    $.ajax({
      url: '../page-functions/updateStrand.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message);
        }
      },
      error: function(xhr, status, error) {
        alert('An error occurred: ' + error);
        console.error(xhr.responseText);
      }
    });
  });

  // Handle delete strand button click
  $(document).on('click', '.remove-strand', function() {
    var strandID = $(this).data('strand-id');

    if (confirm('Are you sure you want to delete this strand?')) {
      $.ajax({
        url: '../page-functions/deleteStrand.php',
        type: 'POST',
        data: { strandID: strandID },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert(response.message);
            location.reload();
          } else {
            alert(response.message);
          }
        },
        error: function(xhr, status, error) {
          alert('An error occurred: ' + error);
          console.error(xhr.responseText);
        }
      });
    }
  });

  // Handle add outcome button click
  $(document).on('click', '.add-outcome', function(e) {
    e.preventDefault();
    const outcomesList = $(this).closest('form, .modal-body').find('.outcomes-list');
    const isNewStrand = $(this).data('target') === 'new';
    
    // Remove empty row if it exists
    outcomesList.find('.empty-row').remove();
    
    const newOutcome = $(`
      <tr>
        <td>
          <input type="text" name="outcomeContent[]" class="outcome-input" data-sectionid="" data-is-new="1">
        </td>
        <td class="text-center">
          <button type="button" class="remove-outcome">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
    `);

    outcomesList.append(newOutcome);
  });

  // Handle delete outcome button click
  $(document).on('click', '.remove-outcome', function() {
    const button = $(this);
    const row = button.closest('tr');
    const sectionId = row.find('input').data('sectionid');
    const outcomesList = row.closest('.outcomes-list');

    if (!sectionId || row.find('input').data('is-new')) {
      row.remove();
      
      // If no more rows, add empty row
      if (outcomesList.find('tr').length === 0) {
        outcomesList.append(`
          <tr class="empty-row">
            <td colspan="2" class="text-center text-gray-500">
              No subjects added yet. Click "Add Subject" to add one.
            </td>
          </tr>
        `);
      }
      
      return;
    }

    if (confirm('Are you sure you want to delete this subject permanently?')) {
      $.ajax({
        url: '../page-functions/deleteStrandOutcome.php',
        type: 'POST',
        data: {
          outcomeID: sectionId
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert(response.message);
            row.remove();
            
            // If no more rows, add empty row
            if (outcomesList.find('tr').length === 0) {
              outcomesList.append(`
                <tr class="empty-row">
                  <td colspan="2" class="text-center text-gray-500">
                    No subjects added yet. Click "Add Subject" to add one.
                  </td>
                </tr>
              `);
            }
          } else {
            alert(response.message);
          }
        },
        error: function(xhr, status, error) {
          alert('An error occurred: ' + error);
          console.error(xhr.responseText);
        }
      });
    }
  });
  
  // Debug log to check if strands exist
  console.log("Number of strands: " + $('.tab[data-strand-tab]').length);
  console.log("Number of strand contents: " + $('.tab-content[id$="-content"]').length);
});
</script>
