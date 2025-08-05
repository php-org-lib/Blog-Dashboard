<?php
global $pdo;
require_once __DIR__ . '/../autoload.php';
$user_id = $_SESSION['user_id'] ?? null;
$action = $_POST['action'] ?? null;

$request = new FriendRequest($pdo);
if ($action === 'send') {
    $receiver_id = $_POST['receiver_id'] ?? null;
    if ($user_id && $receiver_id) {
        $request->sendRequest($user_id, $receiver_id);
        echo json_encode(['success' => true]);
    }
}

if ($action === 'respond') {
    $request_id = $_POST['request_id'] ?? null;
    $status = $_POST['status'] ?? null;
    if ($request_id && in_array($status, ['accepted', 'declined'])) {
        $request->respondToRequest($request_id, $status);
        echo json_encode(['success' => true]);
    }
}