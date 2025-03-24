<head>
    <?php require_once "../../__includes/head.php"; ?>
    <style>
      <?php require_once '../../css/basicEd.css'; ?>
      <?php require_once '../../css/enrollment.css'; ?>
      body, h1, h2, h3, h4, h5, h6, p, a {
          font-family: 'Inter', sans-serif;
      }
    </style>
    <Title>Basic Education</Title>
</head>

<section class="header"><?php require_once '../../__includes/navbar.php'?></section>

<div class="breadcrumb-container">
    <?php require_once '../../__includes/subnav_academics.php' ?>
    </div>
<main>

<div class="banner">Basic Education</div>
    <section class="description">
             <p>At Western Mindanao State University, we are committed to providing high-quality education across all levels of basic education: Elementary School, Junior High School, and Senior High School. Our curriculum is designed to develop students' academic skills, critical thinking, and personal growth in a nurturing learning environment.</p>
    </section>
    <section class="cards">
        <div class="card" onclick="location.href='elementary.html';">
            <img src="../../imgs/wmsu-elem.jpg" alt="School Building">
            <h2>Elementary</h2>
            <p>Our elementary program lays a strong foundation in literacy, numeracy, and critical thinking. We provide a stimulating environment where young learners can thrive academically, socially, and emotionally.</p>
        </div>
        <div class="card" onclick="location.href='elementary.html';">
            <img src="../../imgs/wmsu-hs.jpg" alt="School Building">
            <h2>Junior High School</h2>
            <p>Our Junior high school serves as a crucial stage in a student’s academic journey, where they explore more advanced subjects such as Algebra, Biology, Literature, and Computer Studies. This level helps students build a strong academic base while encouraging extracurricular involvement and leadership development.</p>
        </div>
        <div class="card" onclick="location.href='elementary.html';">
            <img src="../../imgs/wmsu-hs.jpg" alt="School Building">
            <h2>Senior High School</h2>
            <p>Our Senior High School (Grades 11-12) equips students with advanced knowledge and practical skills for college, careers, or entrepreneurship. It fosters critical thinking, independence, and real-world readiness through specialized subjects and hands-on learning.</p>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll(".card");
            cards.forEach(card => {
                card.addEventListener("click", function () {
                    window.location.href = "elementary.html";
                });
            });
        });
    </script>
</body>
