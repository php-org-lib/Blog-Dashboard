<?php
class FriendRequest {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function sendRequest($sender_id, $receiver_id): bool {
        $sql = "INSERT INTO friend_requests (sender_id, receiver_id) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$sender_id, $receiver_id]);
    }

    public function respondToRequest($request_id, $status): bool {
        if (!in_array($status, ['accepted', 'declined'])) return false;
        $sql = "UPDATE friend_requests SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $request_id]);
    }

    public function getPendingRequests($user_id): array {
        $sql = "SELECT fr.*, u.username AS sender_name, u.avatar AS sender_avatar
            FROM friend_requests fr
            JOIN users u ON fr.sender_id = u.id
            WHERE fr.receiver_id = ? AND fr.status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function isFriends($user_id, $friend_id): bool {
        $sql = "SELECT 1 FROM friend_requests 
            WHERE ((sender_id = ? AND receiver_id = ?) 
               OR (sender_id = ? AND receiver_id = ?)) 
              AND status = 'accepted'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $friend_id, $friend_id, $user_id]);
        return $stmt->fetchColumn() !== false;
    }

    public function getFriends($user_id): array {
        $sql = "SELECT u.id, u.username, u.full_name, u.avatar, u.email, u.date_created
                FROM friend_requests fr
                JOIN users u ON (
                    (fr.sender_id = ? AND fr.receiver_id = u.id) OR
                    (fr.receiver_id = ? AND fr.sender_id = u.id))
                WHERE fr.status = 'accepted'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetchAll();
    }
}