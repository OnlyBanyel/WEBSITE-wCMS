
<?php 
require_once '../../CMS-WMSU-Website/classes/pages.class.php';
require_once '../../CMS-WMSU-Website/tools/functions.php';

$onlineRegObj = new Pages;

$contentItemsSQL = "SELECT * from subpages WHERE pagesID = 3 AND subPageName = 'Subpage Online Registration';
";

$itemContentSQL = "SELECT * from page_sections WHERE subpage = 18 AND indicator = 'onlinereg-section';
";

$contentItems = $onlineRegObj->execQuery($contentItemsSQL);

$itemContent = $onlineRegObj->execQuery($itemContent);

foreach ($itemContent as $items){
    if ($items['description' === 'section-title']){
        $sectionTitles[] = $items['content'];
    }
    if ($items['description' === 'section-content']){
        $sectionContent[] = $items['content'];
    }
}
?>



<main>
    <section class="content-container">
        
        <?php  for ($i = 0; $i < count($sectionTitles); $i++) {
                if ($i % 2 != 0){
            ?>
            <div class="section">
        
                    <a href='<?php echo $contentItems['subPagePath']?>'>
                   <div class="content">
                    <img class="circle-img" src="<?php echo $contentItems['imagePath']?>" alt="Undergraduate students">
                    <div class="text">
                        <h2 class="section-title"><?php echo $sectionTitles?></h2>
                        <p class="section-content"><?php echo $sectionContent?></p>
                    </div>
                </div>
                    </a>
            </div>
        <?php }else{ ?>
            <div class="section">
        
                <a href='<?php echo $contentItems['subPagePath']?>'>
                <div class="content reverse">
                 <img class="circle-img" src="<?php echo $contentItems['imagePath']?>" alt="Undergraduate students">
                 <div class="text">
                     <h2 class="section-title"><?php echo $sectionTitles?></h2>
                     <p class="section-content"><?php echo $sectionContent?></p>
                 </div>
                     </div>
                 </a>
            </div>
           <?php } } ?>
    </section>

</main>


<!--     
    <section class="section undergraduate" onclick="navigateTo('under.html')" style="cursor: pointer;">
        <div class="content">
            <img class="circle-img" src="imgs/OCHO.png" alt="Undergraduate students">
            <div class="text">
                <h2 class="section-title">UNDERGRADUATE</h2>
                <p class="section-content">Western Mindanao State University offers a variety of undergraduate programs that provide students with the knowledge and skills needed for professional success. With diverse fields such as Engineering, Medicine, and Liberal Arts, WMSU ensures academic excellence and prepares students to meet local and global challenges.</p>
            </div>
        </div>
    </a>
    
    <section class="section graduate" onclick="navigateTo('grad.html')" style="cursor: pointer;">
        <div class="content reverse">
            <img class="circle-img" src="imgs/Admin-Office1.jpg" alt="Graduate students">
            <div class="text">
                <h2 class="section-title">GRADUATE</h2>
                <p class="section-content">WMSU’s graduate programs focus on advancing professional and academic careers through research and specialized knowledge. These programs equip students to become leaders in their fields, contributing to societal development and addressing complex issues with innovative solutions.</p>
            </div>
        </div>
    </section>
    
    <section class="section medicine" onclick="navigateTo('med_form.html')" style="cursor: pointer;">
        <div class="content">
            <img class="circle-img" src="imgs/Admin-Office2.jpg" alt="Medical students">
            <div class="text">
                <h2 class="section-title">MEDICINE</h2>
                <p class="section-content">The MD Program strives to develop medical doctors who are competent, adaptable, 
                    and skilled in communication, leadership, and collaboration within healthcare settings. 
                    The program emphasizes clinical competence, professional ethics, and commitment to both personal and professional development.</p>
            </div>
        </div>
    </section> -->