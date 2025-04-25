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
    /* Custom styles for online registration page */
    .registration-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .banner {
      background-color: #BD0F03;
      color: white;
      padding: 30px;
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(189, 15, 3, 0.2);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .intro-text {
      font-size: 18px;
      line-height: 1.6;
      color: #333;
      text-align: center;
      max-width: 800px;
      margin: 0 auto 40px;
    }

    .content-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .section, .section-reverse {
      margin-bottom: 50px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 8px;
      overflow: hidden;
      background-color: #fff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .section:hover, .section-reverse:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(189, 15, 3, 0.15);
    }

    .section a, .section-reverse a {
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .content {
      display: flex;
      align-items: center;
      padding: 30px;
    }

    .content.reverse {
      flex-direction: row-reverse;
    }

    .circle-img {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid #BD0F03;
      box-shadow: 0 4px 12px rgba(189, 15, 3, 0.2);
      transition: transform 0.3s ease;
    }

    .section:hover .circle-img, .section-reverse:hover .circle-img {
      transform: scale(1.05);
    }

    .text {
      flex: 1;
      padding: 0 30px;
    }

    .section-title {
      color: #BD0F03;
      font-size: 24px;
      margin-bottom: 15px;
      position: relative;
      padding-bottom: 10px;
    }

    .section-title:after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background-color: #BD0F03;
      border-radius: 3px;
    }

    .content.reverse .section-title:after {
      left: auto;
      right: 0;
    }

    .section-content {
      color: #333;
      line-height: 1.6;
    }

    @media (max-width: 768px) {
      .content, .content.reverse {
        flex-direction: column;
        text-align: center;
      }
      
      .circle-img {
        margin: 0 auto 20px;
      }
      
      .section-title:after {
        left: 50%;
        transform: translateX(-50%);
      }
      
      .content.reverse .section-title:after {
        left: 50%;
        right: auto;
        transform: translateX(-50%);
      }
      
      .banner {
        font-size: 24px;
        padding: 20px;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <Title>Online Registration - WMSU</Title>
</head>

<section class="header"><?php require_once '../../__includes/navbar.php'?></section>

<main>
  <div class="breadcrumb-container">
    <div class="relative z-10">
      <?php require_once '../../__includes/subnav_academics.php' ?>
    </div>
  </div>
  
  <div class="registration-container">
    <div class="banner">Online Registration</div>
    
    <p class="intro-text">
      Choose the appropriate registration option below based on your academic status. 
      Each pathway provides specific requirements and procedures tailored to your needs.
    </p>
    
    <div class="content-container">
      <?php for ($i = 0; $i < count($sectionTitles); $i++) {
        if ($i % 2 == 0) { ?>
          <div class="section">
            <a href='<?php echo $sectionPath[$i]?>'>
              <div class="content">
                <img class="circle-img" src="<?php echo $sectionImage[$i]?>" alt="<?php echo $sectionTitles[$i]?>">
                <div class="text">
                  <h2 class="section-title"><?php echo $sectionTitles[$i]?></h2>
                  <p class="section-content"><?php echo $sectionContent[$i]?></p>
                </div>
              </div>
            </a>
          </div>
        <?php } else { ?>
          <div class="section-reverse">
            <a href='<?php echo $sectionPath[$i]?>'>
              <div class="content reverse">
                <img class="circle-img" src="<?php echo $sectionImage[$i]?>" alt="<?php echo $sectionTitles[$i]?>">
                <div class="text">
                  <h2 class="section-title"><?php echo $sectionTitles[$i]?></h2>
                  <p class="section-content"><?php echo $sectionContent[$i]?></p>
                </div>
              </div>
            </a>
          </div>
        <?php } 
      } ?>
    </div>
  </div>
</main>