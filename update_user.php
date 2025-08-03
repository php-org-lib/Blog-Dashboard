<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include("includes/head.php");
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
