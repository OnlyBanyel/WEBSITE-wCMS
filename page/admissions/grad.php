<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updated Sign Up Form</title>
    <link rel="stylesheet" href="med_form.css">
    <style>
        body {
            background: url('imgs/Admin-Office2.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            position: relative;
        }
        
        body::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(179, 33, 52, 0.6);
            z-index: -1;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            width: 800px;
            max-width: 90%;
            position: relative;
        }
        
        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            counter-reset: step;
        }
        
        .step {
            width: 100%;
            text-align: center;
            position: relative;
        }
        
        .step:before {
            content: counter(step);
            counter-increment: step;
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: inline-block;
            background: lightgray;
            border-radius: 50%;
            color: white;
            font-weight: bold;
        }
        
        .active:before {
            background: #B32134;
        }
        
        .signup-form h2 {
            text-align: center;
            color: #B32134;
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        
        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            font-weight: bold;
            color: #7C0A02;
            margin-bottom: 5px;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #BD0F03;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .submit-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #B32134;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }
        
        .submit-btn:hover {
            background: #7C0A02;
        }
        
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="signup-form">
            <h2>Sign Up</h2>
        <div class="progress-bar">
            <div class="step active">Student Profile</div>
            <div class="step">Confirm Details</div>
            <div class="step">Verification</div>
        </div>

            
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" placeholder="Surname, First Name, Last Name">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" placeholder="Enter Address">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="Enter Email Address">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" placeholder="Enter Phone Number">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Certificate of Registration (COR)</label>
                    <input type="file">
                </div>
                <div class="form-group">
                    <label>Medical Certificate</label>
                    <input type="file">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Transcript of Records (TOR)</label>
                    <input type="file">
                </div>
                <div class="form-group">
                    <label>Picture</label>
                    <input type="file">
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Save & Continue</button>
        </form>
    </div>
</body>
</html>