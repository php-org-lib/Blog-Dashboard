<?php
global $pdo;
require_once __DIR__ . '/../autoload.php';
$comment_id = $_POST['comment_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$post_id = $_POST['post_id'] ?? null;

if ($comment_id && $user_id) {
    $comments = new Comments($pdo);
    $comments->deleteComment((int)$comment_id, (int)$user_id);
}

redirect("../post.php?id=$post_id");
?>