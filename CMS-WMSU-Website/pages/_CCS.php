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
    $subpageID = 1;

    // Fetch fresh data from the database
    $_SESSION['ccsPage'] = $ccsPage->fetchPageData($pageID, $subpageID);
    $_SESSION['ccsPage']['CarouselElement'] = $ccsPage->fetchSectionsByIndicator('Carousel Element');
    $_SESSION['ccsPage']['genInfoFront'] = $ccsPage->fetchSectionsByIndicator('General-Info');
    $_SESSION['ccsPage']['genInfoBack'] = $ccsPage->fetchSectionsByIndicator('General-Info-Back');
    $_SESSION['ccsPage']['AccordionCourses'] = $ccsPage->fetchSectionsByIndicator('Accordion Courses');
    $_SESSION['ccsPage']['AccordionCoursesUndergrad'] = $ccsPage->fetchSectionsByIndicator('Accordion Courses Undergrad');
    $_SESSION['ccsPage']['AccordionCoursesGrad'] = $ccsPage->fetchSectionsByIndicator('Accordion Courses Grad');

    // Update session data
    $pageData = $_SESSION['ccsPage'];

    $sections = [
        'CarouselElement' => $_SESSION['ccsPage']['CarouselElement'],
        'GeneralInfo' => $_SESSION['ccsPage']['genInfoFront'],
        'GeneralInfoItems' => $_SESSION['ccsPage']['genInfoBack'],
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
foreach ($sections as $section => $data) {
    $x++;
    if (!empty($data) && is_array($data)) { 
        $tableId = "datatable" . $x;
        $tableIds[] = $tableId;
        echo "<div class='section'>";
        echo "<h2>" . preg_replace('/([a-z])([A-Z])/', '$1 $2', $section) . "</h2>"; ?>
        <?php
        echo "<hr class='border border-primary border-3 opacity-75'>"; 
?>

        <table id='<?php echo $tableId?>'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Content</th>
                    <th>Description</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data as $data2) { ?>
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

<?php
        echo "</div>";
    }
}
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
