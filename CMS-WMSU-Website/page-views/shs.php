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
                        <form action="" method="POST" class="space-y-4 strand-form" id="updateStrandForm-<?php echo $strand['sectionID']; ?>">
                            <input type="hidden" name="subpage" value="31">
                            <input type="hidden" name="strandID" value="<?php echo $strand['sectionID']; ?>">
                            <input type="hidden" name="descID" value="<?php echo $strand['desc_sectionID']; ?>">
                            <input type="hidden" name="endDescID" value="<?php echo $strand['end_desc_sectionID']; ?>">
                            <input type="hidden" name="isNew" value="<?php echo strpos($strand['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand Name</label>
                                <input type="text" name="strandName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent styleable <?php echo isset($strand['styles']) ? implode(' ', json_decode($strand['styles'], true) ?? []) : ''; ?>" value="<?php echo $strand['name']; ?>" required data-section-id="<?php echo $strand['sectionID']; ?>" data-element-name="Strand Name: <?php echo htmlspecialchars($strand['name']); ?>">
                            </div>

                    
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand Description</label>
                                <textarea name="strandDesc" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent styleable" required data-section-id="<?php echo $strand['desc_sectionID']; ?>" data-element-name="Strand Description"><?php echo $strand['desc']; ?></textarea>
                            </div>
                            
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Outcomes</label>
                                <ul class="outcomes-list" id="outcome-list-<?php echo $strand['sectionID']; ?>">
                                    <?php foreach ($strand['outcomes'] as $outcome) { ?>
                                        <li>
                                            <input type="text" name="outcomeContent[]" class="outcome-input styleable" value="<?php echo $outcome['content']; ?>" data-sectionid="<?php echo $outcome['sectionID']; ?>" data-section-id="<?php echo $outcome['sectionID']; ?>" data-element-name="Outcome: <?php echo substr(htmlspecialchars($outcome['content']), 0, 30) . '...'; ?>">
                                            <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $outcome['sectionID']; ?>">X</button>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <button type="button" class="add-outcome bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Outcome</button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strand End Description</label>
                                <textarea name="strandEndDesc" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent styleable" required data-section-id="<?php echo $strand['end_desc_sectionID']; ?>" data-element-name="Strand End Description"><?php echo $strand['end_desc']; ?></textarea>
                            </div>
                            
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Strand</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal for adding new strand -->
<div id="addStrandModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Strand</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addStrandForm" class="space-y-4" method="POST">
          <input type="hidden" name="subpage" value="31">
          <input type="hidden" name="isNew" value="1">
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strand Name</label>
            <input type="text" name="strandName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strand Description</label>
            <textarea name="strandDesc" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strand End Description</label>
            <textarea name="strandEndDesc" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Outcomes</label>
            <ul class="outcomes-list" id="outcome-list-new">
            </ul>
            <button type="button" class="add-outcome bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" data-target="new">Add Outcome</button>
          </div>
          
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Strand</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Add event listener for the "Add New Strand" button
  $('#addNewStrand').click(function() {
    // Open the modal for adding a new strand
    $('#addStrandModal').modal('show');
  });

  // Handle form submission for adding new strand
  $(document).on('submit', '#addStrandForm', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    // Collect outcomes
    $('#outcome-list-new li input').each(function(index) {
        formData.append('outcome_content[]', $(this).val());
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
      }
    });
  });

  // Handle form submission for updating a strand
  $(document).on('submit', 'form[id^="updateStrandForm-"]', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    // Collect outcomes
    $(this).find('.outcomes-list li input').each(function(index) {
        formData.append('outcome_content[]', $(this).val());
        formData.append('outcome_sectionid[]', $(this).data('sectionid'));
        formData.append('outcome_isnew[]', $(this).data('is-new') ? '1' : '0');
    });

    var strandID = $(this).attr('id').replace('updateStrandForm-', '');

    formData.append('strandID', strandID);

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
        }
      });
    }
  });

  // Handle add outcome button click
  $(document).on('click', '.add-outcome', function(e) {
    e.preventDefault();
    const form = $(this).closest('.strand-form');
    const outcomesList = form.find('.outcomes-list');
    const formID = form.attr('id').replace('updateStrandForm-', '');

    const newOutcome = $(`
      <li>
        <input type="text" name="outcomeContent[]" class="outcome-input" data-sectionid="" data-is-new="1">
        <button type="button" class="remove-outcome btn btn-danger">Remove</button>
      </li>
    `);

    outcomesList.append(newOutcome);
  });

  // Handle delete outcome button click
  $(document).on('click', '.remove-outcome', function() {
    const button = $(this);
    const listItem = button.closest('li');

    if (button.data('is-new') || !button.data('sectionid')) {
      listItem.remove();
      return;
    }

    if (confirm('Are you sure you want to delete this outcome permanently?')) {
      $.ajax({
        url: '../page-functions/deleteStrandOutcome.php',
        type: 'POST',
        data: {
          outcomeID: button.data('sectionid')
        },
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
        }
      });
    }
  });
});

</script>
