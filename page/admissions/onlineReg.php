<?php 
require_once '../../CMS-WMSU-Website/classes/pages.class.php';
require_once '../../CMS-WMSU-Website/tools/functions.php';

$onlineRegObj = new Pages;

$contentItemsSQL = "SELECT * from subpages WHERE pagesID = 3 AND subPageName = 'Subpage Online Registration';
";

$itemContentSQL = "SELECT * from page_sections WHERE subpage = 18 AND indicator = 'onlinereg-section';
";

$contentItems = $onlineRegObj->execQuery($contentItemsSQL);

foreach ($contentItems as $items){
  $sectionPath[] = $items['subPagePath'];
  $sectionImage[] = $items['imagePath'];
}

$itemContent = $onlineRegObj->execQuery($itemContentSQL);

foreach ($itemContent as $items){
    if ($items['description'] === 'section-title'){
        $sectionTitles[] = $items['content'];
    }
    if ($items['description'] === 'section-content'){
        $sectionContent[] = $items['content'];
    }
}
?>



<head>
  <?php require_once "../../__includes/head.php"; ?>
    <style>
      <?php require_once '../../css/onlineReg.css'; ?>
      <?php require_once '../../css/admissionGuide-style.css'; ?>
    </style>
    <Title>Admission Guide</Title>
</head>


<section class="header"><?php require_once '../../__includes/navbar.php'?></section>

<main>
  <div class="breadcrumb-container">
<?php require_once '../../__includes/subnav_academics.php' ?>
</div>
<div class="intro-admission">
<div class="banner">Online Registration</div>


<main>
  <div class="content-container">
    
      <?php  for ($i = 0; $i < count($sectionTitles); $i++) {
              if ($i % 2 == 0){
          ?><div class="section">
            
                  <a href='<?php echo $sectionPath[$i]?>'>
               <div class="content">
                <img class="circle-img" src="<?php echo $sectionImage[$i]?>" alt="Undergraduate students">
                <div class="text">
                    <h2 class="section-title"><?php echo $sectionTitles[$i]?></h2>
                    <p class="section-content"><?php echo $sectionContent[$i]?></p>
                </div>
            </div>
                  </a>
          </div>
      <?php }else{ ?>
        <div class="section-reverse">
          
          <a href='<?php echo $sectionPath[$i]?>'>
               <div class="content reverse">
                <img class="circle-img" src="<?php echo $sectionImage[$i]?>" alt="Undergraduate students">
                <div class="text">
                    <h2 class="section-title"><?php echo $sectionTitles[$i]?></h2>
                    <p class="section-content"><?php echo $sectionContent[$i]?></p>
                </div>
            </div>
                </a>
        </div>
     <?php } } ?>
  </div>

</main>

  
</main>
