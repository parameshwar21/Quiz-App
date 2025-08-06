<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../templates/flash.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        set_flash('Both username and password are required.', 'error');
    } else {
        $stmt = $pdo->prepare("SELECT id, password_hash, is_admin FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = (int)$user['is_admin'];

            set_flash('Login successful.', 'success');
            if ($_SESSION['is_admin']) {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            set_flash('Invalid username or password.', 'error');
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <?php
    require_once __DIR__ . '/../templates/flash.php';
    display_flash();
  ?>
  <div class="auth-card">
  <h2>Login</h2>
  <style>
    * { box-sizing: border-box; }

body {
  margin: 0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
  background: linear-gradient(135deg,#e8f0fe 0%,#f7f9fc 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
}

.auth-card {
  background: #fff;
  max-width: 420px;
  width: 100%;
  padding: 32px 28px;
  border-radius: 12px;
  box-shadow: 0 20px 60px -10px rgba(31,45,58,0.08);
  position: relative;
}

.auth-card h2 {
  margin: 0 0 18px;
  font-size: 1.6rem;
  color: #1f2d3a;
}

.auth-card form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.auth-card label {
  font-size: 0.9rem;
  color: #555;
}

.auth-card input {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #d1d9e6;
  border-radius: 6px;
  font-size: 1rem;
  outline: none;
  transition: border-color .2s, box-shadow .2s;
}

.auth-card input:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
}

button[type="submit"] {
  padding: 12px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: filter .2s;
}

button[type="submit"]:hover {
  filter: brightness(1.1);
}

.flash {
  margin-bottom: 16px;
  padding: 12px 16px;
  border-radius: 6px;
  font-size: 0.9rem;
}

.flash.success {
  background: #e6f4ea;
  color: #1f6d3e;
  border: 1px solid #b7e1c9;
}

.flash.error {
  background: #ffe3e3;
  color: #912d2d;
  border: 1px solid #f4b5b5;
}

.small {
  font-size: 0.85rem;
  color: #666;
  margin-top: 6px;
}

a {
  color: #2563eb;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
  </style>
  <?php display_flash(); ?>
  <form method="post" autocomplete="off">
    <label>Username
      <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>
    <button type="submit">Login</button>
  </form>
  <p class="small">No account? <a href="register.php">Register</a></p>
</div>
