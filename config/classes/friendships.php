<?php
class Friendship {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFriend($user_id, $friend_id): bool {
        $sql = "INSERT IGNORE INTO friendships (user_id, friend_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $friend_id]);
    }

    public function removeFriend($user_id, $friend_id): bool {
        $sql = "DELETE FROM friendships WHERE user_id = ? AND friend_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $friend_id]);
    }

    public function isFriend($user_id, $friend_id): bool {
        $sql = "SELECT 1 FROM friendships WHERE user_id = ? AND friend_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $friend_id]);
        return $stmt->fetchColumn() !== false;
    }
}