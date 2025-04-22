<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Form - WMSU</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #BD0F03;
            --primary-dark: #8B0000;
            --primary-light: #ee948e;
            --secondary: #f5f5f5;
            --text-dark: #333333;
            --text-light: #777777;
            --white: #ffffff;
            --success: #28a745;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: url('imgs/Admin-Office2.jpg') no-repeat center center/cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            color: var(--text-dark);
        }
        
        body::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(189, 15, 3, 0.7);
            z-index: -1;
        }
        
        .container {
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 800px;
            max-width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h2 {
            color: var(--primary);
            font-size: 28px;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        .form-header h2::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }
        
        .form-header p {
            color: var(--text-light);
            font-size: 16px;
            margin-top: 20px;
        }
        
        .progress-container {
            margin-bottom: 40px;
        }
        
        .progress-bar {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 30px;
            counter-reset: step;
        }
        
        .progress-bar::before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 4px;
            width: 100%;
            background-color: #e0e0e0;
            z-index: 1;
        }
        
        .progress-bar .progress {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 4px;
            width: 0%;
            background-color: var(--primary);
            z-index: 2;
            transition: var(--transition);
        }
        
        .step {
            width: 40px;
            height: 40px;
            background-color: #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--text-light);
            position: relative;
            z-index: 3;
            transition: var(--transition);
        }
        
        .step.active {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .step-label {
            position: absolute;
            top: 45px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
            color: var(--text-light);
            white-space: nowrap;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(189, 15, 3, 0.1);
            outline: none;
        }
        
        .form-group input::placeholder {
            color: #aaa;
        }
        
        .form-group input[type="file"] {
            padding: 10px;
            background-color: #f8f8f8;
            border: 1px dashed #ddd;
            cursor: pointer;
        }
        
        .form-group input[type="file"]:hover {
            background-color: #f0f0f0;
            border-color: #ccc;
        }
        
        .file-input-group {
            position: relative;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background-color: #f8f8f8;
            border: 1px dashed #ddd;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .file-input-label:hover {
            background-color: #f0f0f0;
            border-color: #ccc;
        }
        
        .file-input-label i {
            color: var(--primary);
            font-size: 18px;
        }
        
        .file-input-label span {
            font-size: 14px;
            color: var(--text-light);
        }
        
        .file-input {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }
        
        .file-name {
            margin-top: 8px;
            font-size: 14px;
            color: var(--primary);
            display: none;
        }
        
        .submit-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(189, 15, 3, 0.2);
        }
        
        .submit-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(189, 15, 3, 0.3);
        }
        
        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 6px rgba(189, 15, 3, 0.2);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .step-label {
                display: none;
            }
            
            .progress-bar {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2>Medical Form</h2>
            <p>Please complete all required information for your medical records</p>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" id="progress" style="width: 0%"></div>
                <div class="step active" data-step="1">
                    1
                    <span class="step-label">Student Profile</span>
                </div>
                <div class="step" data-step="2">
                    2
                    <span class="step-label">Medical Info</span>
                </div>
                <div class="step" data-step="3">
                    3
                    <span class="step-label">Verification</span>
                </div>
            </div>
        </div>
        
        <form class="signup-form" id="medicalForm">
            <!-- Step 1: Student Profile -->
            <div class="form-step" data-step="1">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" placeholder="Surname, First Name, Middle Name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Complete Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter your complete address" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cor">Certificate of Registration (COR)</label>
                        <div class="file-input-group">
                            <label for="cor" class="file-input-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="cor" name="cor" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-name" id="cor-file-name"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="medCert">Medical Certificate</label>
                        <div class="file-input-group">
                            <label for="medCert" class="file-input-label">
                                <i class="fas fa-file-medical"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="medCert" name="medCert" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-name" id="medCert-file-name"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tor">Transcript of Records (TOR)</label>
                        <div class="file-input-group">
                            <label for="tor" class="file-input-label">
                                <i class="fas fa-file-alt"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="tor" name="tor" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-name" id="tor-file-name"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="picture">ID Picture</label>
                        <div class="file-input-group">
                            <label for="picture" class="file-input-label">
                                <i class="fas fa-image"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="picture" name="picture" class="file-input" accept=".jpg,.jpeg,.png" required>
                            <div class="file-name" id="picture-file-name"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="course">Graduated Course</label>
                        <input type="text" id="course" name="course" placeholder="Enter your graduated course" required>
                    </div>
                    <div class="form-group">
                        <label for="school">Last School Attended</label>
                        <input type="text" id="school" name="school" placeholder="Enter your last school attended" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 2: Medical Information (Hidden initially) -->
            <div class="form-step" data-step="2" style="display: none;">
                <!-- Medical information fields will be added here -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="bloodType">Blood Type</label>
                        <input type="text" id="bloodType" name="bloodType" placeholder="Enter your blood type" required>
                    </div>
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" id="height" name="height" placeholder="Enter your height in cm" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" placeholder="Enter your weight in kg" required>
                    </div>
                    <div class="form-group">
                        <label for="allergies">Allergies (if any)</label>
                        <input type="text" id="allergies" name="allergies" placeholder="List any allergies">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="medications">Current Medications</label>
                        <input type="text" id="medications" name="medications" placeholder="List any current medications">
                    </div>
                    <div class="form-group">
                        <label for="conditions">Medical Conditions</label>
                        <input type="text" id="conditions" name="conditions" placeholder="List any medical conditions">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="emergencyContact">Emergency Contact Name</label>
                        <input type="text" id="emergencyContact" name="emergencyContact" placeholder="Enter emergency contact name" required>
                    </div>
                    <div class="form-group">
                        <label for="emergencyPhone">Emergency Contact Phone</label>
                        <input type="tel" id="emergencyPhone" name="emergencyPhone" placeholder="Enter emergency contact phone" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Verification (Hidden initially) -->
            <div class="form-step" data-step="3" style="display: none;">
                <div class="form-row">
                    <div class="form-group" style="text-align: center;">
                        <h3 style="color: var(--primary); margin-bottom: 20px;">Review Your Information</h3>
                        <p style="margin-bottom: 20px;">Please review all the information you've provided before submitting.</p>
                        
                        <div style="text-align: left; margin-bottom: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                            <div id="review-content">
                                <!-- Will be populated dynamically -->
                            </div>
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <input type="checkbox" id="agreement" name="agreement" required style="width: auto;">
                                <span>I certify that all information provided is true and correct.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="buttons-container" style="display: flex; justify-content: space-between; margin-top: 30px;">
                <button type="button" id="prevBtn" class="submit-btn" style="background-color: #6c757d; width: 48%; display: none;">Previous</button>
                <button type="button" id="nextBtn" class="submit-btn" style="width: 100%;">Next Step</button>
                <button type="submit" id="submitBtn" class="submit-btn" style="width: 48%; display: none;">Submit Form</button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('medicalForm');
            const steps = document.querySelectorAll('.form-step');
            const progressBar = document.getElementById('progress');
            const stepIndicators = document.querySelectorAll('.step');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            let currentStep = 1;
            const totalSteps = steps.length;
            
            // Handle file input changes
            const fileInputs = document.querySelectorAll('.file-input');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0]?.name;
                    const fileNameElement = document.getElementById(`${this.id}-file-name`);
                    
                    if (fileName) {
                        fileNameElement.textContent = fileName;
                        fileNameElement.style.display = 'block';
                    } else {
                        fileNameElement.style.display = 'none';
                    }
                });
            });
            
            // Update progress bar
            function updateProgress() {
                const percent = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressBar.style.width = `${percent}%`;
                
                // Update step indicators
                stepIndicators.forEach((step, index) => {
                    if (index + 1 < currentStep) {
                        step.classList.add('active');
                    } else if (index + 1 === currentStep) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
                
                // Show/hide buttons
                if (currentStep === 1) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.width = '100%';
                } else {
                    prevBtn.style.display = 'block';
                    nextBtn.style.width = '48%';
                }
                
                if (currentStep === totalSteps) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'block';
                    populateReview();
                } else {
                    nextBtn.style.display = 'block';
                    submitBtn.style.display = 'none';
                }
            }
            
            // Show current step
            function showStep(step) {
                steps.forEach(s => {
                    s.style.display = 'none';
                });
                document.querySelector(`.form-step[data-step="${step}"]`).style.display = 'block';
            }
            
            // Populate review content
            function populateReview() {
                const reviewContent = document.getElementById('review-content');
                let reviewHTML = '';
                
                // Personal Information
                reviewHTML += `<h4 style="color: var(--primary-dark); margin-bottom: 10px;">Personal Information</h4>`;
                reviewHTML += `<p><strong>Name:</strong> ${document.getElementById('fullName').value}</p>`;
                reviewHTML += `<p><strong>Email:</strong> ${document.getElementById('email').value}</p>`;
                reviewHTML += `<p><strong>Phone:</strong> ${document.getElementById('phone').value}</p>`;
                reviewHTML += `<p><strong>Address:</strong> ${document.getElementById('address').value}</p>`;
                reviewHTML += `<p><strong>Graduated Course:</strong> ${document.getElementById('course').value}</p>`;
                reviewHTML += `<p><strong>Last School:</strong> ${document.getElementById('school').value}</p>`;
                
                // Documents
                reviewHTML += `<h4 style="color: var(--primary-dark); margin: 20px 0 10px;">Documents</h4>`;
                reviewHTML += `<p><strong>Certificate of Registration:</strong> ${document.getElementById('cor-file-name').textContent || 'Not uploaded'}</p>`;
                reviewHTML += `<p><strong>Medical Certificate:</strong> ${document.getElementById('medCert-file-name').textContent || 'Not uploaded'}</p>`;
                reviewHTML += `<p><strong>Transcript of Records:</strong> ${document.getElementById('tor-file-name').textContent || 'Not uploaded'}</p>`;
                reviewHTML += `<p><strong>ID Picture:</strong> ${document.getElementById('picture-file-name').textContent || 'Not uploaded'}</p>`;
                
                // Medical Information
                reviewHTML += `<h4 style="color: var(--primary-dark); margin: 20px 0 10px;">Medical Information</h4>`;
                reviewHTML += `<p><strong>Blood Type:</strong> ${document.getElementById('bloodType').value}</p>`;
                reviewHTML += `<p><strong>Height:</strong> ${document.getElementById('height').value} cm</p>`;
                reviewHTML += `<p><strong>Weight:</strong> ${document.getElementById('weight').value} kg</p>`;
                reviewHTML += `<p><strong>Allergies:</strong> ${document.getElementById('allergies').value || 'None'}</p>`;
                reviewHTML += `<p><strong>Current Medications:</strong> ${document.getElementById('medications').value || 'None'}</p>`;
                reviewHTML += `<p><strong>Medical Conditions:</strong> ${document.getElementById('conditions').value || 'None'}</p>`;
                reviewHTML += `<p><strong>Emergency Contact:</strong> ${document.getElementById('emergencyContact').value}</p>`;
                reviewHTML += `<p><strong>Emergency Phone:</strong> ${document.getElementById('emergencyPhone').value}</p>`;
                
                reviewContent.innerHTML = reviewHTML;
            }
            
            // Next button click
            nextBtn.addEventListener('click', function() {
                const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
                const inputs = currentStepElement.querySelectorAll('input[required]');
                let isValid = true;
                
                // Validate required fields
                inputs.forEach(input => {
                    if (!input.value && input.type !== 'file') {
                        isValid = false;
                        input.style.borderColor = 'red';
                    } else if (input.type === 'file' && !input.files[0]) {
                        isValid = false;
                        input.previousElementSibling.style.borderColor = 'red';
                    } else {
                        if (input.type === 'file') {
                            input.previousElementSibling.style.borderColor = '#ddd';
                        } else {
                            input.style.borderColor = '#ddd';
                        }
                    }
                });
                
                if (isValid) {
                    currentStep++;
                    showStep(currentStep);
                    updateProgress();
                } else {
                    alert('Please fill in all required fields.');
                }
            });
            
            // Previous button click
            prevBtn.addEventListener('click', function() {
                currentStep--;
                showStep(currentStep);
                updateProgress();
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!document.getElementById('agreement').checked) {
                    alert('Please agree to the certification statement.');
                    return;
                }
                
                // Here you would typically send the form data to the server
                alert('Form submitted successfully! You will receive a confirmation email shortly.');
                
                // Reset form
                form.reset();
                currentStep = 1;
                showStep(currentStep);
                updateProgress();
                
                // Reset file name displays
                document.querySelectorAll('.file-name').forEach(el => {
                    el.style.display = 'none';
                    el.textContent = '';
                });
            });
            
            // Initialize
            showStep(currentStep);
            updateProgress();
        });
    </script>
</body>
</html>