<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure headers are sent before any output
header('Content-Type: application/json');

// Initialize session variables for message cooldown if they don't exist
if (!isset($_SESSION['last_message_time'])) {
    $_SESSION['last_message_time'] = 0;
}

// Get content manager for current subpage
$contentManager = null;
$subpage_id = isset($_SESSION['subpage']) ? $_SESSION['subpage'] : null;

// Only proceed if we have a subpage ID
if ($subpage_id) {
    // Include the Pages class if not already included
    if (!class_exists('Pages')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-WCMS/CMS-WMSU-Website/classes/pages.class.php";
    }
    
    $pagesObj = new Pages();
    
    // Get content manager for the current subpage
    $managers = $pagesObj->fetchContentManagersBySubpage($subpage_id);
    $contentManager = !empty($managers) ? $managers[0] : null;
    
    // If no content manager found, get admin users as fallback
    if (empty($contentManager)) {
        $adminUsers = $pagesObj->fetchAdminUsers();
        if (!empty($adminUsers)) {
            $contentManager = $adminUsers[0];
        }
    }
}

// Handle AJAX message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    
    // Anti-spam check - 60 seconds cooldown
    $cooldownPeriod = 60;
    $timeSinceLastMessage = time() - $_SESSION['last_message_time'];
    
    if ($timeSinceLastMessage < $cooldownPeriod) {
        $timeRemaining = $cooldownPeriod - $timeSinceLastMessage;
        echo json_encode([
            'success' => false,
            'message' => "Please wait {$timeRemaining} seconds before sending another message."
        ]);
        exit;
    }
    
    // Include the Messages class if not already included
    if (!class_exists('Messages')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-WCMS/CMS-WMSU-Website/classes/messages.class.php";
    }
    
    $messagesObj = new Messages();
    
    // Get form data
    $sender_name = !empty($_POST['sender_name']) ? $_POST['sender_name'] : 'Anonymous';
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : ($contentManager ? $contentManager['id'] : null);
    
    // Validate required fields
    if (empty($subject) || empty($message) || empty($receiver_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields.'
        ]);
        exit;
    }
    
    // Send anonymous message (sender_id = 0)
    $result = $messagesObj->sendAnonymousMessage(0, $sender_name, $receiver_id, $subject, $message);
    
    if ($result) {
        // Set cooldown timestamp
        $_SESSION['last_message_time'] = time();
        
        echo json_encode([
            'success' => true,
            'message' => 'Message sent successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send message. Please try again.'
        ]);
    }
    exit;
}
?>

<!-- Message Bubble Styles -->
<style>
    /* Message Bubble */
    .message-bubble {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .message-bubble:hover {
        transform: scale(1.1);
    }

    .message-bubble-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #BD0F03;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .message-bubble-btn:hover {
        background-color: #8B0000;
    }

    /* Message Form Overlay */
    .message-form-overlay {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 320px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        z-index: 999;
        overflow: hidden;
        display: none;
        transition: all 0.3s ease;
    }

    .message-form-header {
        background-color: #BD0F03;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .message-form-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .message-form-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .message-form-body {
        padding: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        font-size: 14px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #BD0F03;
        outline: none;
    }

    .form-control.textarea {
        min-height: 100px;
        resize: vertical;
    }

    .btn-send {
        background-color: #BD0F03;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s;
    }

    .btn-send:hover {
        background-color: #8B0000;
    }

    .btn-send:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    /* Toast Notification */
    .toast-container {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 1100;
    }

    .toast {
        padding: 12px 20px;
        border-radius: 4px;
        margin-bottom: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-width: 250px;
        max-width: 350px;
        animation: slideIn 0.3s ease forwards;
    }

    .toast-success {
        background-color: #4CAF50;
        color: white;
    }

    .toast-error {
        background-color: #F44336;
        color: white;
    }

    .toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        padding: 0;
        margin-left: 10px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }

    .slide-out {
        animation: slideOut 0.3s ease forwards;
    }

    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
        margin-right: 10px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
        .message-form-overlay {
            width: 90%;
            right: 5%;
            left: 5%;
        }
    }
</style>

<!-- Message Bubble Button -->
<div class="message-bubble">
    <div class="message-bubble-btn" id="messageBubbleBtn">
        <i class="fas fa-comment"></i>
    </div>
</div>

<!-- Message Form Overlay -->
<div class="message-form-overlay" id="messageFormOverlay">
    <div class="message-form-header">
        <h3>Send a Message</h3>
        <button class="message-form-close" id="messageFormClose">&times;</button>
    </div>
    <div class="message-form-body">
        <form id="messageForm">
            <input type="hidden" name="action" value="send_message">
            <?php if ($contentManager): ?>
                <input type="hidden" name="receiver_id" value="<?php echo $contentManager['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="sender_name">Your Name (optional)</label>
                <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="Anonymous">
            </div>
            
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea class="form-control textarea" id="message" name="message" required></textarea>
            </div>
            
            <button type="submit" class="btn-send" id="sendButton">
                Send Message
            </button>
        </form>
    </div>
</div>

<!-- Toast Container for Notifications -->
<div class="toast-container" id="toastContainer"></div>

<!-- JavaScript for Message Bubble Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const messageBubbleBtn = document.getElementById('messageBubbleBtn');
        const messageFormOverlay = document.getElementById('messageFormOverlay');
        const messageFormClose = document.getElementById('messageFormClose');
        const messageForm = document.getElementById('messageForm');
        const sendButton = document.getElementById('sendButton');
        const toastContainer = document.getElementById('toastContainer');
        
        // Toggle message form
        messageBubbleBtn.addEventListener('click', function() {
            messageFormOverlay.style.display = messageFormOverlay.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close message form
        messageFormClose.addEventListener('click', function() {
            messageFormOverlay.style.display = 'none';
        });
        
        // Handle form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable send button and show loading state
            sendButton.disabled = true;
            const originalButtonText = sendButton.innerHTML;
            sendButton.innerHTML = '<span class="spinner"></span> Sending...';
            
            // Get form data
            const formData = new FormData(messageForm);
            
            // Send AJAX request
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Show toast notification
                showToast(data.success ? 'success' : 'error', data.message);
                
                // Reset form if successful
                if (data.success) {
                    messageForm.reset();
                    setTimeout(() => {
                        messageFormOverlay.style.display = 'none';
                    }, 2000);
                }
                
                // Reset button state
                sendButton.disabled = false;
                sendButton.innerHTML = originalButtonText;
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An unexpected error occurred. Please try again.');
                
                // Reset button state
                sendButton.disabled = false;
                sendButton.innerHTML = originalButtonText;
            });
        });
        
        // Function to show toast notifications
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const messageSpan = document.createElement('span');
            messageSpan.textContent = message;
            
            const closeButton = document.createElement('button');
            closeButton.className = 'toast-close';
            closeButton.innerHTML = '&times;';
            closeButton.addEventListener('click', function() {
                removeToast(toast);
            });
            
            toast.appendChild(messageSpan);
            toast.appendChild(closeButton);
            toastContainer.appendChild(toast);
            
            // Auto-remove toast after 5 seconds
            setTimeout(() => {
                removeToast(toast);
            }, 5000);
        }
        
        // Function to remove toast with animation
        function removeToast(toast) {
            toast.classList.add('slide-out');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
        
        // Close message form when clicking outside
        document.addEventListener('click', function(event) {
            if (messageFormOverlay.style.display === 'block' && 
                !messageFormOverlay.contains(event.target) && 
                event.target !== messageBubbleBtn) {
                messageFormOverlay.style.display = 'none';
            }
        });
    });
</script>
