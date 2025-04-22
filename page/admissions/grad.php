<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduate Program Registration - WMSU</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #BD0F03;
            --primary-dark: #7C0A02;
            --primary-light: #E84A5F;
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
        }
        
        .form-header p {
            color: var(--text-light);
            font-size: 16px;
        }
        
        .progress-container {
            margin-bottom: 30px;
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
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
        }
        
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(189, 15, 3, 0.1);
            outline: none;
        }
        
        .form-group input::placeholder {
            color: #aaa;
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
        
        .buttons-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: #e0e0e0;
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background-color: #d0d0d0;
        }
        
        .btn-prev {
            display: none;
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
            <h2>Graduate Program Registration</h2>
            <p>Complete the form below to register for WMSU's graduate programs</p>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" id="progress"></div>
                <div class="step active" data-step="1">
                    1
                    <span class="step-label">Personal Info</span>
                </div>
                <div class="step" data-step="2">
                    2
                    <span class="step-label">Documents</span>
                </div>
                <div class="step" data-step="3">
                    3
                    <span class="step-label">Program</span>
                </div>
                <div class="step" data-step="4">
                    4
                    <span class="step-label">Review</span>
                </div>
            </div>
        </div>
        
        <form id="graduateForm">
            <!-- Step 1: Personal Information -->
            <div class="form-step" data-step="1">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="middleName">Middle Name</label>
                        <input type="text" id="middleName" name="middleName" placeholder="Enter your middle name">
                    </div>
                    <div class="form-group">
                        <label for="suffix">Suffix (if any)</label>
                        <input type="text" id="suffix" name="suffix" placeholder="E.g., Jr., Sr., III">
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
                        <label for="address">Complete Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter your complete address" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 2: Document Upload -->
            <div class="form-step" data-step="2" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="transcript">Transcript of Records (TOR)</label>
                        <div class="file-input-group">
                            <label for="transcript" class="file-input-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="transcript" name="transcript" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-name" id="transcript-file-name"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="diploma">Diploma</label>
                        <div class="file-input-group">
                            <label for="diploma" class="file-input-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="diploma" name="diploma" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-name" id="diploma-file-name"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="photo">2x2 ID Picture</label>
                        <div class="file-input-group">
                            <label for="photo" class="file-input-label">
                                <i class="fas fa-image"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="photo" name="photo" class="file-input" accept=".jpg,.jpeg,.png" required>
                            <div class="file-name" id="photo-file-name"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="certificate">Certificate of Employment (if applicable)</label>
                        <div class="file-input-group">
                            <label for="certificate" class="file-input-label">
                                <i class="fas fa-file-pdf"></i>
                                <span>Choose a file or drag it here</span>
                            </label>
                            <input type="file" id="certificate" name="certificate" class="file-input" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="file-name" id="certificate-file-name"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Program Selection -->
            <div class="form-step" data-step="3" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="program">Graduate Program</label>
                        <select id="program" name="program" required>
                            <option value="" disabled selected>Select a program</option>
                            <option value="ma-education">Master of Arts in Education</option>
                            <option value="ms-information-technology">Master of Science in Information Technology</option>
                            <option value="mba">Master of Business Administration</option>
                            <option value="mpa">Master of Public Administration</option>
                            <option value="ms-nursing">Master of Science in Nursing</option>
                            <option value="phd-education">PhD in Education</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="specialization">Specialization (if applicable)</label>
                        <select id="specialization" name="specialization">
                            <option value="" disabled selected>Select a specialization</option>
                            <!-- Options will be populated based on program selection -->
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <select id="term" name="term" required>
                            <option value="" disabled selected>Select a term</option>
                            <option value="first-semester">First Semester</option>
                            <option value="second-semester">Second Semester</option>
                            <option value="summer">Summer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="academicYear">Academic Year</label>
                        <select id="academicYear" name="academicYear" required>
                            <option value="" disabled selected>Select academic year</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="previousDegree">Previous Degree</label>
                        <input type="text" id="previousDegree" name="previousDegree" placeholder="Enter your previous degree" required>
                    </div>
                    <div class="form-group">
                        <label for="institution">Institution</label>
                        <input type="text" id="institution" name="institution" placeholder="Enter the institution name" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 4: Review Information -->
            <div class="form-step" data-step="4" style="display: none;">
                <h3 style="color: var(--primary); margin-bottom: 20px; text-align: center;">Review Your Information</h3>
                
                <div id="review-content" style="margin-bottom: 30px;">
                    <!-- Will be populated dynamically -->
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="agreement">
                            <input type="checkbox" id="agreement" name="agreement" required style="width: auto; margin-right: 10px;">
                            I certify that all information provided is true and correct. I understand that any false information may result in the rejection of my application.
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="buttons-container">
                <button type="button" class="btn btn-secondary btn-prev" id="prevBtn">Previous</button>
                <button type="button" class="btn btn-primary btn-next" id="nextBtn">Next</button>
                <button type="submit" class="btn btn-primary btn-submit" id="submitBtn" style="display: none;">Submit Application</button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('graduateForm');
            const steps = document.querySelectorAll('.form-step');
            const progressBar = document.getElementById('progress');
            const stepIndicators = document.querySelectorAll('.step');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            let currentStep = 1;
            const totalSteps = steps.length;
            
            // Initialize specialization options
            const specializationOptions = {
                'ma-education': [
                    'Educational Management',
                    'Mathematics Education',
                    'Science Education',
                    'English Language Teaching',
                    'Special Education'
                ],
                'ms-information-technology': [
                    'Data Science',
                    'Cybersecurity',
                    'Software Engineering',
                    'Network Administration'
                ],
                'mba': [
                    'Finance',
                    'Marketing',
                    'Human Resource Management',
                    'Operations Management'
                ],
                'mpa': [
                    'Public Policy',
                    'Local Governance',
                    'Development Administration'
                ],
                'ms-nursing': [
                    'Nursing Administration',
                    'Clinical Nursing',
                    'Nursing Education'
                ],
                'phd-education': [
                    'Educational Leadership',
                    'Curriculum and Instruction',
                    'Educational Psychology',
                    'Educational Technology'
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
            
            // Handle program selection change
            const programSelect = document.getElementById('program');
            const specializationSelect = document.getElementById('specialization');
            
            programSelect.addEventListener('change', function() {
                const program = this.value;
                specializationSelect.innerHTML = '<option value="" disabled selected>Select a specialization</option>';
                
                if (program && specializationOptions[program]) {
                    specializationOptions[program].forEach(spec => {
                        const option = document.createElement('option');
                        option.value = spec.toLowerCase().replace(/\s+/g, '-');
                        option.textContent = spec;
                        specializationSelect.appendChild(option);
                    });
                    specializationSelect.disabled = false;
                } else {
                    specializationSelect.disabled = true;
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
                } else {
                    prevBtn.style.display = 'block';
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
                const formData = new FormData(form);
                let reviewHTML = '';
                
                // Personal Information
                reviewHTML += `<div class="review-section">
                    <h4 style="color: var(--primary-dark); margin-bottom: 10px;">Personal Information</h4>
                    <p><strong>Name:</strong> ${formData.get('firstName')} ${formData.get('middleName')} ${formData.get('lastName')} ${formData.get('suffix') || ''}</p>
                    <p><strong>Email:</strong> ${formData.get('email')}</p>
                    <p><strong>Phone:</strong> ${formData.get('phone')}</p>
                    <p><strong>Address:</strong> ${formData.get('address')}</p>
                </div>`;
                
                // Documents
                reviewHTML += `<div class="review-section" style="margin-top: 20px;">
                    <h4 style="color: var(--primary-dark); margin-bottom: 10px;">Documents</h4>
                    <p><strong>Transcript of Records:</strong> ${document.getElementById('transcript-file-name').textContent || 'Not uploaded'}</p>
                    <p><strong>Diploma:</strong> ${document.getElementById('diploma-file-name').textContent || 'Not uploaded'}</p>
                    <p><strong>ID Picture:</strong> ${document.getElementById('photo-file-name').textContent || 'Not uploaded'}</p>
                    <p><strong>Certificate of Employment:</strong> ${document.getElementById('certificate-file-name').textContent || 'Not uploaded'}</p>
                </div>`;
                
                // Program Information
                const program = document.getElementById('program');
                const specialization = document.getElementById('specialization');
                const term = document.getElementById('term');
                const academicYear = document.getElementById('academicYear');
                
                reviewHTML += `<div class="review-section" style="margin-top: 20px;">
                    <h4 style="color: var(--primary-dark); margin-bottom: 10px;">Program Information</h4>
                    <p><strong>Program:</strong> ${program.options[program.selectedIndex]?.text || 'Not selected'}</p>
                    <p><strong>Specialization:</strong> ${specialization.options[specialization.selectedIndex]?.text || 'Not applicable'}</p>
                    <p><strong>Term:</strong> ${term.options[term.selectedIndex]?.text || 'Not selected'}</p>
                    <p><strong>Academic Year:</strong> ${academicYear.options[academicYear.selectedIndex]?.text || 'Not selected'}</p>
                    <p><strong>Previous Degree:</strong> ${formData.get('previousDegree')}</p>
                    <p><strong>Institution:</strong> ${formData.get('institution')}</p>
                </div>`;
                
                reviewContent.innerHTML = reviewHTML;
            }
            
            // Next button click
            nextBtn.addEventListener('click', function() {
                const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
                const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
                let isValid = true;
                
                // Validate required fields
                inputs.forEach(input => {
                    if (!input.value) {
                        isValid = false;
                        input.style.borderColor = 'red';
                    } else {
                        input.style.borderColor = '#ddd';
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