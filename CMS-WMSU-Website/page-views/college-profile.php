<?php 
session_start();
require_once "../classes/pages.class.php";
$collegeProfileObj = new Pages;
$collegeProfile = [];

foreach($_SESSION['collegeData'] as $data){
    if ($data['indicator'] == 'College Profile'){
        $collegeProfile[] = $data;
    }
}



?>
