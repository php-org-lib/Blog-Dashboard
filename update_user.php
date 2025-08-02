<?php
global $pdo;
require('includes/config/db_config.php');
require('includes/config/functions.php');
require('classes/users.php');
require('classes/posts.php');
include('includes/head.php');
$users = new Users($pdo);
$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$userId) {
    redirect('index.php');
} else {
    $us = $users->getUserById($userId);
}

?>

<section id="section">
</section>
<?php include("includes/foot.php"); ?>
