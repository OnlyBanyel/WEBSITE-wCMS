<?php
$pageTitle = "Elementary Department";
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
            <h1 class="department-title">WMSU Integrated Laboratory School (ILS) - Elementary Department</h1>
        </div>
        
        <div class="container">
            <img src="../../imgs//wmsu-elem.jpg" alt="Elementary School" class="banner-img">
            
            <div class="content">
                <h1>About Our Department</h1>
                <p>The <span class="highlight">WMSU Integrated Laboratory School (ILS) Elementary Department</span> is dedicated to providing a strong foundation for young learners through quality education, fostering academic excellence, character development, and positive growth. As part of Western Mindanao State University, The department upholds a tradition of excellence, equipping students with fundamental skills in literacy, numeracy, and critical thinking, with a well-structured curriculum and a nurturing environment. The Elementary Program aims to create globally competitive students under the guidance of skilled and competent educators who inspire curiosity and creativity.</p>
                
                <p>Beyond academics, The ILS Elementary Department emphasizes values formation, leadership, and social responsibility, preparing students to become well-rounded individuals. Various co-curricular and extracurricular activities, including clubs, sports, and community involvement programs, encourage students to explore their talents and interests while promoting teamwork, resilience, discipline, rigor and character-building. The department serves as a stepping stone for students' future success in higher education and beyond.</p>
                
                <div class="values-container">
                    <div class="value-card">
                        <h3>Academic Excellence</h3>
                        <p>We foster a learning environment that encourages intellectual growth, critical thinking, and the pursuit of knowledge.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Character Development</h3>
                        <p>We nurture positive values, ethical behavior, and strong moral principles in our students.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Leadership</h3>
                        <p>We develop future leaders through opportunities that foster responsibility, initiative, and decision-making skills.</p>
                    </div>
                    
                    <div class="value-card">
                        <h3>Community Involvement</h3>
                        <p>We encourage active participation in community service to develop social responsibility and civic consciousness.</p>
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