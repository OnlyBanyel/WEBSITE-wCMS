<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undergraduate Registration - WMSU</title>
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
            <h2>Undergraduate Registration</h2>
            <p>Complete the form below to register for WMSU's undergraduate programs</p>
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
                    <span class="step-label">Program Selection</span>
                </div>
                <div class="step" data-step="3">
                    3
                    <span class="step-label">Verification</span>
                </div>
            </div>
        </div>
        
        <form class="signup-form" id="undergraduateForm">
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
                        <label for="birthdate">Date of Birth</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                            <option value="" disabled selected>Select your gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Prefer not to say</option>
                        </select>
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
            </div>
            
            <!-- Step 2: Program Selection (Hidden initially) -->
            <div class="form-step" data-step="2" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="college">College</label>
                        <select id="college" name="college" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                            <option value="" disabled selected>Select a college</option>
                            <option value="cas">College of Arts and Sciences</option>
                            <option value="coe">College of Engineering</option>
                            <option value="cba">College of Business Administration</option>
                            <option value="coe">College of Education</option>
                            <option value="ccs">College of Computing Studies</option>
                            <option value="cnm">College of Nursing and Midwifery</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="program">Program</label>
                        <select id="program" name="program" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                            <option value="" disabled selected>Select a program</option>
                            <!-- Options will be populated based on college selection -->
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <select id="term" name="term" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                            <option value="" disabled selected>Select a term</option>
                            <option value="first-semester">First Semester</option>
                            <option value="second-semester">Second Semester</option>
                            <option value="summer">Summer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="academicYear">Academic Year</label>
                        <select id="academicYear" name="academicYear" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;">
                            <option value="" disabled selected>Select academic year</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="highSchool">High School Graduated</label>
                        <input type="text" id="highSchool" name="highSchool" placeholder="Enter your high school" required>
                    </div>
                    <div class="form-group">
                        <label for="yearGraduated">Year Graduated</label>
                        <input type="number" id="yearGraduated" name="yearGraduated" placeholder="Enter year graduated" min="2000" max="2023" required>
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
                <button type="submit" id="submitBtn" class="submit-btn" style="width: 48%; display: none;">Submit Application</button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('undergraduateForm');
            const steps = document.querySelectorAll('.form-step');
            const progressBar = document.getElementById('progress');
            const stepIndicators = document.querySelectorAll('.step');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            let currentStep = 1;
            const totalSteps = steps.length;
            
            // Program options by college
            const programOptions = {
                'cas': [
                    'Bachelor of Science in Biology',
                    'Bachelor of Science in Chemistry',
                    'Bachelor of Arts in English',
                    'Bachelor of Arts in Political Science',
                    'Bachelor of Science in Mathematics'
                ],
                'coe': [
                    'Bachelor of Science in Civil Engineering',
                    'Bachelor of Science in Electrical Engineering',
                    'Bachelor of Science in Mechanical Engineering',
                    'Bachelor of Science in Electronics Engineering'
                ],
                'cba': [
                    'Bachelor of Science in Accountancy',
                    'Bachelor of Science in Business Administration',
                    'Bachelor of Science in Hospitality Management',
                    'Bachelor of Science in Tourism Management'
                ],
                'coe': [
                    'Bachelor of Elementary Education',
                    'Bachelor of Secondary Education',
                    'Bachelor of Physical Education',
                    'Bachelor of Technology and Livelihood Education'
                ],
                'ccs': [
                    'Bachelor of Science in Computer Science',
                    'Bachelor of Science in Information Technology',
                    'Bachelor of Science in Information Systems'
                ],
                'cnm': [
                    'Bachelor of Science in Nursing',
                    'Bachelor of Science in Midwifery'
                ]
            };
            
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
            
            // Handle college selection change
            const collegeSelect = document.getElementById('college');
            const programSelect = document.getElementById('program');
            
            collegeSelect.addEventListener('change', function() {
                const college = this.value;
                programSelect.innerHTML = '<option value="" disabled selected>Select a program</option>';
                
                if (college && programOptions[college]) {
                    programOptions[college].forEach(program => {
                        const option = document.createElement('option');
                        option.value = program.toLowerCase().replace(/\s+/g, '-');
                        option.textContent = program;
                        programSelect.appendChild(option);
                    });
                    programSelect.disabled = false;
                } else {
                    programSelect.disabled = true;
                }
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
                reviewHTML += `<p><strong>Date of Birth:</strong> ${document.getElementById('birthdate').value}</p>`;
                reviewHTML += `<p><strong>Gender:</strong> ${document.getElementById('gender').options[document.getElementById('gender').selectedIndex].text}</p>`;
                
                // Documents
                reviewHTML += `<h4 style="color: var(--primary-dark); margin: 20px 0 10px;">Documents</h4>`;
                reviewHTML += `<p><strong>Certificate of Registration:</strong> ${document.getElementById('cor-file-name').textContent || 'Not uploaded'}</p>`;
                reviewHTML += `<p><strong>Medical Certificate:</strong> ${document.getElementById('medCert-file-name').textContent || 'Not uploaded'}</p>`;
                reviewHTML += `<p><strong>Transcript of Records:</strong> ${document.getElementById('tor-file-name').textContent || 'Not uploaded'}</p>`;
                reviewHTML += `<p><strong>ID Picture:</strong> ${document.getElementById('picture-file-name').textContent || 'Not uploaded'}</p>`;
                
                // Program Information
                const college = document.getElementById('college');
                const program = document.getElementById('program');
                const term = document.getElementById('term');
                const academicYear = document.getElementById('academicYear');
                
                reviewHTML += `<h4 style="color: var(--primary-dark); margin: 20px 0 10px;">Program Information</h4>`;
                reviewHTML += `<p><strong>College:</strong> ${college.options[college.selectedIndex]?.text || 'Not selected'}</p>`;
                reviewHTML += `<p><strong>Program:</strong> ${program.options[program.selectedIndex]?.text || 'Not selected'}</p>`;
                reviewHTML += `<p><strong>Term:</strong> ${term.options[term.selectedIndex]?.text || 'Not selected'}</p>`;
                reviewHTML += `<p><strong>Academic Year:</strong> ${academicYear.options[academicYear.selectedIndex]?.text || 'Not selected'}</p>`;
                reviewHTML += `<p><strong>High School:</strong> ${document.getElementById('highSchool').value}</p>`;
                reviewHTML += `<p><strong>Year Graduated:</strong> ${document.getElementById('yearGraduated').value}</p>`;
                
                reviewContent.innerHTML = reviewHTML;
            }
            
            // Next button click
            nextBtn.addEventListener('click', function() {
                const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
                const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
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
                alert('Application submitted successfully! You will receive a confirmation email shortly.');
                
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