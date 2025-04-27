<?php
session_start();

// Check if the user is logged in and has the 'academics' role
if (!isset($_SESSION['account']) || $_SESSION['account']['role'] !== 'academics') {
    header("Location: /login.php"); // Redirect to login page if not logged in or not an academics user
    exit();
}

// Include database connection
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

// Function to display error messages
function displayError($message) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">' . $message . '</span>
          </div>';
}

// Function to display success messages
function displaySuccess($message) {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">' . $message . '</span>
          </div>';
}

// Handle form submission (example: updating profile information)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Example: Update first name
    if (isset($_POST['firstName'])) {
        $firstName = htmlspecialchars($_POST['firstName']); // Sanitize input

        // Basic validation
        if (empty($firstName)) {
            displayError("First name cannot be empty.");
        } else {
            // Update the database (replace with your actual database update logic)
            $userId = $_SESSION['account']['id'];
            $sql = "UPDATE accounts SET firstName = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("si", $firstName, $userId);
                if ($stmt->execute()) {
                    // Update session variable
                    $_SESSION['account']['firstName'] = $firstName;
                    displaySuccess("First name updated successfully!");
                } else {
                    displayError("Error updating first name: " . $stmt->error);
                }
                $stmt->close();
            } else {
                displayError("Error preparing statement: " . $conn->error);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academics Account</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Academics Account Management</h1>

    <!-- Profile Information Display -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Full Name</label>
        <div class="mt-1 p-3 bg-gray-50 rounded-md styleable" 
             data-section-id="profile_name_<?php echo $_SESSION['account']['id']; ?>" 
             data-element-name="Profile Name">
            <?php echo $_SESSION['account']['firstName'] . ' ' . $_SESSION['account']['lastName']; ?>
        </div>
    </div>

    <!-- Account Details Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-4">
            <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" 
                   name="firstName" 
                   id="firstName" 
                   value="<?php echo $_SESSION['account']['firstName']; ?>" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm styleable" 
                   data-section-id="input_firstname_<?php echo $_SESSION['account']['id']; ?>" 
                   data-element-name="First Name Input">
        </div>

        <!-- Add more form fields here as needed -->

        <button type="submit" class="bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50">
            Update Profile
        </button>
    </form>

    <a href="/logout.php" class="inline-block mt-4 text-blue-500 hover:underline">Logout</a>
</div>

</body>
</html>
