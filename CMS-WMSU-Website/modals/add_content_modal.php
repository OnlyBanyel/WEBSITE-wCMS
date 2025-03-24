<?php
session_start();
if (isset($_POST['section']) && isset($_POST['elementType']) && isset($_POST['elementDesc'])) {
    $section = $_POST['section']; // Fixed sectionID mismatch
    $elementType = $_POST['elementType'];
    $elementDesc = $_POST['elementDesc'];

    echo '<div class="modal-header">';
    echo '<h5 class="modal-title">Add ' . htmlspecialchars(ucwords(str_replace('-', ' ', $elementType)), ENT_QUOTES, 'UTF-8') . '</h5>';
    echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo '<form id="elementForm" data-type="' . htmlspecialchars($elementType, ENT_QUOTES, 'UTF-8') . '">';
    
    echo '<div class="form-group">';
    echo '<label for="elementInput">' . htmlspecialchars(ucwords(str_replace('-', ' ', $elementDesc)), ENT_QUOTES, 'UTF-8') . '</label>';
    echo '<input type="text" class="form-control" id="elementInput" name="elementInput">';
    echo '</div>';
    
    // ✅ Hidden fields to store indicator and description
    echo '<input type="hidden" id="indicator" name="indicator" value="' . htmlspecialchars($section, ENT_QUOTES, 'UTF-8') . '">';
    echo '<input type="hidden" id="description" name="description" value="' . htmlspecialchars($elementDesc, ENT_QUOTES, 'UTF-8') . '">';
    echo '<input type="hidden" id="pageID" name="description" value="' . htmlspecialchars($_SESSION['pageID'], ENT_QUOTES, 'UTF-8') . '">';
    echo '<input type="hidden" id="pageID" name="subpageID" value="' . htmlspecialchars($_SESSION['subpageID'], ENT_QUOTES, 'UTF-8') . '">';
    
    echo '<button type="button" id="saveElement" class="btn btn-primary">Save</button>';
    echo '</form>';
    echo '</div>';
} else {
    echo 'Invalid request';
}
?>
