<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($due_date)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, due_date) VALUES (:user_id, :title, :due_date)");
            $stmt->execute([
                'user_id' => $user_id,
                'title' => $title,
                'due_date' => $due_date
            ]);
            header("Location: task.php");
            exit;
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <a href="index.php">Task Manager</a>
    </div>
    <div class="nav-right">
        <a href="task.php">Back to Tasks</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Create New Task</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="create_task.php">
        <label for="title">Task Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="due_date">Due Date:</label><br>
        <input type="date" id="due_date" name="due_date" required><br><br>

        <button type="submit">Create Task</button>
    </form>
</div>

</body>
</html>
