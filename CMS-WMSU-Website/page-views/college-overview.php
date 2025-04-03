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

    $genInfoBackItems = [];
    $genInfoBackCGList = [];
    $genInfoBackCMList = [];
    $genInfoBackCVList = [];

foreach ($collegeOverview as $data){
    if ($data["description"] == "geninfo-front-img") {
        $genInfoImgs[] = $data;
    }
    if ($data['description'] == 'geninfo-back-head'){
        $genInfoBackHead[] = $data;
    }
    if ($data['description'] == 'geninfo-front-title'){
        $genInfoTitles[] = $data;
    }
    if ($data["description"] == "CG-list-item" ) {
        $genInfoBackCGList[] = $data;
    }
    if ($data["description"] == "CM-list-item") {
        $genInfoBackCMList[] = $data;
    }
    if ($data["description"] == "CV-list-item") {
        $genInfoBackCVList[] = $data;
    }

    $genInfoBackLists = [
    0 => $genInfoBackCGList,
    1 => $genInfoBackCMList,
    2 => $genInfoBackCVList
    ];
    }
?>
<section class="overview-container">
<div class="gen-info-container">
        <div class="gen-info-col-1">
        <?php for ($i = 0; $i < count($genInfoBackHead); $i ++){
            ?>
                <h4 class="gen-info-heading"> <?php echo $genInfoTitles[$i]['content'] ?></h4>
                <p class="gen-info-top-content"><?php echo $genInfoBackHead[$i]['content']?></p>
                <?php foreach ($genInfoBackLists[$i] as $item) {
                                  echo "<li class='gen-info-content'>" . $item['content'] . "</li>";
                                }  ?>

                        <br>
            <?php } ?>
        </div>
        <div class="gen-info-col-2">
            <img src="<?php echo $genInfoImgs[1]['imagePath']?>" alt="">
        </div>
    </div>



    <div class="overview-wrapper">
        
        <div class="overview-col-1">
        <?php for ($q = 0; $q < count($genInfoBackHead); $q++) { ?> 
<section class="overview-item-container">
    <form action="" method="POST" class="overview-form" name="<?php echo $genInfoTitles[$q]['content']?>-overviewItems" id="<?php echo $genInfoTitles[$q]['content']?>-overviewItems">
        <input type="text" name="overviewTitle" data-overviewsectionid="<?php echo $genInfoTitles[$q]['sectionID']?>" class="overviewTitle" id="<?php echo $genInfoTitles[$q]['content']?>" value="<?php echo $genInfoTitles[$q]['content'] ?>">
        <div class="divider-line"></div>
        <input type="text" class="overview-top-content" name="overview-top-content-<?php echo $q ?>" id="overview-top-content-<?php echo $q ?>" data-sectionid="<?php echo $genInfoBackHead[$q]['sectionID']?>" value="<?php echo $genInfoBackHead[$q]['content'] ?>">
        
        <ul class="outcomes-list">
            <?php $i = 1; foreach ($genInfoBackLists[$q] as $item) { ?>
                <li>
                    <input type="text" 
                        name="<?php echo $genInfoTitles[$q]['content']?>-<?php echo $i ?>-outcomes" 
                        id="<?php echo $genInfoTitles[$q]['content']?>-<?php echo $i ?>-outcomes" 
                        data-sectionid="<?php echo $item['sectionID']?>" 
                        value="<?php echo $item['content']?>">
                    <button type="button" class="remove-outcome btn btn-danger" data-sectionid="<?php echo $item['sectionID']?>">×</button>
                </li>
            <?php $i++; } ?>
        </ul>
        
        <div class="btn-container">
            <button type="button" class="add-outcome btn btn-primary">Add Outcome</button>
            <input type="submit" value="Submit" class="submitCourse btn btn-success">
        </div>
    </form>
</section>
<?php } ?>

            <section class="item-container">
            <div class="edit-content">
                    <div class="edit-title">Change Image <?php echo $i; ?></div>
                    <div class="divider-line"></div>
                    <form action="#" method="POST" id="overviewImg-<?php echo $genInfoImgs[1]['sectionID']; ?>" enctype="multipart/form-data">
                        <div class="img-container">
                            <img src="<?php echo $genInfoImgs[1]['imagePath']; ?>" alt="">
                        </div>
                        <input type="hidden" name="overviewImgIndex" value="<?php echo $genInfoImgs[1]['sectionID']; ?>">
                        <input type="file" class="itemImage" name="overviewImg" id="overviewImg-<?php echo $genInfoImgs[1]['sectionID']; ?>" accept="image/*">
                        <input type="submit" name="submitImg" class="btn btn-success" value="Submit">
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
