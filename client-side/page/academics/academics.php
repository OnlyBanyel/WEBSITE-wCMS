<?php 
require_once '../../CMS-WMSU-Website/classes/pages.class.php';

$acadSubpagesObj = new Pages;
$acadSubpages = $acadSubpagesObj->fetchCollegeSubpages(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../../__includes/head.php"; ?>
    <title>WMSU - Academics</title>
    <style>
        a {
            text-decoration: none !important;
        }
        
        .college-title {
            color: #0066cc;
            font-weight: 500;
            font-size: 1.25rem;
            line-height: 1.3;
        }
        
        .red-line {
            height: 2px;
            background-color: #BD0F03;
            width: 100%;
        }
        
        .view-details-btn {
            background-color: #BD0F03;
            color: white !important;
            text-align: center;
            padding: 8px 0;
            font-size: 0.9rem;
            border-radius: 4px;
            display: block;
            width: 100%;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800 overflow-x-hidden bg-white">
    <!-- Header Section -->
    <section class="header m-0 p-0">
        <?php require_once '../../__includes/navbar.php'?>
    </section>

    <main class="w-full">
        <!-- Subnav -->
        <div class="relative z-10 subnav-container">
            <?php require_once '../../__includes/subnav_academics.php'?>
        </div>
        
        <!-- Page Banner -->
        <div class="bg-[#BD0F03] text-white text-center py-6">
            <h1 class="text-4xl font-semibold">Academic Programs</h1>
            <p class="max-w-3xl mx-auto mt-2 text-base px-4">
                Explore WMSU's diverse range of colleges, schools, and academic programs designed to 
                prepare you for success in your chosen field.
            </p>
        </div>
        
        <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Colleges Section -->
            <div class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-1">Colleges and Schools</h2>
                <div class="w-full h-px bg-[#BD0F03]"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8">
                <?php 
                foreach ($acadSubpages as $item) {
                ?>
                <div>
                    <div class="flex items-start mb-1">
                        <img src="<?php echo $item['imagePath'] ?>" class="w-12 h-12 object-contain mr-3" alt="<?php echo $item['subPageName'] ?> logo">
                        <a href="<?php echo $item['subPagePath'] ?>" class="college-title"><?php echo $item['subPageName'] ?></a>
                    </div>
                    <div class="red-line mb-2"></div>
                    <p class="text-sm text-gray-700 mb-3">
                        Explore programs, faculty, and opportunities at the <?php echo $item['subPageName'] ?>.
                    </p>
                    <a href="<?php echo $item['subPagePath'] ?>" class="view-details-btn">View Details</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <?php require_once '../../__includes/footer.php' ?>
</body>
</html>