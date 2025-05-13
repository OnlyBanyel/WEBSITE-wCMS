<?php
// Update the file path references at the beginning of the file
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/login.class.php";
require_once "../classes/element_styler.class.php";
require_once "../classes/messages.class.php"; // Added messages class

/**
 * Get college name from subpage ID
 */
function getCollegeName($pagesObj, $subpageId) {
    $query = "SELECT subPageName FROM subpages WHERE subpageID = $subpageId";
    $result = $pagesObj->execQuery($query);
    
    if (!empty($result) && isset($result[0]['subPageName'])) {
        return $result[0]['subPageName'];
    }
    
    return 'College';
}

// Add a function to get the primary color for the college
// Add this after the getCollegeName function:

/**
 * Get primary color for college
 * This could be expanded to pull from a database table of college colors
 */
function getCollegePrimaryColor($subpageId) {
    // Default color
    $defaultColor = '#BD0F03';
    
    // Map of college IDs to colors
    $collegeColors = [
        1 => '#BD0F03',  // CCS - Red
        2 => '#0077B6',  // CN - Blue
        3 => '#38B000',  // CSM - Green
        4 => '#6A994E',  // CAgri - Green
        5 => '#7B3F00',  // CL - Brown
        6 => '#1E3A8A',  // CCJE - Dark Blue
        7 => '#FF9500',  // CET - Orange
        8 => '#6A0DAD',  // CPADS - Purple
        9 => '#FF5A5F',  // CSWCD - Pink
        10 => '#2C7A7B', // CTE - Teal
        11 => '#38B000', // CAIS - Green
        12 => '#006400', // CFES - Dark Green
        13 => '#FF6B6B', // CHE - Coral
        14 => '#4361EE', // CM - Blue
        15 => '#D90429', // CCSPE - Red
        16 => '#4CC9F0', // CLA - Light Blue
        17 => '#7209B7'  // CoA - Purple
    ];
    
    return isset($collegeColors[$subpageId]) ? $collegeColors[$subpageId] : $defaultColor;
}

// Add a helper function to adjust color brightness
// Add this after the getCollegePrimaryColor function:

/**
 * Adjust color brightness
 * @param string $hex Hex color code
 * @param int $steps Steps to adjust brightness (-255 to 255)
 * @return string Adjusted hex color
 */

// Add path constants for client-side templates
define('CLIENT_SIDE_PATH', '../../client-side/page/academics/');
define('CLIENT_SIDE_INCLUDES', '../../client-side/__includes/');

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize classes
$pagesObj = new Pages();
$loginObj = new Login();
$styler = new ElementStyler();
$messagesObj = new Messages(); // Initialize messages class

// Get the current page and preview data
$page = isset($_POST['page']) ? $_POST['page'] : '';
$previewData = isset($_POST['preview_data']) ? json_decode($_POST['preview_data'], true) : [];

// Store preview data in session
$_SESSION['preview_data'] = $previewData;
$_SESSION['current_preview_page'] = $page;

// Get the subpage ID dynamically from session
$subpageId = null;

// First check if we have a subpage ID directly in the session
if (isset($_SESSION['subpage'])) {
    $subpageId = $_SESSION['subpage'];
} 
// Then check if it's in the account data
elseif (isset($_SESSION['account']['subpage_assigned'])) {
    $subpageId = $_SESSION['account']['subpage_assigned'];
}
// Then check if it's in the subpageData
elseif (isset($_SESSION['subpageData']['subpageID'])) {
    $subpageId = $_SESSION['subpageData']['subpageID'];
}
// Finally, if we still don't have it, try to get it from the database based on the account
elseif (isset($_SESSION['account']['id'])) {
    $accountId = $_SESSION['account']['id'];
    $subpageQuery = "SELECT subpage_assigned FROM accounts WHERE id = $accountId";
    $result = $pagesObj->execQuery($subpageQuery);
    if (!empty($result) && isset($result[0]['subpage_assigned'])) {
        $subpageId = $result[0]['subpage_assigned'];
        // Store it in session for future use
        $_SESSION['subpage'] = $subpageId;
    }
}

// If we still don't have a subpage ID, default to 1 (likely CCS)
if ($subpageId === null) {
    $subpageId = 1;
}

// Debug information
$debug = [
    'requested_page' => $page,
    'form_data_keys' => array_keys($previewData),
    'session_subpage' => $subpageId,
    'client_side_path' => CLIENT_SIDE_PATH,
    'timestamp' => date('Y-m-d H:i:s')
];

// Generate preview HTML based on the page
$html = '';
$success = true;
$message = '';

try {
    // Update the pageMap to dynamically determine the template file based on subpage ID
    // Replace the existing pageMap definition with this code:

    // Get the correct template file path based on subpage ID
    $subpageQuery = "SELECT subPagePath FROM subpages WHERE subpageID = $subpageId";
    $subpageResult = $pagesObj->execQuery($subpageQuery);
    $templatePath = '';

    if (!empty($subpageResult) && isset($subpageResult[0]['subPagePath'])) {
        // Convert Windows-style path to web path
        $templatePath = str_replace('\\', '/', $subpageResult[0]['subPagePath']);
        // Remove the leading slash if present
        $templatePath = ltrim($templatePath, '/');
        // Make it relative to the project root
        $templatePath = '../../' . $templatePath;
    } else {
        // Default to CCS.php if no template is found
        $templatePath = CLIENT_SIDE_PATH . 'CCS.php';
    }

    // Debug the template path
    $debug['template_path_from_db'] = $templatePath;

    // Map admin page names to their corresponding client-side templates and preview generators
    $pageMap = [
        'college-overview.php' => [
            'template' => $templatePath,
            'generator' => 'generateCollegeOverviewPreview',
            'section' => 'overview'
        ],
        'college-profile.php' => [
            'template' => $templatePath,
            'generator' => 'generateCollegeProfilePreview',
            'section' => 'profile'
        ],
        'courses-offered.php' => [
            'template' => $templatePath,
            'generator' => 'generateCoursesOfferedPreview',
            'section' => 'courses'
        ],
        'departments.php' => [
            'template' => $templatePath,
            'generator' => 'generateDepartmentsPreview',
            'section' => 'departments'
        ],
        'shs.php' => [
            'template' => $templatePath,
            'generator' => 'generateSHSPreview',
            'section' => 'shs'
        ]
    ];
    
    // Check if we have a direct match in our page map
    if (isset($pageMap[$page])) {
        $generatorFunction = $pageMap[$page]['generator'];
        $html = $generatorFunction($previewData, $pagesObj, $styler, $subpageId);
        $debug['generator_used'] = $generatorFunction;
        $debug['template_path'] = $pageMap[$page]['template'];
        $debug['section'] = $pageMap[$page]['section'];
    } 
    // If no direct match, try to determine the page type from the form data
    else {
        // Look for clues in the form data to determine the page type
        $detectedPage = detectPageTypeFromFormData($previewData);
        
        if ($detectedPage && isset($pageMap[$detectedPage])) {
            $generatorFunction = $pageMap[$detectedPage]['generator'];
            $html = $generatorFunction($previewData, $pagesObj, $styler, $subpageId);
            $debug['generator_used'] = $generatorFunction;
            $debug['template_path'] = $pageMap[$detectedPage]['template'];
            $debug['section'] = $pageMap[$detectedPage]['section'];
            $debug['detected_page'] = $detectedPage;
        } else {
            // Default to college overview if we can't determine the page type
            $html = generateCollegeOverviewPreview($previewData, $pagesObj, $styler, $subpageId);
            $debug['generator_used'] = 'generateCollegeOverviewPreview (default)';
            $debug['template_path'] = $pageMap['college-overview.php']['template'];
            $debug['section'] = 'overview';
        }
    }
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    $debug['exception'] = $e->getMessage();
    $debug['exception_trace'] = $e->getTraceAsString();
}

// Return the preview HTML
header('Content-Type: application/json');
echo json_encode([
    'success' => $success,
    'html' => $html,
    'message' => $message,
    'debug' => $debug
]);
exit;

/**
 * Try to detect the page type from the form data
 */
function detectPageTypeFromFormData($formData) {
    // Look for specific form IDs or field names that indicate the page type
    foreach ($formData as $formId => $data) {
        // College Profile detection
        if ($formId === 'collegeNameForm' || isset($data['collegeName'])) {
            return 'college-profile.php';
        }
        
        // Courses Offered detection
        if (strpos($formId, 'courseForm') !== false || isset($data['courseTitle'])) {
            return 'courses-offered.php';
        }
        
        // Departments detection
        if (strpos($formId, 'departmentForm') !== false || isset($data['deptName'])) {
            return 'departments.php';
        }
        
        // SHS detection
        if (strpos($formId, 'strandForm') !== false || isset($data['strandName'])) {
            return 'shs.php';
        }
        
        // College Overview detection
        if (strpos($formId, 'overviewItems') !== false || isset($data['overviewTitle'])) {
            return 'college-overview.php';
        }
    }
    
    return null;
}

/**
 * Generate preview HTML for College Overview page by pulling data from the database
 */
function generateCollegeOverviewPreview($previewData, $pagesObj, $styler, $subpageId) {
    // Pull data from database just like in CCS.php
    $carouselItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'College Profile' 
        AND description IN ('carousel-logo', 'carousel-logo-text', 'carousel-img');
    ";
    $carouselItems = $pagesObj->execQuery($carouselItemsSQL);
    
    $carouselLogo = '';
    $carouselLogoStyles = '';
    $carouselLogoImage = '';
    $carouselItem = [];
    
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
    
    // Get general information items
    $genInfoItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'College Overview' 
        AND description IN ('geninfo-front-img', 'geninfo-front-title');
    ";
    
    $genInfoItems = $pagesObj->execQuery($genInfoItemsSQL);
    $genInfoImgs = [];
    $genInfoImgsStyles = [];
    $genInfoTitles = [];
    $genInfoTitlesStyles = [];
    
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
    
    // Get general information back items
    $genInfoBackItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'College Overview' 
        AND description IN ('geninfo-back-head', 'CG-list-item', 'CM-list-item', 'CV-list-item');
    ";
    
    $genInfoBackItems = [];
    $genInfoBackCGList = [];
    $genInfoBackCMList = [];
    $genInfoBackCVList = [];
    $genInfoBackHead = [];
    $genInfoBackHeadStyles = [];
    $genInfoBackCGListStyles = [];
    $genInfoBackCMListStyles = [];
    $genInfoBackCVListStyles = [];
    
    $genInfoBackItems = $pagesObj->execQuery($genInfoBackItemsSQL);
    
    foreach ($genInfoBackItems as $item) {
        if ($item["description"] == "geninfo-back-head") {
            $genInfoBackHead[] = $item['content'];
            $genInfoBackHeadStyles[] = $item['styles'];
        }
        if ($item["description"] == "CG-list-item") {
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
    }
    
    $genInfoBackLists = [
        0 => $genInfoBackCGList,
        1 => $genInfoBackCMList,
        2 => $genInfoBackCVList
    ];
    
    // Generate HTML for the College Overview section
    $html = '<section class="py-12 md:py-20 bg-gradient-to-b from-white to-secondary preview-section">';
    $html .= '<div class="section-container">';
    
    // Update the generateCollegeOverviewPreview function to include the college name
    // Find the section header in generateCollegeOverviewPreview and replace it with:

    // Get college name
    $collegeName = getCollegeName($pagesObj, $subpageId);

    // Section header
    $html .= '<div class="text-center mb-10 md:mb-16 px-4">';
    $html .= '<h2 class="text-2xl sm:text-3xl md:text-5xl font-bold gradient-text font-montserrat mb-4">' . htmlspecialchars($collegeName) . ' Overview</h2>';
    $html .= '<div class="w-16 md:w-24 h-1 bg-primary mx-auto rounded-full"></div>';
    $html .= '<p class="text-neutral mt-4 md:mt-6 max-w-3xl mx-auto">Discover our commitment to excellence in education and research</p>';
    $html .= '</div>';
    
    // Content grid
    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">';
    
    // Text content
    $html .= '<div class="space-y-8 md:space-y-10 order-2 md:order-1">';
    
    for ($i = 0; $i < count($genInfoBackHead); $i++) {
        $html .= '<div class="card-content space-y-4 bg-white p-8 rounded-xl shadow-card border-l-4 border-primary transform transition-all duration-300 hover:shadow-xl">';
        
        // Title
        $html .= '<h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-primaryDark font-montserrat red-underline inline-block ' . $genInfoTitlesStyles[$i] . '">';
        $html .= htmlspecialchars($genInfoTitles[$i]);
        $html .= '</h2>';
        
        // Content
        $html .= '<p class="text-base sm:text-lg md:text-xl font-semibold text-gray-700 ' . $genInfoBackHeadStyles[$i] . '">';
        $html .= htmlspecialchars($genInfoBackHead[$i]);
        $html .= '</p>';
        
        // List items
        $html .= '<ul class="space-y-2 md:space-y-3 text-neutral custom-list">';
        
        foreach ($genInfoBackLists[$i] as $index => $item) {
            $html .= '<li class="transition-all duration-300 hover:text-primary ' . ($genInfoBackListStyles[$i][$index] ?? '') . '">';
            $html .= '<span>' . htmlspecialchars($item) . '</span>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    // Image column
    $html .= '<div class="rounded-2xl overflow-hidden shadow-custom h-full relative order-1 md:order-2 group">';
    
    if (!empty($genInfoImgs) && isset($genInfoImgs[1])) {
        $html .= '<img src="' . htmlspecialchars($genInfoImgs[1]) . '" alt="College Image" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
        $html .= '<div class="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">';
        $html .= '<div class="p-4 md:p-6 text-white">';
        $html .= '<h3 class="text-xl md:text-2xl font-bold">Excellence in Education</h3>';
        $html .= '<p>Preparing students for the digital future</p>';
        $html .= '</div>';
        $html .= '</div>';
    } else {
        $html .= '<div class="w-full h-64 bg-gray-200 flex items-center justify-center">';
        $html .= '<p class="text-gray-600">No image available</p>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>'; // End grid
    $html .= '</div>'; // End section container
    $html .= '</section>';
    
    // Add preview styles
    // Get college primary color
    $primaryColor = getCollegePrimaryColor($subpageId);

    // Add preview styles
    $html .= '<style>
        .preview-section {
            font-family: "Inter", sans-serif;
            color: #1f2937;
            background: linear-gradient(to bottom, #ffffff, #f8f9fa);
            padding: 3rem 1rem;
            border-radius: 0.5rem;
        }
        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .gradient-text {
            background: linear-gradient(90deg, ' . $primaryColor . ', ' . adjustBrightness($primaryColor, -20) . ');
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .bg-primary {
            background-color: ' . $primaryColor . ';
        }
        .text-primary {
            color: ' . $primaryColor . ';
        }
        .border-primary {
            border-color: ' . $primaryColor . ';
        }
        .shadow-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .red-underline::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: ' . $primaryColor . ';
        }
        .custom-list li {
            position: relative;
            padding-left: 1.5rem;
        }
        .custom-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10px;
            width: 8px;
            height: 8px;
            background-color: ' . $primaryColor . ';
            border-radius: 50%;
        }
        @media (min-width: 768px) {
            .md\\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .md\\:order-1 {
                order: 1;
            }
            .md\\:order-2 {
                order: 2;
            }
        }
    </style>';
    
    return $html;
}

/**
 * Generate preview HTML for College Profile page by pulling data from the database
 */
function generateCollegeProfilePreview($previewData, $pagesObj, $styler, $subpageId) {
    // Pull data from database just like in CCS.php
    $carouselItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'College Profile' 
        AND description IN ('carousel-logo', 'carousel-logo-text', 'carousel-img');
    ";
    $carouselItems = $pagesObj->execQuery($carouselItemsSQL);
    
    $carouselLogo = '';
    $carouselLogoStyles = '';
    $carouselLogoImage = '';
    $carouselItem = [];
    
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
    
    // Generate HTML for the College Profile section
    $html = '<div class="flex flex-col md:flex-row gap-8">';
    
    // Left column with logo and name
    $html .= '<div class="md:w-1/3">';
    $html .= '<div class="bg-white p-4 rounded-lg shadow-md">';
    
    if (!empty($carouselLogoImage)) {
        $html .= '<img src="' . htmlspecialchars($carouselLogoImage) . '" alt="College Logo" class="w-full h-auto object-contain mb-4">';
    } else {
        $html .= '<div class="w-full h-32 bg-gray-200 flex items-center justify-center mb-4">';
        $html .= '<p class="text-gray-600">No logo available</p>';
        $html .= '</div>';
    }
    
    $html .= '<h3 class="text-xl font-bold text-center text-gray-800 ' . $carouselLogoStyles . '">';
    $html .= htmlspecialchars($carouselLogo ?? 'College of Computing Sciences');
    $html .= '</h3>';
    
    $html .= '</div>';
    $html .= '</div>';
    
    // Right column with carousel
    $html .= '<div class="md:w-2/3">';
    $html .= '<div id="previewCarousel" class="carousel slide" data-bs-ride="carousel">';
    $html .= '<div class="carousel-inner rounded-lg overflow-hidden shadow-md">';
    
    if (!empty($carouselItem)) {
        foreach ($carouselItem as $index => $img) {
            $active = $index === 0 ? 'active' : '';
            $html .= '<div class="carousel-item ' . $active . '">';
            
            if (!empty($img['imagePath'])) {
                $html .= '<img src="' . htmlspecialchars($img['imagePath']) . '" class="d-block w-100 h-64 object-cover" alt="College Image">';
            } else {
                $html .= '<div class="w-full h-64 bg-gray-200 flex items-center justify-center">';
                $html .= '<p class="text-gray-600">No image available</p>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="carousel-item active">';
        $html .= '<div class="w-full h-64 bg-gray-200 flex items-center justify-center">';
        $html .= '<p class="text-gray-600">No images available</p>';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    // Add preview styles
    $html .= '<style>
        .carousel-item {
            display: block;
        }
        .carousel-item:not(.active) {
            display: none;
        }
    </style>';
    
    return $html;
}

/**
 * Generate preview HTML for Courses Offered page by pulling data from the database
 */
function generateCoursesOfferedPreview($previewData, $pagesObj, $styler, $subpageId) {
    // Pull data from database just like in CCS.php
    $accordionCoursesSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'Courses and Programs';
    ";
    
    $programHeaders = [];
    $undergradCourses = [];
    $gradCourses = [];
    $currentUndergrad = null;
    $currentGrad = null;
    
    // Execute the query
    $accordionCourses = $pagesObj->execQuery($accordionCoursesSQL);
    
    foreach ($accordionCourses as $item) {
        // Identify Course Type (Undergrad or Grad)
        $isUndergrad = $item["description"] === "course-header-undergrad";
        $isGrad = $item["description"] === "course-header-grad";
        
        // Store Course Headers & Reset Properly
        if ($isUndergrad) {
            $currentUndergrad = $item['content'];
            $undergradCourses[$currentUndergrad] = ["outcomes" => []];
        } elseif ($isGrad) {
            $currentGrad = $item['content'];
            $gradCourses[$currentGrad] = ["outcomes" => []];
        }
        
        // Ensure Outcomes Are Stored Under Correct Course
        if (isset($currentUndergrad) && preg_match('/undergrad-course-list-items-\d+$/', $item["description"])) {
            $undergradCourses[$currentUndergrad]["outcomes"][] = $item['content'];
            $undergradCourses[$currentUndergrad]["styles"][] = $item['styles'];
        }
        
        if (isset($currentGrad) && preg_match('/grad-course-list-items-\d+$/', $item["description"])) {
            $gradCourses[$currentGrad]["outcomes"][] = $item['content'];
            $gradCourses[$currentGrad]["styles"][] = $item['styles'];
        }
    }
    
    // Generate HTML for the Courses section
    $html = '<div class="space-y-8">';
    
    // Update the generateCoursesOfferedPreview function to include the college name
    // Find the Undergraduate Programs section in generateCoursesOfferedPreview and replace it with:

    // Get college name
    $collegeName = getCollegeName($pagesObj, $subpageId);

    // Undergraduate Programs
    $html .= '<div class="mb-8">';
    $html .= '<div class="bg-primary py-3 px-4 rounded-t-lg">';
    $html .= '<h3 class="text-white font-semibold">' . htmlspecialchars($collegeName) . ' - Undergraduate Programs</h3>';
    $html .= '</div>';
    $html .= '<div class="bg-white border border-gray-200 rounded-b-lg p-4 space-y-3">';
    
    if (!empty($undergradCourses)) {
        foreach ($undergradCourses as $courseName => $courseData) {
            $html .= '<div class="course-card bg-gray-50 p-4 rounded-lg border-l-4 border-primary">';
            $html .= '<h4 class="font-bold text-gray-800 mb-2">' . htmlspecialchars($courseName) . '</h4>';
            $html .= '<p class="text-sm text-gray-600 mb-2">Program Objectives/Outcomes:</p>';
            $html .= '<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">';
            
            foreach ($courseData["outcomes"] as $i => $outcome) {
                $style = isset($courseData["styles"][$i]) ? $courseData["styles"][$i] : '';
                $html .= '<li class="' . $style . '">' . htmlspecialchars($outcome) . '</li>';
            }
            
            $html .= '</ul>';
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="text-center py-4 text-gray-500">No undergraduate programs available</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    // Graduate Programs
    $html .= '<div>';
    $html .= '<div class="bg-primary py-3 px-4 rounded-t-lg">';
    $html .= '<h3 class="text-white font-semibold">' . htmlspecialchars($collegeName) . ' - Graduate Programs</h3>';
    $html .= '</div>';
    $html .= '<div class="bg-white border border-gray-200 rounded-b-lg p-4 space-y-3">';
    
    if (!empty($gradCourses)) {
        foreach ($gradCourses as $courseName => $courseData) {
            $html .= '<div class="course-card bg-gray-50 p-4 rounded-lg border-l-4 border-primary">';
            $html .= '<h4 class="font-bold text-gray-800 mb-2">' . htmlspecialchars($courseName) . '</h4>';
            $html .= '<p class="text-sm text-gray-600 mb-2">Program Objectives/Outcomes:</p>';
            $html .= '<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">';
            
            foreach ($courseData["outcomes"] as $i => $outcome) {
                $style = isset($courseData["styles"][$i]) ? $courseData["styles"][$i] : '';
                $html .= '<li class="' . $style . '">' . htmlspecialchars($outcome) . '</li>';
            }
            
            $html .= '</ul>';
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="text-center py-4 text-gray-500">No graduate programs available</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    // Add preview styles
    $html .= '<style>
        .bg-primary {
            background-color: #BD0F03;
        }
        .border-primary {
            border-color: #BD0F03;
        }
    </style>';
    
    return $html;
}

/**
 * Generate preview HTML for Departments page by pulling data from the database
 */
function generateDepartmentsPreview($previewData, $pagesObj, $styler, $subpageId) {
    // Pull data from database just like in CCS.php
    $departmentsSQL = "
        SELECT * from page_sections WHERE subpage = $subpageId AND indicator = 'departments' AND description = 'department-name';
    ";
    
    $departments = $pagesObj->execQuery($departmentsSQL);
    
    // Get images for departments
    $genInfoItemsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'College Overview' 
        AND description IN ('geninfo-front-img');
    ";
    
    $genInfoItems = $pagesObj->execQuery($genInfoItemsSQL);
    $genInfoImgs = [];
    
    foreach ($genInfoItems as $item) {
        if ($item["description"] == "geninfo-front-img") {
            $genInfoImgs[] = $item['imagePath'];
        }
    }
    
    // Update the generateDepartmentsPreview function to include the college name
    // Add this at the beginning of the HTML generation in generateDepartmentsPreview:

    // Get college name
    $collegeName = getCollegeName($pagesObj, $subpageId);

    // Generate HTML for the Departments section
    $html = '<div class="mb-6">';
    $html .= '<h2 class="text-2xl font-bold text-gray-800 mb-4">' . htmlspecialchars($collegeName) . ' Departments</h2>';
    $html .= '</div>';
    $html .= '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';
    
    if (!empty($departments)) {
        foreach ($departments as $i => $item) {
            $html .= '<div class="dept-card rounded-lg overflow-hidden shadow-md h-48 relative">';
            $imgSrc = isset($genInfoImgs[$i]) ? $genInfoImgs[$i] : '';
            $html .= '<div class="absolute inset-0 bg-primary/70" style="background: linear-gradient(rgba(189, 15, 3, 0.7), rgba(189, 15, 3, 0.7)), url(\'' . $imgSrc . '\') no-repeat center center; background-size: cover;"></div>';
            $html .= '<div class="absolute inset-0 flex items-center justify-center p-4">';
            $html .= '<h3 class="text-xl font-bold text-white text-center">' . htmlspecialchars($item['content']) . '</h3>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="col-span-3 text-center py-8 text-gray-500">No departments available</div>';
    }
    
    $html .= '</div>';
    
    // Add preview styles
    // Get college primary color
    $primaryColor = getCollegePrimaryColor($subpageId);

    $html .= '<style>
        .bg-primary {
            background-color: ' . $primaryColor . ';
        }
        .dept-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
        }
        .dept-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(189, 15, 3, 0.2), 0 10px 10px -5px rgba(189, 15, 3, 0.1);
        }
    </style>';
    
    return $html;
}

/**
 * Generate preview HTML for SHS page by pulling data from the database
 */
function generateSHSPreview($previewData, $pagesObj, $styler, $subpageId) {
    // For SHS, we'll need to query the appropriate tables
    // This is a simplified version since we don't have the exact schema for SHS
    $strandsSQL = "
        SELECT * FROM page_sections 
        WHERE subpage = $subpageId 
        AND indicator = 'SHS' 
        AND description IN ('strand-name', 'strand-desc', 'strand-outcome', 'strand-end-desc');
    ";
    
    $strandsData = $pagesObj->execQuery($strandsSQL);
    $strands = [];
    
    // Process strand data
    foreach ($strandsData as $item) {
        if ($item["description"] == "strand-name") {
            $strandName = $item['content'];
            if (!isset($strands[$strandName])) {
                $strands[$strandName] = [
                    'name' => $strandName,
                    'sectionID' => $item['id'],
                    'desc' => '',
                    'desc_sectionID' => '',
                    'end_desc' => '',
                    'end_desc_sectionID' => '',
                    'outcomes' => []
                ];
            }
        } elseif ($item["description"] == "strand-desc") {
            $strandName = $item['content']; // Assuming strand name is stored in content for desc items
            if (isset($strands[$strandName])) {
                $strands[$strandName]['desc'] = $item['content'];
                $strands[$strandName]['desc_sectionID'] = $item['id'];
            }
        } elseif ($item["description"] == "strand-outcome") {
            $strandName = $item['content']; // Assuming strand name is stored in content for outcome items
            if (isset($strands[$strandName])) {
                $strands[$strandName]['outcomes'][] = [
                    'content' => $item['content'],
                    'sectionID' => $item['id']
                ];
            }
        } elseif ($item["description"] == "strand-end-desc") {
            $strandName = $item['content']; // Assuming strand name is stored in content for end desc items
            if (isset($strands[$strandName])) {
                $strands[$strandName]['end_desc'] = $item['content'];
                $strands[$strandName]['end_desc_sectionID'] = $item['id'];
            }
        }
    }
    
    // If no strands are found, add a sample one for preview
    if (empty($strands)) {
        $strands['Sample Strand'] = [
            'name' => 'Sample Strand',
            'sectionID' => 'sample_strand',
            'desc' => 'This is a sample strand description for preview purposes.',
            'desc_sectionID' => 'sample_desc',
            'end_desc' => 'This is a sample end description.',
            'end_desc_sectionID' => 'sample_end_desc',
            'outcomes' => [
                [
                    'content' => 'Sample outcome 1',
                    'sectionID' => 'sample_outcome_1'
                ],
                [
                    'content' => 'Sample outcome 2',
                    'sectionID' => 'sample_outcome_2'
                ]
            ]
        ];
    }
    
    // Update the generateSHSPreview function to include the college name
    // Add this at the beginning of the HTML generation in generateSHSPreview:

    // Get college name
    $collegeName = getCollegeName($pagesObj, $subpageId);

    // Generate HTML for the SHS section
    $html = '<div class="mb-6">';
    $html .= '<h2 class="text-2xl font-bold text-gray-800 mb-4">' . htmlspecialchars($collegeName) . ' - Senior High School Strands</h2>';
    $html .= '</div>';
    $html .= '<div class="space-y-6">';
    
    if (!empty($strands)) {
        foreach ($strands as $strandName => $strand) {
            $html .= '<div class="strand-card bg-white p-6 rounded-lg shadow-md border-l-4 border-primary">';
            $html .= '<div class="strand-title">';
            $html .= '<h3 class="text-lg font-bold text-primary ' . $styler->getElementClassString($strand['sectionID']) . '">';
            $html .= htmlspecialchars($strand['name']);
            $html .= '</h3>';
            $html .= '</div>';
            
            $html .= '<p class="text-gray-700 my-3 ' . $styler->getElementClassString($strand['desc_sectionID']) . '">';
            $html .= htmlspecialchars($strand['desc']);
            $html .= '</p>';
            
            $html .= '<h4 class="font-medium text-gray-800 mb-2">Core Subjects Include:</h4>';
            $html .= '<ul class="space-y-2 pl-5 list-disc text-gray-600">';
            
            foreach ($strand['outcomes'] as $outcome) {
                $html .= '<li class="' . $styler->getElementClassString($outcome['sectionID']) . '">';
                $html .= htmlspecialchars($outcome['content']);
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            
            $html .= '<p class="mt-3 text-gray-700 ' . $styler->getElementClassString($strand['end_desc_sectionID']) . '">';
            $html .= htmlspecialchars($strand['end_desc']);
            $html .= '</p>';
            
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="text-center py-8 text-gray-500">No strands available</div>';
    }
    
    $html .= '</div>';
    
    // Add preview styles
    // Get college primary color
    $primaryColor = getCollegePrimaryColor($subpageId);

    $html .= '<style>
        .text-primary {
            color: ' . $primaryColor . ';
        }
        .border-primary {
            border-color: ' . $primaryColor . ';
        }
    </style>';
    
    return $html;
}

/**
 * Adjust brightness of a color (hex code)
 *
 * @param   string  $hex   Hex code of the color
 * @param   int     $steps Amount (+/-) to change brightness
 * @return  string          Adjusted hex code
 */
function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        // Convert to decimal
        $color   = hexdec($color);

        // Adjust color
        $color   = max(0,min(255,$color + $steps));

        // Make two char hex code
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }

    return $return;
}
