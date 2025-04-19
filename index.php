<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="navbar">
        <div class="nav-left">
            <a href="index.php">Task Manager</a>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <h1>Welcome to Task Manager</h1>
            <p>Please Login or Register</p>
            <div>
                <a href="login.php" class="button">Login</a>
                <a href="register.php" class="button">Register</a>
            </div>
        <?php else: ?>
            <h1>Welcome back!</h1>
            <p><a href="task.php" class="button">Go to Tasks</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
