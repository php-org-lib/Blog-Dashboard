<?php
global $pdo;
require_once __DIR__ . '/../autoload.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM friend_requests WHERE receiver_id = ? AND status = 'pending'");
$stmt->execute([$_SESSION['user_id']]);
$count = $stmt->fetchColumn();

echo json_encode(['count' => $count]);