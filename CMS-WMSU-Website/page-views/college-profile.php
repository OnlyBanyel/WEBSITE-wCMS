<?php 
session_start();
require_once "../classes/pages.class.php";
$collegeProfileObj = new Pages;
$collegeProfile = [];
$CollegeProfileItems = [];
$collegeProfileLogo = [];

foreach($_SESSION['collegeData'] as $data){
    if ($data['indicator'] == 'College Profile'){
        $collegeProfile[] = $data;
    }
}


foreach ($collegeProfile as $item) {
    if ($item["description"] == "carousel-logo-text") {
    $CollegeProfileLogo = $item;
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
<?php if (!empty($collegeProfile)){?>
    <h2>College Profile Section Preview</h2>
        <div class="carousel-wrapper">
                <div class="college-heading">
                    <img src="<?php echo $CollegeProfileLogoImage ?>" class="logo" alt="CCS Logo">
                    <h2 class="college-header"><?php echo $CollegeProfileLogo['content'] ?></h2>
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

<?php }else{ ?>
            <h2>College Profile Section Preview</h2>
        <div class="carousel-wrapper">
                <div class="college-heading">
                    <h2 class="college-header">N/A</h2>
                </div>

                <!-- Carousel -->
                <div class="carousel-container">
                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            
                        </div>
                    </div> <!-- End of carousel -->
                </div>
        </div>
<?php }?>

    <div class="items-wrapper">
        <div class="column-1">
            <?php if (!empty($CollegeProfileLogo)){?>
                <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Change College Name
                            </div>
                            <div class="divider-line"></div>
                        <form action="" method="POST" name="editnameForm" id="editnameForm" class="column-1-form">
                                        <input type="text" name='collegeName' id='collegeName' data-textID="<?php echo $CollegeProfileLogo['sectionID']?>" value="<?php echo $CollegeProfileLogo['content']?>">
                                        <br>
                                        <input type="submit" name="submitName" class='btn btn-success' value="Submit">
                        </form>
                    </div>
                </section>

            <?php }else{?>

                 <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Add College Name
                            </div>
                            <div class="divider-line"></div>
                        <form action="#" method="POST" id="addNameForm" class="column-1-form">
            
                                        <input type="text" id='collegeName'>
                                        <br>
                                        <input type="submit" name="submitName" class='btn btn-success' value="Submit">
                        </form>
                    </div>
                </section>
                <?php } ?>

            <?php if (!empty($CollegeProfileLogoImage)){?>
                <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Change College Logo
                            </div>
                            <div class="divider-line"></div>
                            <form action="" method="POST" id="editLogoForm" class="column-1-form" enctype="multipart/form-data">
                                        <div class="img-container">
                                            <img src="<?php echo $CollegeProfileLogoImage?>" id="collegeLogo" alt="">    
                                        </div>
                                        <input type="file" class='logoImage'name="logoImage" id="logoImage" accept="image/*">
                                <input type="submit"  name="submitLogo" class='btn btn-success' value="Submit">
                            </form>
                    </div>
                </section>
            <?php }else{ ?>
                
                <section class="item-container">
                    <div class="edit-content">
                            <div class="edit-title">
                                Add College Logo
                            </div>
                            <div class="divider-line"></div>
                            <form action="" method="POST" id="addlogoForm" class="column-1-form" enctype="multipart/form-data">
                                        <input type="file" class='logoImage'name="logoImage" id="logoImage" accept="image/*">
                                <input type="submit"  name="submitLogo" class='btn btn-success' value="Submit">
                            </form>
                    </div>
                </section>
                
                <?php } ?>
        </div>

        <div class="column-2">

                <?php if (!empty($CollegeProfileItems)) { ?>
            <?php
            $i = 1;
            foreach ($CollegeProfileItems as $img) { ?>
                <section class="item-container">
                    <div class="edit-content">
                        <div class="edit-title">Change Image <?php echo $i; ?></div>
                        <div class="divider-line"></div>
                        <form action="#" method="POST" id="profileImgForm-<?php echo $img['sectionID']; ?>" enctype="multipart/form-data">
                            <div class="img-container">
                                <img src="<?php echo $img['imagePath']; ?>" alt="">
                            </div>
                            <input type="hidden" name="imageIndex" value="<?php echo $img['sectionID']; ?>">
                            <input type="file" class="itemImage" name="logoImage" id="logoImage-<?php echo $img['sectionID']; ?>" accept="image/*">
                            <input type="submit" name="submitImg" class="btn btn-success" value="Submit">
                        </form>
                    </div>
                </section>
            <?php $i++; } 
        } else {
            for ($i = 1; $i < 4; $i++) { ?>
                <section class="item-container">
                    <div class="edit-content">
                        <div class="edit-title">Add Image <?php echo $i; ?></div>
                        <div class="divider-line"></div>
                        <form action="#" method="POST" id="profileImgForm-temp_<?php echo $i; ?>" enctype="multipart/form-data">
                            <input type="hidden" name="imageIndex" value="temp_<?php echo $i; ?>">
                            <input type="file" class="itemImage" name="logoImage" id="logoImage-temp_<?php echo $i; ?>" accept="image/*">
                            <input type="submit" name="submitImg" class="btn btn-success" value="Submit">
                        </form>
                    </div>
                </section>
        <?php } } ?>



        </div>
    </div>
</div>
