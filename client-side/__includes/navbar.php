<head>
    
  <?php require_once "head.php"; 

    require_once dirname(__DIR__) . "/CMS-WMSU-Website/classes/pages.class.php";
        

$genElements = new Pages;

/** @region navbar */
    $navBarItemsSQL = "
    SELECT * FROM generalelements WHERE indicator = 'Navbar' AND description !='academics-items';
    ";
    $navBarListItemsSQL = "
    SELECT elementID, linkFor, content, linking.description, link FROM generalelements LEFT JOIN linking ON elementID = linkFor WHERE generalelements.indicator = 'Navbar' OR generalelements.indicator = 'Subnav';
    ";

    $subPageListItemsSQL = "
    SELECT * FROM subpages;
    ";


    $navBarItems = $genElements->execQuery($navBarItemsSQL);
    $navBarListItems = $genElements->execQuery($navBarListItemsSQL);
    $subPageListItems = $genElements->execQuery($subPageListItemsSQL);
    
    foreach ($navBarItems as $items){
        if ($items['description'] == 'navbar-logo'){
            $navbarLogo = $items['imagePath'];
        }
        if ($items['description'] == 'navbar-header'){
            $navbarHeader = $items['content'];
        }
        if ($items['description'] == 'navbar-tagline'){
            $navbarTagline = $items['content'];
        }
    }
    $navBarListItem = [];

    foreach ($navBarListItems as $items){
            $navBarListItem[$items['elementID']] =[
                'elementID'=> $items['elementID'],
                'linkFor' => $items['linkFor'],
                'content' => $items['content'],
                'link' => $items['link'],
                'description' => $items['description']
            ];
        
    }





    
/** @endregion */
?>
</head>
<nav>
    <section class="nav-cont">
        <div class="WMSU-Logo-cont">
            <img src="<?php echo $navbarLogo?>" alt="" class="WMSU-Logo">
            <div class="logo-text">
                <div class="nav-logo-line"></div>
                <div class="nav-title-cont">
                    <div class="nav-logo-title inter-bold"><?php echo $navbarHeader ?></div>
                    <div class="nav-logo-subtitle montserrat-regular"><?php echo $navbarTagline ?></div>
                </div>
            </div>
        </div>
        <div class="nav-links">
            <a class="inter-extralight" id="Home" href="">HOME</a>
            <a class="inter-extralight inline-align" id="About" href="">ABOUT US <img src="/WEBSITE-wCMS/imgs/Expand Arrow.png" alt=""></a>
            <div id="About-dropdown" class="nav-dropdown">
                <div class="dropdown-cont">
                    <div class="dropdown-title">
                        <div class="dropdown-content">
                            <h6 class="inter-bold">CORE VALUES</h6>
                            <p class="inter-light">Mission</p>
                            <p class="inter-light">Vision</p>
                            <p class="inter-light">Quality Policy</p>
                            <p class="inter-light">University Function</p>
                        </div>
                        <div class="nav-divider"></div>
                        <div class="dropdown-content">
                            <h6 class="inter-bold">INSTITUTIONAL IDENTITY</h6>
                            <p class="inter-light">Strategic Plan</p>
                            <p class="inter-light">Transparency Seal</p>
                        </div>
                        <div class="nav-divider"></div>
                        <div class="dropdown-content">
                            <h6 class="inter-bold">FOUNDATION</h6>
                            <p class="inter-light">History of WMSU</p>
                            <p class="inter-light">News Archive</p>
                            <p class="inter-light">Bids & Awards</p>
                            <p class="inter-light">Gallery</p>
                        </div>
                    </div>
                </div>
            </div>
            <a class="inter-extralight inline-align" id="Admissions" href="">ADMISSIONS <img src="/WEBSITE-wCMS/imgs/Expand Arrow.png" alt=""></a>
            <div id="Admissions-dropdown" class="nav-dropdown">
                <div class="dropdown-cont">
                    <div class="dropdown-title">
                        <div class="dropdown-content">
                            <h6 class="inter-bold">ADMISSION GUIDE</h6>
                            <p class="inter-light">New Students</p>
                            <p class="inter-light">Old Students</p>
                            <p class="inter-light">Enrollment Procedure</p>
                        </div>
                        <div class="nav-divider"></div>
                        <div class="dropdown-content">
                            <h6 class="inter-bold">SCHEDULE OF FEES</h6>
                            <p class="inter-light">Undergraduate / Non-Corporate Programs</p>
                            <p class="inter-light">Graduate / Corporate Programs</p>
                            <p class="inter-light">College of Law</p>
                            <p class="inter-light">College of Medicine</p>
                            <p class="inter-light">Masters Degree</p>
                            <p class="inter-light">Doctorate Degree</p>
                        </div>
                    </div>
                </div>
            </div>
            <a class="inter-extralight inline-align" id="Academic" href="">ACADEMICS <img src="/WEBSITE-wCMS/imgs/Expand Arrow.png" alt=""></a>
            <div id="Academic-dropdown" class="nav-dropdown">
                <div class="dropdown-cont">
                <div class="dropdown-title">
                        <div class="dropdown-content">
                            <h6 class="inter-bold">COLLEGE DEPARTMENT</h6>
                            
                            <div class="two-columns">
                            
                                <div class="dropdown-col-1">
                                    <?php 
                                       $i = 1;
                                       $lastIndex = 0;
                                        foreach ($subPageListItems as $items){
                                            if ($items['pagesID'] != 3){
                                                continue;
                                            }
                                         ?>
                                            <p class="inter-light"><a href="<?php echo $items['subPagePath'] ?>"><?php echo $items['subPageName']?></a></p>
                                          
                                        <?php $i++;
                                        $lastIndex = $items['subpageID'] + 1;
                                        if ($i == 9) break;
                                        }
                                    ?>
                                </div>
                                <div class="dropdown-col-2">
                                    <?php 
                                          $i = 1;
                                          $found = false;
                                          
                                          foreach ($subPageListItems as $items) {
                                              // Wait until we reach lastIndex
                                              if (!$found) {
                                                  if ($items['subpageID'] != $lastIndex) {
                                                      continue;
                                                  }
                                                  $found = true;
                                              }
                                          
                                              ?>
                                              <p class="inter-light"><a href="<?php echo $items['subPagePath']; ?>"><?php echo $items['subPageName']; ?></a></p>
                                              <?php                                          
                                              $i++;
                                              if ($i == 10) break;
                                          }
                                  ?>
                                </div>
                            </div>

                        </div>

                        <div class="nav-divider"></div>
                        <div class="dropdown-content">
                            <h6 class="inter-bold">BASIC EDUCATION DEPARTMENT</h6>
                            <p class="inter-light">Elementary</p>
                            <p class="inter-light">Junior HighSchool</p>
                            <p class="inter-light">Senior HighSchool</p>
                        </div>
                        <div class="nav-divider"></div>
                        <div class="dropdown-content">
                            <h6 class="inter-bold">EXTERNAL STUDIES UNIT</h6>
                            <div class="two-columns">                                
                                <div class="dropdown-col-1">
                                <?php 
                                       $i = 0;
                                        foreach ($navBarListItem as $items){
                                            if ($items['description'] != 'esu-list-items'){
                                                continue;
                                            }
                                         ?>
                                            <p class="inter-light"><a href="<?php echo $items['link'] ?>"><?php echo $items['content']?></a></p>
                                          
                                        <?php $i++;
                                        $lastIndex = $items['elementID'] + 1;
                                        if ($i == 6) break;
                                        }
                                    ?>
                                
                                </div>
                                <div class="dropdown-col-2">
                                <?php 
                                          $i = 0;
                                          $found = false;
                                          
                                          foreach ($navBarListItem as $items) {
                                              if (!$found) {
                                                  if ($items['elementID'] != $lastIndex) {
                                                      continue;
                                                  }
                                                  $found = true;
                                              }
                                          
                                              ?>
                                              <p class="inter-light"><a href="<?php echo $items['link']; ?>"><?php echo $items['content']; ?></a></p>
                                              <?php                                          
                                              $i++;
                                              if ($i == 6) break;
                                          }
                                  ?>
                                </div>
                            </div>
                        </div>
                        <div class="nav-divider"></div>
                        <div class="dropdown-content">
                            <h6 class="inter-bold">ADMISSIONS</h6>
                            <?php 
                                foreach ($navBarListItems as $items){
                                    if ($items['description'] == 'admissions-list-items'){
                                        ?>
                                        <p class="inter-light"><a href="<?php echo $items['link']?>"><?php echo $items['content']?></a></p>

                                <?php
                                    }
                                }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
            <a class="inter-extralight inline-align" id="Administration" href="">ADMINISTRATION <img src="/WEBSITE-wCMS/imgs/Expand Arrow.png" alt=""></a>
            <div id="Administration-dropdown" class="nav-dropdown">
                <div class="dropdown-cont">
                    <h1>Administration</h1>
                </div>
            </div>
            <a class="inter-extralight inline-align" id="Research" href="">RESEARCH <img src="/WEBSITE-wCMS/imgs/Expand Arrow.png" alt=""></a>
            <div id="Research-dropdown" class="nav-dropdown">
                <h6 class="dropdown-title">RESEARCH</h6>
            </div>
            <a class="inter-extralight inline-align" id="Other-Links" href="">OTHER LINKS <img src="/WEBSITE-wCMS/imgs/Expand Arrow.png" alt=""></a>
            <div id="Other-dropdown" class="nav-dropdown">
                    <div class="dropdown-title">
                        <div class="dropdown-content">
                            <a href="../page/linkages.php">Linkages</a>
                        </div>
                    </div>
            </div>
            <div class="MyWmsu-btn">
                <a class="MyWmsu-link inter-regular" href="/WEBSITE-WCMS/CMS-WMSU-Website/pages/login-form.php">MyWMSU</a>
            </div>
        </div>
    </section>
</nav>
