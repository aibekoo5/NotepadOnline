<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $check_query = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check_query->execute([$email]);
        $count = $check_query->fetchColumn();

        if ($count > 0) {
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

            $result = $query->execute([
                $username,
                $email,
                $hashed_password
            ]);

            if ($result) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
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
</head>
<body>
    <div class="auth-page">
        <h2>Register</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" required>
            <label for="username">Email</label>
            <input type="email" name="email" placeholder="Email" required>
            <label for="username">Password</label>
            <input type="password" name="password" placeholder="Password" required>
            <label for="username">Confirm password</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>