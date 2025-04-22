<?php
// Get the current full URL
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$currentUrl .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// Target URL to match
$targetUrl = "http://localhost/WEBSITE-WCMS/CMS-WMSU-Website/";

// Compare and redirect
if ($currentUrl === $targetUrl) {
    header("Location: pages/login-form.php"); // Change this to your desired page
    exit();
}
?>
