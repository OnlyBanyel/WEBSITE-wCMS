<?php 
session_start();
require_once "../classes/login.class.php";
$loginObj = new Login;

$_SESSION['account'] = '';

unset($_SESSION['lastRole']);

if ((isset($_POST['submit'])) && ($_SERVER['REQUEST_METHOD'] === 'POST')){
    $loginObj->clean();

    $email = $loginObj->email;
    $password = $loginObj->password;

    $emailErr = errText(validateInput($email, "email"));
    $passwordErr = errText(validateInput($password, "text"));

    
    if(empty($emailErr) && empty($passwordErr)){
      if ($loginObj->auth()){
        $_SESSION['account'] = $loginObj->fetchAccount();
        if ($_SESSION['account']['role_id'] == 2 && isset($_SESSION['account']['subpage_assigned'])){
            $subpage_assigned = $_SESSION['account']['subpage_assigned'];
            $_SESSION['subpageData'] = $loginObj->fetchSubpage($subpage_assigned);
            $_SESSION['collegeData'] = $loginObj->fetchCollegeData($subpage_assigned);
        }
        header('Location: dashboard.php');
        exit;}
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../__includes/head.php' ?>
    <title>Login - WMSU</title>
    <style>
        :root {
            --primary: #BD0F03;
            --primary-dark: #8B0000;
            --primary-light: rgba(189, 15, 3, 0.1);
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-800);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            margin: 0;
        }

        .form-container {
            background-color: var(--white);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--primary);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
        }

        .form-header img {
            max-width: 80px;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-700);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: var(--gray-700);
            background-color: var(--white);
            background-clip: padding-box;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(189, 15, 3, 0.25);
        }

        .form-text {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .form-check-input {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            background-color: var(--white);
            border: 1px solid var(--gray-400);
            border-radius: 0.25rem;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            margin-bottom: 0;
            font-size: 0.875rem;
            color: var(--gray-700);
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.5rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            cursor: pointer;
        }

        .btn-primary {
            color: var(--white);
            background-color: var(--primary);
            border-color: var(--primary);
            width: 100%;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline {
            color: var(--primary);
            background-color: transparent;
            border-color: var(--primary);
            width: 100%;
            margin-top: 0.5rem;
        }

        .btn-outline:hover {
            color: var(--white);
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .alert {
            position: relative;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.5rem;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-group .btn {
            flex: 1;
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php if(!empty($emailErr) || !empty($passwordErr)){ ?>
        <div id="errfields" class="alert alert-danger" role="alert">
            <?php if(!empty($emailErr)) echo "<div>$emailErr</div>"; ?>
            <?php if(!empty($passwordErr)) echo "<div>$passwordErr</div>"; ?>
        </div>
    <?php } ?>

    <div class="form-container">
        <div class="form-header flex flex-column justify-center items-center">
            <img src="../../imgs/WMSU-Logo.png" class="" alt="WMSU Logo">
            <h2>Login to WMSU</h2>
            <p>Enter your credentials to access your account</p>
        </div>
        
        <form method="POST" action="#">
            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary">Login</button>
            
            <div class="form-footer">
                Don't have an account? <a href="create-acc-form.php">Create one</a>
            </div>
        </form>
    </div>
</body>
</html>
