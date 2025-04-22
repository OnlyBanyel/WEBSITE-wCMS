<?php
require_once "../../CMS-WMSU-Website/classes/messages.class.php";
require_once "../../CMS-WMSU-Website/classes/pages.class.php";

// Get the content manager for the current subpage
$contentManager = null;
$managerId = 1; // Default fallback to admin ID 1

if (isset($_SESSION['subpage'])) {
    $pagesObj = new Pages();
    $managers = $pagesObj->fetchContentManagersBySubpage($_SESSION['subpage']);
    // Get the first manager if available
    $contentManager = !empty($managers) ? $managers[0] : null;
    
    // Set the manager ID for the form
    if ($contentManager) {
        $managerId = $contentManager['id'];
    }
}

// Store manager's name for display
$managerName = $contentManager ? $contentManager['firstName'] . ' ' . $contentManager['lastName'] : 'Page Administrator';

// Anti-spam measures
$canSendMessage = true;
$spamErrorMessage = '';
$timeRemaining = 0;

// Check if there's a recent message timestamp in the session
if (isset($_SESSION['last_message_time'])) {
    $timeSinceLastMessage = time() - $_SESSION['last_message_time'];
    $cooldownPeriod = 60; // 60 seconds (1 minute) cooldown
    
    if ($timeSinceLastMessage < $cooldownPeriod) {
        $canSendMessage = false;
        $timeRemaining = $cooldownPeriod - $timeSinceLastMessage;
        $spamErrorMessage = "Please wait " . $timeRemaining . " seconds before sending another message.";
    }
}

// Handle AJAX message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_action']) && $_POST['message_action'] == 'send') {
    header('Content-Type: application/json');
    
    if (!$canSendMessage) {
        echo json_encode([
            'success' => false,
            'message' => $spamErrorMessage
        ]);
        exit;
    }
    
    $sender_name = !empty($_POST['sender_name']) ? $_POST['sender_name'] : 'Anonymous';
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Use the manager ID from the form, fallback to the one we determined above
    $receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : $managerId;
    
    // Set the sender_id to 0 for anonymous users
    $sender_id = 0;
    
    $messagesObj = new Messages();
    $result = $messagesObj->sendAnonymousMessage($sender_id, $sender_name, $receiver_id, $subject, $message);
    
    if ($result) {
        // Set the last message timestamp to prevent spam
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

<!-- Message Bubble -->
<div id="message-bubble" class="fixed bottom-6 right-6 z-50" style="cursor: pointer;">
    <button id="messageBubbleBtn" class="relative flex items-center justify-center w-14 h-14 text-white bg-primary hover:bg-primaryDark rounded-full shadow-lg transition-all duration-300 hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
</div>

<!-- Message Overlay -->
<div id="messageOverlay" class="fixed right-6 bottom-24 w-96 max-w-[90vw] bg-white rounded-lg shadow-xl z-40 overflow-hidden hidden">
    <div class="bg-primary text-white px-4 py-3 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Contact Page Manager</h3>
        <button onclick="toggleMessageOverlay()" class="text-white hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="max-h-[70vh] overflow-y-auto">
        <!-- Message Form -->
<div id="messageForm" class="p-4">
    <p class="text-gray-700 mb-4">Send a message to the manager of this page. You can remain anonymous if you prefer.</p>
    
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient</label>
        <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            <?php echo htmlspecialchars($managerName); ?>
        </div>
        <input type="hidden" id="receiver_id" name="receiver_id" value="<?php echo $managerId; ?>">
    </div>
    
    <form id="contactForm" class="space-y-4">
        <div>
            <label for="sender_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name (Optional)</label>
            <input type="text" id="sender_name" name="sender_name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="Enter your name or leave blank to remain anonymous">
        </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required placeholder="Enter message subject">
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required placeholder="Enter your message here..."></textarea>
                </div>
                
                <div>
                    <button type="button" onclick="sendMessage()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primaryDark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" <?php echo !$canSendMessage ? 'disabled' : ''; ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Send Message
                    </button>
                    
                    <?php if (!$canSendMessage): ?>
                        <div class="text-xs text-gray-500 text-center mt-2" id="cooldown-message">
                            <?php echo $spamErrorMessage; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Success Message (hidden by default) -->
<div id="successMessage" class="p-6 text-center hidden">
    <svg class="mx-auto h-12 w-12 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Message Sent!</h3>
    <p class="text-gray-600 mb-4">Thank you for your message. <span class="font-semibold"><?php echo htmlspecialchars($managerName); ?></span> will review it soon.</p>
    <button onclick="resetMessageForm()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primaryDark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        Send Another Message
    </button>
</div>
        
        <!-- Error Message (hidden by default) -->
        <div id="errorMessage" class="p-6 text-center hidden">
            <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Error</h3>
            <p id="errorText" class="text-gray-600 mb-4">Failed to send message. Please try again.</p>
            <button onclick="showMessageForm()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primaryDark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Try Again
            </button>
        </div>
    </div>
</div>

<!-- Toast Notifications Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-50">
    <!-- Toasts will be dynamically added here -->
</div>

<script>
// Add event listener to the button
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('messageBubbleBtn').addEventListener('click', toggleMessageOverlay);
    
    <?php if (!$canSendMessage): ?>
        startCooldownTimer(<?php echo $timeRemaining; ?>);
    <?php endif; ?>
});

// Toggle message overlay visibility
function toggleMessageOverlay() {
    const overlay = document.getElementById('messageOverlay');
    if (overlay.style.display === 'none' || overlay.classList.contains('hidden')) {
        overlay.style.display = 'block';
        overlay.classList.remove('hidden');
    } else {
        overlay.style.display = 'none';
        overlay.classList.add('hidden');
    }
    
    // If showing the overlay, ensure we're showing the form
    if (overlay.style.display === 'block' || !overlay.classList.contains('hidden')) {
        showMessageForm();
    }
}
    
// Send message via AJAX
function sendMessage() {
    const form = document.getElementById('contactForm');
    const receiver_id = document.getElementById('receiver_id').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    const sender_name = document.getElementById('sender_name').value;
    
    // Basic validation
    if (!subject) {
        showToast('Please enter a subject', 'error');
        return;
    }
    
    if (!message) {
        showToast('Please enter your message', 'error');
        return;
    }
    
    // Create form data
    const formData = new FormData();
    formData.append('message_action', 'send');
    formData.append('receiver_id', receiver_id);
    formData.append('subject', subject);
    formData.append('message', message);
    formData.append('sender_name', sender_name);
        
    // Send AJAX request
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showSuccessMessage();
            
            // Start cooldown timer if needed
            if (data.cooldown) {
                startCooldownTimer(data.cooldown);
            }
        } else {
            // Show error message
            showErrorMessage(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred. Please try again.');
    });
}
    
// Show success message
function showSuccessMessage() {
    document.getElementById('messageForm').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('successMessage').classList.remove('hidden');
}
    
// Show error message
function showErrorMessage(message) {
    document.getElementById('messageForm').classList.add('hidden');
    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('errorText').textContent = message;
    document.getElementById('errorMessage').classList.remove('hidden');
}
    
// Show message form
function showMessageForm() {
    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('messageForm').classList.remove('hidden');
}
    
// Reset message form
function resetMessageForm() {
    document.getElementById('contactForm').reset();
    showMessageForm();
}
    
// Show toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `flex items-center p-3 mb-3 text-sm rounded-md shadow-md
                        ${type === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-500' : 
                          type === 'error' ? 'bg-red-50 text-red-800 border-l-4 border-red-500' : 
                          'bg-blue-50 text-blue-800 border-l-4 border-blue-500'}`;
    
    let icon = '';
    if (type === 'success') {
        icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    } else if (type === 'error') {
        icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    } else {
        icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    toast.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span>${message}</span>
        </div>
        <button class="ml-auto text-gray-500 hover:text-gray-700" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Remove toast after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
    
// Start cooldown timer
function startCooldownTimer(seconds) {
    const sendButton = document.querySelector('button[onclick="sendMessage()"]');
    const cooldownMessage = document.getElementById('cooldown-message');
    
    if (!cooldownMessage) {
        const cooldownElement = document.createElement('div');
        cooldownElement.id = 'cooldown-message';
        cooldownElement.className = 'text-xs text-gray-500 text-center mt-2';
        sendButton.parentNode.appendChild(cooldownElement);
    }
    
    sendButton.disabled = true;
    
    const updateCooldown = function(remaining) {
        document.getElementById('cooldown-message').textContent = `Please wait ${remaining} seconds before sending another message.`;
        
        if (remaining <= 0) {
            sendButton.disabled = false;
            document.getElementById('cooldown-message').textContent = '';
            clearInterval(interval);
        }
    };
    
    updateCooldown(seconds);
    
    const interval = setInterval(() => {
        seconds--;
        updateCooldown(seconds);
    }, 1000);
}
    
// Initialize
<?php if (!$canSendMessage): ?>
document.addEventListener('DOMContentLoaded', function() {
    startCooldownTimer(<?php echo $timeRemaining; ?>);
});
<?php endif; ?>
</script>

<style>
    /* Custom scrollbar for message overlay */
    #messageOverlay::-webkit-scrollbar {
        width: 6px;
    }
    
    #messageOverlay::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #messageOverlay::-webkit-scrollbar-thumb {
        background: #BD0F03;
        border-radius: 10px;
    }
    
    #messageOverlay::-webkit-scrollbar-thumb:hover {
        background: #8B0000;
    }
    
    /* Disabled button styles */
    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>