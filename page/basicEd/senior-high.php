<?php
$pageTitle = "Senior High School Department";
session_start();
require_once "../../CMS-WMSU-Website/classes/pages.class.php"; 
$shsObj = new Pages;

$strandsItemSQL = "
    SELECT * FROM page_sections 
    WHERE subpage = 31 
    AND indicator = 'Strand'
";

$strands = [];
$currentStrand = null;

// Execute the query
$strandsData = $shsObj->execQuery($strandsItemSQL);

foreach ($strandsData as $item) {
    $desc = $item["description"];

    // Start a new strand group
    if ($desc === "strand-name") {
        $currentStrand = $item["content"];
        $strands[$currentStrand] = [
            "name" => $currentStrand,
            "desc" => "",
            "end-desc" => "",
            "outcomes" => [],
        ];
    }

    // Assign `strand-desc` to 'desc'
    if ($desc === "strand-desc" && isset($currentStrand)) {
        $strands[$currentStrand]["desc"] = $item["content"];
    }

    // Assign `strand-desc-end` to 'end-desc'
    if ($desc === "strand-desc-end" && isset($currentStrand)) {
        $strands[$currentStrand]["end-desc"] = $item["content"];
    }

    // Assign `strand-item-*` to outcomes
    if (preg_match('/^strand-item-\d+$/', $desc) && isset($currentStrand)) {
        $strands[$currentStrand]["outcomes"][] = $item["content"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../../__includes/head.php"; ?>
    <title>WMSU ILS - <?php echo $pageTitle; ?></title>
    <style>
        /* Department Page Styles */
        .department-header {
            background-color: #BD0F03;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .department-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem 3rem;
        }
        
        .banner-img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            font-family: 'Inter', sans-serif;
        }
        
        .content h1 {
            color: #BD0F03;
            border-bottom: 2px solid #BD0F03;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .content h2 {
            color: #BD0F03;
            margin: 1.5rem 0 1rem;
            font-size: 1.5rem;
        }
        
        .content h3 {
            color: #333;
            margin: 1.2rem 0 0.8rem;
            font-size: 1.2rem;
        }
        
        .content p {
            margin-bottom: 1rem;
            line-height: 1.6;
            color: #333;
        }
        
        .highlight {
            font-weight: bold;
            color: #BD0F03;
        }
        
        .values-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 2rem;
            gap: 1.5rem;
        }
        
        .value-card {
            background-color: #f9f9f9;
            border-left: 4px solid #BD0F03;
            padding: 1.5rem;
            flex: 1;
            min-width: 250px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .value-card h3 {
            color: #BD0F03;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        
        .strands-container {
            margin-top: 2.5rem;
        }
        
        .strand-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-top: 5px solid #BD0F03;
            transition: all 0.3s ease;
        }
        
        .strand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .strand-title {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .strand-icon {
            font-size: 2rem;
            margin-right: 1rem;
            color: #BD0F03;
        }
        
        .subject-list {
            list-style-type: none;
            margin: 1rem 0;
        }
        
        .subject-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .subject-list li:last-child {
            border-bottom: none;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .department-title {
                font-size: 1.8rem;
                padding: 0 1rem;
            }
            
            .content {
                padding: 1.5rem;
            }
            
            .value-card {
                min-width: 100%;
                margin-bottom: 1rem;
            }
            
            .values-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .strand-card {
                padding: 1.2rem;
            }
            
            .strand-title {
                flex-direction: column;
                text-align: center;
            }
            
            .strand-icon {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
            
            .banner-img {
                max-height: 250px;
            }
        }
        
        /* Small mobile devices */
        @media (max-width: 480px) {
            .department-title {
                font-size: 1.5rem;
            }
            
            .content h1 {
                font-size: 1.5rem;
            }
            
            .content h2 {
                font-size: 1.3rem;
            }
            
            .content {
                padding: 1.2rem;
            }
            
            .strand-card {
                padding: 1rem;
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
        
        <!-- Department Header -->
        <div class="department-header">
            <h1 class="department-title">WMSU Integrated Laboratory School (ILS) - Senior High School Department</h1>
        </div>
        
        <div class="container">
            <img src="../../imgs//wmsu-shs.jpg" alt="Senior High School" class="banner-img">
            
            <div class="content">
                <h1>About Our Department</h1>
                <p>The <span class="highlight">WMSU Integrated Laboratory School (ILS) Senior High School Department</span> offers a comprehensive educational experience that bridges basic education and higher learning. As an essential component of Western Mindanao State University's Integrated Laboratory School, our Senior High School program prepares students for college education, employment, entrepreneurship, and middle-level skills development.</p>
                
                <p>Our curriculum follows the K to 12 program's standards of excellence, providing specialized academic tracks that allow students to focus on areas aligned with their aptitudes, interests, and career goals. With state-of-the-art facilities and highly qualified educators, we offer a learning environment that encourages critical thinking, problem-solving, and practical application of knowledge.</p>
                
                <p>We empower our senior high school students to make informed choices about their future academic and career paths through personalized guidance and a well-structured curriculum. Our holistic approach to education ensures that graduates possess not only academic knowledge but also the necessary life skills, values, and character to succeed in their chosen fields and contribute meaningfully to society.</p>
                
                <div class="values-container">
                    <div class="value-card">
                        <h3>College and Career Readiness</h3>
                        <p>We equip students with the knowledge, skills, and mindset necessary for success in higher education and future careers through specialized academic tracks.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Research and Innovation</h3>
                        <p>We foster a culture of inquiry, research, and innovation, encouraging students to develop solutions to real-world problems through evidence-based approaches.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Entrepreneurial Mindset</h3>
                        <p>We cultivate creativity, initiative, and business acumen, preparing students to recognize opportunities and develop entrepreneurial ventures.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Global Citizenship</h3>
                        <p>We develop socially responsible global citizens who understand diverse perspectives and are prepared to contribute positively to an interconnected world.</p>
                    </div>
                </div>
                
                <div class="strands-container">
                    <h1>Academic Strands Offered</h1>

                <?php
                foreach ($strands as $strandName => $strand){
                ?>
                    <div class="strand-card">
                        <div class="strand-title">
                            <div class="strand-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h2><?php echo $strand['name'] ?></h2>
                        </div>
                        <p><?php echo $strand['desc'] ?></p>
                        
                        <h3>Core Subjects Include:</h3>
                        <ul class="subject-list">
                            <?php foreach ($strand['outcomes'] as $item){ ?>
                            <li><i class="fas fa-check-circle text-primary mr-2"></i> <?php echo $item ?></li>
                            <?php } ?>
                        </ul>
                        
                        <p><?php echo $strand['end-desc'] ?></p>
                    </div>
                <?php } ?>
                </div>
            </div>
            
            <div class="text-center mt-8 p-3">
                <a href="basic.php" class="inline-block px-6 py-3 bg-[#BD0F03] text-white font-medium rounded-md hover:bg-[#8B0000] transition duration-300">Back to Basic Education</a>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <?php require_once '../../__includes/footer.php' ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation effect for content appearance
            const content = document.querySelector('.content');
            content.style.opacity = '0';
            content.style.transition = 'opacity 1s ease';
            
            setTimeout(function() {
                content.style.opacity = '1';
            }, 300);
            
            // Responsive navigation for mobile
            const navToggle = document.querySelector('.nav-toggle');
            if (navToggle) {
                navToggle.addEventListener('click', function() {
                    const navMenu = document.querySelector('.nav-menu');
                    navMenu.classList.toggle('active');
                });
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
