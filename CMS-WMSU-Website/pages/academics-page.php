<?php 
session_start();
require_once '../classes/db_connection.class.php';
$dbObj = new Database;


if (empty($_SESSION['account'])){
    header('Location: login-form.php');
    exit;
}

?>

<head>
<title>Academics Page</title>
<?php require_once '../__includes/head.php' ?>
<link rel="stylesheet" href="../css/academics-page.css">
</head>

    <script>
    $(document).ready(function() {
        console.log("This Works")
        $('#datatable').DataTable();
    });
    </script>

    
    <?php require_once '../__includes/navbar.php'?>
    
     <?php require_once '../__includes/sidebar.php'; ?>

<body>

<main>
    
<div class="content">
  
  <div class="col-1">
            <a href="CCS.php"><div class="college-container">
      <img src="../../imgs/ccs-logo-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Computing Studies</h2>
            </div></a>
            <a href="#"><div class="college-container alt-color">
      <img src="../../imgs/CA-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Architecture</h2>
            </div></a>
            <a href="CSM.php"><div class="college-container">
      <img src="../../imgs/CSM-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Science and Mathematics</h2>
            </div></a>

            <a href="#"><div class="college-container alt-color">
      <img src="../../imgs/cl-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Law</h2>
            </div></a>
            <a href="#"><div class="college-container">
      <img src="../../imgs/ccje-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Criminal Justice Education</h2>
            </div></a>


           

  </div>

  <div class="col-2">

            <a href="#"><div class="college-container alt-color">
      <img src="../../imgs/cet-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Engineering and Technology</h2>
            </div></a>

            <a href="#"><div class="college-container">
      <img src="../../imgs/cpads-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Public Administration and Development Studies</h2>
            </div></a>

            <a href="#"><div class="college-container alt-color">
      <img src="../../imgs/cswcd-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Social Work and Community Development</h2>
            </div></a>

            <a href="CN.php"><div class="college-container">
      <img src="../../imgs/cn-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Nursing</h2>
            </div></a>

            <a href="#"><div class="college-container alt-color">
      <img src="../../imgs/cte-proc.png" class="logo-item" alt="ccs logo">
      <h2>College of Teacher Education</h2>
            </div></a>

  </div>

  <div class="col-3">
    
        <a href="#"><div class="college-container">
                  <img src="../../imgs/cais-proc.png" class="logo-item" alt="ccs logo">
                  <h2>College of Asian and Islamic Studies</h2>
        </div></a>
        <a href="#"><div class="college-container alt-color">
                  <img src="../../imgs/cfes-proc.png" class="logo-item" alt="ccs logo">
                  <h2>College of Forestry and Environmental Studies</h2>
        </div></a>
        <a href="#"><div class="college-container">
                  <img src="../../imgs/che-proc.png" class="logo-item" alt="ccs logo">
                  <h2>College of Home Economics</h2>
        </div></a>
        <a href="#"><div class="college-container alt-color">
                  <img src="../../imgs/cm-proc.png" class="logo-item" alt="ccs logo">
                  <h2>College of Medicine</h2>
        </div></a>
        <a href="#"><div class="college-container">
                  <img src="../../imgs/ccspe-proc.png" class="logo-item" alt="ccs logo">
                  <h2>College of Sports Science and Physical Education</h2>
        </div></a>
  </div>

</div>
</main>

</body>
