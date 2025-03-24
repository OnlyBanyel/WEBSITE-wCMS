<?php
if (isset($_POST['section']) && isset($_POST['allowedElements'])) {
    $section = $_POST['section'];
    $allowedElements = json_decode($_POST['allowedElements'], true); // Ensure JSON parsing

    if (!is_array($allowedElements)) {
        echo 'Invalid elements data';
        exit;
    }

    echo '<div class="modal-header">';
    echo '<h5 class="modal-title">Add Elements to ' . htmlspecialchars($section, ENT_QUOTES, 'UTF-8') . '</h5>';
    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo '<ul>';
    foreach ($allowedElements as $element) {
        echo '<li><a href="#" class="open-second-modal" data-section="' . htmlspecialchars($section, ENT_QUOTES, 'UTF-8') . 
             '" data-type="' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . 
             '" data-desc="' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . '">' . 
             htmlspecialchars(ucwords(str_replace('-', ' ', $element)), ENT_QUOTES, 'UTF-8') . '</a></li>';
    }
    echo '</ul>';
    echo '</div>';
} else {
    echo 'Invalid request';
}
?>
