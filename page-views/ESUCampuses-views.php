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

        /* REGION CARD STYLES (like screenshot) */
        .region-cards {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .region-card {
            position: relative;
            width: 420px;
            height: 180px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 2px solid transparent;
            transition: box-shadow 0.2s, border-color 0.2s;
            background: #eee;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .region-card.active {
            border-color: #c00000;
            box-shadow: 0 4px 16px rgba(192,0,0,0.10);
        }
        .region-card .region-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-size: cover;
            background-position: center;
            filter: brightness(0.92);
            z-index: 1;
        }
        .region-card .region-label {
            position: relative;
            z-index: 2;
            background: #f8e6e6;
            padding: 18px 0 12px 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #c00000;
            letter-spacing: 1px;
            border-top: 1px solid #f3cccc;
        }
        @media (max-width: 900px) {
            .region-cards { flex-direction: column; align-items: center; }
            .region-card { width: 98vw; max-width: 420px; }
        }
        /* Hide/show campus sections */
        .campus-sections { margin-bottom: 30px; }
        .campus-section { display: none; }
        .campus-section.active { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; }
        /* CAMPUS CARD STYLES */
        .campus-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 0 0 10px 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 160px;
        }
        .campus-header {
            padding: 18px 20px 8px 20px;
        }
        .campus-title {
            color: #c00000;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .campus-description {
            color: #555;
            font-size: 1rem;
        }
        .programs-button {
            width: 100%;
            padding: 10px 20px;
            background: #f8e6e6;
            border: none;
            border-top: 1px solid #e0c0c0;
            color: #c00000;
            font-weight: bold;
            cursor: pointer;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1rem;
        }
        .programs-button span { font-size: 1.3em; transition: transform 0.2s; }
        .programs-button.active span { transform: rotate(180deg); }
        .programs-content {
            display: none;
            padding: 10px 30px 0 30px;
        }
        .programs-content.active { display: block; }
        .programs-list { margin: 0; padding-left: 18px; }
        .programs-list li { margin-bottom: 6px; color: #444; }
    </style>
</head>
<body>
     <div class="relative z-10 subnav-container">
            <?php require_once '../../__includes/subnav_academics.php' ?>
        </div>
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

        <!-- REGION CARDS -->
        <div class="region-cards">
            <div class="region-card active" id="sibugay-card" onclick="expandRegion('sibugay')">
                <div class="region-bg" style="background-image:url('../../imgs/Admin-Office2.jpg');"></div>
                <div class="region-label">ZAMBOANGA SIBUGAY</div>
            </div>
            <div class="region-card" id="delsur-card" onclick="expandRegion('delsur')">
                <div class="region-bg" style="background-image:url('../../imgs/Admin-Office2.jpg');"></div>
                <div class="region-label">ZAMBOANGA DEL SUR</div>
            </div>
        </div>
        <!-- CAMPUS SECTIONS -->
        <div class="campus-sections">
            <div class="campus-section active" id="sibugay-section">
                <!-- ALL Sibugay campuses -->
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Alicia Campus</div>
                        <div class="campus-description">Extending WMSU's reach in Alicia, Zamboanga Sibugay.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Science in Agriculture (Crop Science)</li>
                            <li>Bachelor of Elementary Education</li>
                            <li>Batsilyer ng Sining sa Filipino</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Diplahan Campus</div>
                        <div class="campus-description">Serving Diplahan, Zamboanga Sibugay with quality education.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>Associate in Computer Technology major in Networking (ladderized to BSIT)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Ipil Campus</div>
                        <div class="campus-description">Located in Ipil, Zamboanga Sibugay, offering diverse programs.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>BS Computer Science</li>
                            <li>Bachelor of Secondary Education major in: English, Mathematics, Filipino</li>
                            <li>Associate in Computer Technology major in Application Development (ladderized to BSCS)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Naga Campus</div>
                        <div class="campus-description">Naga, Zamboanga Sibugay campus for local community needs.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Secondary Education major in Filipino</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Pagadian Campus</div>
                        <div class="campus-description">Pagadian, Zamboanga Sibugay campus for higher learning.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
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
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Tungawan Campus</div>
                        <div class="campus-description">Tungawan, Zamboanga Sibugay campus for accessible education.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="campus-section" id="delsur-section">
                <!-- ALL Del Sur campuses -->
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Curuan Campus</div>
                        <div class="campus-description">Curuan, Zamboanga del Sur campus for regional development.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>Batsilyer ng Sining sa Filipino</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Imelda Campus</div>
                        <div class="campus-description">Imelda, Zamboanga del Sur campus for higher education.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>BA Political Science</li>
                            <li>Bachelor of Elementary Education</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Mabuhay Campus</div>
                        <div class="campus-description">Mabuhay, Zamboanga del Sur campus for academic excellence.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>Bachelor of Secondary Education major in Mathematics</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Malangas Campus</div>
                        <div class="campus-description">Malangas, Zamboanga del Sur campus for community growth.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>BS Criminology</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Molave Campus</div>
                        <div class="campus-description">Molave, Zamboanga del Sur campus for future leaders.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>Bachelor of Secondary Education major in: English, Filipino, Social Studies</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Qintanga Campus</div>
                        <div class="campus-description">Qintanga, Zamboanga del Sur campus for accessible learning.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
                    <div class="programs-content">
                        <ul class="programs-list">
                            <li>Bachelor of Elementary Education</li>
                            <li>Associate in Computer Technology major in Application Development (Stand Alone)</li>
                        </ul>
                    </div>
                </div>
                <div class="campus-card">
                    <div class="campus-header">
                        <div class="campus-title">WMSU Siay Campus</div>
                        <div class="campus-description">Siay, Zamboanga del Sur campus for diverse programs.</div>
                    </div>
                    <button class="programs-button" onclick="togglePrograms(this)">Programs Available <span>▼</span></button>
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

    <script>
        function expandRegion(region) {
            // Single expand: only one region's campuses visible
            document.getElementById('sibugay-card').classList.remove('active');
            document.getElementById('delsur-card').classList.remove('active');
            document.getElementById('sibugay-section').classList.remove('active');
            document.getElementById('delsur-section').classList.remove('active');
            if(region==='sibugay') {
                document.getElementById('sibugay-card').classList.add('active');
                document.getElementById('sibugay-section').classList.add('active');
            } else {
                document.getElementById('delsur-card').classList.add('active');
                document.getElementById('delsur-section').classList.add('active');
            }
        }
        function togglePrograms(btn) {
            const content = btn.nextElementSibling;
            const arrow = btn.querySelector('span');
            // Close all other program dropdowns in this section
            btn.closest('.campus-section').querySelectorAll('.programs-content').forEach(el => {
                if(el!==content) el.classList.remove('active');
            });
            btn.closest('.campus-section').querySelectorAll('.programs-button').forEach(b => {
                if(b!==btn) b.classList.remove('active');
            });
            // Toggle current
            btn.classList.toggle('active');
            content.classList.toggle('active');
            arrow.textContent = content.classList.contains('active') ? '▲' : '▼';
        }
    </script>
</body>
</html>