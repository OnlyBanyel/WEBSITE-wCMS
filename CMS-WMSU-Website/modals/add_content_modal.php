<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sectionID = $_POST['sectionID'] ?? '';
    $elementType = $_POST['elementType'] ?? '';
    $elementDesc = $_POST['elementDesc'] ?? '';
    
    echo "<div class='modal-body'>";
    echo "<h3>Add to $elementDesc</h3>";
    echo "<form id='elementForm' data-section-id='$sectionID' data-type='$elementType'>";
    
    if (strpos($elementType, 'img') !== false) {
        echo "<input type='text' id='elementInput' class='form-control'>";
    } else {
        echo "<input type='text' id='elementInput' class='form-control'>";
    }

    echo "<br><button type='button' class='btn btn-success' id='saveElement'>Save</button>";
    echo "</form></div>";
}
?>
