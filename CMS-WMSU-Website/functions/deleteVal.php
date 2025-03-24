<?php
require_once '../classes/pages.class.php';
require_once '../tools/functions.php';

$deleteObj = new Pages;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Alert message
    echo "<script>alert('Deleting record with ID: " . htmlspecialchars($id) . "');</script>";

    $deleteObj->deleteVal($id);
    // Delay for 2 seconds before redirecting
    sleep(2);

    // Redirect after delay
    $prevPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php'; // Default page if no referer
    echo "<script>window.location.href = '$prevPage';</script>";
    exit();
} else {
    echo "<script>alert('No ID provided.');</script>";
}
?>
