<head>
    <?php require_once "../../__includes/head.php"; ?>
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ADMISSION</title>
    <style>
        .banner {
            transition: all 0.3s ease;
        }
        
        .banner:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(189, 15, 3, 0.2);
        }
        
        @media (max-width: 640px) {
            .main {
                flex-direction: column;
            }
            
            .banner {
                width: 100%;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<section class="header">
  <?php require_once '../../__includes/navbar.php'?>
</section>

<body class="bg-gray-50">
    <nav class="container-breadcrumb">
        <div class="relative z-10">
            <?php require_once '../../__includes/subnav_academics.php' ?>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-[#BD0F03] mb-8">Admission Information</h1>
        
        <div class="main flex flex-wrap justify-center gap-6">
            <a class="banner w-full sm:w-5/12 bg-[#BD0F03] text-white text-center py-8 px-4 rounded-lg shadow-md hover:bg-[#9B0C02] transition-colors" href='admissionGuide.php'>
                <h2 class="text-2xl font-bold mb-2">ADMISSION GUIDE</h2>
                <p class="text-sm opacity-80">Learn about our admission requirements and process</p>
            </a>
            
            <a class="banner w-full sm:w-5/12 bg-[#BD0F03] text-white text-center py-8 px-4 rounded-lg shadow-md hover:bg-[#9B0C02] transition-colors" href='enrollment.php'>
                <h2 class="text-2xl font-bold mb-2">ENROLLMENT PROCEDURE</h2>
                <p class="text-sm opacity-80">Step-by-step guide to enrolling at WMSU</p>
            </a>
            
            <a class="banner w-full sm:w-5/12 bg-[#BD0F03] text-white text-center py-8 px-4 rounded-lg shadow-md hover:bg-[#9B0C02] transition-colors" href='fees.php'>
                <h2 class="text-2xl font-bold mb-2">SCHEDULE OF FEES</h2>
                <p class="text-sm opacity-80">Information about tuition and other fees</p>
            </a>
            
            <a class="banner w-full sm:w-5/12 bg-[#BD0F03] text-white text-center py-8 px-4 rounded-lg shadow-md hover:bg-[#9B0C02] transition-colors" href='onlineReg.php'>
                <h2 class="text-2xl font-bold mb-2">ONLINE REGISTRATION</h2>
                <p class="text-sm opacity-80">Register for admission online</p>
            </a>
        </div>
    </div>
    
    <!-- Footer -->
    <?php require_once '../../__includes/footer.php' ?>
</body>
</html>
