<?php 
session_start();
require_once "../classes/pages.class.php";
$collegeProfileObj = new Pages;
$collegeOverview = [];

foreach($_SESSION['collegeData'] as $data){
    if ($data['indicator'] == 'College Overview'){
        $collegeOverview[] = $data;
    }
}
?>
<section class="container">
    HELLOOO
</section>
