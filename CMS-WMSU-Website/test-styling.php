<?php
session_start();
require_once __DIR__ . "/classes/element_styler.class.php";
require_once __DIR__ . "/classes/pages.class.php";

// Check if user is logged in
if (!isset($_SESSION['account'])) {
    header('Location: pages/login-form.php');
    exit;
}

// Initialize classes
$styler = new ElementStyler();
$pagesObj = new Pages();

// Get some sample content to style
$collegeData = $pagesObj->fetchSectionsByIndicator('College Profile', 3, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Styling Test</title>
    <?php include_once "__includes/head.php"; ?>
    <style>
        .style-editing {
            outline: 2px dashed #BD0F03 !important;
            position: relative;
        }
        
        .style-editing::after {
            content: "Editing";
            position: absolute;
            top: -20px;
            right: 0;
            background-color: #BD0F03;
            color: white;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 3px;
            z-index: 100;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include_once "components/style-sidebar.php"; ?>
    
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h1 class="text-2xl font-bold mb-4">Dynamic Styling Test</h1>
            <p class="mb-4">Hold the <kbd class="px-2 py-1 bg-gray-200 rounded">Alt</kbd> key and click on any element with the "styleable" class to edit its styles.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($collegeData as $section): ?>
                <?php 
                // Get any custom styles for this element
                $customStyles = $styler->getElementClassString('page_sections', $section['sectionID']);
                ?>
                
                <?php if ($section['elemType'] === 'text'): ?>
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h2 class="text-xl font-semibold mb-2">Text Element #<?php echo $section['sectionID']; ?></h2>
                        <div 
                            class="styleable p-4 border border-gray-200 rounded <?php echo $customStyles; ?>" 
                            data-element-type="page_sections" 
                            data-element-id="<?php echo $section['sectionID']; ?>"
                            data-element-name="<?php echo htmlspecialchars($section['description']); ?>"
                        >
                            <?php echo htmlspecialchars($section['content']); ?>
                        </div>
                    </div>
                <?php elseif ($section['elemType'] === 'image' && $section['imagePath']): ?>
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h2 class="text-xl font-semibold mb-2">Image Element #<?php echo $section['sectionID']; ?></h2>
                        <div 
                            class="styleable p-4 border border-gray-200 rounded <?php echo $customStyles; ?>" 
                            data-element-type="page_sections" 
                            data-element-id="<?php echo $section['sectionID']; ?>"
                            data-element-name="<?php echo htmlspecialchars($section['description']); ?>"
                        >
                            <img src="<?php echo htmlspecialchars($section['imagePath']); ?>" alt="<?php echo htmlspecialchars($section['description']); ?>" class="max-w-full h-auto">
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script src="js/style-sidebar.js"></script>
</body>
</html>
