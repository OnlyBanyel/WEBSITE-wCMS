<?php
// Update the file path references at the beginning of the file
session_start();
require_once "../classes/pages.class.php";
require_once "../classes/login.class.php";
require_once "../classes/element_styler.class.php";

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

// Get the current page and preview data
$page = isset($_POST['page']) ? $_POST['page'] : '';
$previewData = isset($_POST['preview_data']) ? json_decode($_POST['preview_data'], true) : [];

// Store preview data in session
$_SESSION['preview_data'] = $previewData;

// Debug information
$debug = [
    'requested_page' => $page,
    'form_data_keys' => array_keys($previewData),
    'session_subpage' => isset($_SESSION['subpage']) ? $_SESSION['subpage'] : 'not set',
    'client_side_path' => CLIENT_SIDE_PATH
];

// Generate preview HTML based on the page
$html = '';
$success = true;
$message = '';

try {
    // Map admin page names to their corresponding client-side templates and preview generators
    $pageMap = [
        'college-overview.php' => [
            'template' => CLIENT_SIDE_PATH . 'CCS.php',
            'generator' => 'generateCollegeOverviewPreview'
        ],
        'college-profile.php' => [
            'template' => CLIENT_SIDE_PATH . 'CCS.php',
            'generator' => 'generateCollegeProfilePreview'
        ],
        'courses-offered.php' => [
            'template' => CLIENT_SIDE_PATH . 'CCS.php',
            'generator' => 'generateCoursesOfferedPreview'
        ],
        'departments.php' => [
            'template' => CLIENT_SIDE_PATH . 'CCS.php',
            'generator' => 'generateDepartmentsPreview'
        ],
        'shs.php' => [
            'template' => CLIENT_SIDE_PATH . 'CCS.php',
            'generator' => 'generateSHSPreview'
        ]
    ];
    
    // Check if we have a direct match in our page map
    if (isset($pageMap[$page])) {
        $generatorFunction = $pageMap[$page]['generator'];
        $html = $generatorFunction($previewData, $pagesObj, $styler);
        $debug['generator_used'] = $generatorFunction;
        $debug['template_path'] = $pageMap[$page]['template'];
    } 
    // If no direct match, try to determine the page type from the form data
    else {
        // Default to college overview if we can't determine the page type
        $html = generateCollegeOverviewPreview($previewData, $pagesObj, $styler);
        $debug['generator_used'] = 'generateCollegeOverviewPreview (default)';
        $debug['template_path'] = $pageMap['college-overview.php']['template'];
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
 * Generate preview HTML for College Overview page
 */
function generateCollegeOverviewPreview($previewData, $pagesObj, $styler) {
    // Extract data from preview data
    $genInfoTitles = [];
    $genInfoBackHead = [];
    $genInfoBackLists = [[], [], []];
    $genInfoImgs = [];
    
    // Process form data
    foreach ($previewData as $formId => $formData) {
        if (strpos($formId, 'overviewItems') !== false) {
            // Extract section index from form ID
            preg_match('/(\d+)/', $formId, $matches);
            $index = isset($matches[1]) ? (int)$matches[1] : 0;
            
            // Process title
            if (isset($formData['overviewTitle'])) {
                $genInfoTitles[$index] = [
                    'content' => $formData['overviewTitle'],
                    'sectionID' => $formData['overviewSectionID'] ?? ''
                ];
            }
            
            // Process content
            if (isset($formData['overviewTopContent'])) {
                $genInfoBackHead[$index] = [
                    'content' => $formData['overviewTopContent'],
                    'sectionID' => $formData['topContentSectionID'] ?? ''
                ];
            }
            
            // Process outcomes
            if (isset($formData['items'])) {
                foreach ($formData['items'] as $item) {
                    $genInfoBackLists[$index][] = [
                        'content' => $item['content'],
                        'sectionID' => $item['sectionID']
                    ];
                }
            }
        }
        
        // Process images
        if (strpos($formId, 'overviewImg-') !== false) {
            // For preview, we'll use existing images
            if (isset($_SESSION['collegeData'])) {
                foreach ($_SESSION['collegeData'] as $data) {
                    if ($data['indicator'] == 'College Overview' && $data['description'] == 'geninfo-front-img') {
                        $genInfoImgs[] = $data;
                    }
                }
            }
        }
    }
    
    // Generate full section HTML similar to the actual page
    $html = '<section class="py-12 md:py-20 bg-gradient-to-b from-white to-secondary preview-section">';
    $html .= '<div class="section-container">';
    
    // Section header
    $html .= '<div class="text-center mb-10 md:mb-16 px-4">';
    $html .= '<h2 class="text-2xl sm:text-3xl md:text-5xl font-bold gradient-text font-montserrat mb-4">College Overview</h2>';
    $html .= '<div class="w-16 md:w-24 h-1 bg-primary mx-auto rounded-full"></div>';
    $html .= '<p class="text-neutral mt-4 md:mt-6 max-w-3xl mx-auto">Discover our commitment to excellence in computing education and research</p>';
    $html .= '</div>';
    
    // Content grid
    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">';
    
    // Text content
    $html .= '<div class="space-y-8 md:space-y-10 order-2 md:order-1">';
    
    for ($i = 0; $i < count($genInfoTitles); $i++) {
        $html .= '<div class="card-content space-y-4 bg-white p-8 rounded-xl shadow-card border-l-4 border-primary transform transition-all duration-300 hover:shadow-xl">';
        
        // Title
        $html .= '<h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-primaryDark font-montserrat red-underline inline-block ' . $styler->getElementClassString($genInfoTitles[$i]['sectionID']) . '">';
        $html .= htmlspecialchars($genInfoTitles[$i]['content']);
        $html .= '</h2>';
        
        // Content
        $html .= '<p class="text-base sm:text-lg md:text-xl font-semibold text-gray-700 ' . $styler->getElementClassString($genInfoBackHead[$i]['sectionID']) . '">';
        $html .= htmlspecialchars($genInfoBackHead[$i]['content']);
        $html .= '</p>';
        
        // List items
        $html .= '<ul class="space-y-2 md:space-y-3 text-neutral custom-list">';
        
        foreach ($genInfoBackLists[$i] as $item) {
            $html .= '<li class="transition-all duration-300 hover:text-primary ' . $styler->getElementClassString($item['sectionID']) . '">';
            $html .= '<span>' . htmlspecialchars($item['content']) . '</span>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    // Image column
    $html .= '<div class="rounded-2xl overflow-hidden shadow-custom h-full relative order-1 md:order-2 group">';
    
    if (!empty($genInfoImgs) && !empty($genInfoImgs[1]['imagePath'])) {
        $html .= '<img src="' . htmlspecialchars($genInfoImgs[1]['imagePath']) . '" alt="College Image" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
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
            background: linear-gradient(90deg, #BD0F03, #8B0000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .bg-primary {
            background-color: #BD0F03;
        }
        .text-primary {
            color: #BD0F03;
        }
        .border-primary {
            border-color: #BD0F03;
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
            background-color: #BD0F03;
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
            background-color: #BD0F03;
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
 * Generate preview HTML for College Profile page
 */
function generateCollegeProfilePreview($previewData, $pagesObj, $styler) {
    // Extract data from preview data
    $collegeName = [];
    $carouselLogo = [];
    $carouselImgs = [];
    
    // Process form data
    foreach ($previewData as $formId => $formData) {
        if ($formId === 'collegeNameForm') {
            if (isset($formData['collegeName'])) {
                $collegeName = [
                    'content' => $formData['collegeName'],
                    'sectionID' => $formData['textID'] ?? ''
                ];
            }
        }
        
        // For preview, we'll use existing images
        if (isset($_SESSION['collegeData'])) {
            foreach ($_SESSION['collegeData'] as $data) {
                if ($data['indicator'] == 'College Profile') {
                    if ($data['description'] == 'carousel-logo') {
                        $carouselLogo[] = $data;
                    } elseif ($data['description'] == 'carousel-img') {
                        $carouselImgs[] = $data;
                    }
                }
            }
        }
    }
    
    // Generate HTML
    $html = '<div class="flex flex-col md:flex-row gap-8">';
    
    // Left column with logo and name
    $html .= '<div class="md:w-1/3">';
    $html .= '<div class="bg-white p-4 rounded-lg shadow-md">';
    
    if (!empty($carouselLogo) && !empty($carouselLogo[0]['imagePath'])) {
        $html .= '<img src="' . htmlspecialchars($carouselLogo[0]['imagePath']) . '" alt="College Logo" class="w-full h-auto object-contain mb-4">';
    } else {
        $html .= '<div class="w-full h-32 bg-gray-200 flex items-center justify-center mb-4">';
        $html .= '<p class="text-gray-600">No logo available</p>';
        $html .= '</div>';
    }
    
    $html .= '<h3 class="text-xl font-bold text-center text-gray-800 ' . $styler->getElementClassString($collegeName['sectionID']) . '">';
    $html .= htmlspecialchars($collegeName['content']);
    $html .= '</h3>';
    
    $html .= '</div>';
    $html .= '</div>';
    
    // Right column with carousel
    $html .= '<div class="md:w-2/3">';
    $html .= '<div id="previewCarousel" class="carousel slide" data-bs-ride="carousel">';
    $html .= '<div class="carousel-inner rounded-lg overflow-hidden shadow-md">';
    
    if (!empty($carouselImgs)) {
        foreach ($carouselImgs as $index => $img) {
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
    
    return $html;
}

/**
 * Generate preview HTML for Courses Offered page
 */
function generateCoursesOfferedPreview($previewData, $pagesObj, $styler) {
    // Extract data from preview data
    $undergradCourses = [];
    $gradCourses = [];
    
    // Process form data
    foreach ($previewData as $formId => $formData) {
        if (strpos($formId, '-items') !== false) {
            $courseTitle = $formData['courseTitle'] ?? 'Untitled Course';
            $courseType = $formData['courseType'] ?? 'undergrad';
            $sectionID = $formData['titleSectionID'] ?? '';
            
            $courseData = [
                'sectionID' => $sectionID,
                'outcomes' => []
            ];
            
            // Process outcomes
            if (isset($formData['items'])) {
                foreach ($formData['items'] as $item) {
                    $courseData['outcomes'][] = [
                        'content' => $item['content'],
                        'sectionID' => $item['sectionID']
                    ];
                }
            }
            
            // Add to appropriate course type
            if ($courseType === 'undergrad') {
                $undergradCourses[$courseTitle] = $courseData;
            } else {
                $gradCourses[$courseTitle] = $courseData;
            }
        }
    }
    
    // Generate HTML
    $html = '<div class="space-y-8">';
    
    // Undergraduate Programs
    $html .= '<div class="mb-8">';
    $html .= '<div class="bg-primary py-3 px-4 rounded-t-lg">';
    $html .= '<h3 class="text-white font-semibold">Undergraduate Programs</h3>';
    $html .= '</div>';
    $html .= '<div class="bg-white border border-gray-200 rounded-b-lg p-4 space-y-3">';
    
    if (!empty($undergradCourses)) {
        foreach ($undergradCourses as $courseName => $courseData) {
            $html .= '<div class="course-card bg-gray-50 p-4 rounded-lg border-l-4 border-primary">';
            $html .= '<h4 class="font-bold text-gray-800 mb-2">' . htmlspecialchars($courseName) . '</h4>';
            $html .= '<p class="text-sm text-gray-600 mb-2">Program Objectives/Outcomes:</p>';
            $html .= '<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">';
            
            foreach ($courseData['outcomes'] as $outcome) {
                $html .= '<li>' . htmlspecialchars($outcome['content']) . '</li>';
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
    $html .= '<h3 class="text-white font-semibold">Graduate Programs</h3>';
    $html .= '</div>';
    $html .= '<div class="bg-white border border-gray-200 rounded-b-lg p-4 space-y-3">';
    
    if (!empty($gradCourses)) {
        foreach ($gradCourses as $courseName => $courseData) {
            $html .= '<div class="course-card bg-gray-50 p-4 rounded-lg border-l-4 border-primary">';
            $html .= '<h4 class="font-bold text-gray-800 mb-2">' . htmlspecialchars($courseName) . '</h4>';
            $html .= '<p class="text-sm text-gray-600 mb-2">Program Objectives/Outcomes:</p>';
            $html .= '<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">';
            
            foreach ($courseData['outcomes'] as $outcome) {
                $html .= '<li>' . htmlspecialchars($outcome['content']) . '</li>';
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
    
    return $html;
}

/**
 * Generate preview HTML for Departments page
 */
function generateDepartmentsPreview($previewData, $pagesObj, $styler) {
    // Extract data from preview data
    $departments = [];
    $genInfoImgs = [];
    
    // Process form data
    foreach ($previewData as $formId => $formData) {
        if (strpos($formId, 'departmentForm-') !== false) {
            if (isset($formData['deptName'])) {
                $departments[] = [
                    'content' => $formData['deptName'],
                    'sectionID' => $formData['textID'] ?? ''
                ];
            }
        }
    }
    
    // For preview, we'll use existing images
    if (isset($_SESSION['collegeData'])) {
        foreach ($_SESSION['collegeData'] as $data) {
            if ($data['indicator'] == 'College Overview' && $data['description'] == 'geninfo-front-img') {
                $genInfoImgs[] = $data;
            }
        }
    }
    
    // Ensure we have enough images for all departments
    while (count($genInfoImgs) < count($departments)) {
        $genInfoImgs[] = ['imagePath' => '', 'sectionID' => 'temp_img_' . (count($genInfoImgs) + 1)];
    }
    
    // Generate HTML
    $html = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';
    
    if (!empty($departments)) {
        foreach ($departments as $i => $item) {
            $html .= '<div class="dept-card rounded-lg overflow-hidden shadow-md h-48 relative">';
            $html .= '<div class="absolute inset-0 bg-primary/70" style="background: linear-gradient(rgba(189, 15, 3, 0.7), rgba(189, 15, 3, 0.7)), url(\'' . (!empty($genInfoImgs[$i]['imagePath']) ? $genInfoImgs[$i]['imagePath'] : '') . '\') no-repeat center center; background-size: cover;"></div>';
            $html .= '<div class="absolute inset-0 flex items-center justify-center p-4">';
            $html .= '<h3 class="text-xl font-bold text-white text-center">' . htmlspecialchars($item['content']) . '</h3>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html .= '<div class="col-span-3 text-center py-8 text-gray-500">No departments available</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate preview HTML for SHS page
 */
function generateSHSPreview($previewData, $pagesObj, $styler) {
    // Extract data from preview data
    $strands = [];
    
    // Process form data
    foreach ($previewData as $formId => $formData) {
        if (strpos($formId, 'updateStrandForm-') !== false) {
            $strandID = str_replace('updateStrandForm-', '', $formId);
            
            $strand = [
                'name' => $formData['strandName'] ?? 'Untitled Strand',
                'sectionID' => $strandID,
                'desc' => $formData['strandDesc'] ?? '',
                'desc_sectionID' => $formData['descID'] ?? '',
                'end_desc' => $formData['strandEndDesc'] ?? '',
                'end_desc_sectionID' => $formData['endDescID'] ?? '',
                'outcomes' => []
            ];
            
            // Process outcomes
            if (isset($formData['items'])) {
                foreach ($formData['items'] as $item) {
                    $strand['outcomes'][] = [
                        'content' => $item['content'],
                        'sectionID' => $item['sectionID']
                    ];
                }
            }
            
            $strands[$strand['name']] = $strand;
        }
    }
    
    // Generate HTML
    $html = '<div class="space-y-6">';
    
    if (!empty($strands)) {
        foreach ($strands as $strandName => $strand) {
            $html .= '<div class="strand-card">';
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
    
    return $html;
}
?>
