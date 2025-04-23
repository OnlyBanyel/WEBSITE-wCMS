<?php 
// Existing PHP code
?>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#BD0F03',
                    primaryLight: '#ee948e',
                    primaryDark: '#8B0000',
                    secondary: '#f5efef',
                    neutral: '#6a6a6a',
                }
            }
        }
    }
</script>
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
    
    /* Custom sidebar styling */
    .sidebar-nav-item {
        transition: all 0.2s ease;
    }
    
    .sidebar-nav-item:hover {
        background-color: rgba(189, 15, 3, 0.1);
    }
    
    .sidebar-nav-item.active {
        background-color: rgba(189, 15, 3, 0.2);
        border-left: 3px solid #BD0F03;
    }
</style>

<?php 
if ($_SESSION['account']['role_id'] == 1){
?>

<div class="h-screen flex flex-col bg-white border-r border-gray-200 w-64 shadow-md">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-primary">WMSU - CMS</h2>
        <button class="sidebar-toggler text-gray-500 hover:text-primary focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    
    <div class="flex-1 overflow-y-auto py-4">
        <div class="px-4 mb-2">
            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-semibold">General Elements</h3>
        </div>
        <ul class="space-y-1">
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Navbar
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Footer
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
        </ul>
        <ul class="space-y-1">

        <div class="px-4 mb-2">
            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Page Management</h3>
        </div>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Home
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    About Us
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/super-admin/academics-account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Academics
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Administration
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Research
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Other Links
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
        </ul>
        <ul class="space-y-1">
        <div class="px-4 mb-2">
            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Admin Account Management</h3>
        </div>
            <li class="sidebar-nav-item">
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/account-management.php" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Account Management
                    </a>
                </li>
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/messages.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Messages
                    <?php 
                    // Get unread message count
                    require_once __DIR__ . "/../classes/messages.class.php";
                    $messagesObj = new Messages();
                    $unread_count = $messagesObj->getUnreadCount($_SESSION['account']['id']);
                    if ($unread_count > 0): 
                    ?>
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>

    </div>

    
    
    <div class="border-t border-gray-200 p-4">
        <button class="sidebar-toggler w-full flex items-center justify-center text-gray-500 hover:text-primary focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>
</div>

<?php 
} else {
?>

<?php 
$words = explode(" ", $_SESSION['account']['subPageName']); // Split into words
$collegeName = "";

foreach ($words as $word) {
    if (strtolower($word) !== "of") { // Ignore "of"
        $collegeName .= strtoupper($word[0]); // Take first letter
    }
}
?>

<div class="h-screen flex flex-col bg-white border-r border-gray-200 w-64 shadow-md">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-primary"><?php echo $collegeName?> - CMS</h2>
        <button class="sidebar-toggler text-gray-500 hover:text-primary focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    
    <div class="flex-1 overflow-y-auto py-4">
        <div class="px-4 mb-2">
            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Main Webpages</h3>
        </div>
        <ul class="space-y-1">
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/college-profile.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    College Profile
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/college-overview.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    College Overview
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/departments.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Departments
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/courses-offered.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Courses Offered
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">NEW</span>
                </a>
            </li>
        </ul>
        
        <div class="px-4 mt-6 mb-2">
            <h3 class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Others</h3>
        </div>
        <ul class="space-y-1">
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/account-management.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Account Management
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:text-primary dynamic-load" data-file="../page-views/messages.php" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Messages
                    <?php 
                    // Get unread message count
                    require_once __DIR__ . "/../classes/messages.class.php";
                    $messagesObj = new Messages();
                    $unread_count = $messagesObj->getUnreadCount($_SESSION['account']['id']);
                    if ($unread_count > 0): 
                    ?>
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="border-t border-gray-200 p-4">
        <button class="sidebar-toggler w-full flex items-center justify-center text-gray-500 hover:text-primary focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>
</div>

<?php } ?>
<script src="../js/script.js"></script>
