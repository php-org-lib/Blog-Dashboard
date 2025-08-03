<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include("includes/head.php");
$user = new Users($pdo);
$user->logout();
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
}
?>

<?php include("includes/foot.php"); ?>