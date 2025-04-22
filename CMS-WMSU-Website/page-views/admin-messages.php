<?php
session_start();
require_once "../classes/messages.class.php";

// Check if user is logged in as admin
if (empty($_SESSION['account']) || $_SESSION['account']['role_id'] != 1) {
    // Redirect to login page if not logged in as admin
    header("Location: login-form.php");
    exit();
}

$messagesObj = new Messages();
$user_id = $_SESSION['account']['id']; // Using the account session structure

// Handle message actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Send new message
    if (isset($_POST['send_message'])) {
        $sender_id = $user_id;
        $receiver_id = $_POST['receiver_id'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        
        $result = $messagesObj->sendMessage($sender_id, $receiver_id, $subject, $message);
        
        if ($result) {
            $_SESSION['success_msg'] = "Message sent successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to send message. Please try again.";
        }
    }
    
    // Mark message as read
    if (isset($_POST['mark_read']) && isset($_POST['message_id'])) {
        $message_id = $_POST['message_id'];
        $result = $messagesObj->markAsRead($message_id);
        
        if ($result) {
            $_SESSION['success_msg'] = "Message marked as read.";
        } else {
            $_SESSION['error_msg'] = "Failed to mark message as read.";
        }
    }
    
    // Delete message
    if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
        $message_id = $_POST['message_id'];
        $result = $messagesObj->deleteMessage($message_id);
        
        if ($result) {
            $_SESSION['success_msg'] = "Message deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete message.";
        }
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch messages for the current user
$inbox = $messagesObj->getInboxMessages($user_id);
$sent = $messagesObj->getSentMessages($user_id);
$unread_count = $messagesObj->getUnreadCount($user_id);

// Fetch users for the compose message dropdown
require_once "../classes/pages.class.php";
$pagesObj = new Pages();
$users = $pagesObj->fetchAllUsers();

// Current user info from session
$currentUser = [
    'id' => $_SESSION['account']['id'],
    'firstName' => $_SESSION['account']['firstName'],
    'lastName' => $_SESSION['account']['lastName'],
    'profileImg' => $_SESSION['account']['profileImg'] ?? null
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages - WMSU CMS</title>
    
    <!-- Include your CSS files -->
    <link rel="stylesheet" href="../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- Include Bootstrap and other libraries -->
    <link rel="stylesheet" href="../vendors/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #BD0F03;
            --primary-dark: #8B0000;
            --primary-light: #ee948e;
            --secondary: #f5efef;
        }
        
        .message-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .message-sidebar {
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            height: calc(100vh - 180px);
            overflow-y: auto;
        }
        
        .message-content {
            height: calc(100vh - 180px);
            overflow-y: auto;
            padding: 0;
        }
        
        .message-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        .message-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            background-color: #f8f9fa;
        }
        
        .message-tab {
            padding: 15px 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }
        
        .message-tab.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
            background-color: #fff;
        }
        
        .message-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .message-item {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .message-item:hover {
            background-color: rgba(189, 15, 3, 0.05);
        }
        
        .message-item.unread {
            background-color: rgba(189, 15, 3, 0.05);
            font-weight: 600;
        }
        
        .message-item.active {
            background-color: rgba(189, 15, 3, 0.1);
            border-left: 4px solid var(--primary-color);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(189, 15, 3, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .anonymous-badge {
            font-size: 0.7rem;
            background-color: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 5px;
        }
        
        .message-preview {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            color: #666;
            font-size: 0.85rem;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: #888;
        }
        
        .message-detail {
            padding: 20px;
        }
        
        .message-subject {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        
        .message-meta {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .message-body {
            line-height: 1.6;
            color: #333;
            white-space: pre-line;
        }
        
        .compose-form {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(189, 15, 3, 0.25);
            outline: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            background-color: transparent;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-primary {
            background-color: var(--primary-color);
            color: white;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 10px;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 40px;
            color: #666;
            text-align: center;
        }
        
        .empty-icon {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .message-sidebar {
                height: auto;
                max-height: 300px;
            }
            
            .message-content {
                height: auto;
                max-height: calc(100vh - 480px);
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #BD0F03;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #8B0000;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
                <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
                    <a class="navbar-brand brand-logo" href="dashboard.php">
                        <img src="../images/logo.png" alt="logo" style="width: 50px; height: 50px;"/>
                        <span style="font-weight: bold; font-size: 1.2rem; color: #333;">WMSU CMS</span>
                    </a>
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-sort-variant"></span>
                    </button>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                            <?php if(!empty($currentUser['profileImg'])): ?>
                                <img src="<?php echo $currentUser['profileImg']; ?>" alt="profile" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"/>
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; background-color: #BD0F03; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?php echo strtoupper(substr($currentUser['firstName'] ?? 'A', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <span class="nav-profile-name"><?php echo $currentUser['firstName'] ?? 'Admin'; ?> <?php echo $currentUser['lastName'] ?? 'User'; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="profile.php">
                                <i class="mdi mdi-account text-primary"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="logout.php">
                                <i class="mdi mdi-logout text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        
        <!-- Page Container -->
        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <div class="sidebar-container">
                <?php require_once '../__includes/sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="me-md-3 me-xl-5">
                                        <h2>Messages</h2>
                                        <p class="mb-md-0">Manage your communications with users and administrators</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alert Messages -->
                    <?php if(isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $_SESSION['success_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success_msg']); ?>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['error_msg'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $_SESSION['error_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error_msg']); ?>
                    <?php endif; ?>
                    
                    <!-- Messages Container -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="message-container">
                                        <div class="message-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="m-0">Message Center</h4>
                                                <?php if($unread_count > 0): ?>
                                                    <span class="badge bg-light text-primary"><?php echo $unread_count; ?> unread</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="message-tabs">
                                            <div id="tab-inbox" class="message-tab active" onclick="switchTab('inbox')">
                                                Inbox
                                                <?php if($unread_count > 0): ?>
                                                    <span class="badge badge-primary ml-1"><?php echo $unread_count; ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div id="tab-sent" class="message-tab" onclick="switchTab('sent')">
                                                Sent
                                            </div>
                                            <div id="tab-compose" class="message-tab" onclick="switchTab('compose')">
                                                Compose
                                            </div>
                                        </div>
                                        
                                        <div class="row m-0">
                                            <!-- Message List -->
                                            <div class="col-md-4 p-0 message-sidebar">
                                                <!-- Inbox Content -->
                                                <div id="inbox-list">
                                                    <?php if(empty($inbox)): ?>
                                                        <div class="empty-state">
                                                            <i class="fas fa-inbox empty-icon"></i>
                                                            <p>Your inbox is empty</p>
                                                        </div>
                                                    <?php else: ?>
                                                        <ul class="message-list">
                                                            <?php foreach($inbox as $index => $message): ?>
                                                                <li class="message-item <?php echo $message['is_read'] ? '' : 'unread'; ?> <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                                    onclick="viewMessage(<?php echo $message['id']; ?>, '<?php echo addslashes($message['sender_name']); ?>', '<?php echo addslashes($message['subject']); ?>', '<?php echo addslashes($message['message']); ?>', '<?php echo $message['created_at']; ?>', false, this, <?php echo $message['sender_id'] == 0 ? 'true' : 'false'; ?>)">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="user-avatar">
                                                                            <?php echo strtoupper(substr($message['sender_name'], 0, 1)); ?>
                                                                        </div>
                                                                        <div class="flex-grow-1 min-width-0">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <h6 class="mb-0 text-truncate">
                                                                                    <?php echo $message['sender_name']; ?>
                                                                                    <?php if($message['sender_id'] == 0): ?>
                                                                                        <span class="anonymous-badge">Guest</span>
                                                                                    <?php endif; ?>
                                                                                </h6>
                                                                                <span class="message-time"><?php echo date('M d', strtotime($message['created_at'])); ?></span>
                                                                            </div>
                                                                            <div class="message-preview"><?php echo $message['subject']; ?></div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Sent Content -->
                                                <div id="sent-list" style="display: none;">
                                                    <?php if(empty($sent)): ?>
                                                        <div class="empty-state">
                                                            <i class="fas fa-paper-plane empty-icon"></i>
                                                            <p>You haven't sent any messages yet</p>
                                                        </div>
                                                    <?php else: ?>
                                                        <ul class="message-list">
                                                            <?php foreach($sent as $message): ?>
                                                                <li class="message-item" 
                                                                    onclick="viewMessage(<?php echo $message['id']; ?>, '<?php echo addslashes($message['receiver_name']); ?>', '<?php echo addslashes($message['subject']); ?>', '<?php echo addslashes($message['message']); ?>', '<?php echo $message['created_at']; ?>', true, this)">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="user-avatar">
                                                                            <?php echo strtoupper(substr($message['receiver_name'], 0, 1)); ?>
                                                                        </div>
                                                                        <div class="flex-grow-1 min-width-0">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <h6 class="mb-0 text-truncate"><?php echo $message['receiver_name']; ?></h6>
                                                                                <span class="message-time"><?php echo date('M d', strtotime($message['created_at'])); ?></span>
                                                                            </div>
                                                                            <div class="message-preview"><?php echo $message['subject']; ?></div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Message Content -->
                                            <div class="col-md-8 p-0 message-content">
                                                <!-- Message Detail View -->
                                                <div id="message-detail">
                                                    <?php if(!empty($inbox)): ?>
                                                        <div class="message-detail">
                                                            <div class="message-subject" id="detail-subject"><?php echo $inbox[0]['subject']; ?></div>
                                                            <div class="message-meta">
                                                                <div class="user-avatar" id="detail-avatar">
                                                                    <?php echo strtoupper(substr($inbox[0]['sender_name'], 0, 1)); ?>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold" id="detail-from-to">
                                                                        From: <?php echo $inbox[0]['sender_name']; ?>
                                                                        <?php if($inbox[0]['sender_id'] == 0): ?>
                                                                            <span class="anonymous-badge">Guest</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="text-muted small" id="detail-date"><?php echo date('F d, Y g:i A', strtotime($inbox[0]['created_at'])); ?></div>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <form method="POST" id="mark-read-form" class="d-inline">
                                                                        <input type="hidden" name="message_id" id="detail-message-id" value="<?php echo $inbox[0]['id']; ?>">
                                                                        <?php if(!$inbox[0]['is_read']): ?>
                                                                            <button type="submit" name="mark_read" class="btn btn-sm btn-outline-primary me-2">
                                                                                <i class="fas fa-check me-1"></i> Mark as Read
                                                                            </button>
                                                                        <?php endif; ?>
                                                                        <button type="submit" name="delete_message" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                                                            <i class="fas fa-trash me-1"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="message-body" id="detail-message">
                                                                <?php echo nl2br($inbox[0]['message']); ?>
                                                            </div>
                                                            <div class="mt-4">
                                                                <?php if($inbox[0]['sender_id'] != 0): ?>
                                                                <button class="btn btn-primary" onclick="replyToMessage('<?php echo addslashes($inbox[0]['sender_name']); ?>', <?php echo $inbox[0]['sender_id']; ?>)">
                                                                    <i class="fas fa-reply me-1"></i> Reply
                                                                </button>
                                                                <?php else: ?>
                                                                <div class="alert alert-info">
                                                                    <i class="fas fa-info-circle me-2"></i>
                                                                    This message was sent by a guest user. Direct replies are not possible.
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php elseif(!empty($sent)): ?>
                                                        <div class="message-detail">
                                                            <div class="message-subject" id="detail-subject"><?php echo $sent[0]['subject']; ?></div>
                                                            <div class="message-meta">
                                                                <div class="user-avatar" id="detail-avatar">
                                                                    <?php echo strtoupper(substr($sent[0]['receiver_name'], 0, 1)); ?>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold" id="detail-from-to">To: <?php echo $sent[0]['receiver_name']; ?></div>
                                                                    <div class="text-muted small" id="detail-date"><?php echo date('F d, Y g:i A', strtotime($sent[0]['created_at'])); ?></div>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <form method="POST" id="mark-read-form" class="d-inline">
                                                                        <input type="hidden" name="message_id" id="detail-message-id" value="<?php echo $sent[0]['id']; ?>">
                                                                        <button type="submit" name="delete_message" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                                                            <i class="fas fa-trash me-1"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="message-body" id="detail-message">
                                                                <?php echo nl2br($sent[0]['message']); ?>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="empty-state">
                                                            <i class="fas fa-envelope-open empty-icon"></i>
                                                            <p>Select a message to view or compose a new one</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Compose Form -->
                                                <div id="compose-form" class="compose-form" style="display: none;">
                                                    <form method="POST">
                                                        <div class="form-group">
                                                            <label for="receiver_id" class="form-label">To:</label>
                                                            <select id="receiver_id" name="receiver_id" class="form-control" required>
                                                                <option value="">Select recipient</option>
                                                                <?php foreach($users as $user): ?>
                                                                    <?php if($user['id'] != $user_id): ?>
                                                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="subject" class="form-label">Subject:</label>
                                                            <input type="text" id="subject" name="subject" class="form-control" required>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="message" class="form-label">Message:</label>
                                                            <textarea id="message" name="message" rows="10" class="form-control" required></textarea>
                                                        </div>
                                                        
                                                        <div class="text-end">
                                                            <button type="button" class="btn btn-outline-secondary me-2" onclick="switchTab('inbox')">Cancel</button>
                                                            <button type="submit" name="send_message" class="btn btn-primary">
                                                                <i class="fas fa-paper-plane me-1"></i> Send Message
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © <?php echo date('Y'); ?> WMSU. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Western Mindanao State University</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="../vendors/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="../vendors/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    
    <script>
        // Current message tracking
        let currentMessageId = <?php echo !empty($inbox) ? $inbox[0]['id'] : (!empty($sent) ? $sent[0]['id'] : 'null'); ?>;
        let currentIsSent = <?php echo !empty($inbox) ? 'false' : (!empty($sent) ? 'true' : 'false'); ?>;
        let currentIsAnonymous = <?php echo !empty($inbox) && $inbox[0]['sender_id'] == 0 ? 'true' : 'false'; ?>;
        
        // Switch between tabs
        function switchTab(tab) {
            // Hide all content
            document.getElementById('inbox-list').style.display = 'none';
            document.getElementById('sent-list').style.display = 'none';
            document.getElementById('message-detail').style.display = 'none';
            document.getElementById('compose-form').style.display = 'none';
            
            // Remove active class from all tabs
            document.getElementById('tab-inbox').classList.remove('active');
            document.getElementById('tab-sent').classList.remove('active');
            document.getElementById('tab-compose').classList.remove('active');
            
            // Show selected content and activate tab
            if (tab === 'inbox') {
                document.getElementById('inbox-list').style.display = 'block';
                document.getElementById('message-detail').style.display = 'block';
                document.getElementById('tab-inbox').classList.add('active');
            } else if (tab === 'sent') {
                document.getElementById('sent-list').style.display = 'block';
                document.getElementById('message-detail').style.display = 'block';
                document.getElementById('tab-sent').classList.add('active');
            } else if (tab === 'compose') {
                document.getElementById('compose-form').style.display = 'block';
                document.getElementById('tab-compose').classList.add('active');
            }
        }
        
        // View message
        function viewMessage(id, name, subject, message, date, isSent, element, isAnonymous = false) {
            // Update current message tracking
            currentMessageId = id;
            currentIsSent = isSent;
            currentIsAnonymous = isAnonymous;
            
            // Update form hidden input
            document.getElementById('detail-message-id').value = id;
            
            // Update message details
            document.getElementById('detail-subject').textContent = subject;
            
            // Update from/to text with anonymous badge if needed
            const fromToElement = document.getElementById('detail-from-to');
            if (isSent) {
                fromToElement.innerHTML = 'To: ' + name;
            } else {
                if (isAnonymous) {
                    fromToElement.innerHTML = 'From: ' + name + ' <span class="anonymous-badge">Guest</span>';
                } else {
                    fromToElement.innerHTML = 'From: ' + name;
                }
            }
            
            document.getElementById('detail-date').textContent = formatDate(date);
            document.getElementById('detail-message').innerHTML = message.replace(/\n/g, '<br>');
            document.getElementById('detail-avatar').textContent = name.charAt(0).toUpperCase();
            
            // Update mark as read button visibility
            const markReadBtn = document.querySelector('#mark-read-form button[name="mark_read"]');
            if (markReadBtn) {
                if (!isSent && element && element.classList.contains('unread')) {
                    markReadBtn.style.display = 'inline-block';
                } else {
                    markReadBtn.style.display = 'none';
                }
            }
            
            // Update reply button visibility
            const replyBtn = document.querySelector('.message-detail .btn-primary');
            if (replyBtn) {
                if (isSent || isAnonymous) {
                    replyBtn.style.display = 'none';
                    
                    // Show anonymous message info if needed
                    const anonymousInfo = document.querySelector('.message-detail .alert-info');
                    if (anonymousInfo) {
                        anonymousInfo.style.display = isAnonymous ? 'block' : 'none';
                    }
                } else {
                    replyBtn.style.display = 'inline-block';
                    
                    // Hide anonymous message info
                    const anonymousInfo = document.querySelector('.message-detail .alert-info');
                    if (anonymousInfo) {
                        anonymousInfo.style.display = 'none';
                    }
                }
            }
            
            // Set active class on selected message
            const messageItems = document.querySelectorAll('.message-item');
            messageItems.forEach(item => {
                item.classList.remove('active');
            });
            if (element) {
                element.classList.add('active');
            }
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });
        }
        
        // Reply to message
        function replyToMessage(name, id) {
            // Don't allow replies to anonymous users
            if (currentIsAnonymous) {
                return;
            }
            
            // Switch to compose tab
            switchTab('compose');
            
            // Set recipient
            document.getElementById('receiver_id').value = id;
            
            // Set subject with Re: prefix if not already there
            const subject = document.getElementById('detail-subject').textContent;
            document.getElementById('subject').value = subject.startsWith('Re:') ? subject : 'Re: ' + subject;
            
            // Focus on message field
            document.getElementById('message').focus();
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial tab
            <?php if(!empty($_GET['tab'])): ?>
                switchTab('<?php echo $_GET['tab']; ?>');
            <?php else: ?>
                switchTab('inbox');
            <?php endif; ?>
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
