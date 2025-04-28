<?php
require_once __DIR__ . "/db_connection.class.php";

class Messages {
    protected $db;
    
    function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Send a new message
     * 
     * @param int $sender_id The ID of the sender
     * @param int $receiver_id The ID of the receiver
     * @param string $subject The message subject
     * @param string $message The message content
     * @return bool Success or failure
     */
    public function sendMessage($sender_id, $receiver_id, $subject, $message) {
        try {
            $sql = "INSERT INTO messages (sender_id, receiver_id, subject, message, created_at) 
                    VALUES (:sender_id, :receiver_id, :subject, :message, NOW())";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':sender_id', $sender_id);
            $qry->bindParam(':receiver_id', $receiver_id);
            $qry->bindParam(':subject', $subject);
            $qry->bindParam(':message', $message);
            
            return $qry->execute();
        } catch (PDOException $e) {
            error_log("Error sending message: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send a new message from an anonymous user
     * 
     * @param int $sender_id The ID of the sender (NULL for anonymous)
     * @param string $sender_name The name of the anonymous sender
     * @param int $receiver_id The ID of the receiver
     * @param string $subject The message subject
     * @param string $message The message content
     * @return bool Success or failure
     */
    public function sendAnonymousMessage($sender_id, $sender_name, $receiver_id, $subject, $message) {
        try {
            $sql = "INSERT INTO messages (sender_id, sender_name, receiver_id, subject, message, created_at) 
                    VALUES (:sender_id, :sender_name, :receiver_id, :subject, :message, NOW())";
            
            $qry = $this->db->connect()->prepare($sql);
            
            // Bind sender_id as NULL if it's 0
            if ($sender_id === 0) {
                $qry->bindValue(':sender_id', null, PDO::PARAM_NULL);
            } else {
                $qry->bindParam(':sender_id', $sender_id);
            }
            
            $qry->bindParam(':sender_name', $sender_name);
            $qry->bindParam(':receiver_id', $receiver_id);
            $qry->bindParam(':subject', $subject);
            $qry->bindParam(':message', $message);
            
            return $qry->execute();
        } catch (PDOException $e) {
            error_log("Error sending anonymous message: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get inbox messages for a user
     * 
     * @param int $user_id The ID of the user
     * @return array The messages
     */
    public function getInboxMessages($user_id) {
        try {
            $sql = "SELECT m.*, 
                    CASE 
                        WHEN m.sender_id IS NULL THEN m.sender_name
                        ELSE CONCAT(a_sender.firstName, ' ', a_sender.lastName)
                    END AS sender_name,
                    a_sender.profileImg AS sender_profile_img
                    FROM messages m
                    LEFT JOIN accounts a_sender ON m.sender_id = a_sender.id
                    WHERE m.receiver_id = :user_id AND m.deleted_by_receiver = 0
                    ORDER BY m.created_at DESC";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':user_id', $user_id);
            $qry->execute();
            
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting inbox messages: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get sent messages for a user
     * 
     * @param int $user_id The ID of the user
     * @return array The messages
     */
    public function getSentMessages($user_id) {
        try {
            $sql = "SELECT m.*, 
                    a_receiver.firstName AS receiver_first_name, 
                    a_receiver.lastName AS receiver_last_name,
                    a_receiver.profileImg AS receiver_profile_img,
                    CONCAT(a_receiver.firstName, ' ', a_receiver.lastName) AS receiver_name
                    FROM messages m
                    JOIN accounts a_receiver ON m.receiver_id = a_receiver.id
                    WHERE m.sender_id = :user_id AND m.deleted_by_sender = 0
                    ORDER BY m.created_at DESC";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':user_id', $user_id);
            $qry->execute();
            
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting sent messages: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Mark a message as read
     * 
     * @param int $message_id The ID of the message
     * @return bool Success or failure
     */
    public function markAsRead($message_id) {
        try {
            $sql = "UPDATE messages SET is_read = 1 WHERE id = :message_id";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':message_id', $message_id);
            
            return $qry->execute();
        } catch (PDOException $e) {
            error_log("Error marking message as read: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a message
     * 
     * @param int $message_id The ID of the message
     * @param int $user_id The ID of the user deleting the message
     * @return bool Success or failure
     */
    public function deleteMessage($message_id, $user_id = null) {
            // If user_id is provided, mark as deleted only for that user
            if ($user_id) {
                $sql = "SELECT * FROM messages WHERE id = :message_id";
                $qry = $this->db->connect()->prepare($sql);
                $qry->bindParam(':message_id', $message_id);
                $qry->execute();
                $message = $qry->fetch(PDO::FETCH_ASSOC);
                
                if ($message) {
                    if ($message['sender_id'] == $user_id) {
                        $sql = "UPDATE messages SET deleted_by_sender = 1 WHERE id = :message_id";
                    } else if ($message['receiver_id'] == $user_id) {
                        $sql = "UPDATE messages SET deleted_by_receiver = 1 WHERE id = :message_id";
                    } else {
                        return false; // User doesn't own this message
                    }
                    
                    $qry = $this->db->connect()->prepare($sql);
                    $qry->bindParam(':message_id', $message_id);
                    return $qry->execute();
                }
                return false;
            } else {
                // If no user_id, completely delete the message
                $sql = "DELETE FROM messages WHERE id = :message_id";
                $qry = $this->db->connect()->prepare($sql);
                $qry->bindParam(':message_id', $message_id);
                return $qry->execute();
            }
    }
    
    /**
     * Get count of unread messages for a user
     * 
     * @param int $user_id The ID of the user
     * @return int The count of unread messages
     */
    public function getUnreadCount($user_id) {
        try {
            $sql = "SELECT COUNT(*) FROM messages 
                    WHERE receiver_id = :user_id 
                    AND is_read = 0 
                    AND deleted_by_receiver = 0";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':user_id', $user_id);
            $qry->execute();
            
            return $qry->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting unread count: " . $e->getMessage());
            return 0;
        }
    }
}
?>
