<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['task_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized or invalid data']);
    exit;
}

$user_id = $_SESSION['user_id'];
$task_id = (int)$_POST['task_id'];

$stmt = $pdo->prepare("UPDATE tasks SET status = 'completed' WHERE id = :id AND user_id = :user_id");
$success = $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);

echo json_encode(['success' => $success]);
exit;
