<?php 
session_start();
require_once "../../CMS-WMSU-Website/classes/pages.class.php"; 
require_once "../../CMS-WMSU-Website/classes/messages.class.php"; // Added messages class
require_once "../../CMS-WMSU-Website/classes/element_styler.class.php";
$ccsPage = new Pages;
$styler = new ElementStyler();

$_SESSION['subpage'] = 2;
// Get unread message count for the notification bubble
$unreadCount = 0;
$inbox = [];
$sent = [];

if (!isset($_SESSION['subpage']) && isset($subpage)) {
  $_SESSION['subpage'] = $subpage;
}

// Get content manager for current subpage
$contentManager = null;
if (isset($_SESSION['subpage'])) {
  $managers = $ccsPage->fetchContentManagersBySubpage($_SESSION['subpage']);
  $contentManager = !empty($managers) ? $managers[0] : null;

}

// If no content manager found, get admin users as fallback
if (empty($contentManager)) {
  $adminUsers = $ccsPage->fetchAdminUsers();
  if (!empty($adminUsers)) {
      $contentManager = $adminUsers[0];
  }
}

// Handle AJAX request for content manager
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'get_content_manager') {
   header('Content-Type: application/json');
   
   $subpage_id = isset($_POST['subpage_id']) ? $_POST['subpage_id'] : $_SESSION['subpage'];
   
   // Get content manager for the specified subpage
   $managers = $ccsPage->fetchContentManagersBySubpage($subpage_id);
   $manager = !empty($managers) ? $managers[0] : null;
   
   // If no content manager found, get admin users as fallback
   if (empty($manager)) {
       $adminUsers = $ccsPage->fetchAdminUsers();
       $manager = !empty($adminUsers) ? $adminUsers[0] : null;
   }
   
   if ($manager) {
       echo json_encode([
           'success' => true,
           'manager' => $manager
       ]);
   } else {
       echo json_encode([
           'success' => false,
           'message' => 'No content manager found'
       ]);
   }
   exit;
}

// Handle anonymous message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_action']) && $_POST['message_action'] == 'send_anonymous') {
  header('Content-Type: application/json');
  
  // Anti-spam check
  $canSendMessage = true;
  if (isset($_SESSION['last_message_time'])) {
      $timeSinceLastMessage = time() - $_SESSION['last_message_time'];
      $cooldownPeriod = 60; // 60 seconds cooldown
      
      if ($timeSinceLastMessage < $cooldownPeriod) {
          $canSendMessage = false;
          $timeRemaining = $cooldownPeriod - $timeSinceLastMessage;
          echo json_encode([
              'success' => false,
              'message' => "Please wait $timeRemaining seconds before sending another message."
          ]);
          exit;
      }
  }
  
  if ($canSendMessage) {
      $messagesObj = new Messages();
      
      $sender_name = !empty($_POST['sender_name']) ? $_POST['sender_name'] : 'Anonymous';
      $subject = $_POST['subject'];
      $message = $_POST['message'];
      $receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : ($contentManager ? $contentManager['id'] : 1);
      
      // Send anonymous message (sender_id = 0)
      $result = $messagesObj->sendAnonymousMessage(0, $sender_name, $receiver_id, $subject, $message);
      
      if ($result) {
          // Set cooldown timestamp
          $_SESSION['last_message_time'] = time();
          
          echo json_encode([
              'success' => true,
              'message' => 'Message sent successfully!'
          ]);
      } else {
          echo json_encode([
              'success' => false,
              'message' => 'Failed to send message. Please try again.'
          ]);
      }
  }
  exit;
}

/** @region Carousel */
  $carouselItemsSQL = "
      SELECT * FROM page_sections 
      WHERE subpage = 2 
      AND indicator = 'College Profile' 
      AND description IN ('carousel-logo', 'carousel-logo-text', 'carousel-img');
  ";
  $carouselItems = $ccsPage->execQuery($carouselItemsSQL);

  foreach ($carouselItems as $item) {
  if ($item["description"] == "carousel-logo-text") {
  $carouselLogo = $item['content'];
  $carouselLogoStyles = $item['styles'];
      }
  if ($item["description"] == "carousel-logo") {
  $carouselLogoImage = $item['imagePath'];
      }
  if ($item["description"] == "carousel-img") {
  $carouselItem[] = $item;
      }
  }
/** @endregion */


/** @region Card Front */
      $genInfoItemsSQL = "
          SELECT * FROM page_sections 
          WHERE subpage = 2 
          AND indicator = 'College Overview' 
          AND description IN ('geninfo-front-img', 'geninfo-front-title');
      ";

      $genInfoItems = $ccsPage->execQuery($genInfoItemsSQL);

      foreach ($genInfoItems as $item) {
      if ($item["description"] == "geninfo-front-img") {
          $genInfoImgs[] = $item['imagePath'];
          $genInfoImgsStyles[] = $item['styles'];
      }
      if ($item["description"] == "geninfo-front-title") {
          $genInfoTitles[] = $item['content'];
          $genInfoTitlesStyles[] = $item['styles'];
      }
  }
/** @engregion */

/** @region Card Back */
  $genInfoBackItemsSQL = "
      SELECT * FROM page_sections 
      WHERE subpage = 2 
      AND indicator = 'College Overview' 
      AND description IN ('geninfo-back-head', 'CG-list-item', 'CM-list-item', 'CV-list-item');
  ";

  $genInfoBackItems = [];
  $genInfoBackCGList = [];
  $genInfoBackCMList = [];
  $genInfoBackCVList = [];

  $genInfoBackItems = $ccsPage->execQuery($genInfoBackItemsSQL);

  foreach ($genInfoBackItems as $item) {
  if ($item["description"] == "geninfo-back-head") {
      $genInfoBackHead[] = $item['content'];
      $genInfoBackHeadStyles[] = $item['styles'];
  }
  if ($item["description"] == "CG-list-item" ) {
      $genInfoBackCGList[] = $item['content'];
      $genInfoBackCGListStyles[] = $item['styles'];
  }
  if ($item["description"] == "CM-list-item") {
      $genInfoBackCMList[] = $item['content'];
      $genInfoBackCMListStyles[] = $item['styles'];
  }
  if ($item["description"] == "CV-list-item") {
      $genInfoBackCVList[] = $item['content'];
      $genInfoBackCVListStyles[] = $item['styles'];
  }

  $genInfoBackLists = [
  0 => $genInfoBackCGList,
  1 => $genInfoBackCMList,
  2 => $genInfoBackCVList
  ];
  }
/** @endregion*/

$departmentsSQL = "
  SELECT * from page_sections WHERE subpage = 2 AND indicator = 'departments' AND description = 'department-name';
";

$departments = $ccsPage->execQuery($departmentsSQL);

/** @region Accordion Courses */
$accordionCoursesSQL = "
SELECT * FROM page_sections 
WHERE subpage = 2 
AND indicator = 'Courses and Programs';
";

$programHeaders = [];
$undergradCourses = [];
$gradCourses = [];
$currentUndergrad = null;
$currentGrad = null;

// ✅ Execute the query
$accordionCourses = $ccsPage->execQuery($accordionCoursesSQL);

foreach ($accordionCourses as $item) {

// ✅ Identify Course Type (Undergrad or Grad)
$isUndergrad = $item["description"] === "course-header-undergrad";
$isGrad = $item["description"] === "course-header-grad";

// ✅ Store Course Headers & Reset Properly
if ($isUndergrad) {
  $currentUndergrad = $item['content'];
  $undergradCourses[$currentUndergrad] = ["outcomes" => []];
} elseif ($isGrad) {
  $currentGrad = $item['content'];
  $gradCourses[$currentGrad] = ["outcomes" => []];
}

// ✅ Ensure Outcomes Are Stored Under Correct Course
if (isset($currentUndergrad) && preg_match('/undergrad-course-list-items-\d+$/', $item["description"])) {
  $undergradCourses[$currentUndergrad]["outcomes"][] = $item['content'];
  $undergradCourses[$currentUndergrad]["styles"][] = $item['styles'];
}

if (isset($currentGrad) && preg_match('/grad-course-list-items-\d+$/', $item["description"])) {
  $gradCourses[$currentGrad]["outcomes"][] = $item['content'];
  $gradCourses[$currentGrad]["styles"][] = $item['styles'];
}
}


/** @endregion */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "../../__includes/head.php"; ?>
  <title><?php echo $carouselLogo?></title>
  <!-- Tailwind CSS -->
  <style>
      /* Custom styles that need precise control */
      html {
          scroll-behavior: smooth;
      }
  
      
      /* Carousel fade animation */
      .carousel-item.active {
          animation: fadeIn 1.5s ease-in-out;
      }
      
      @keyframes fadeIn {
          from { opacity: 0; }
          to { opacity: 1; }
      }
      
      /* Department card hover effect */
      .dept-card {
          transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
          overflow: hidden;
      }
      
      .dept-card:hover {
          transform: translateY(-10px);
          box-shadow: 0 20px 25px -5px rgba(189, 15, 3, 0.2), 0 10px 10px -5px rgba(189, 15, 3, 0.1);
      }
      
      .dept-card:hover .dept-overlay {
          opacity: 0.95;
      }
      
      .dept-card:hover .dept-content {
          transform: translateY(-5px);
      }
      
      /* Accordion custom styling */
      .accordion-button {
          transition: all 0.3s ease;
      }
      
      .accordion-button:not(.collapsed)::after {
          transform: rotate(180deg);
      }
      
      .accordion-content {
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.8s ease;
      }

      .accordion-content.active {
          max-height: 2000px; /* Increased from 1000px to accommodate more content */
      }
      
      /* Custom scrollbar */
      ::-webkit-scrollbar {
          width: 8px;
      }
      
      ::-webkit-scrollbar-track {
          background: #f1f1f1;
      }
      
      ::-webkit-scrollbar-thumb {
          background: #BD0F03;
          border-radius: 10px;
      }
      
      ::-webkit-scrollbar-thumb:hover {
          background: #8B0000;
      }
      
      /* Highlight text selection */
      ::selection {
          background-color: #BD0F03;
          color: white;
      }
      
      /* Custom list styling */
      .custom-list li {
          position: relative;
          padding-left: 1.5rem;
          }
          
          .custom-list li::before {
          content: '';
          position: absolute;
          left: 0;
          top: 10px;
          width: 8px;
          height: 8px;
          background-color: #BD0F03;
          border-radius: 50%;
          }
          
          /* Gradient text */
          .gradient-text {
          background: linear-gradient(90deg, #BD0F03, #8B0000);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          }
          
          /* Animated underline */
          .animated-underline {
          position: relative;
          display: inline-block;
          }
          
          .animated-underline::after {
          content: '';
          position: absolute;
          width: 100%;
          transform: scaleX(0);
          height: 2px;
          bottom: -4px;
          left: 0;
          background-color: #BD0F03;
          transform-origin: bottom right;
          transition: transform 0.3s ease-out;
          }
          
          .animated-underline:hover::after {
          transform: scaleX(1);
          transform-origin: bottom left;
          }
          
          /* Red underline */
          .red-underline {
          position: relative;
          display: inline-block;
          }
          
          .red-underline::after {
          content: '';
          position: absolute;
          width: 100%;
          height: 2px;
          bottom: -4px;
          left: 0;
          background-color: #BD0F03;
          }
          
          /* Stats card */
          .stats-card {
          position: relative;
          overflow: hidden;
          transition: all 0.3s ease;
          }
          
          .stats-card::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 4px;
          background: linear-gradient(90deg, #BD0F03, #8B0000);
          }
          
          .stats-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 15px -3px rgba(189, 15, 3, 0.1), 0 4px 6px -2px rgba(189, 15, 3, 0.05);
          }
          
          /* Course card */
          .course-card {
          border-left: 4px solid #BD0F03;
          transition: all 0.3s ease;
          }
          
          .course-card:hover {
          background-color: rgba(189, 15, 3, 0.05);
          }
           /* Override Bootstrap's primary color with our red theme */
      .bg-primary,
      .bg-primary.active,
      .bg-primary:not([class*="bg-opacity"]) {
          --tw-bg-opacity: 1 !important;
          --bs-bg-opacity: 1 !important;
          background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
      }
      
      /* Override any other primary-related classes */
      .btn-primary,
      .btn-primary:hover,
      .btn-primary:focus,
      .btn-primary:active {
          background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
          border-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
      }
      
      /* Override Bootstrap variables */
      :root {
          --bs-primary: #BD0F03 !important;
          --bs-primary-rgb: 189, 15, 3 !important;
      }
      
      /* Fix for subnav element and container spacing */
      .subnav-container {
          width: 100%;
          max-width: 100%;
          position: relative;
          z-index: 20;
      }
      
      /* Increase subnav height and padding */
      .subnav-academics {
          padding-top: 1rem !important;
          padding-bottom: 1rem !important;
          min-height: 60px !important;
      }
      
      /* Fix card padding to prevent content from hitting borders */
      .card-content {
          padding: 2rem !important;
          margin-bottom: 2rem !important;
      }
      
      /* Add proper spacing for department cards */
      .dept-card-container {
          padding: 2rem !important;
          margin: 0 auto !important;
          max-width: 1200px !important;
          margin: 0 auto !important;
      }
      
      .dept-card {
          margin: 1rem !important;
      }
      
      /* Fix container padding for all sections */
      .section-container {
          padding-left: 2rem !important;
          padding-right: 2rem !important;
          max-width: 1400px !important;
          margin: 0 auto !important;
      }
      
      /* Ensure proper spacing between sections */
      main section {
          padding-top: 3rem !important;
          padding-bottom: 3rem !important;
      }
      
      
      /* Fix accordion spacing */
      .accordion-container {
          padding: 0 2rem !important;
          max-width: 1200px !important;
          margin: 0 auto !important;
      }
      
      /* Fix for mobile responsiveness */
      @media (max-width: 768px) {
          .section-container {
              padding-left: 1rem !important;
              padding-right: 1rem !important;
          }
          
          .card-content {
              padding: 1.5rem !important;
          }
          
          .dept-card-container {
              padding: 1rem !important;
          }
          
          .grid-cols-3 {
              grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
          }
          
          .grid-cols-2 {
              grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
          }
          
          .order-1, .order-2 {
              order: 0 !important;
          }
          
          .h-\[650px\], .h-\[700px\] {
              height: 500px !important;
          }
          
          .text-3xl {
              font-size: 1.5rem !important;
          }
          
          .text-4xl, .text-5xl, .text-6xl {
              font-size: 2rem !important;
          }
          
          .h-48, .h-80, .w-48, .w-80 {
              height: auto !important;
              width: 150px !important;
          }
      }

      /* Add additional styling for better content spacing */
      .syllabus-section {
          padding: 1rem;
          background-color: rgba(255, 255, 255, 0.7);
          border-radius: 0.5rem;
          margin-top: 1rem;
          margin-bottom: 1rem;
      }

      /* Improve responsive layout for smaller screens */
      @media (max-width: 768px) {
          .accordion-content .px-4 {
              padding-left: 0.75rem !important;
              padding-right: 0.75rem !important;
          }
          
          .accordion-content .py-4 {
              padding-top: 0.75rem !important;
              padding-bottom: 0.75rem !important;
          }
      }
  </style>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="font-inter antialiased text-gray-800 overflow-x-hidden bg-gray-50">
  <!-- Header Section -->
  <section class="header">
      <?php require_once '../../__includes/navbar.php'?>
  </section>

  <main class="w-full">
      <!-- Subnav & Hero Section -->
      <section class="relative w-full pt-0">
          <!-- Subnav -->
          <div class="relative z-10 subnav-container">
              <?php require_once '../../__includes/subnav_academics.php'?>
          </div>
          
          <!-- Hero Section with Carousel -->
          <div class="relative h-[650px] md:h-[700px] w-full overflow-hidden">
              <!-- Carousel Images -->
              <div class="absolute inset-0 w-full h-full">
                  <div id="carouselHero" class="carousel slide relative h-full" data-bs-ride="carousel">
                      <div class="carousel-inner h-full">
                          <?php foreach ($carouselItem as $index => $img) { ?>
                              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> h-full" data-bs-interval="4000">
                                  <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-primary/60"></div>
                                  <img src="<?php echo $img['imagePath'] ?>" class="h-full w-full object-cover" alt="CCS Carousel Image">
                              </div>
                          <?php } ?>
                      </div>          
                      
                      <!-- Carousel Indicators -->
                      <div class="carousel-indicators absolute bottom-4">
                          <?php foreach ($carouselItem as $index => $img) { ?>
                              <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?> w-3 h-3 rounded-full bg-white mx-1"></button>
                          <?php } ?>
                      </div>
                  </div>
              </div>
              
              <!-- College Logo & Title Overlay -->
              <div class="absolute inset-0 flex flex-col items-center justify-center z-10 px-4 md:px-8">
                  <div class="bg-primary/10 backdrop-blur-sm p-6 md:p-8 rounded-2xl shadow-2xl animate-fade">
                      <img src="<?php echo $carouselLogoImage ?>" class="h-32 w-32 sm:h-48 sm:w-48 md:h-80 md:w-80 drop-shadow-lg animate-pulse-slow" alt="CCS Logo">
                  </div>
                  <div class="mt-6 md:mt-8 text-center">
                      <h1 class="<?php echo $carouselLogoStyles ?> text-2xl sm:text-3xl md:text-6xl font-bold mt-4 md:mt-6 text-center drop-shadow-lg animate-slide-up">
                          <?php echo $carouselLogo?>
                      </h1>
                      <div class="w-16 md:w-24 h-1 bg-primary mx-auto mt-3 md:mt-4 rounded-full animate-slide-up"></div>
                      <p class="text-white text-base sm:text-lg md:text-xl mt-3 md:mt-4 max-w-2xl mx-auto px-4 animate-slide-up">Empowering Future Innovators Through Technology and Computing Excellence</p>
                  </div>
              </div>
          </div>
      </section>
      
      <!-- General Information Section -->
      <section class="py-12 md:py-20 bg-gradient-to-b from-white to-secondary">
          <div class="section-container">
              <div class="text-center mb-10 md:mb-16 px-4">
                  <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold gradient-text font-montserrat mb-4">College Overview</h2>
                  <div class="w-16 md:w-24 h-1 bg-primary mx-auto rounded-full"></div>
                  <p class="text-neutral mt-4 md:mt-6 max-w-3xl mx-auto">Discover our commitment to excellence in computing education and research</p>
              </div>
              
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">

                  <!-- Text Content -->
                  <div class="space-y-8 md:space-y-10 order-2 md:order-1">
                      <?php for ($i = 0; $i < count($genInfoBackHead); $i++) { ?>
                          <div class="card-content space-y-4 bg-white p-8 rounded-xl shadow-card border-l-4 border-primary transform transition-all duration-300 hover:shadow-xl">
                              <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-primaryDark font-montserrat red-underline inline-block <?php echo $genInfoTitlesStyles[$i] ?>">
                                  <?php echo $genInfoTitles[$i] ?>
                              </h2>
                              <p class="text-base sm:text-lg md:text-xl font-semibold text-gray-700 <?php echo $genInfoBackHeadStyles[$i] ?>">
                                  <?php echo $genInfoBackHead[$i]?>
                              </p>
                              <ul class="space-y-2 md:space-y-3 text-neutral custom-list">
                                <?php foreach ($genInfoBackLists[$i] as $index => $item) { ?>
                                    <li class="transition-all duration-300 hover:text-primary <?php echo $genInfoBackListStyles[$i][$index] ?? ''; ?>">
                                        <span><?php echo htmlspecialchars($item); ?></span>
                                    </li>
                                <?php } ?>

                              </ul>
                          </div>
                      <?php } ?>
                  </div>
                  
                  <!-- Image -->
                  <div class="rounded-2xl overflow-hidden shadow-custom h-full relative order-1 md:order-2 group">
                      <img src="<?php echo $genInfoImgs[1]?>" alt="College Image" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                      <div class="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                          <div class="p-4 md:p-6 text-white">
                              <h3 class="text-xl md:text-2xl font-bold">Excellence in Education</h3>
                              <p>Preparing students for the digital future</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      
      <!-- Departments Section -->
      <section class="py-12 md:py-20 bg-white">
          <div class="section-container">
              <div class="text-center mb-10 md:mb-16 px-4">
                  <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold gradient-text font-montserrat mb-4">Our Departments</h2>
                  <div class="w-16 md:w-24 h-1 bg-primary mx-auto rounded-full"></div>
                  <p class="text-neutral mt-4 md:mt-6 max-w-3xl mx-auto">Specialized academic units dedicated to different areas of computing sciences</p>
              </div>
              
              <div class="dept-card-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                  <?php $i = 0; foreach ($departments as $items) { ?>
                      <div class="dept-card rounded-2xl overflow-hidden shadow-card h-64 md:h-72 relative group border border-primary/10">
                          <!-- Background Image with Overlay -->
                          <div class="absolute inset-0 bg-primary/70 dept-overlay transition-opacity duration-500">
                              <img src="<?php echo $genInfoImgs[$i];?>" alt="Department Background" class="w-full h-full object-cover mix-blend-overlay">
                          </div>
                          
                          <!-- Content -->
                          <div class="absolute inset-0 flex flex-col items-center justify-center p-4 md:p-6 dept-content transition-all duration-500">
                              <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 rounded-full flex items-center justify-center mb-3 md:mb-4">
                                  <div class="w-8 h-8 md:w-12 md:h-12 bg-white rounded-full flex items-center justify-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-6 md:w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                      </svg>
                                  </div>
                              </div>
                              <h3 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-white text-center mb-2">
                                  <?php echo $items['content']; ?>
                              </h3>
                              <div class="w-10 md:w-12 h-1 bg-white rounded-full mb-2 md:mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                              <p class="text-white/90 text-center text-xs md:text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-500 px-2 md:px-4">
                                  Explore our cutting-edge curriculum and research opportunities
                              </p>
                          </div>
                      </div>
                  <?php $i++; } ?>
              </div>
          </div>
      </section>
      
      <!-- Programs Section -->
      <section class="py-12 md:py-20 bg-gradient-to-b from-white to-secondary">
          <div class="section-container">
              <div class="text-center mb-10 md:mb-16 px-4">
                  <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold gradient-text font-montserrat mb-4">Academic Programs</h2>
                  <div class="w-16 md:w-24 h-1 bg-primary mx-auto rounded-full"></div>
                  <p class="text-neutral mt-4 md:mt-6 max-w-3xl mx-auto">Comprehensive educational pathways designed to prepare students for successful careers in computing</p>
              </div>
              
              <!-- Undergraduate Programs -->
              <div class="accordion-container mb-12 md:mb-20">
                  <div class="bg-primary py-4 md:py-6 px-4 md:px-8 rounded-t-2xl">
                      <h2 class="text-xl sm:text-2xl md:text-4xl font-bold text-white text-center font-montserrat">
                          Undergraduate Programs
                      </h2>
                  </div>
                  
                  <div class="max-w-4xl mx-auto bg-white rounded-b-2xl shadow-custom overflow-hidden">
                      <?php foreach ($undergradCourses as $courseName => $courseData) { ?>
                          <div class="border-b border-gray-200 last:border-b-0">
                              <!-- Accordion Header -->
                              <button class="accordion-button w-full course-card bg-white hover:bg-primary/5 text-gray-800 py-4 md:py-5 px-4 md:px-8 text-left font-semibold text-base md:text-lg flex justify-between items-center transition-colors duration-300" 
                                      onclick="toggleAccordion('undergrad-<?php echo md5($courseName); ?>')">
                                  <div class="flex items-center">
                                      <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3 md:mr-4 flex-shrink-0">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                              <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                          </svg>
                                      </div>
                                      <span class="line-clamp-1"><?php echo $courseName; ?></span>
                                  </div>
                                  <svg class="w-5 h-5 md:w-6 md:h-6 transform transition-transform duration-300 text-primary flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                  </svg>
                              </button>
                              
                              <!-- Accordion Content -->
                              <div id="undergrad-<?php echo md5($courseName); ?>" class="accordion-content">
                                  <div class="px-4 md:px-8 py-4 md:py-6 bg-primary/5 border-l-4 border-primary">
                                      <h4 class="font-bold text-base md:text-lg text-primary mb-3 md:mb-4 red-underline inline-block">Program Objectives/Outcomes:</h4>
                                      <ul class="space-y-2 md:space-y-3 pl-4 md:pl-5 list-disc text-gray-700">
                                          <?php foreach ($courseData["outcomes"] as $outcome) { ?>
                                              <li class="pl-1 md:pl-2"><?php echo $outcome; ?></li>
                                          <?php } ?>
                                      </ul>
                                      
                                      <!-- Course Syllabus Section -->
                                      <div class="mt-6 pt-6 border-t border-gray-200">
   <h4 class="font-bold text-base md:text-lg text-primary mb-3 md:mb-4 red-underline inline-block">Course Syllabus:</h4>
   
   <div class="space-y-4 syllabus-section">
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Course Description:</h5>
           <p class="text-sm text-gray-700 mt-1">This course provides a comprehensive introduction to the fundamental concepts and practices of <?php echo $courseName; ?>. Students will develop both theoretical knowledge and practical skills necessary for success in the computing industry.</p>
       </div>
       
       <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
           <div>
               <h5 class="text-sm font-semibold text-gray-800">Units/Credits:</h5>
               <p class="text-sm text-gray-700 mt-1">3 Units</p>
           </div>
           
           <div>
               <h5 class="text-sm font-semibold text-gray-800">Prerequisites:</h5>
               <p class="text-sm text-gray-700 mt-1">Introduction to Computing, Basic Programming</p>
           </div>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Main Topics:</h5>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Fundamental principles and theories</li>
               <li>Current industry practices and standards</li>
               <li>Problem-solving methodologies</li>
               <li>Practical application development</li>
               <li>Ethical considerations in computing</li>
           </ul>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Learning Outcomes:</h5>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Demonstrate proficiency in core concepts</li>
               <li>Apply theoretical knowledge to practical scenarios</li>
               <li>Develop critical thinking and analytical skills</li>
               <li>Create solutions to complex computing problems</li>
           </ul>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Assessment Methods:</h5>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Written examinations (40%)</li>
               <li>Practical projects (30%)</li>
               <li>Assignments and quizzes (20%)</li>
               <li>Class participation (10%)</li>
           </ul>
       </div>
   </div>
</div>
                                       
                                       <div class="mt-4 md:mt-6 flex justify-end">
                                           <a href="#" class="inline-flex items-center text-primary font-medium hover:text-primaryDark transition-colors duration-300 group">
                                               Learn more
                                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                               </svg>
                                           </a>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       <?php } ?>
                   </div>
               </div>
               
               <!-- Graduate Programs -->
               <div class="accordion-container">
                   <div class="bg-primary py-4 md:py-6 px-4 md:px-8 rounded-t-2xl">
                       <h2 class="text-xl sm:text-2xl md:text-4xl font-bold text-white text-center font-montserrat">
                           Graduate Programs
                       </h2>
                   </div>
                   
                   <div class="max-w-4xl mx-auto bg-white rounded-b-2xl shadow-custom overflow-hidden">
                       <?php foreach ($gradCourses as $courseName => $courseData) { ?>
                           <div class="border-b border-gray-200 last:border-b-0">
                               <!-- Accordion Header -->
                               <button class="accordion-button w-full course-card bg-white hover:bg-primary/5 text-gray-800 py-4 md:py-5 px-4 md:px-8 text-left font-semibold text-base md:text-lg flex justify-between items-center transition-colors duration-300" 
                                       onclick="toggleAccordion('grad-<?php echo md5($courseName); ?>')">
                                   <div class="flex items-center">
                                       <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3 md:mr-4 flex-shrink-0">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                               <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                           </svg>
                                       </div>
                                       <span class="line-clamp-1"><?php echo $courseName; ?></span>
                                   </div>
                                   <svg class="w-5 h-5 md:w-6 md:h-6 transform transition-transform duration-300 text-primary flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                   </svg>
                               </button>
                               
                               <!-- Accordion Content -->
                               <div id="grad-<?php echo md5($courseName); ?>" class="accordion-content">
                                   <div class="px-4 md:px-8 py-4 md:py-6 bg-primary/5 border-l-4 border-primary">
                                       <h4 class="font-bold text-base md:text-lg text-primary mb-3 md:mb-4 red-underline inline-block">Program Objectives/Outcomes:</h4>
                                       <ul class="space-y-2 md:space-y-3 pl-4 md:pl-5 list-disc text-gray-700">
                                           <?php foreach ($courseData['outcomes'] as $i => $outcome) { ?>
                                               <li class="pl-1 md:pl-2 <?php echo $courseData['styles'][$i]  ?>"><?php echo $outcome; ?></li>
                                           <?php } ?>
                                       </ul>
                                       
                                       <!-- Course Syllabus Section -->
                                       <div class="mt-6 pt-6 border-t border-gray-200">
   <h4 class="font-bold text-base md:text-lg text-primary mb-3 md:mb-4 red-underline inline-block">Course Syllabus:</h4>
   
   <div class="space-y-4 syllabus-section">
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Course Description:</h5>
           <p class="text-sm text-gray-700 mt-1">This advanced graduate course explores cutting-edge concepts and methodologies in <?php echo $courseName; ?>. Students will engage with current research, develop specialized knowledge, and contribute to the field through original research projects.</p>
       </div>
       
       <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
           <div>
               <h5 class="text-sm font-semibold text-gray-800">Units/Credits:</h5>
               <p class="text-sm text-gray-700 mt-1">4 Units</p>
           </div>
           
           <div>
               <h5 class="text-sm font-semibold text-gray-800">Prerequisites:</h5>
               <p class="text-sm text-gray-700 mt-1">Bachelor's degree in Computing or related field, Advanced Programming</p>
           </div>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Main Topics:</h5>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Advanced theoretical frameworks</li>
               <li>Current research trends and innovations</li>
               <li>Specialized methodologies and techniques</li>
               <li>Industry applications and case studies</li>
               <li>Ethical and societal implications</li>
           </ul>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Research Component:</h5>
           <p class="text-sm text-gray-700 mt-1">Students are required to complete a substantial research project that contributes to the field. This includes:</p>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Literature review of current research</li>
               <li>Development of research proposal</li>
               <li>Implementation of research methodology</li>
               <li>Analysis and interpretation of results</li>
               <li>Presentation and defense of findings</li>
           </ul>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Learning Outcomes:</h5>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Demonstrate expertise in advanced concepts</li>
               <li>Critically evaluate current research and methodologies</li>
               <li>Design and conduct original research</li>
               <li>Develop innovative solutions to complex problems</li>
               <li>Communicate research findings effectively</li>
           </ul>
       </div>
       
       <div>
           <h5 class="text-sm font-semibold text-gray-800">Assessment Methods:</h5>
           <ul class="mt-1 space-y-1 pl-4 list-disc text-sm text-gray-700">
               <li>Research project (50%)</li>
               <li>Written examinations (25%)</li>
               <li>Seminar presentations (15%)</li>
               <li>Class participation and discussion (10%)</li>
           </ul>
       </div>
   </div>
</div>
                                       
                                       <div class="mt-4 md:mt-6 flex justify-end">
                                           <a href="#" class="inline-flex items-center text-primary font-medium hover:text-primaryDark transition-colors duration-300 group">
                                               Learn more
                                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                               </svg>
                                           </a>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       <?php } ?>
                   </div>
               </div>
           </div>
       </section>
       
       <!-- Call to Action Section -->
       <section class="py-12 md:py-16 bg-primary">
           <div class="section-container">
               <div class="max-w-4xl mx-auto text-center text-white">
                   <h2 class="text-xl sm:text-2xl md:text-4xl font-bold font-montserrat mb-4 md:mb-6">Ready to Shape the Future of Technology?</h2>
                   <p class="text-base md:text-lg mb-6 md:mb-8 text-white/90">Join our community of innovators, problem-solvers, and digital pioneers</p>
                   <div class="flex flex-wrap justify-center gap-3 md:gap-4">
                       <a href="#" class="px-6 md:px-8 py-2.5 md:py-3 bg-white text-primary font-semibold rounded-full shadow-lg hover:bg-gray-100 transition-colors duration-300">
                           Apply Now
                       </a>
                       <a href="#" class="px-6 md:px-8 py-2.5 md:py-3 bg-transparent border-2 border-white text-white font-semibold rounded-full hover:bg-white/10 transition-colors duration-300">
                           Request Information
                       </a>
                   </div>
               </div>
           </div>
       </section>
       
       <!-- Stats Section -->
       <section class="py-10 md:py-16 bg-white">
           <div class="section-container">
               <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                   <div class="stats-card text-center p-4 md:p-6 rounded-xl bg-white shadow-card">
                       <div class="text-2xl sm:text-3xl md:text-5xl font-bold text-primary mb-1 md:mb-2">500+</div>
                       <p class="text-gray-600 red-underline inline-block text-sm md:text-base">Students Enrolled</p>
                   </div>
                   <div class="stats-card text-center p-4 md:p-6 rounded-xl bg-white shadow-card">
                       <div class="text-2xl sm:text-3xl md:text-5xl font-bold text-primary mb-1 md:mb-2">30+</div>
                       <p class="text-gray-600 red-underline inline-block text-sm md:text-base">Expert Faculty</p>
                   </div>
                   <div class="stats-card text-center p-4 md:p-6 rounded-xl bg-white shadow-card">
                       <div class="text-2xl sm:text-3xl md:text-5xl font-bold text-primary mb-1 md:mb-2">95%</div>
                       <p class="text-gray-600 red-underline inline-block text-sm md:text-base">Employment Rate</p>
                   </div>
                   <div class="stats-card text-center p-4 md:p-6 rounded-xl bg-white shadow-card">
                       <div class="text-2xl sm:text-3xl md:text-5xl font-bold text-primary mb-1 md:mb-2">20+</div>
                       <p class="text-gray-600 red-underline inline-block text-sm md:text-base">Industry Partners</p>
                   </div>
               </div>
           </div>
       </section>
  </main>

  <!-- Footer -->
  <?php require_once '../../__includes/footer.php' ?>

  <!-- Custom JavaScript for Accordion -->
  <script>
      function toggleAccordion(id) {
  const content = document.getElementById(id);
  const button = content.previousElementSibling;
  const icon = button.querySelector('svg');
  
  // Close all other accordions first (optional - remove if you want multiple open)
  /*
  document.querySelectorAll('.accordion-content.active').forEach(item => {
      if(item.id !== id) {
          item.classList.remove('active');
          item.previousElementSibling.querySelector('svg').classList.remove('rotate-180');
      }
  });
  */
  
  if (!content.classList.contains('active')) {
      content.classList.add('active');
      icon.classList.add('rotate-180');
      
      // Scroll into view with a slight delay to ensure animation completes
      setTimeout(() => {
          content.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }, 300);
  } else {
      content.classList.remove('active');
      icon.classList.remove('rotate-180');
  }
}
      
      // Initialize accordions
      document.addEventListener('DOMContentLoaded', function() {
          // Add smooth scrolling to all links
          document.querySelectorAll('a[href^="#"]').forEach(anchor => {
              anchor.addEventListener('click', function (e) {
                  e.preventDefault();
                  
                  document.querySelector(this.getAttribute('href')).scrollIntoView({
                      behavior: 'smooth'
                  });
              });
          });
          
          // Add animation classes to elements when they come into view
          const animateOnScroll = function() {
              const elements = document.querySelectorAll('.dept-card, .gradient-text, h2, h3');
              
              elements.forEach(element => {
                  const elementPosition = element.getBoundingClientRect().top;
                  const windowHeight = window.innerHeight;
                  
                  if (elementPosition < windowHeight - 100) {
                      element.classList.add('animate-slide-up');
                  }
              });
          };
          
          // Run on load
          animateOnScroll();
          
          // Run on scroll
          window.addEventListener('scroll', animateOnScroll);
      });
  </script>
  <?php require_once "../../__includes/message-bubble.php"; ?>
</body>
</html>
