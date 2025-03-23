<?php

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/WMSU-HOMEPAGE';
require_once "Q:/XAMPP/htdocs/WMSU-HOMEPAGE/CMS-WMSU-Website/classes/pages.class.php"; 

$csmPage = new Pages;

/** @region Carousel */
    $carouselItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = 3 
        AND indicator = 'Carousel Element' 
        AND description IN ('carousel-logo', 'carousel-logo-text', 'carousel-img');
    ";
    $carouselItems = $csmPage->execQuery($carouselItemsSQL);

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
            WHERE subpage = 3 
            AND indicator = 'Card Element Front' 
            AND description IN ('card-front-img', 'card-front-title');
        ";

        $cardFrontItems = $csmPage->execQuery($cardFrontItemsSQL);

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
        WHERE subpage = 3 
        AND indicator = 'Card Element Back' 
        AND description IN ('card-back-head', 'CG-list-item', 'CM-list-item', 'CV-list-item');
    ";

    $cardBackHead = [];
    $cardBackCGList = [];
    $cardBackCMList = [];
    $cardBackCVList = [];

    $cardBackItems = $csmPage->execQuery($CardBackItemsSQL);

    foreach ($cardBackItems as $item) {
    if ($item["description"] == "card-back-head") {
        $cardBackHead[] = $item['content'];
    }
    if ($item["description"] == "CG-list-item") {
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

/** @region Accordion Courses */
    $accordionCoursesSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = 3 
        AND indicator IN ('Accordion Courses', 'Accordion Courses Undergrad', 'Accordion Courses Grad');
    ";

    $programHeaders = [];
    $undergradCourses = [];
    $gradCourses = [];
    $currentCourse = null;

    // ✅ Execute the query
    $accordionCourses = $csmPage->execQuery($accordionCoursesSQL);

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

      <div class="flex-container">
        <div class="card-container">

        <?php for ($i = 0; $i < count($cardFrontimgs); $i++) { ?>
            <div class="card">
                <div class="card-front card-1" 
                    style="background-image: linear-gradient(rgba(255, 152, 152, 0.6), rgba(255, 0, 0, 0.6)), url('<?php echo $cardFrontimgs[$i];?>'); background-size: cover; background-position: center;">
                    <h3><?php echo $cardFrontTitle[$i]; ?></h3>
                </div>
                <div class="card-back">
                    <div class="college-goals">
                        <p><?php echo $cardBackHead[$i]?></p>
                        <ol>
                            <?php 
                                foreach ($cardBackLists[$i] as $item) {
                                  echo "<li>" . $item . "</li>";
                                }  
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div> <!-- End of college-top -->
  </div>

    <div class="activities">
      <p>HERE LIES THE ACTIVITIES OF THE COLLEGES</p>
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