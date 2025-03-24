<?php 
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';
$dbObj = new Database;
$cnPage = new Pages;


if (empty($_SESSION['account'])){ 
    header('Location: login-form.php');
    exit;
}
else{

    $pageID = 3;
    $subpageID = 2;

    // Fetch fresh data from the database
    $_SESSION['cnPage'] = $cnPage->fetchPageData($pageID, $subpageID);
    $_SESSION['cnPage']['CarouselElement'] = $cnPage->fetchSectionsByIndicator('Carousel Element');
    $_SESSION['cnPage']['genInfoFront'] = $cnPage->fetchSectionsByIndicator('General-Info');
    $_SESSION['cnPage']['genInfoBack'] = $cnPage->fetchSectionsByIndicator('General-Info-Back');
    $_SESSION['cnPage']['department'] = $cnPage->fetchSectionsByIndicator('Departments');
    $_SESSION['cnPage']['AccordionCourses'] = $cnPage->fetchSectionsByIndicator('Accordion Courses');
    $_SESSION['cnPage']['AccordionCoursesUndergrad'] = $cnPage->fetchSectionsByIndicator('Accordion Courses Undergrad');
    $_SESSION['cnPage']['AccordionCoursesGrad'] = $cnPage->fetchSectionsByIndicator('Accordion Courses Grad');

    // Update session data
    $pageData = $_SESSION['cnPage'];

    $sections = [
        'CarouselElement' => $_SESSION['cnPage']['CarouselElement'],
        'GeneralInfo' => $_SESSION['cnPage']['genInfoFront'],
        'GeneralInfoItems' => $_SESSION['cnPage']['genInfoBack'],
        'Department' => $_SESSION['cnPage']['department'],
        'AccordionCourses' => $_SESSION['cnPage']['AccordionCourses'],
        'AccordionCoursesUndergrad' => $_SESSION['cnPage']['AccordionCoursesUndergrad'],
        'AccordionCoursesGrad' => $_SESSION['cnPage']['AccordionCoursesGrad'],
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
    echo "<h2>" . preg_replace('/([a-z])([A-Z])/', '$1 $2', $section) . "</h2>";
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

        console.log("Row ID:", rowId, "Column:", column, "Value:", newValue)

        $.ajax({
            url: '../functions/updateData.php',
            type: 'POST',
            data: {
                id: rowId,
                value: newValue,
                column: column
            },
            success: function(response) {
                console.log("Updated Successfully:", response);
                input.val(response.updatedData[column]); 

                // setTimeout(function() {
                //     location.reload();
                // }, 500);
            },

            error: function() {
                alert("Error updating data.");
            }
        });
    });
});
</script>
</body>
