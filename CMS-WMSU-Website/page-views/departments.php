<?php
session_start();
require_once '../classes/pages.class.php';


$pagesObj = new Pages;
$departments = [];
foreach ($_SESSION['collegeData'] as $data) {
    if ($data['indicator'] == "Departments"){
        $departments[] = $data;
    }
    if ($data['indicator'] == "College Overview"){
        $collegeOverview[] = $data;
    }
}

foreach ($collegeOverview as $imgs){
    if ($imgs == 'geninfo-front-img'){
    $genInfoImgs[] = $imgs['imagePath'];
}
}


?>
  <div class="department">
      <p class="title-header">Departments</p>
            <div class="dept">
                <?php 
                $i = 0;
                foreach ($departments as $items){?>
                    <a href="javascript:void(0);"><div class="deptimg" style="background: linear-gradient(rgba(189, 15, 3, 0.7), rgba(189, 15, 3, 0.7)), url('<?php echo $genInfoImgs[$i];?>') no-repeat center center;
                    background-size: cover;"></div><span><?php echo $items['content']; ?></span></a>
                    <?php $i++;} ?>
                </div>
    </div>