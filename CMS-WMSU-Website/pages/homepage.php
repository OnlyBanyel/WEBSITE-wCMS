<?php 
session_start();
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';
$dbObj = new Database;
$homePage = new Pages;

if (empty($_SESSION['account'])) { 
    header('Location: login-form.php');
    exit;
} else {
    $pageID = 1;
    
    // Fetch fresh data from the database
    $_SESSION['homePage']['WmsuNews'] = $homePage->fetchSectionsByIndicator('Wmsu News', $pageID, NULL);
    $_SESSION['homePage']['ResearchArchives'] = $homePage->fetchSectionsByIndicator('Research Archives', $pageID, NULL);
    $_SESSION['homePage']['AboutWMSU'] = $homePage->fetchSectionsByIndicator('About WMSU', $pageID, NULL);
    $_SESSION['homePage']['PresCorner'] = $homePage->fetchSectionsByIndicator('Pres Corner', $pageID, NULL);
    $_SESSION['homePage']['Services'] = $homePage->fetchSectionsByIndicator('Services', $pageID, NULL);
    
    // Update session data
    $pageData = $_SESSION['homePage'];

    $sections = [
        'WmsuNews' => $_SESSION['homePage']['WmsuNews'], 
        'ResearchArchives' => $_SESSION['homePage']['ResearchArchives'],
        'AboutWMSU' => $_SESSION['homePage']['AboutWMSU'],
        'PresCorner' => $_SESSION['homePage']['PresCorner'],
        'Services' => $_SESSION['homePage']['Services'],
    ];
}
?>

<head>
<title>Homepage</title>
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
        <div class="description-table-wrapper"> 
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
        </div>
    <?php } ?>
    </div>
<?php
    echo "</div>";
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

        console.log("Row ID:", rowId, "Column:", column, "Value:", newValue);

        $.ajax({
            url: '../functions/updateHomePage.php',
            type: 'POST',
            dataType: 'json',
            data: {
                id: rowId,
                value: newValue,
                column: column
            },
            success: function(response) {
                if (response.status === "success") {
                    console.log("Updated Successfully:", response);
                    input.val(response.updatedData[column]);
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
});
</script>
<script src="../js/script.js"></script>

</body>
