<head>
    <?php require_once "../../__includes/head.php"; ?>
    <title>College of Computing Studies</title>
</head>

<section class="header">
  <?php require_once '../../__includes/navbar.php'?>
</section>

<main class="main-content">
  <section class="invisible-section">
    <div class="college-top">
    <?php require_once '../../__includes/subnav_academics.php'?>
    <div class="hero-container">
      
        <div class="college-heading">
          <img src="../../imgs/cn-proc.png" class="logo" alt="ccs logo">
          <h2 class="college-header">College of Nursing</h2>
        </div>
        <div class="carousel-container">
          <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active" data-bs-interval='1500'>
                <img src="../../imgs/cn1.jpg" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item" data-bs-interval='1500'>
                <img src="../../imgs/cn2.jpg" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item" data-bs-interval='1500'>
                <img src="../../imgs/cn3.jpg" class="d-block w-100" alt="...">
              </div>
            </div>
          </div> <!-- End of carousel -->
        </div>
    </div>

      <div class="flex-container">
        <div class="card-container">
          <div class="card">
            <div class="card-front card-1" style="background: url('../../imgs/cn-card-1.jpg'); background-color: rgba(255, 0, 0, 0.6); background-blend-mode: overlay;">
              <h3>College Goals</h3>
            </div>
            <div class="card-back">
                <div class="college-goals">
                <p>The College envisions itself to be the center of distinctive nursing education fostering the development of graduates who are values oriented, socially responsive and globally competitive. It is committed to develop a perceptive, caring, ethically responsive, innovative and professionally competent nurses.</p>
                
                <ol>
                  <!-- <li>Produce quality, excellent and environmentally proactive graduates imbued with gender responsiveness.&nbsp;</li>
                  <li>Achieve the highest level of accreditation and center of excellence imbued with outcomes-based education.&nbsp;</li>
                  <li>Partner with national and international industries as an outlet for research development and extension.&nbsp;</li>
                  <li>Support faculty members through faculty development programs to be competitive with the highest global standards.</li> -->
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="card-container">
          <div class="card">
            <div class="card-front card-2" style="background: url('../../imgs/cn-card-2.jpg'); background-color: rgba(255, 0, 0, 0.6); background-blend-mode: overlay;">
              <h3>College Mission</h3>
            </div>
            <div class="card-back">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
            </div>
          </div>
        </div>
        <div class="card-container">
          <div class="card">
            <div class="card-front card-3" style="background: url('../../imgs/cn-card-3.jpg'); background-color: rgba(255, 0, 0, 0.6); background-blend-mode: overlay;">
              <h3>College Vision</h3>
            </div>
            <div class="card-back">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- End of college-top -->

    <div class="activities">
      <p>HERE LIES THE ACTIVITIES OF THE COLLEGES</p>
    </div>
  <div class="container mt-4">
    <!-- Undergraduate Courses Label -->
    <h3 class="mb-3">Undergraduate Programs</h3>

    <div class="accordion" id="undergradAccordion">
        <!-- Bachelor of Science in Computer Science (BSCS) -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bsnCollapse">
                    Bachelor of Science in Nursing (BSN)
                </button>
            </h2>
            <div id="bsnCollapse" class="accordion-collapse collapse" data-bs-parent="#undergradAccordion">
                <div class="accordion-body">
                    <p><strong>Program Objectives/Outcomes:</strong></p>
                    <ul>
                        <!-- <li>Utilize effectively the concepts of computer science theories and methodologies and adapt new technologies and ideas.</li>
                        <li>Work cohesively with a team to successfully complete projects.</li>
                        <li>Pursue personal development and lifelong learning through research and graduate studies.</li>
                        <li>Communicate effectively with the computing community and society through oral and written correspondence.</li> -->
                    </ul>
                </div>
            </div>
        </div>


    </div>

    <!-- Graduate Programs Label -->
    <h3 class="mt-5 mb-3">Graduate Programs</h3>

    <div class="accordion" id="gradAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mnCollapse">
                    Master in Nursing (MN)
                </button>
            </h2>
            <div id="mnCollapse" class="accordion-collapse collapse" data-bs-parent="#gradAccordion">
                <div class="accordion-body">
                    <p><strong>Program Objectives/Outcomes:</strong></p>
                    <ul>
                        <!-- <li>Demonstrate advanced IT knowledge and skills in a specialized, interdisciplinary, or multidisciplinary field of learning for professional practice.</li>
                        <li>Utilize effectively advanced knowledge and skills in Information Technology through research and software application development that address IT-related problems of the organization and recognize gender responsiveness.</li>
                        <li>Apply a significant level of expertise-based autonomy and accountability to professional leadership for innovation and research in a specialized, interdisciplinary, or multidisciplinary field.</li>
                        <li>Pursue lifelong learning through research with a highly substantial degree of independence in individual work or teams of interdisciplinary or multidisciplinary settings.</li> -->
                    </ul>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manCollapse">
                    Master in of Arts in Nursing (MaN)
                </button>
            </h2>
            <div id="manCollapse" class="accordion-collapse collapse" data-bs-parent="#gradAccordion">
                <div class="accordion-body">
                    <p><strong>Program Objectives/Outcomes:</strong></p>
                    <ul>
                        <!-- <li>Demonstrate advanced IT knowledge and skills in a specialized, interdisciplinary, or multidisciplinary field of learning for professional practice.</li>
                        <li>Utilize effectively advanced knowledge and skills in Information Technology through research and software application development that address IT-related problems of the organization and recognize gender responsiveness.</li>
                        <li>Apply a significant level of expertise-based autonomy and accountability to professional leadership for innovation and research in a specialized, interdisciplinary, or multidisciplinary field.</li>
                        <li>Pursue lifelong learning through research with a highly substantial degree of independence in individual work or teams of interdisciplinary or multidisciplinary settings.</li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
  </div>


    </div>
  </section>
  <script src="../../JS/ccs.script.js"></script>
</main>