<?php 
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter username and password";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: task.php");
                exit();
            } else {
                $error = "Incorret username or password";
            }
        } catch (PDOException $e) {
            $error = "Database error：" . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   
    <div class="navbar">
        <div class="nav-left">
            <a href="index.php">Task Manager</a>
        </div>
        <div class="nav-right">
            <a href="register.php" class="nav-item nav-link">Register</a> 
        </div>
    </div>

    <div class="container">
        <h1>Login</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST" class="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Please enter username" required>
            <p>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Please enter password" required>
            <p>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
