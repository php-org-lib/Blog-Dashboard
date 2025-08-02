<?php
global $pdo;
require('includes/config/db_config.php');
require('includes/config/functions.php');
require('classes/users.php');
require('classes/posts.php');
include('includes/head.php');
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
    echo "<script>alert('You are not logged in. Please login to view this page.');</script>";
}
$posts = new Posts($pdo);
$postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if($postId == null) {
    redirect('index.php');
}
?>

<section id="section">
</section>
<?php include("includes/foot.php"); ?>
