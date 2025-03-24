<?php 
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';
$dbObj = new Database;
$ccsPage = new Pages;

if (empty($_SESSION['account'])){ 
    header('Location: login-form.php');
    exit;
}
else{
    $pageID = 3;
    $_SESSION['pageID'] = $pageID;
    $subpageID = 1;
    $_SESSION['subpageID'] = $subpageID;

    // Fetch fresh data from the database
    $_SESSION['ccsPage']['CarouselElement'] = $ccsPage->fetchSectionsByIndicator('Carousel Element', $pageID, $subpageID);
    $_SESSION['ccsPage']['genInfoFront'] = $ccsPage->fetchSectionsByIndicator('General-Info', $pageID, $subpageID);
    $_SESSION['ccsPage']['genInfoBack'] = $ccsPage->fetchSectionsByIndicator('General-Info-Back', $pageID, $subpageID);
    $_SESSION['ccsPage']['department'] = $ccsPage->fetchSectionsByIndicator('Departments', $pageID, $subpageID);
    $_SESSION['ccsPage']['AccordionCourses'] = $ccsPage->fetchSectionsByIndicator('Accordion Courses', $pageID, $subpageID);
    $_SESSION['ccsPage']['AccordionCoursesUndergrad'] = $ccsPage->fetchSectionsByIndicator('Accordion Courses Undergrad', $pageID, $subpageID);
    $_SESSION['ccsPage']['AccordionCoursesGrad'] = $ccsPage->fetchSectionsByIndicator('Accordion Courses Grad', $pageID, $subpageID);

    // Update session data
    $pageData = $_SESSION['ccsPage'];

    $sections = [
        'CarouselElement' => $_SESSION['ccsPage']['CarouselElement'],
        'GeneralInfo' => $_SESSION['ccsPage']['genInfoFront'],
        'GeneralInfoItems' => $_SESSION['ccsPage']['genInfoBack'],
        'Department' => $_SESSION['ccsPage']['department'],
        'AccordionCourses' => $_SESSION['ccsPage']['AccordionCourses'],
        'AccordionCoursesUndergrad' => $_SESSION['ccsPage']['AccordionCoursesUndergrad'],
        'AccordionCoursesGrad' => $_SESSION['ccsPage']['AccordionCoursesGrad'],
    ];
}
?>

<head>
<title>Academics Page</title>
<?php require_once '../__includes/head.php' ?>
<link rel="stylesheet" href="../css/academics-page.css">
</head>
    
<?php require_once '../__includes/navbar.php'?>
<?php require_once '../__includes/sidebar.php'; ?>

<body>

<?php 
$x = 0;
$tableIds = [];
$dataAssoc = [];

// Grouping data by section and description
foreach ($sections as $section => $data) {
    if (!empty($data) && is_array($data)) { 
        foreach ($data as $items) {
            $desc = $items['description'];
            $dataAssoc[$section][$desc][] = $items; // Store rows grouped by description
        }
    }
}

// Now display grouped data
foreach ($dataAssoc as $section => $sectionData) {
    $x++;
    $tableId = "datatable" . $x;
    $tableIds[] = $tableId;

    echo "<div class='section'>";
    $allowedElementsMap = [
        'CarouselElement' => ['carousel-logo-text', 'carousel-img'],
        'GeneralInfo' => ['geninfo-front-img', 'geninfo-front-title'],
        'GeneralInfoItems' => ['CG-list-item', 'CM-list-item', 'CV-list-item'],
        'Department' => ['department-name'],
        'AccordionCoursesUndergrad' => ['course-header', 'undergrad-course-list-items-1','undergrad-course-list-items-2'],
        'AccordionCoursesGrad' => ['course-header', 'grad-course-list-items-3']
    ];
    
    $allowedElements = json_encode($allowedElementsMap[$section] ?? []);

    echo "<h2>" . preg_replace('/([a-z])([A-Z])/', '$1 $2', $section) . " 
            <a class='btn btn-primary add-modal' 
            data-section='$section' 
            data-allowed-elements='" . htmlspecialchars($allowedElements, ENT_QUOTES, 'UTF-8') . "' 
            href='#' role='button'>Add Elements</a>
        </h2>";

    echo "<hr class='border border-primary border-3 opacity-75'>";  
?>
    <div class="container">
   <?php  foreach ($sectionData as $desc => $dataGroup) { ?>
        <div class="description-table-wrapper"> <!-- FLEX CONTAINER -->
            <h3 class="table-title"><?php echo preg_replace('/([a-z])([A-Z])/', '$1 $2', $desc); ?></h3>
            
            <div class="table-container">
                <table id='<?php echo "table_" . str_replace(' ', '_', strtolower($desc)); ?>'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Content</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataGroup as $data2) { ?>
                        <tr>
                            <td><?php echo $data2['sectionID'] ?? ''; ?></td>
                            <td><?php echo $data2['elemType'] ?? ''; ?></td>
                            <td>
                                <input type="text"
                                    class="editable-input"
                                    data-id="<?php echo $data2['sectionID']; ?>"
                                    data-column="<?php echo ($data2['elemType'] === 'text') ? 'content' : 'imagePath'; ?>"
                                    value="<?php echo ($data2['elemType'] === 'text') ? ($data2['content'] ?? '') : ($data2['imagePath'] ?? ''); ?>">
                            </td>
                            <td><?php echo $data2['description'] ?? ''; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div> <!-- Close FLEX CONTAINER -->
    <?php } ?>
    </div>
    
    
                </tbody>
            </table>
        </div>
        <?php
    }
    echo "</div>";
?>

<script>
   $(document).ready(function() {
    <?php foreach ($tableIds as $id) { ?>
        $('#<?php echo $id; ?>').DataTable({
            "autoWidth": false,
            "paging": true,
            "ordering": false,
            "info": false
        });
    <?php } ?>

    $('.editable-input').on('blur', function(){
        var input = $(this);
        var newValue = input.val().trim();
        var rowId = input.data('id');
        var column = input.data('column');

        console.log("Row ID:", rowId, "Column:", column, "Value:", newValue);

        $.ajax({
            url: '../functions/updateData.php',
            type: 'POST',
            dataType: 'json', // Ensure JSON response is expected
            data: {
                id: rowId,
                value: newValue,
                column: column
            },
            success: function(response) {
                if (response.status === "success") {
                    console.log("Updated Successfully:", response);
                    
                    // Update the input field with the new value from the server
                    input.val(response.updatedData[column]);

                    // Optionally highlight the updated row
                    input.closest('tr').css("background-color", "#d4edda");

                } else {
                    console.error("Update Failed:", response.message);
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("An error occurred while updating data.");
            }
        });
    });

    // Load modal content via AJAX
    $('.add-modal').on('click', function(e) {
        e.preventDefault();
        var section = $(this).data('section');
        var allowedElements = $(this).data('allowed-elements');

        $.ajax({
            url: '../modals/add_modal.php',
            type: 'POST',
            data: {
                section: section,
                allowedElements: allowedElements
            },
            success: function(response) {
                $('#modalContent').html(response);
                $('#addModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("An error occurred while loading modal content.");
            }
        });
    });

    // Handle form submission inside modal
    $('#addModal').on('submit', 'form', function(e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: '../functions/save_element.php',
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.status === "success") {
                    alert("Element added successfully!");
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("An error occurred while saving the element.");
            }
        });
    });
});
</script>
<script src="../js/script.js"></script>

<!-- Bootstrap Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Element</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- AJAX-loaded content goes here -->
            </div>
        </div>
    </div>
</div>
</body>
