<?php 

?>

<?php 
if ($_SESSION['account']['role_id'] == 1){
?>

<div class="sidebar border-end">
  <div class="sidebar-header border-bottom">
    <div class="sidebar-brand">WMSU - CMS</div>
  </div>
  <ul class="sidebar-nav">
    <li class="nav-title">General Elements</li>
    <li class="nav-item">
      <a class="nav-link active" href="#">
        <i class="nav-icon cil-speedometer"></i> Navbar
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="nav-icon cil-speedometer"></i> Footer
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
  </ul>
  <div class="sidebar-footer border-top d-flex">
    <button class="sidebar-toggler" type="button"></button>
  </div>
</div>

<?php 
} else {
?>

<?php 
$words = explode(" ", $_SESSION['account']['subPageName']); // Split into words
$collegeName = "";

foreach ($words as $word) {
    if (strtolower($word) !== "of") { // Ignore "of"
        $collegeName .= strtoupper($word[0]); // Take first letter
    }
}
?>

<div class="sidebar border-end">
  <div class="sidebar-header border-bottom">
    <div class="sidebar-brand"><?php echo $collegeName?> - CMS</div>
  </div>
  <ul class="sidebar-nav">
    <li class="nav-title">Main Webpages</li>
    <li class="nav-item">
      <a class="nav-link dynamic-load" data-file="../page-views/college-profile.php" href="#">
        <i class="nav-icon cil-speedometer"></i> College Profile
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link dynamic-load" data-file="../page-views/college-overview.php" href="#">
        <i class="nav-icon cil-speedometer"></i> College Overview
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link dynamic-load" data-file="../page-views/departments.php" href="#">
        <i class="nav-icon cil-speedometer"></i> Departments
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link dynamic-load" data-file="../page-views/courses-offered.php" href="#">
        <i class="nav-icon cil-speedometer"></i> Courses Offered
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
  </ul>
  <ul class="sidebar-nav">
    <li class="nav-title">Others</li>
    <li class="nav-item">
      <a class="nav-link dynamic-load" href="#">
        <i class="nav-icon cil-speedometer"></i> Account Management
        <span class="badge bg-primary ms-auto"></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link dynamic-load"href="#">
        <i class="nav-icon cil-speedometer"></i> Messages
        <span class="badge bg-primary ms-auto"></span>
      </a>
    </li>
   
  </ul>
  
  <div class="sidebar-footer border-top d-flex">
    <button class="sidebar-toggler" type="button"></button>
  </div>
</div>

<?php } ?>
<script src="../js/script.js"></script>