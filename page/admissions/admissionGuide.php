<head>
  
  <?php require_once "../../__includes/head.php"; ?>
    <style>
      <?php require_once '../../css/admissionGuide-style.css'; ?>
    </style>
    <Title>Admission Guide</Title>
</head>


<section class="header"><?php require_once '../../__includes/navbar.php'?></section>

<main>
  <div class="breadcrumb-container">
<?php require_once '../../__includes/subnav_academics.php' ?>
</div>
<div class="intro-admission">
<div class="banner">ADMISSION GUIDE</div>
<div class="container-instruction">
  
  <p>The Admission Guide for New Students can be found below, providing all the essential steps, requirements, and procedures for a smooth application process.
    Make sure to review each section carefully to ensure you meet the necessary qualifications and deadlines.</p>
</div>
</div>
<div class="container">

  <!-- Image Grid Gallery Section -->
  <div class="grid-gallery">
    <img src="../../imgs/admissionGuide/1.PNG" alt="Slide 1" data-slide-to="0">
    <img src="../../imgs/admissionGuide/2.PNG" alt="Slide 2" data-slide-to="1">
    <img src="../../imgs/admissionGuide/3.PNG" alt="Slide 3" data-slide-to="2">
    <img src="../../imgs/admissionGuide/4.PNG" alt="Slide 4" data-slide-to="3">
    <img src="../../imgs/admissionGuide/5.PNG" alt="Slide 5" data-slide-to="4">
    <img src="../../imgs/admissionGuide/6.PNG" alt="Slide 6" data-slide-to="5">
    <img src="../../imgs/admissionGuide/7.PNG" alt="Slide 7" data-slide-to="6">
    <div class="invisible-div"></div> <!-- Invisible div to maintain grid layout -->
    <div class="invisible-div"></div> <!-- Invisible div to maintain grid layout -->
    <div class="invisible-div"></div> <!-- Invisible div to maintain grid layout -->
  </div>

  <!-- Bootstrap Slideshow Overlay -->
  <div id="admissionGuideCarousel" class="carousel slide carousel-overlay" data-bs-ride="false">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../../imgs/admissionGuide/1.PNG" class="d-block w-100" alt="Slide 1">
      </div>
      <div class="carousel-item">
        <img src="../../imgs/admissionGuide/2.PNG" class="d-block w-100" alt="Slide 2">
      </div>
      <div class="carousel-item">
        <img src="../../imgs/admissionGuide/3.PNG" class="d-block w-100" alt="Slide 3">
      </div>
      <div class="carousel-item">
        <img src="../../imgs/admissionGuide/4.PNG" class="d-block w-100" alt="Slide 4">
      </div>
      <div class="carousel-item">
        <img src="../../imgs/admissionGuide/5.PNG" class="d-block w-100" alt="Slide 5">
      </div>
      <div class="carousel-item">
        <img src="../../imgs/admissionGuide/6.PNG" class="d-block w-100" alt="Slide 6">
      </div>
      <div class="carousel-item">
        <img src="../../imgs/admissionGuide/7.PNG" class="d-block w-100" alt="Slide 7">
      </div>
    </div>
    <a class="carousel-control-prev" href="#admissionGuideCarousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#admissionGuideCarousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <script>
    $(document).ready(function() {
      var carousel = new bootstrap.Carousel('#admissionGuideCarousel', {
        interval: false
      });

      $('.grid-gallery img').on('click', function() {
        var slideTo = $(this).data('slide-to');
        carousel.to(slideTo);
        $('#admissionGuideCarousel').css('display', 'flex');
      });

      // Hide the carousel overlay when clicking outside of it
      $('#admissionGuideCarousel').on('click', function(e) {
        if ($(e.target).closest('.carousel-inner, .carousel-control-prev, .carousel-control-next').length === 0) {
          $(this).hide();
        }
      });
    });
  </script>
</div>
</main>
