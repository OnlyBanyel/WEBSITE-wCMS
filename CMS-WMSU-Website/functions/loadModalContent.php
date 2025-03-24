<?php
require_once '../classes/db_connection.class.php';
require_once '../classes/pages.class.php';

if (isset($_GET['section']) && isset($_GET['sectionId']) && isset($_GET['allowedElements'])) {
    $section = $_GET['section'];
    $sectionId = $_GET['sectionId'];
    $allowedElements = is_array($_GET['allowedElements']) ? $_GET['allowedElements'] : json_decode($_GET['allowedElements'], true);

    // Generate the form based on allowed elements
    echo '<form id="addElementForm">';
    echo '<input type="hidden" name="section" value="' . htmlspecialchars($section, ENT_QUOTES, 'UTF-8') . '">';
    echo '<input type="hidden" name="sectionId" value="' . htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') . '">';
    
    foreach ($allowedElements as $element) {
        echo '<div class="form-group">';
        echo '<label for="' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(ucwords(str_replace('-', ' ', $element)), ENT_QUOTES, 'UTF-8') . '</label>';
        echo '<input type="text" class="form-control" name="' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . '" id="' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . '">';
        echo '</div>';
    }
    
    echo '<button type="submit" class="btn btn-primary">Save</button>';
    echo '</form>';
} else {
    echo 'Invalid request';
}
?>
