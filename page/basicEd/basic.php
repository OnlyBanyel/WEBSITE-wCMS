<?php
$pageTitle = "Basic Education";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../../__includes/head.php"; ?>
    <title><?php echo $pageTitle; ?></title>
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Basic Education Styles */
        .banner {
            background-color: #BD0F03;
            color: white;
            text-align: center;
            padding: 2rem 0;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            font-family: 'Inter', sans-serif;
        }
        
        @media (max-width: 640px) {
            .banner {
                font-size: 1.75rem;
                padding: 1.5rem 0;
            }
        }
    </style>
</head>

<body class="font-inter antialiased text-gray-800 overflow-x-hidden bg-gray-50">
    <!-- Header Section -->
    <section class="header m-0 p-0">
        <?php require_once '../../__includes/navbar.php'?>
    </section>

    <main class="w-full">
        <!-- Subnav -->
        <div class="relative z-10 subnav-container">
            <?php require_once '../../__includes/subnav_academics.php'?>
        </div>
        
        <!-- Banner -->
        <div class="banner"><?php echo $pageTitle; ?></div>
        
        <!-- Description -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <p class="text-center text-base sm:text-lg md:text-xl mb-8 max-w-3xl mx-auto">
                At Western Mindanao State University, we are committed to providing high-quality education across all levels of basic education: Elementary School, Junior High School, and Senior High School. Our curriculum is designed to develop students' academic skills, critical thinking, and personal growth in a nurturing learning environment.</p>
        </section>
        
        <!-- Department Cards -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg cursor-pointer" onclick="location.href='elementary.php';">
                    <img src="../../imgs/wmsu-elem.jpg" alt="Elementary School Building" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-[#BD0F03] mb-2">Elementary</h2>
                        <p class="text-gray-700">Our elementary program lays a strong foundation in literacy, numeracy, and critical thinking. We provide a stimulating environment where young learners can thrive academically, socially, and emotionally.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg cursor-pointer" onclick="location.href='high-school.php';">
                    <img src="../../imgs/wmsu-hs.jpg" alt="Junior High School Building" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-[#BD0F03] mb-2">Junior High School</h2>
                        <p class="text-gray-700">Our Junior high school serves as a crucial stage in a student's academic journey, where they explore more advanced subjects such as Algebra, Biology, Literature, and Computer Studies. This level helps students build a strong academic base while encouraging extracurricular involvement and leadership development.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg cursor-pointer" onclick="location.href='senior-high.php';">
                    <img src="../../imgs//wmsu-shs.jpg" alt="Senior High School Building" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-[#BD0F03] mb-2">Senior High School</h2>
                        <p class="text-gray-700">Our Senior High School (Grades 11-12) equips students with advanced knowledge and practical skills for college, careers, or entrepreneurship. It fosters critical thinking, independence, and real-world readiness through specialized subjects and hands-on learning.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <?php require_once '../../__includes/footer.php' ?>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll(".cursor-pointer");
            cards.forEach(card => {
                card.addEventListener("click", function () {
                    const url = this.getAttribute("onclick").replace("location.href='", "").replace("';", "");
                    window.location.href = url;
                });
            });
        });
    </script>
</body>
</html>
