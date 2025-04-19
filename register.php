<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in the blank";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        try {
            
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            if ($stmt->fetch()) {
                $error = "Username already exists";
            } else {
               
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();
                header("Location: login.php?registered=1");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database Error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
   
    <div class="navbar">
        <div class="nav-left">
            <a href="index.php">Task Manager</a>
        </div>
        <div class="nav-right">
            <a href="login.php">Login</a>
        </div>
    </div>

    <div class="container">
        <h1>Register</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST" class="register-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
            <p>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
            <p>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <p>
            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login Here</a></p>
    </div>

</body>
</html>
