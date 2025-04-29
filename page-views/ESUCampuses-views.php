<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WMSU External Studies Units</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
        }

        h1 {
            color: #c00000;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        h2 {
            color: #c00000;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        h3 {
            color: #c00000;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .header-underline {
            width: 100px;
            height: 4px;
            background-color: #c00000;
            margin: 0 auto 20px;
        }

        .description {
            max-width: 900px;
            margin: 0 auto 30px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Updated mission-vision styles without icons */
        .mission-vision-container {
            max-width: 900px;
            margin: 0 auto 60px;
            display: flex;
            flex-direction: column;
            gap: 40px;
            position: relative;
        }

        .mission-box, .vision-box {
            position: relative;
        }

        .mission-vision-label {
            position: relative;
            z-index: 2;
            margin-bottom: -20px;
            margin-left: 20px;
        }

        .label-text {
            background-color: #c00000;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            padding: 10px 10px;
            border-radius: 30px;
            text-transform: uppercase;
            display: inline-block;
        }

        .mission-vision-content {
            background-color:rgb(231, 219, 219);
            border-radius: 15px;
            padding: 30px 25px 20px 25px;
            margin-left: 40px;
        }

        .mission-vision-content p {
            color: #333;
            font-size: 1rem;
            line-height: 1.5;
        }

        /* End of updated mission-vision styles */

        .columns {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .column {
            flex: 1;
            min-width: 300px;
        }

        .column-title {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #c00000;
            margin-bottom: 20px;
        }

        .campus-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .campus-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .campus-image {
            height: 200px;
            background-color: #ddd;
            background-image: url('../../imgs/Admin-Office2.jpg');
            background-size: cover;
            background-position: center;
        }

        .campus-header {
            background-color: #f8e6e6;
            padding: 15px;
        }

        .campus-title {
            color: #c00000;
            margin-bottom: 5px;
        }

        .campus-description {
            color: #555;
            font-size: 0.95rem;
        }

        .campus-content {
            padding: 15px;
        }

        .programs-button {
            width: 100%;
            padding: 10px;
            background-color: #f8e6e6;
            border: 1px solid #e0c0c0;
            border-radius: 4px;
            color: #c00000;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .programs-button:hover {
            background-color: #f0d0d0;
        }

        .programs-content {
            display: none;
            margin-top: 15px;
            padding-left: 20px;
            border-left: 2px solid #e0c0c0;
        }

        .programs-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        .programs-list {
            list-style-type: disc;
            padding-left: 20px;
        }

        .programs-list li {
            margin-bottom: 5px;
            color: #444;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .columns {
                flex-direction: column;
            }
            
            .mission-vision-label {
                margin-left: 10px;
            }
            
            .label-text {
                padding: 8px 20px;
                font-size: 1.3rem;
            }
            
            .mission-vision-content {
                margin-left: 20px;
                padding: 25px 20px 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Western Mindanao State University</h1>
            <h2>External Studies Units (ESUs)</h2>
            <div class="header-underline"></div>
            <p class="description">
                Western Mindanao State University (WMSU) extends its educational services beyond its main campus through a
                network of External Studies Units (ESUs) strategically located across various provinces. These ESUs provide
                accessible higher education to students in remote and underserved areas.
            </p>
        </header>

        <!-- Updated mission-vision layout without icons -->
        <div class="mission-vision-container">
            <div class="vision-box">
                <div class="mission-vision-label">
                    <div class="label-text">Vision</div>
                </div>
                <div class="mission-vision-content">
                    <p>
                        To be the Center of Excellence and leading institution in human resource development and research in the
                        country and the ASEAN region with international recognition.
                    </p>
                </div>
            </div>
            
            <div class="mission-box">
                <div class="mission-vision-label">
                    <div class="label-text">Mission</div>
                </div>
                <div class="mission-vision-content">
                    <p>
                        To educate and produce well-trained, development-oriented, and forward-looking professional and technical
                        manpower for the socio-economic, political, and technological development of the Philippines. The university
                        endeavors to expand the frontiers of knowledge and its applications to society through research in
                        technology, natural resources, and the physical and social sciences.
                    </p>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <h2 class="column-title">ZAMBOANGA SIBUGAY</h2>
                
                <!-- Alicia Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Alicia Campus</h3>
                        <p class="campus-description">
                            Established to extend WMSU's educational reach within Zamboanga Sibugay, the Alicia ESU offers programs tailored to local community needs.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Science in Agriculture (Crop Science)</li>
                                <li>Bachelor of Elementary Education</li>
                                <li>Batsilyer ng Sining sa Filipino</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Diplahan Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Diplahan Campus</h3>
                        <p class="campus-description">
                            Established to extend WMSU's educational reach within Zamboanga Sibugay, the Alicia ESU offers programs tailored to local community needs.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>Associate in Computer Technology major in Networking (ladderized to BSIT)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Rest of Zamboanga Sibugay campuses remain the same -->
                <!-- Ipil Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Ipil Campus</h3>
                        <p class="campus-description">
                            Established to extend WMSU's educational reach within Zamboanga Sibugay, the Alicia ESU offers programs tailored to local community needs.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>BS Computer Science</li>
                                <li>Bachelor of Secondary Education major in: English, Mathematics, Filipino</li>
                                <li>Associate in Computer Technology major in Application Development (ladderized to BSCS)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Naga Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Naga Campus</h3>
                        <p class="campus-description">
                            Established to extend WMSU's educational reach within Zamboanga Sibugay, the Alicia ESU offers programs tailored to local community needs.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Secondary Education major in Filipino</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Pagadian Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Pagadian Campus</h3>
                        <p class="campus-description">
                            Established to extend WMSU's educational reach within Zamboanga Sibugay, the Alicia ESU offers programs tailored to local community needs.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>BA Political Science</li>
                                <li>Bachelor of Elementary Education</li>
                                <li>Bachelor of Secondary Education major in: English, Science</li>
                                <li>BS Criminology</li>
                                <li>BS Computer Science</li>
                                <li>BS Social Work</li>
                                <li>Associate in Computer Technology major in Application Development (ladderized to BSCS)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tungawan Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Tungawan Campus</h3>
                        <p class="campus-description">
                            Established to extend WMSU's educational reach within Zamboanga Sibugay, the Alicia ESU offers programs tailored to local community needs.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column">
                <h2 class="column-title">ZAMBOANGA DEL SUR</h2>
                
                <!-- Zamboanga Del Sur campuses remain the same -->
                <!-- Curuan Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Curuan Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>Batsilyer ng Sining sa Filipino</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Imelda Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Imelda Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>BA Political Science</li>
                                <li>Bachelor of Elementary Education</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Mabuhay Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Mabuhay Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>Bachelor of Secondary Education major in Mathematics</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Malangas Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Malangas Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>BS Criminology</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Molave Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Molave Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>Bachelor of Secondary Education major in: English, Filipino, Social Studies</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Qintanga Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Qintanga Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>Bachelor of Elementary Education</li>
                                <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Siay Campus -->
                <div class="campus-card">
                    <div class="campus-image"></div>
                    <div class="campus-header">
                        <h3 class="campus-title">WMSU Siay Campus</h3>
                        <p class="campus-description">
                            The Aurora ESU was developed to provide higher education opportunities in Zamboanga del Sur, focusing on courses relevant to the region's development.
                        </p>
                    </div>
                    <div class="campus-content">
                        <button class="programs-button" onclick="togglePrograms(this)">
                            Programs Available
                            <span>▼</span>
                        </button>
                        <div class="programs-content">
                            <ul class="programs-list">
                                <li>BA in Political Science</li>
                                <li>Bachelor of Elementary Education</li>
                                <li>BS Computer Science</li>
                                <li>Associate in Computer Technology major in Application Development (ladderized to BSCS)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePrograms(button) {
            const content = button.nextElementSibling;
            const arrow = button.querySelector('span');
            
            if (content.classList.contains('active')) {
                content.classList.remove('active');
                arrow.textContent = '▼';
            } else {
                content.classList.add('active');
                arrow.textContent = '▲';
            }
        }
    </script>
</body>
</html>