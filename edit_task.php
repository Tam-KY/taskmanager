<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Not logged in');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $taskTitle = $_POST['task_title']; 
    $dueDate = $_POST['due_date'];

    if (empty($taskTitle) || empty($dueDate)) {
        echo json_encode(['success' => false, 'message' => 'Task Title and Due Date are required.']);
        exit;
    }

    $sql = "UPDATE tasks SET title = :title, due_date = :due_date WHERE id = :task_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'title' => $taskTitle,
        'due_date' => $dueDate,
        'task_id' => $taskId,
        'user_id' => $_SESSION['user_id']
    ]);

    if ($stmt->rowCount()) {
        echo json_encode(['success' => true, 'message' => 'Task updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task.']);
    }
}
?>
