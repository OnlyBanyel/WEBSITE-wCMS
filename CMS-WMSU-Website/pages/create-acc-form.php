<?php 
require_once "../classes/login.class.php";
require_once "../classes/account.class.php";
require_once "../classes/pages.class.php";
$accObj = new Accounts;
$pagesObj = new Pages;

$collegeData = $pagesObj->fetchColleges();


$_SESSION['account'] = '';

if ((isset($_POST['submit'])) && ($_SERVER['REQUEST_METHOD'] === 'POST')){
    if (empty($_POST['subpage_assigned'])){
        $_POST['subpage_assigned'] = '';
    }
    $accObj->cleanAccount($_POST['email'], $_POST['password'], $_POST['role'], $_POST['subpage_assigned']);

    $email = $accObj->email;
    $password = $accObj->password;
    $role = $accObj->role;
    $subpage_assigned = $accObj->subpage_assigned;

    $emailErr = errText(validateInput($email, "email"));
    $passwordErr = errText(validateInput($password, "text"));
    $roleErr = errText(validateInput($role, "number"));
    $subpage_assignedErr = errText(validateInput($subpage_assigned, "number"));

    if(empty($emailErr) && empty($passwordErr) && empty($roleErr) && empty($subpage_assignedErr) ){
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
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">role</label>
        <select name="role" id="role">
            <option value="1">Super Admin</option>
            <option value="2">Content Manager</option>
        </select>
      </div>
      <div id="contentmanagerpage-container"></div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <input type="submit" name="submit" class="btn btn-primary"></input>
    </form>
</div>


<select name="subpage_assigned" id="subpage_assigned">
    <?php foreach ($collegeData as $college){
        ?> <option value="<?php echo $college['subpageID'] ?>"> <?php echo $college['subPageName'] ?></option>

        <?php
    }
     ?>

</select>
<script>
      $(document).ready(function() {
        $("#role").change(function() {
            var selectedValue = $(this).val(); // Get selected value
            var container = $("#contentmanagerpage-container"); // Get the container div
            if (selectedValue == "2") {

                var subpageDropdown = `
                    <div class="mb-3">
                        <label for="subpage_assigned">Select Subpage:</label>
                        <select name="subpage_assigned" id="subpage_assigned">
                            <?php foreach ($collegeData as $college) { ?>
                                <option value="<?php echo $college['subpageID']; ?>">
                                    <?php echo $college['subPageName']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                `;
                container.append(subpageDropdown);
            }
        });
    });
</script>