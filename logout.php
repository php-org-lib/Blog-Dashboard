<?php
global $pdo;
include("includes/config/db_config.php");
include("includes/config/functions.php");
include("classes/users.php");
include("includes/head.php");
$user = new Users($pdo);
$user->logout();
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
}
?>

<?php include("includes/foot.php"); ?>