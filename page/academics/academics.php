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
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="font-sans">
    <!-- Header -->
    <section class="header">
        <?php require_once '../../__includes/navbar.php'?>
    </section>

    <main class="w-full">
        <!-- Subnav -->
        <div class="relative z-10 subnav-container">
            <?php require_once '../../__includes/subnav_academics.php' ?>
        </div>

        <!-- Page Content -->
        <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Colleges Section -->
            <div class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-1">Colleges and Schools</h2>
                <div class="h-px bg-red-500"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                <?php 
                foreach ($acadSubpages as $item) {
                ?>
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-start p-4">
                        <img src="<?php echo $item['imagePath'] ?>" 
                             class="w-12 h-12 object-contain mr-3 rounded-lg" 
                             alt="<?php echo $item['subPageName'] ?> logo">
                        <a href="<?php echo $item['subPagePath'] ?>" 
                           class="font-semibold text-gray-800 hover:text-red-500 transition-colors duration-300">
                            <?php echo $item['subPageName'] ?>
                        </a>
                    </div>
                    <p class="text-sm text-gray-600 p-4 mb-4">
                        Explore programs, faculty, and opportunities at the <?php echo $item['subPageName'] ?>.
                    </p>
                    <a href="<?php echo $item['subPagePath'] ?>" 
                       class="w-full text-center py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-300">
                        View Details
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once '../../__includes/footer.php' ?>
</body>
</html>
