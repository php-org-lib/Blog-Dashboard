<?php
global $pdo;
require_once __DIR__ . '/../autoload.php';
$success = "";
$error = [];
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int) $_POST['post_id'];

    $posts = new Posts($pdo);
    $deleted = $posts->deletePost($post_id);

    if($deleted) {
        $success = "Post deleted successfully.";
        redirect('../blog.php');
    }
} else {
    $error[] = "Something went wrong. Please try again.";
}

?>

