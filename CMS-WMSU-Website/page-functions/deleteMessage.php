<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['account'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once "../classes/messages.class.php";

// Initialize Messages class
$messagesObj = new Messages();

// Get user ID from session
$user_id = $_SESSION['account']['id'];

// Get message ID from POST
$message_id = isset($_POST['message_id']) ? (int)$_POST['message_id'] : null;

if (!$message_id) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
    exit;
}

// Delete the message
$success = $messagesObj->deleteMessage($message_id, $user_id);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Failed to delete message']);
}