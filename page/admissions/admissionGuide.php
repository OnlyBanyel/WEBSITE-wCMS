<head>
  <?php require_once "../../__includes/head.php"; ?>
  <style>
    /* Custom styles for admission guide page */
    .admission-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .banner {
      background-color: #BD0F03;
      color: white;
      padding: 30px;
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(189, 15, 3, 0.2);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .intro-text {
      font-size: 18px;
      line-height: 1.6;
      color: #333;
      text-align: center;
      max-width: 800px;
      margin: 0 auto 40px;
    }

    /* Grid Gallery Styles */
    .grid-gallery-container {
      margin-top: 40px;
      margin-bottom: 60px;
    }

    .grid-gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .gallery-item {
      position: relative;
      overflow: hidden;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(189, 15, 3, 0.15);
    }

    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
      transform: scale(1.05);
    }

    .gallery-item::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 30%;
      background: linear-gradient(to top, rgba(189, 15, 3, 0.7), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .gallery-item:hover::after {
      opacity: 1;
    }

    .gallery-item-number {
      position: absolute;
      bottom: 10px;
      left: 10px;
      background-color: #BD0F03;
      color: white;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      z-index: 2;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-item-number {
      opacity: 1;
    }

    /* Carousel Overlay Styles */
    .carousel-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .carousel-container {
      width: 80%;
      max-width: 1000px;
      position: relative;
    }

    .carousel-inner {
      border-radius: 8px;
      overflow: hidden;
    }

    .carousel-item img {
      width: 100%;
      height: auto;
      object-fit: contain;
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 50px;
      height: 50px;
      background-color: rgba(189, 15, 3, 0.7);
      border-radius: 50%;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0.8;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
      background-color: #BD0F03;
      opacity: 1;
    }

    .carousel-close {
      position: absolute;
      top: -40px;
      right: 0;
      color: white;
      font-size: 24px;
      cursor: pointer;
      background: none;
      border: none;
      z-index: 1001;
    }

    .carousel-indicator {
      position: absolute;
      bottom: -40px;
      left: 0;
      width: 100%;
      text-align: center;
      color: white;
      font-size: 16px;
    }

    @media (max-width: 768px) {
      .grid-gallery {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
      }
      
      .banner {
        font-size: 24px;
        padding: 20px;
      }
      
      .carousel-container {
        width: 95%;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <Title>Admission Guide - WMSU</Title>
</head>

<section class="header"><?php require_once '../../__includes/navbar.php'?></section>

<!-- Subnav & Hero Section -->
<section class="relative w-full">
  <!-- Subnav -->
  <div class="relative z-10">
    <?php require_once '../../__includes/subnav_academics.php'?>
  </div>
</section>

<main>
  <div class="admission-container">
    <div class="banner">ADMISSION GUIDE</div>
    
    <p class="intro-text">
      The Admission Guide for New Students can be found below, providing all the essential steps, requirements, and procedures for a smooth application process.
      Make sure to review each section carefully to ensure you meet the necessary qualifications and deadlines.
    </p>
    
    <div class="grid-gallery-container">
      <div class="grid-gallery">
        <?php 
        // Array of image paths
        $images = [
          '../../imgs/admissionGuide/1.PNG',
          '../../imgs/admissionGuide/2.PNG',
          '../../imgs/admissionGuide/3.PNG',
          '../../imgs/admissionGuide/4.PNG',
          '../../imgs/admissionGuide/5.PNG',
          '../../imgs/admissionGuide/6.PNG',
          '../../imgs/admissionGuide/7.PNG'
        ];
        
        // Loop through images and create gallery items
        foreach ($images as $index => $image) {
          echo '<div class="gallery-item" data-slide-to="' . $index . '">';
          echo '<img src="' . $image . '" alt="Admission Guide Slide ' . ($index + 1) . '">';
          echo '<div class="gallery-item-number">' . ($index + 1) . '</div>';
          echo '</div>';
        }
        ?>
      </div>
      
      <p class="text-center text-muted">Click on any image to view in full screen</p>
    </div>
    
    <!-- Bootstrap Slideshow Overlay -->
    <div id="admissionGuideCarousel" class="carousel slide carousel-overlay">
      <button class="carousel-close">
        <i class="fas fa-times"></i>
      </button>
      
      <div class="carousel-container">
        <div class="carousel-inner">
          <?php 
          // Create carousel items
          foreach ($images as $index => $image) {
            $activeClass = $index === 0 ? 'active' : '';
            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<img src="' . $image . '" class="d-block w-100" alt="Admission Guide Slide ' . ($index + 1) . '">';
            echo '</div>';
          }
          ?>
        </div>
        
        <a class="carousel-control-prev" href="#admissionGuideCarousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#admissionGuideCarousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
        
        <div class="carousel-indicator">
          <span id="current-slide">1</span> / <span id="total-slides">7</span>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  $(document).ready(function() {
    var carousel = new bootstrap.Carousel('#admissionGuideCarousel', {
      interval: false
    });
    
    // Update slide indicator when carousel slides
    $('#admissionGuideCarousel').on('slide.bs.carousel', function (e) {
      $('#current-slide').text(e.to + 1);
    });
    
    // Set total slides
    $('#total-slides').text($('.carousel-item').length);
    
    // Open carousel when clicking on a gallery item
    $('.gallery-item').on('click', function() {
      var slideTo = $(this).data('slide-to');
      $('#admissionGuideCarousel').carousel(slideTo);
      $('#admissionGuideCarousel').css('display', 'flex');
    });
    
    // Close carousel when clicking the close button
    $('.carousel-close').on('click', function() {
      $('#admissionGuideCarousel').hide();
    });
    
    // Close carousel when clicking outside of it
    $('#admissionGuideCarousel').on('click', function(e) {
      if ($(e.target).closest('.carousel-inner, .carousel-control-prev, .carousel-control-next').length === 0) {
        $(this).hide();
      }
    });
  });
</script>