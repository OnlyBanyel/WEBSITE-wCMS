<?php 
require_once '../../CMS-WMSU-Website/classes/pages.class.php';

$acadSubpagesObj = new Pages;

$acadSubpages = $acadSubpagesObj->fetchSubpages(3);


?>
<head>
    <?php require_once "../../__includes/head.php"; ?>
    <style>
        <?php require_once '../../css/academics.css'?>
    </style>
    <title>Academics</title>
</head>

<section class="header">
  <?php require_once '../../__includes/navbar.php'?>
</section>

<main class="main-content">
  <section class="invisible-section">
    <div class="college-top">
      <br>
    <?php require_once '../../__includes/subnav_academics.php'?>
<div class="content-container">
  
      <div class="content">
  
          <div class="col-1">
            
          <?php 
                  $i = 0;
                  $lastIndex = 1;
                  foreach ($acadSubpages as $items){
                  ?>
                        <a href="<?php echo $items['subPagePath'] ?>"><div class="college-container">
                        <img src="<?php echo $items['imagePath']?>" class="logo-item" alt="ccs logo">
                        <h2><?php echo $items['subPageName']?></h2>
                              </div></a>
                              
                  <?php $i++;
                  $lastIndex = $items['subpageID'] + 1;
                  if ($i == 6) break;
                  }
                                    ?>

          </div>
  
          <div class="col-2">
          <?php 
                  $i = 0;
                  $found = false;
                  
                  foreach ($acadSubpages as $items) {
                        // Wait until we reach lastIndex
                        if (!$found) {
                              if ($items['subpageID'] != $lastIndex) {
                              continue;
                              }
                              $found = true;
                        }
                  
                        ?>
                         <a href="<?php echo $items['subPagePath'] ?>"><div class="college-container">
                        <img src="<?php echo $items['imagePath']?>" class="logo-item" alt="ccs logo">
                        <h2><?php echo $items['subPageName']?></h2>
                              </div></a>
                        <?php                                          
                        $i++;
                        $lastIndex = $items['subpageID'] + 1;
                        if ($i == 5) break;
                  }
            ?>
                  

          </div>

          <div class="col-3">
          <?php 
                  $i = 0;
                  $found = false;
                  
                  foreach ($acadSubpages as $items) {
                        // Wait until we reach lastIndex
                        if (!$found) {
                              if ($items['subpageID'] != $lastIndex) {
                              continue;
                              }
                              $found = true;
                        }
                  
                        ?>
                         <a href="<?php echo $items['subPagePath'] ?>"><div class="college-container">
                        <img src="<?php echo $items['imagePath']?>" class="logo-item" alt="ccs logo">
                        <h2><?php echo $items['subPageName']?></h2>
                              </div></a>
                        <?php                                          
                        $i++;
                        if ($i == 6) break;
                  }
            ?>
         
          </div>

      </div>
</div>



  <script src="../../JS/ccs.script.js"></script>
</main>