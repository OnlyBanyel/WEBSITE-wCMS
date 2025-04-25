<head>
  <?php require_once "../../__includes/head.php"; ?>
  <style>
    /* Custom styles for enrollment page */
    .enrollment-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .steps-container {
      margin-top: 40px;
      margin-bottom: 40px;
    }

    .step-card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 25px;
      margin-bottom: 25px;
      position: relative;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-left: 5px solid #BD0F03;
    }

    .step-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(189, 15, 3, 0.15);
    }

    .step-number {
      position: absolute;
      top: -15px;
      left: -15px;
      width: 40px;
      height: 40px;
      background-color: #BD0F03;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 18px;
      box-shadow: 0 4px 8px rgba(189, 15, 3, 0.3);
    }

    .step-content {
      padding-left: 15px;
    }

    .step-title {
      font-size: 18px;
      font-weight: bold;
      color: #BD0F03;
      margin-bottom: 10px;
    }

    .step-description {
      color: #333;
      line-height: 1.6;
    }

    .banner {
      background-color: #BD0F03;
      color: white;
      padding: 30px;
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(189, 15, 3, 0.2);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .intro-text {
      font-size: 18px;
      line-height: 1.6;
      color: #333;
      text-align: center;
      max-width: 800px;
      margin: 0 auto 40px;
    }

    .enrollment-header {
      margin: 40px 0 30px;
      color: #BD0F03;
      font-size: 28px;
      font-weight: bold;
      text-align: center;
      position: relative;
    }

    .enrollment-header:after {
      content: "";
      display: block;
      width: 80px;
      height: 4px;
      background-color: #BD0F03;
      margin: 15px auto 0;
      border-radius: 2px;
    }

    .enrollment-footer {
      background-color: #f8f8f8;
      padding: 30px;
      border-radius: 8px;
      margin-top: 40px;
      text-align: center;
    }

    .enrollment-footer h3 {
      color: #BD0F03;
      margin-bottom: 15px;
    }

    .enrollment-footer p {
      color: #555;
    }

    .contact-info {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-top: 20px;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .contact-icon {
      color: #BD0F03;
      font-size: 20px;
    }

    @media (max-width: 768px) {
      .step-card {
        padding: 20px 15px 20px 20px;
      }
      
      .step-number {
        width: 35px;
        height: 35px;
        font-size: 16px;
        top: -12px;
        left: -12px;
      }
      
      .banner {
        font-size: 24px;
        padding: 20px;
      }
      
      .enrollment-header {
        font-size: 24px;
      }
      
      .contact-info {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <Title>Enrollment Procedure - WMSU</Title>
</head>

<section class="header"><?php require_once '../../__includes/navbar.php'?></section>

<div class="breadcrumb-container">
  <div class="relative z-10">
    <?php require_once '../../__includes/subnav_academics.php' ?>
  </div>
</div>

<main>
  <div class="enrollment-container">
    <div class="banner">Enrollment Procedure</div>
    
    <p class="intro-text">
      The Enrollment Procedures outline all the essential steps, requirements, and guidelines to ensure a seamless registration process. 
      Carefully review each section to complete your enrollment smoothly and meet all necessary deadlines.
    </p>
    
    <h2 class="enrollment-header">New Students</h2>
    
    <div class="steps-container">
      <!-- Step 1 -->
      <div class="step-card">
        <div class="step-number">1</div>
        <div class="step-content">
          <div class="step-title">Payment of Medical Examination Fee</div>
          <div class="step-description">
            Pay the necessary Fee for the Physical Medical Examination at the WMSU Cashier's Office, Ground Floor Administration Building, WMSU.
          </div>
        </div>
      </div>
      
      <!-- Step 2 -->
      <div class="step-card">
        <div class="step-number">2</div>
        <div class="step-content">
          <div class="step-title">Physical/Medical Examination</div>
          <div class="step-description">
            Proceed to the University Health Center, WMSU Main Campus and inquire about necessary requirements for Physical/Medical Examination and have your PME. When done, secure PME certification.
          </div>
        </div>
      </div>
      
      <!-- Step 3 -->
      <div class="step-card">
        <div class="step-number">3</div>
        <div class="step-content">
          <div class="step-title">College Interview</div>
          <div class="step-description">
            Go to the College of your choice for the interview (if needed) and/or enrollment on the appointed date. If you get disqualified in the first course you chose, you may proceed to the next college of your choice, provided however, you meet all requirements of your next chosen college.
          </div>
        </div>
      </div>
      
      <!-- Step 4 -->
      <div class="step-card">
        <div class="step-number">4</div>
        <div class="step-content">
          <div class="step-title">Complete Enrollment</div>
          <div class="step-description">
            Go to the college where you qualify and enroll. Follow the college-specific enrollment procedures to complete your registration.
          </div>
        </div>
      </div>
    </div>
    
    <div class="enrollment-footer">
      <h3>Need Assistance?</h3>
      <p>If you have any questions or need help with the enrollment process, please contact us:</p>
      <div class="contact-info">
        <div class="contact-item">
          <i class="fas fa-phone-alt contact-icon"></i>
          <span>(062) 991-1040</span>
        </div>
        <div class="contact-item">
          <i class="fas fa-envelope contact-icon"></i>
          <span>admissions@wmsu.edu.ph</span>
        </div>
        <div class="contact-item">
          <i class="fas fa-map-marker-alt contact-icon"></i>
          <span>Normal Road, Baliwasan, Zamboanga City</span>
        </div>
      </div>
    </div>
  </div>
</main>