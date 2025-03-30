<?php 

?>

  <?php 
    if ($_SESSION['account']['role_id'] == 1){
      ?>

  <div class="sidebar sidebar-narrow-unfoldable border-end">
    <div class="sidebar-header border-bottom">
      <div class="sidebar-brand">WMSU - CMS</div>
    </div>


   <ul class="sidebar-nav">
    <li class="nav-title">General Elements</li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="nav-icon cil-speedometer"></i> Navbar
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="nav-icon cil-speedometer"></i> Footer
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>

  </ul>

<?php 
    }else {
  ?>
  <?php 
  $words = explode(" ", $_SESSION['account']['subPageName']); // Split into words
  $collegeName = "";
  
  foreach ($words as $word) {
      if (strtolower($word) !== "of") { // Ignore "of"
          $collegeName .= strtoupper($word[0]); // Take first letter
      }

      
  }

  var_export($_SESSION['account']['subpage_assigned']);
  ?>
   <div class="sidebar sidebar-narrow-unfoldable border-end">
    <div class="sidebar-header border-bottom">
      <div class="sidebar-brand"><?php echo $collegeName?> - CMS</div>
    </div>
  <ul class="sidebar-nav">
    <li class="nav-title">General Elements</li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="nav-icon cil-speedometer"></i> Navbar
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="nav-icon cil-speedometer"></i> Footer
        <span class="badge bg-primary ms-auto">NEW</span>
      </a>
    </li>

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

  <?php } ?>
</div>
<script src="../js/script.js"></script>