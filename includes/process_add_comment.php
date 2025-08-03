<?php
global $pdo;
require_once '../autoload.php';

$user_id = $_SESSION['user_id'] ?? null;
$post_id = $_POST['post_id'] ?? null;
$content = trim($_POST['content'] ?? '');

if ($user_id && $post_id && !empty($content)) {
    $comments = new Comments($pdo);
    $comments->addComment($post_id, $user_id, $content);
}

redirect("../post.php?id=$post_id");
exit;