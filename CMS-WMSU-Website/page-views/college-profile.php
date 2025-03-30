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

foreach ($collegeProfile as $item) {
    if ($item["description"] == "carousel-logo-text") {
    $CollegeProfileLogo = $item['content'];
        }
    if ($item["description"] == "carousel-logo") {
    $CollegeProfileLogoImage = $item['imagePath'];
        }
    if ($item["description"] == "carousel-img") {
    $CollegeProfileItems[] = $item;
        }
    }



?>

<div class="everything-container">
    <h2>College Profile Section Preview</h2>
        <div class="carousel-wrapper">
                <div class="college-heading">
                    <img src="<?php echo $CollegeProfileLogoImage ?>" class="logo" alt="CCS Logo">
                    <h2 class="college-header"><?php echo $CollegeProfileLogo ?></h2>
                </div>

                <!-- Carousel -->
                <div class="carousel-container">
                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php 
                                $isActive = true; // First item should be active
                                foreach ($CollegeProfileItems as $img) {
                            ?>
                            <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>" data-bs-interval='1500'>
                                <img src="<?php echo $img['imagePath'] ?>" class="d-block w-100 carousel-img" alt="...">
                            </div>
                            <?php
                                $isActive = false; // After first item, remove active class
                                }
                            ?>
                        </div>
                    </div> <!-- End of carousel -->
                </div>
        </div>

    <div class="items-wrapper">
        <div class="column-1">
            
                <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Change College Name
                            </div>
                            <div class="divider-line"></div>
                        <form action="" method="POST" class="column-1-form">
            
                                        <input type="text" value="<?php echo $CollegeProfileLogo?>" name="college-logo-text" id="college-logo-text">
                                        <br>
                                        <input type="submit" class='btn btn-success' value="Submit">
                        </form>
                    </div>
                </section>
                <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Change College Logo
                            </div>
                            <div class="divider-line"></div>
                            <form action="" method="POST" class="column-1-form">
            
                                        <input type="text" value="<?php echo $CollegeProfileLogoImage?>" name="college-logo-text" id="college-logo-text">
                                        <br>
                                <input type="submit" class='btn btn-success' value="Submit">
                            </form>
                    </div>
                </section>
        </div>

        <div class="column-2">
                <?php
                $i = 1;
                foreach ($CollegeProfileItems as $img) {
                                            ?>
                    <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Change Image <?php echo $i ?>
                            </div>
                            <div class="divider-line"></div>
                            <form action="" method="POST">
                                <div class="img-container">
                                            <img src="<?php echo $img['imagePath']?>" alt="">
                                </div>
                                <input type="submit" class='btn btn-success' value="Submit">
                            </form>
                    </div>
                </section>
                <?php $i++; } ?>
        </div>
    </div>
</div>
