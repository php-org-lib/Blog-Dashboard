<?php
require_once("includes/config/db_config.php");
class Posts {
    private $pdo;
    private $table = "posts";
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getPosts(): array {
        $sql = "SELECT 
                    posts.*, 
                    users.full_name AS author_name,
                    users.username AS author_username,
                    users.email AS author_email,
                    users.date_created AS author_date_created,
                    users.avatar AS author_avatar
                FROM posts
                JOIN users ON posts.user_id = users.id
                ORDER BY posts.date_created DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPostById(int $postId) {
        $sql = "SELECT 
                    posts.*, 
                    users.full_name AS author_name,
                    users.avatar AS author_avatar,
                    users.email AS author_email,
                    users.username AS author_username
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.id = :postId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        return $post ?: null;
    }

}