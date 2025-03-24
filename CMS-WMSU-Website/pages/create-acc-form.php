<?php 
session_start();
require_once "../classes/login.class.php";
require_once "../classes/account.class.php";
$accObj = new Accounts;



$_SESSION['account'] = '';

if ((isset($_POST['submit'])) && ($_SERVER['REQUEST_METHOD'] === 'POST')){
    $accObj->cleanAccount($_POST['email'], $_POST['password']);

    $email = $accObj->email;
    $password = $accObj->password;

    $emailErr = errText(validateInput($email, "email"));
    $passwordErr = errText(validateInput($password, "text"));

    if(empty($emailErr) && empty($passwordErr)){
    $accObj->addAccount();
    header("Location: login-form.php");
    exit;
    }
}
?>
<head>
<?php require_once '../__includes/head.php' ?>
<link rel="stylesheet" href="../css/login-form-style.css">
</head>

<?php if(!empty($UsernameErr) || !empty($PasswordErr) || !empty($LoginErr)){?>
                <div id="errfields" class="alert alert-danger" role="alert">
                        <span id="err">
                            <?php if(!empty($UsernameErr) && !empty($PasswordErr)){
                                echo $Errtxt;
                            } ?>
                        </span> 

                        <span id="err">
                            <?php if(!empty($UsernameErr) && empty($PasswordErr)){
                                echo $UsernameErr;
                            } ?>
                        </span> 

                        <span id="err">
                            <?php if(!empty($PasswordErr) && empty($UsernameErr)){
                                echo $PasswordErr;
                            } ?>
                        </span> 

                        <span id="err">
                            <?php if(!empty($LoginErr)){
                                echo $LoginErr;
                            } ?>
                        </span> 
                </div>
            <?php } ?>
<div class="form-container">
    <form method="POST" action="#">
      <div class="mb-3">
        <h2>Create Account</h2>
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <input type="submit" name="submit" class="btn btn-primary"></input>
    </form>
</div>