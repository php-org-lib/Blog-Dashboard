<?php
global $pdo;
require_once __DIR__ . '/../autoload.php';
$user_id = $_SESSION['user_id'] ?? null;
$friend_id = $_POST['friend_id'] ?? null;
$action = $_POST['action'] ?? '';
$success = '';
$error = [];
$message = '';
if(!$user_id || !$friend_id || $user_id == $friend_id) {
    $error[] = "Something went wrong. Please try again.";
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}
$friendship = new Friendship($pdo);
if($action == 'add') {
    $success_action = $friendship->addFriend($user_id, $friend_id);
    $success = "Friend added successfully.";
} elseif($action == 'remove') {
    $success_action = $friendship->removeFriend($user_id, $friend_id);
    $success = "Friend removed successfully.";
} else {
    $error[] = "Something went wrong. Please try again.";
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

echo json_encode(['success_action' => $success_action]);