<?php
session_start();
require_once __DIR__ . '/../../src/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash, is_admin FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $user['is_admin'] && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = 1;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid admin credentials.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <style>
    * { box-sizing:border-box; }
    body {
      background: #f0f4f8;
      font-family: system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin:0;
      padding:0;
    }
    .login-card {
      background: #ffffff;
      padding: 32px 28px;
      border-radius: 12px;
      width: 360px;
      box-shadow: 0 16px 40px -10px rgba(0,0,0,0.1);
      position: relative;
    }
    h2 {
      margin-top: 0;
      margin-bottom: 16px;
      font-size: 1.5rem;
      color: #1f2d3a;
    }
    .field {
      margin-bottom: 14px;
    }
    .field input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #d1d9e6;
      border-radius: 6px;
      font-size: 0.95rem;
      outline: none;
      transition: border-color .2s;
    }
    .field input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
    }
    .btn {
      width: 100%;
      padding: 12px;
      background: #2563eb;
      border: none;
      color: white;
      font-size: 1rem;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background .2s;
    }
    .btn:hover { background: #1d4ed8; }
    .error {
      background: #ffe3e3;
      color: #b80f0f;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 12px;
      font-size: 0.9rem;
    }
    .small {
      font-size: 0.85rem;
      color: #556b88;
      margin-top: 8px;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <div class="field">
        <input type="text" name="username" required placeholder="Username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
      </div>
      <div class="field">
        <input type="password" name="password" required placeholder="Password">
      </div>
      <button class="btn" type="submit">Login</button>
    </form>
    <div class="small">Only the designated admin can log in here.</div>
  </div>
</body>
</html>
