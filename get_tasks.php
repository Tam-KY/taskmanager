<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Not logged in');
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$tasks = $stmt->fetchAll();

ob_start();
?>

<table class="task-table">
    <thead>
        <tr>
            <th>Task Title</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr class="<?php echo $task['status'] === 'completed' ? 'completed' : ''; ?>">
                <td><?php echo htmlspecialchars($task['title']); ?></td>
                <td><?php echo $task['due_date']; ?></td>
                <td><?php echo ucfirst($task['status']); ?></td>
                <td>
                    <?php if ($task['status'] === 'pending'): ?>
                        <button onclick="markAsCompleted(<?php echo $task['id']; ?>, this)">Mark as Completed</button>
                    <?php else: ?>
                        <span class="completed-label">✔ Completed</span>
                    <?php endif; ?>
                    <button onclick="editTask(<?php echo $task['id']; ?>, '<?php echo addslashes($task['title']); ?>')">Edit</button>
                    <button onclick="deleteTask(<?php echo $task['id']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
echo ob_get_clean();
?>
