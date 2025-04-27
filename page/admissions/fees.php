<head>
  <?php require_once "../../__includes/head.php"; ?>
  <style>
    /* Custom styles for online registration page */
    .registration-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
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

    .registration-steps {
      margin-bottom: 50px;
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
      font-size: 20px;
      font-weight: bold;
      color: #BD0F03;
      margin-bottom: 15px;
    }

    .step-description {
      color: #333;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    .fees-section {
      margin-top: 50px;
    }

    .fees-header {
      color: #BD0F03;
      font-size: 28px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      position: relative;
    }

    .fees-header:after {
      content: "";
      display: block;
      width: 80px;
      height: 4px;
      background-color: #BD0F03;
      margin: 15px auto 0;
      border-radius: 2px;
    }

    .fees-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    .fees-table th {
      background-color: #BD0F03;
      color: white;
      padding: 15px;
      text-align: left;
    }

    .fees-table td {
      padding: 15px;
      border-bottom: 1px solid #eee;
    }

    .fees-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .fees-table tr:last-child td {
      border-bottom: none;
    }

    .fees-table tr:hover {
      background-color: #f0f0f0;
    }

    .fee-amount {
      font-weight: bold;
      color: #BD0F03;
    }

    .registration-cta {
      background-color: #f8f8f8;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      margin-top: 40px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .registration-cta h3 {
      color: #BD0F03;
      font-size: 24px;
      margin-bottom: 15px;
    }

    .registration-cta p {
      color: #555;
      margin-bottom: 25px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .btn-register {
      display: inline-block;
      background-color: #BD0F03;
      color: white;
      padding: 12px 30px;
      border-radius: 30px;
      font-weight: bold;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(189, 15, 3, 0.3);
    }

    .btn-register:hover {
      background-color: #8B0000;
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(189, 15, 3, 0.4);
    }

    .important-note {
      background-color: #fff3cd;
      border-left: 5px solid #ffc107;
      padding: 15px;
      margin-top: 30px;
      border-radius: 8px;
    }

    .important-note h4 {
      color: #856404;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .important-note p {
      color: #856404;
      margin-bottom: 0;
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
      
      .fees-header {
        font-size: 24px;
      }
      
      .fees-table th, 
      .fees-table td {
        padding: 10px;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <Title>Online Registration - WMSU</Title>
</head>

<section class="header"><?php require_once '../../__includes/navbar.php'?></section>
<div class="breadcrumb-container">
  <div class="relative z-10">
    <?php require_once '../../__includes/subnav_academics.php' ?>
  </div>
</div>

<main>
  <div class="registration-container">
    <div class="banner">Online Registration</div>
    
    <p class="intro-text">
      The Online Registration process provides a convenient and efficient way to enroll in your desired program. 
      Follow the outlined steps carefully to complete your registration smoothly and meet all necessary requirements and deadlines.
    </p>
    
    <div class="registration-steps">
      <!-- Step 1 -->
      <div class="step-card">
        <div class="step-number">1</div>
        <div class="step-content">
          <div class="step-title">Create an Account</div>
          <div class="step-description">
            Visit the WMSU Online Registration Portal and create a new account using your email address. You will receive a verification email to activate your account.
          </div>
        </div>
      </div>
      
      <!-- Step 2 -->
      <div class="step-card">
        <div class="step-number">2</div>
        <div class="step-content">
          <div class="step-title">Complete Your Profile</div>
          <div class="step-description">
            Fill in all required personal information, including your full name, contact details, and address. Make sure to provide accurate information as this will be used for your official records.
          </div>
        </div>
      </div>
      
      <!-- Step 3 -->
      <div class="step-card">
        <div class="step-number">3</div>
        <div class="step-content">
          <div class="step-title">Upload Required Documents</div>
          <div class="step-description">
            Scan and upload all required documents, including your transcript of records, diploma, birth certificate, and 2x2 ID picture. All documents should be clear and in PDF or JPG format.
          </div>
        </div>
      </div>
      
      <!-- Step 4 -->
      <div class="step-card">
        <div class="step-number">4</div>
        <div class="step-content">
          <div class="step-title">Select Your Program</div>
          <div class="step-description">
            Browse through the available programs and select your desired course. You can choose up to three program options in order of preference.
          </div>
        </div>
      </div>
      
      <!-- Step 5 -->
      <div class="step-card">
        <div class="step-number">5</div>
        <div class="step-content">
          <div class="step-title">Pay Registration Fee</div>
          <div class="step-description">
            Pay the non-refundable registration fee through the available payment methods. Keep your payment receipt as proof of transaction.
          </div>
        </div>
      </div>
      
      <!-- Step 6 -->
      <div class="step-card">
        <div class="step-number">6</div>
        <div class="step-content">
          <div class="step-title">Confirm Registration</div>
          <div class="step-description">
            Review all your information, submit your application, and wait for the confirmation email. You will receive updates on your application status through your registered email address.
          </div>
        </div>
      </div>
    </div>
    
    <div class="fees-section">
      <h2 class="fees-header">Registration Fees</h2>
      
      <table class="fees-table">
        <thead>
          <tr>
            <th>Fee Type</th>
            <th>Amount</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Application Fee</td>
            <td class="fee-amount">₱500.00</td>
            <td>Non-refundable fee for processing your application</td>
          </tr>
          <tr>
            <td>Entrance Examination Fee</td>
            <td class="fee-amount">₱300.00</td>
            <td>Fee for the university entrance examination</td>
          </tr>
          <tr>
            <td>Medical Examination Fee</td>
            <td class="fee-amount">₱250.00</td>
            <td>Fee for the required medical examination</td>
          </tr>
          <tr>
            <td>ID Processing Fee</td>
            <td class="fee-amount">₱150.00</td>
            <td>Fee for processing your student ID card</td>
          </tr>
          <tr>
            <td>Confirmation Fee</td>
            <td class="fee-amount">₱1,000.00</td>
            <td>Fee to confirm your enrollment (deductible from tuition)</td>
          </tr>
        </tbody>
      </table>
      
      <div class="important-note">
        <h4><i class="fas fa-exclamation-circle"></i> Important Note</h4>
        <p>All fees must be paid through authorized payment channels only. The university will not be responsible for payments made through unauthorized channels. Keep all payment receipts for future reference.</p>
      </div>
    </div>
    
    <div class="registration-cta">
      <h3>Ready to Begin Your Academic Journey?</h3>
      <p>Start your online registration process today and take the first step towards your future at Western Mindanao State University. Our dedicated support team is available to assist you throughout the registration process.</p>
      <a href="#" class="btn-register">Register Now <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</main>
<?php require_once '../../__includes/footer.php' ?>