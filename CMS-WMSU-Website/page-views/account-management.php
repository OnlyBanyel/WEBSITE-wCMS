<?php
session_start();
require_once "../classes/account.class.php";

// Check if user is logged in
if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Get user ID from session
$userId = $_SESSION['account']['id'];

// Initialize Accounts class
$accountsObj = new Accounts();

// Fetch user data from database
$userData = $accountsObj->getUserData($userId);

// If user data couldn't be fetched, use session data as fallback
if (!$userData) {
    $userData = [
        'profileImg' => isset($_SESSION['account']['profileImg']) ? $_SESSION['account']['profileImg'] : null,
        'firstName' => isset($_SESSION['account']['firstName']) ? $_SESSION['account']['firstName'] : '',
        'lastName' => isset($_SESSION['account']['lastName']) ? $_SESSION['account']['lastName'] : '',
        'email' => isset($_SESSION['account']['email']) ? $_SESSION['account']['email'] : '',
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Override Bootstrap's primary color with our red theme */
        .bg-primary,
        .bg-primary.active,
        .bg-primary:not([class*="bg-opacity"]) {
            --tw-bg-opacity: 1 !important;
            --bs-bg-opacity: 1 !important;
            background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
        }
        
        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
            border-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
        }
        
        :root {
            --bs-primary: #BD0F03 !important;
            --bs-primary-rgb: 189, 15, 3 !important;
        }
        
        /* Custom styles for sections */
        .account-section {
            transition: all 0.3s ease;
        }
        
        .account-section:hover {
            box-shadow: 0 10px 25px -5px rgba(189, 15, 3, 0.1), 0 8px 10px -6px rgba(189, 15, 3, 0.1);
        }
        
        /* Profile picture upload area */
        .profile-upload-area {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            border: 3px solid #BD0F03;
        }
        
        .profile-upload-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-upload-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(189, 15, 3, 0.7);
            color: white;
            padding: 8px 0;
            text-align: center;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .profile-upload-overlay:hover {
            background: rgba(189, 15, 3, 0.9);
        }
        
        /* Form focus styles */
        input:focus, textarea:focus {
            border-color: #BD0F03 !important;
            --tw-ring-color: rgba(189, 15, 3, 0.5) !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto p-4 md:p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Account Management</h1>
            <p class="text-gray-600 mt-2">Update your personal information and account settings</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Profile Picture -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 account-section">
                    <h2 class="text-xl font-semibold text-primary mb-6">Profile Picture</h2>
                    
                    <!-- Important: No action attribute, let AJAX handle it -->
                    <form method="POST" id="profilePictureForm" enctype="multipart/form-data">
                        <input type="hidden" name="formType" value="profilePicture">
                        <div class="profile-upload-area mb-6">
                            <img id="profilePreview" src="<?php echo !empty($userData['profileImg']) ? $userData['profileImg'] : '/placeholder.svg?height=150&width=150'; ?>" alt="Profile Picture">
                            <div class="profile-upload-overlay" id="uploadOverlay">
                                <span>Change Photo</span>
                            </div>
                            <input type="file" name="profilePicture" id="profilePicture" class="hidden" accept="image/*">
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-4">Upload a new profile picture. JPG, PNG or GIF, max 5MB.</p>
                            <button type="submit" name="updateProfilePicture" id="updateProfilePicture" class="bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors">
                                Save Profile Picture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Column: Account Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Section -->
                <div class="bg-white rounded-xl shadow-md p-6 account-section">
                    <h2 class="text-xl font-semibold text-primary mb-6">Personal Information</h2>
                    
                    <form method="POST" id="personalInfoForm">
                        <input type="hidden" name="formType" value="personalInfo">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="firstName" id="firstName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo htmlspecialchars($userData['firstName']); ?>">
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="lastName" id="lastName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo htmlspecialchars($userData['lastName']); ?>">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" name="updatePersonalInfo" id="updatePersonalInfo" class="bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Email Section -->
                <div class="bg-white rounded-xl shadow-md p-6 account-section">
                    <h2 class="text-xl font-semibold text-primary mb-6">Email Address</h2>
                    
                    <form method="POST" id="emailForm">
                        <input type="hidden" name="formType" value="email">
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" value="<?php echo htmlspecialchars($userData['email']); ?>">
                            <p class="text-sm text-gray-500 mt-1">We'll send a verification link to your new email if changed.</p>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" name="updateEmail" id="updateEmail" class="bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors">
                                Update Email
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Password Section -->
                <div class="bg-white rounded-xl shadow-md p-6 account-section">
                    <h2 class="text-xl font-semibold text-primary mb-6">Change Password</h2>
                    
                    <form method="POST" id="passwordForm">
                        <input type="hidden" name="formType" value="password">
                        <div class="space-y-4 mb-6">
                            <div>
                                <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password" name="currentPassword" id="currentPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" name="newPassword" id="newPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" name="updatePassword" id="updatePassword" class="bg-primary hover:bg-primaryDark text-white px-4 py-2 rounded-md transition-colors">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Profile picture change functionality
        $("#uploadOverlay").click(function() {
            $("#profilePicture").click();
        });
        
        // Preview image when selected
        $("#profilePicture").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $("#profilePreview").attr("src", e.target.result);
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Debug form submission
        $("#profilePictureForm").on("submit", function() {
            console.log("Form submitted");
            console.log("File selected:", $("#profilePicture")[0].files[0] ? "Yes" : "No");
        });
    });
    </script>
</body>
</html>