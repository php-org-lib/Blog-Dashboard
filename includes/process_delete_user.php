<?php
global $pdo;
require('includes/config/db_config.php');
require('includes/config/functions.php');
require('classes/users.php');
require('classes/posts.php');
$users = new Users($pdo);
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
}
$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$userId) {
    redirect('index.php');
}

if($users->deleteUser($userId)) {
    redirect('user_list.php');
    echo "<script>alert('User deleted successfully.');</script>";
}
?>

