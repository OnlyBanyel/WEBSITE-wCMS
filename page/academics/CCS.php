<?php

require_once "../../CMS-WMSU-Website/classes/pages.class.php"; 
$ccsPage = new Pages;

/** @region Carousel */
    $carouselItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = 1 
        AND indicator = 'Carousel Element' 
        AND description IN ('carousel-logo', 'carousel-logo-text', 'carousel-img');
    ";
    $carouselItems = $ccsPage->execQuery($carouselItemsSQL);

    foreach ($carouselItems as $item) {
    if ($item["description"] == "carousel-logo-text") {
    $carouselLogo = $item['content'];
        }
    if ($item["description"] == "carousel-logo") {
    $carouselLogoImage = $item['imagePath'];
        }
    if ($item["description"] == "carousel-img") {
    $carouselItem[] = $item;
        }
    }
/** @endregion */


/** @region Card Front */
        $cardFrontItemsSQL = "
            SELECT * FROM page_sections 
            WHERE subpage = 1 
            AND indicator = 'Card Element Front' 
            AND description IN ('card-front-img', 'card-front-title');
        ";

        $cardFrontItems = $ccsPage->execQuery($cardFrontItemsSQL);

        foreach ($cardFrontItems as $item) {
        if ($item["description"] == "card-front-img") {
            $cardFrontimgs[] = $item['imagePath'];
        }
        if ($item["description"] == "card-front-title") {
            $cardFrontTitle[] = $item['content'];
        }
    }
/** @engregion */

/** @region Card Back */
    $CardBackItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = 1 
        AND indicator = 'Card Element Back' 
        AND description IN ('card-back-head', 'CG-list-item', 'CM-list-item', 'CV-list-item');
    ";

    $cardBackHead = [];
    $cardBackCGList = [];
    $cardBackCMList = [];
    $cardBackCVList = [];

    $cardBackItems = $ccsPage->execQuery($CardBackItemsSQL);

    foreach ($cardBackItems as $item) {
    if ($item["description"] == "card-back-head") {
        $cardBackHead[] = $item['content'];
    }
    if ($item["description"] == "CG-list-item" ) {
        $cardBackCGList[] = $item['content'];
    }
    if ($item["description"] == "CM-list-item") {
        $cardBackCMList[] = $item['content'];
    }
    if ($item["description"] == "CV-list-item") {
        $cardBackCVList[] = $item['content'];
    }

    $cardBackLists = [
    0 => $cardBackCGList,
    1 => $cardBackCMList,
    2 => $cardBackCVList
    ];
    }
/** @endregion*/

$departmentsSQL = "
    SELECT * from page_sections WHERE subpage = 1 AND indicator = 'departments' AND description = 'department-name';
";

$departments = $ccsPage->execQuery($departmentsSQL);

/** @region Accordion Courses */
    $accordionCoursesSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = 1 
        AND indicator IN ('Accordion Courses', 'Accordion Courses Undergrad', 'Accordion Courses Grad');
    ";

    $programHeaders = [];
    $undergradCourses = [];
    $gradCourses = [];
    $currentCourse = null;

    // ✅ Execute the query
    $accordionCourses = $ccsPage->execQuery($accordionCoursesSQL);

    foreach ($accordionCourses as $item) {
        // Store Program Headers
        if ($item["description"] == "program-header") {
            $programHeaders[] = $item['content'];
        }

        // ✅ Identify Course Type (Undergrad or Grad)
        $isUndergrad = $item["indicator"] === "Accordion Courses Undergrad";
        $isGrad = $item["indicator"] === "Accordion Courses Grad";

        // ✅ Store Course Headers
        if ($item["description"] == "course-header") {
            $currentCourse = $item['content'];
            if ($isUndergrad) {
                $undergradCourses[$currentCourse] = ["outcomes" => []];
            } elseif ($isGrad) {
                $gradCourses[$currentCourse] = ["outcomes" => []];
            }
        }

        // ✅ Store Course Outcomes (Matching -1, -2, etc.)
        if (preg_match('/undergrad-course-list-items-\d+$/', $item["description"]) && $isUndergrad && $currentCourse !== null) {
            $undergradCourses[$currentCourse]["outcomes"][] = $item['content'];
        }

        if (preg_match('/grad-course-list-items-\d+$/', $item["description"]) && $isGrad && $currentCourse !== null) {
            $gradCourses[$currentCourse]["outcomes"][] = $item['content'];
        }
    }



/** @endregion */
?>




<head>
    <?php require_once "../../__includes/head.php"; ?>
    <title><?php echo $carouselLogo?></title>
</head>

<section class="header">
  <?php require_once '../../__includes/navbar.php'?>
</section>

<main class="main-content">
  <section class="invisible-section">
    <div class="college-top">
    <?php require_once '../../__includes/subnav_academics.php'?>
    <div class="hero-container">
      
        <div class="college-heading">
          <img src="<?php echo $carouselLogoImage ?>" class="logo" alt="ccs logo">
          <h2 class="college-header"><?php echo $carouselLogo?></h2>
        </div>
        <div class="carousel-container">
          <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php 
                  foreach ($carouselItem as $img) {
                    ?>
                    <div class="carousel-item active" data-bs-interval='1500'>
                    <img src="<?php echo $img['imagePath'] ?>" class="d-block w-100" alt="...">
                  </div>
               <?php   
                }
              ?>
            </div>
          </div> <!-- End of carousel -->
        </div>
    </div>
            <div class="gen-info-header-container"><span class='title-header'>College Information</span></div>
    <div class="gen-info-container">
        <div class="gen-info-col-1">
        <?php for ($i = 0; $i < count($cardBackHead); $i ++){
            ?>
                <h4 class="gen-info-heading"> <?php echo $cardFrontTitle[$i] ?></h4>
                <p class="gen-info-top-content"><?php echo $cardBackHead[$i]?></p>
                <?php foreach ($cardBackLists[$i] as $item) {
                                  echo "<li class='gen-info-content'>" . $item . "</li>";
                                }  ?>

                        <br>
            <?php } ?>
        </div>
        <div class="gen-info-col-2">
            <img src="../../imgs/ccs2.jpg" alt="">
        </div>
    </div>
    
    

    <div class="department">
      <p class="title-header">Departments</p>
            <div class="dept">
                <
                <?php 
                $i = 0;
                foreach ($departments as $items){?>
                    <a href="javascript:void(0);" style="background: linear-gradient(rgba(189, 15, 3, 0.7), rgba(189, 15, 3, 0.7)), url('<?php echo $cardFrontimgs[$i];?>') no-repeat center center;
    background-size: cover;"><span><?php echo $items['content']; ?></span></a>
                    <?php $i++;} ?>
                </div>
    </div>


    <div class="container mt-4">
        <h2 class="text-center">Undergraduate Programs</h2>
        <div class="accordion" id="undergradAccordion">
            <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo md5($courseName); ?>">
                            <?php echo $courseName; ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo md5($courseName); ?>" class="accordion-collapse collapse" data-bs-parent="#undergradAccordion">
                        <div class="accordion-body">
                            <p><strong>Program Objectives/Outcomes:</strong></p>
                            <ul>
                                <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                                    <li><?php echo $outcome; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Graduate Programs -->
        <h2 class="text-center mt-5">Graduate Program</h2>
        <div class="accordion" id="gradAccordion">
            <?php foreach ($gradCourses as $courseName => $courseData) { ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo md5($courseName); ?>">
                            <?php echo $courseName; ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo md5($courseName); ?>" class="accordion-collapse collapse" data-bs-parent="#gradAccordion">
                        <div class="accordion-body">
                            <p><strong>Program Objectives/Outcomes:</strong></p>
                            <ul>
                                <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                                    <li><?php echo $outcome; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>



    </div>
  </section>
  <script src="../../JS/ccs.script.js"></script>
</main>