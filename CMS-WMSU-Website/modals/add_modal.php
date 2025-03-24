<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sectionID = $_POST['sectionID'] ?? '';
    $allowedElements = $_POST['allowedElements'] ?? [];
    
    echo '<div class="modal-body">';
    echo '<h3>Select an Element to Add</h3>';
    
    foreach ($allowedElements as $element) {
        echo "<button class='btn btn-secondary open-second-modal' 
                data-section-id='$sectionID' 
                data-type='$element' 
                data-desc='$element'>
                Add $element
              </button><br><br>";
    }

    echo '</div>';
}
?>
