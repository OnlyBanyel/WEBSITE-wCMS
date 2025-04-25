<?php
$pageTitle = "Junior High School Department";
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
        }
        
        .value-card h3 {
            color: #BD0F03;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .value-card {
                min-width: 100%;
            }
            
            .department-title {
                font-size: 2rem;
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
            <h1 class="department-title">WMSU Integrated Laboratory School (ILS) - Junior High School Department</h1>
        </div>
        
        <div class="container">
            <img src="../../imgs//wmsu-hs.jpg" alt="Junior High School" class="banner-img">
            
            <div class="content">
                <h1>About Our Department</h1>
                <p>The <span class="highlight">WMSU Integrated Laboratory School (ILS) High School Department</span> is committed to providing quality education that prepares adolescents for the challenges of higher education and future careers. As an integral part of Western Mindanao State University, our department upholds academic excellence while nurturing well-rounded individuals equipped with critical thinking skills, ethical values, and social responsibility.</p>
                
                <p>Our comprehensive curriculum integrates advanced academic subjects with practical applications, encouraging students to develop analytical thinking, problem-solving abilities, and effective communication skills. The High School Department focuses on holistic development through various academic tracks that cater to diverse student interests and aptitudes, preparing them for their chosen fields in college and beyond.</p>
                
                <p>Beyond academics, we emphasize character formation and leadership development through various co-curricular and extracurricular activities. Our sports programs, cultural organizations, student government, academic clubs, and community outreach initiatives provide students with opportunities to discover their passions, develop their talents, and contribute meaningfully to society. The supportive learning environment created by our dedicated faculty and staff fosters both intellectual growth and emotional maturity during these crucial formative years.</p>
                
                <div class="values-container">
                    <div class="value-card">
                        <h3>Academic Excellence</h3>
                        <p>We promote intellectual rigor and academic distinction through innovative teaching methods and a comprehensive curriculum that challenges students to reach their full potential.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Holistic Development</h3>
                        <p>We nurture the physical, emotional, social, and intellectual aspects of our students' development, recognizing the importance of balance in creating well-rounded individuals.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Leadership and Service</h3>
                        <p>We cultivate student leadership through hands-on experiences and service opportunities that develop responsibility, initiative, and a commitment to positive social change.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Innovation and Technology</h3>
                        <p>We integrate modern technology and innovative practices into our teaching methods, preparing students for the demands of an increasingly digital and interconnected world.</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
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
            
            // Interactive value cards
            const valueCards = document.querySelectorAll('.value-card');
            valueCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.backgroundColor = '#ffe6e6';
                    setTimeout(() => {
                        this.style.backgroundColor = '#f9f9f9';
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>