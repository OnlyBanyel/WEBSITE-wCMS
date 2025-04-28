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
    if($_POST['role'] == 1){
        $accObj->cleanAccount($_POST['email'], $_POST['password'], $_POST['role'], null);
    }else{
    $accObj->cleanAccount($_POST['email'], $_POST['password'], $_POST['role'], $_POST['subpage_assigned']);
    $subpage_assigned = $accObj->subpage_assigned;
    $subpage_assignedErr = errText(validateInput($subpage_assigned, "number"));
    }

    $email = $accObj->email;
    $password = $accObj->password;
    $role = $accObj->role;


    $emailErr = errText(validateInput($email, "email"));
    $passwordErr = errText(validateInput($password, "text"));
    $roleErr = errText(validateInput($role, "number"));

    if($_POST['role'] == 1){
        if(empty($emailErr) && empty($passwordErr) && empty($roleErr)){
            $accObj->addAdminAccount();
            header("Location: login-form.php");
            exit;
    }}
    else{
    if(empty($emailErr) && empty($passwordErr) && empty($roleErr) && empty($subpage_assignedErr) ){
    $accObj->addAccount();
    header("Location: login-form.php");
    exit;
    }    
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../__includes/head.php' ?>
    <title>Create Account - WMSU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wmsu: {
                            red: '#BD0F03',
                            darkred: '#8B0000',
                            lightred: 'rgba(189, 15, 3, 0.1)'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles that can't be handled by Tailwind */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 md:p-8">
    <?php if(!empty($emailErr) || !empty($passwordErr) || !empty($roleErr) || !empty($subpage_assignedErr)){ ?>
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 w-full max-w-md bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-md" role="alert">
            <?php if(!empty($emailErr)) echo "<div class='mb-1'>$emailErr</div>"; ?>
            <?php if(!empty($passwordErr)) echo "<div class='mb-1'>$passwordErr</div>"; ?>
            <?php if(!empty($roleErr)) echo "<div class='mb-1'>$roleErr</div>"; ?>
            <?php if(!empty($subpage_assignedErr)) echo "<div>$subpage_assignedErr</div>"; ?>
        </div>
    <?php } ?>

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden relative">
        <!-- Red accent bar -->
        <div class="h-1.5 bg-wmsu-red w-full absolute top-0 left-0"></div>
        
        <div class="p-6 md:p-8">
            <!-- Form header -->
            <div class="text-center mb-8">
                <img src="../../imgs/WMSU-Logo.png" alt="WMSU Logo" class="h-16 mx-auto mb-4">
                <h2 class="text-2xl font-bold text-wmsu-red mb-1">Create Account</h2>
                <p class="text-gray-600 text-sm">Enter your details to create a new account</p>
            </div>
            
            <form method="POST" action="#" class="space-y-5">
                <!-- Email field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-wmsu-red/30 focus:border-wmsu-red outline-none transition-colors" required>
                    <p class="mt-1 text-xs text-gray-500">We'll never share your email with anyone else.</p>
                </div>
                
                <!-- Password field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-wmsu-red/30 focus:border-wmsu-red outline-none transition-colors" required>
                </div>
                
                <!-- Role selection -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-wmsu-red/30 focus:border-wmsu-red outline-none transition-colors form-select appearance-none" required>
                        <option value="1">Super Admin</option>
                        <option value="2">Content Manager</option>
                    </select>
                </div>
                
                <!-- Container for subpage selection (dynamically populated) -->
                <div id="contentmanagerpage-container"></div>
                
                <!-- Terms checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" id="terms" class="h-4 w-4 text-wmsu-red border-gray-300 rounded focus:ring-wmsu-red">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">I agree to the terms and conditions</label>
                </div>
                
                <!-- Submit button -->
                <button type="submit" name="submit" class="w-full bg-wmsu-red hover:bg-wmsu-darkred text-white font-medium py-2.5 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-wmsu-red focus:ring-offset-2">
                    Create Account
                </button>
                
                <!-- Form footer -->
                <div class="text-center text-sm text-gray-600 mt-4">
                    Already have an account? <a href="login-form.php" class="text-wmsu-red font-medium hover:underline">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#role").change(function() {
                var selectedValue = $(this).val(); // Get selected value
                var container = $("#contentmanagerpage-container"); // Get the container div
                container.empty(); // Clear previous content
                
                if (selectedValue == "2") {
                    var subpageDropdown = `
                        <div>
                            <label for="subpage_assigned" class="block text-sm font-medium text-gray-700 mb-1">Select College/Department:</label>
                            <select name="subpage_assigned" id="subpage_assigned" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-wmsu-red/30 focus:border-wmsu-red outline-none transition-colors form-select appearance-none" required>
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
</body>
</html>
