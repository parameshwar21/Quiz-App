<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../templates/flash.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        set_flash('All fields are required.', 'error');
    } else {
        // uniqueness check
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            set_flash('Username or email already taken.', 'error');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (username, email, password_hash, is_admin) VALUES (?, ?, ?, 0)");
            $insert->execute([$username, $email, $hash]);
            set_flash('Registered successfully. Please log in.', 'success');
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title></head>
<body>
  <?php
    
    require_once __DIR__ . '/../templates/flash.php';
    display_flash();
  ?>
  <h2>Register</h2>
  <form method="post">
    <label>Username:<br><input name="username" required></label><br>
    <label>Email:<br><input name="email" type="email" required></label><br>
    <label>Password:<br><input type="password" name="password" required></label><br>
    <button type="submit">Register</button>
  </form>
  <p>Already have account? <a href="login.php">Login</a></p>
</body>
</html>
