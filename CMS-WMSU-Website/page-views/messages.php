<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['account'])) {
    header('Location: login-form.php');
    exit;
}

require_once "../classes/messages.class.php";

// Initialize Messages class
$messagesObj = new Messages();

// Get user ID from session
$user_id = $_SESSION['account']['id'];

// Handle message actions
if (isset($_POST['action'])) {
    $message_id = isset($_POST['message_id']) ? $_POST['message_id'] : null;
    
    if ($_POST['action'] == 'mark_read' && $message_id) {
        $messagesObj->markAsRead($message_id);
    } elseif ($_POST['action'] == 'delete' && $message_id) {
        $messagesObj->deleteMessage($message_id, $user_id);
    }
}

// Get inbox messages for the current user
$messages = $messagesObj->getInboxMessages($user_id);

// Get unread count
$unread_count = $messagesObj->getUnreadCount($user_id);
?>

<!-- Tailwind CSS -->
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
    /* Custom styles for messages page */
    .message-card {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .message-card.unread {
        border-left-color: #BD0F03;
    }
    
    .message-card.unread .message-sender {
        font-weight: 600;
    }
    
    .message-card.unread .message-subject {
        font-weight: 600;
    }
    
    .message-avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f3f4f6;
        color: #6b7280;
        font-weight: 600;
    }
    
    .message-avatar.guest {
        background-color: #9ca3af;
        color: white;
    }
    
    .message-time {
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .message-preview {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #6b7280;
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-container {
        background-color: white;
        border-radius: 0.5rem;
        max-width: 700px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: all 0.3s ease;
    }
    
    .modal-overlay.active .modal-container {
        transform: scale(1);
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
    }
    
    .empty-icon {
        width: 64px;
        height: 64px;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Messages</h1>
        <p class="text-gray-600 mt-1">View and manage your incoming messages</p>
    </div>
    
    <!-- Messages Container -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header with unread count -->
        <div class="bg-primary text-white p-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Inbox</h2>
            <?php if ($unread_count > 0): ?>
                <span class="bg-white text-primary text-sm font-medium px-2.5 py-0.5 rounded-full">
                    <?php echo $unread_count; ?> unread
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Messages List -->
        <div class="divide-y divide-gray-200">
            <?php if (empty($messages)): ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No messages</h3>
                    <p class="text-gray-500 mt-1">Your inbox is empty</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <?php 
                    $is_unread = $message['is_read'] == 0;
                    $is_guest = $message['sender_id'] == 0;
                    $sender_initial = strtoupper(substr($message['sender_name'], 0, 1));
                    ?>
                    <div class="message-card <?php echo $is_unread ? 'unread' : ''; ?> p-4 cursor-pointer" 
                         onclick="openMessageModal(<?php echo $message['id']; ?>)">
                        <div class="flex items-start">
                            <div class="message-avatar <?php echo $is_guest ? 'guest' : ''; ?> mr-3 flex-shrink-0">
                                <?php if (!empty($message['sender_profile_img'])): ?>
                                    <img src="<?php echo htmlspecialchars($message['sender_profile_img']); ?>" 
                                         alt="<?php echo htmlspecialchars($message['sender_name']); ?>" 
                                         class="w-full h-full object-cover rounded-full">
                                <?php else: ?>
                                    <?php echo $sender_initial; ?>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between">
                                    <p class="message-sender text-sm text-gray-900 styleable <?php echo isset($message['styles']) ? implode(' ', json_decode($message['styles'], true) ?? []) : ''; ?>" 
   data-section-id="<?php echo $message['id']; ?>" 
   data-element-name="Sender: <?php echo htmlspecialchars($message['sender_name']); ?>">
    <?php echo htmlspecialchars($message['sender_name']); ?>
    <?php if ($is_guest): ?>
        <span class="ml-1 text-xs bg-gray-200 text-gray-800 px-1.5 py-0.5 rounded">Guest</span>
    <?php endif; ?>
</p>
<span class="message-time styleable <?php echo isset($message['styles_time']) ? implode(' ', json_decode($message['styles_time'], true) ?? []) : ''; ?>" 
      data-section-id="<?php echo $message['id']; ?>_time" 
      data-element-name="Time: <?php echo date('M d, Y', strtotime($message['created_at'])); ?>">
    <?php echo date('M d, Y', strtotime($message['created_at'])); ?>
</span>
                                </div>
                                <h3 class="message-subject text-base text-gray-900 mt-1 styleable <?php echo isset($message['styles_subject']) ? implode(' ', json_decode($message['styles_subject'], true) ?? []) : ''; ?>" 
    data-section-id="<?php echo $message['id']; ?>_subject" 
    data-element-name="Subject: <?php echo htmlspecialchars($message['subject']); ?>">
    <?php echo htmlspecialchars($message['subject']); ?>
</h3>
                                <p class="message-preview text-sm mt-1 styleable <?php echo isset($message['styles_message']) ? implode(' ', json_decode($message['styles_message'], true) ?? []) : ''; ?>" 
   data-section-id="<?php echo $message['id']; ?>_message" 
   data-element-name="Message: <?php echo substr(htmlspecialchars($message['message']), 0, 30) . '...'; ?>">
    <?php echo htmlspecialchars($message['message']); ?>
</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message Modal for each message -->
                    <div id="messageModal<?php echo $message['id']; ?>" class="modal-overlay">
                        <div class="modal-container">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($message['subject']); ?>
                                    </h3>
                                    <button onclick="closeMessageModal(<?php echo $message['id']; ?>)" class="text-gray-400 hover:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        
                                    </button>
                                </div>
                                
                                <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
                                    <div class="message-avatar <?php echo $is_guest ? 'guest' : ''; ?> mr-3">
                                        <?php if (!empty($message['sender_profile_img'])): ?>
                                            <img src="<?php echo htmlspecialchars($message['sender_profile_img']); ?>" 
                                                 alt="<?php echo htmlspecialchars($message['sender_name']); ?>" 
                                                 class="w-full h-full object-cover rounded-full">
                                        <?php else: ?>
                                            <?php echo $sender_initial; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            <?php echo htmlspecialchars($message['sender_name']); ?>
                                            <?php if ($is_guest): ?>
                                                <span class="ml-1 text-xs bg-gray-200 text-gray-800 px-1.5 py-0.5 rounded">Guest</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <?php echo date('F d, Y \a\t h:i A', strtotime($message['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="prose max-w-none mb-6">
                                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                    <button onclick="closeMessageModal(<?php echo $message['id']; ?>)" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Include Font Awesome for icons -->

<script>
    // Open message modal and mark as read
    function openMessageModal(messageId) {
        const modal = document.getElementById('messageModal' + messageId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Check if message is unread
            const messageCard = modal.previousElementSibling;
            if (messageCard && messageCard.classList.contains('unread')) {
                // Send AJAX request to mark as read
                const formData = new FormData();
                formData.append('message_id', messageId);
                formData.append('action', 'mark_read');
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(() => {
                    // Remove unread styling
                    messageCard.classList.remove('unread');
                    
                    // Update unread count in header
                    const unreadBadge = document.querySelector('.bg-white.text-primary.rounded-full');
                    if (unreadBadge) {
                        const currentCount = parseInt(unreadBadge.textContent);
                        if (currentCount > 1) {
                            unreadBadge.textContent = (currentCount - 1) + ' unread';
                        } else {
                            unreadBadge.remove();
                        }
                    }
                });
            }
        }
    }
    
    // Close message modal
    function closeMessageModal(messageId) {
        const modal = document.getElementById('messageModal' + messageId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal-overlay.active');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-overlay.active');
        modals.forEach(modal => {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }); 
    } 
}); 

</script>
