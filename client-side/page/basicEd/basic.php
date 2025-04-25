<?php
$pageTitle = "Basic Education";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../../__includes/head.php"; ?>
    <title><?php echo $pageTitle; ?></title>
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
        
        .description {
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 1.5rem;
            text-align: center;
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }
        
        .description p {
            font-size: 1.1rem;
            color: #333;
        }
        
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 1.5rem;
        }
        
        .card {
            flex: 1;
            min-width: 300px;
            max-width: 380px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(189, 15, 3, 0.2);
        }
        
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .card h2 {
            color: #BD0F03;
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 1.5rem;
            font-family: 'Inter', sans-serif;
        }
        
        .card p {
            padding: 0 1.5rem 1.5rem;
            color: #555;
            font-family: 'Inter', sans-serif;
        }
        
        @media (max-width: 768px) {
            .cards {
                flex-direction: column;
                align-items: center;
            }
            
            .card {
                max-width: 100%;
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
        <section class="description">
            <p>At Western Mindanao State University, we are committed to providing high-quality education across all levels of basic education: Elementary School, Junior High School, and Senior High School. Our curriculum is designed to develop students' academic skills, critical thinking, and personal growth in a nurturing learning environment.</p>
        </section>
        
        <!-- Department Cards -->
        <section class="cards">
            <div class="card" onclick="location.href='elementary.php';">
                <img src="../../imgs/wmsu-elem.jpg" alt="Elementary School Building">
                <h2>Elementary</h2>
                <p>Our elementary program lays a strong foundation in literacy, numeracy, and critical thinking. We provide a stimulating environment where young learners can thrive academically, socially, and emotionally.</p>
            </div>
            
            <div class="card" onclick="location.href='high-school.php';">
                <img src="../../imgs/wmsu-hs.jpg" alt="Junior High School Building">
                <h2>Junior High School</h2>
                <p>Our Junior high school serves as a crucial stage in a student's academic journey, where they explore more advanced subjects such as Algebra, Biology, Literature, and Computer Studies. This level helps students build a strong academic base while encouraging extracurricular involvement and leadership development.</p>
            </div>
            
            <div class="card" onclick="location.href='senior-high.php';">
                <img src="../../imgs//wmsu-shs.jpg" alt="Senior High School Building">
                <h2>Senior High School</h2>
                <p>Our Senior High School (Grades 11-12) equips students with advanced knowledge and practical skills for college, careers, or entrepreneurship. It fosters critical thinking, independence, and real-world readiness through specialized subjects and hands-on learning.</p>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <?php require_once '../../__includes/footer.php' ?>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll(".card");
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