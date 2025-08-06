<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';

require_login();


$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$username = $user ? $user['username'] : 'User';


$quizzes = $pdo->query("SELECT id, title, description FROM quizzes ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>User Dashboard - Quiz App</title>
  <style>
    * { box-sizing: border-box; }

body {
  margin: 0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, Arial, sans-serif;
  background: #f0f4f9;
  color: #1f2d3a;
  line-height: 1.4;
  padding: 20px;
}

.container {
  max-width: 960px;
  margin: 0 auto;
  background: #fff;
  padding: 24px 30px;
  border-radius: 10px;
  box-shadow: 0 18px 48px -12px rgba(31,45,58,0.08);
}

h1 {
  margin-top: 0;
  font-size: 2rem;
}

nav {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 8px;
}

nav a {
  text-decoration: none;
  padding: 8px 14px;
  background: #2563eb;
  color: #fff;
  border-radius: 6px;
  font-weight: 500;
  transition: background .2s;
}

nav a:hover {
  background: #1d4ed8;
}

hr {
  border: none;
  border-top: 1px solid #e2e8f0;
  margin: 16px 0;
}

.quiz-list {
  margin-top: 24px;
  display: grid;
  gap: 16px;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
}

.quiz-item {
  background: #f9fbfe;
  padding: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.quiz-item strong {
  display: block;
  font-size: 1.1rem;
}

.quiz-item small {
  color: #556b88;
}

.quiz-item a {
  margin-top: auto;
  align-self: flex-start;
  padding: 8px 12px;
  background: #10b981;
  color: white;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  transition: background .2s;
}

.quiz-item a:hover {
  background: #0f9f72;
}

@media (max-width: 700px) {
  .container {
    padding: 18px 20px;
  }
  nav {
    flex-direction: column;
  }
}
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
    <nav>
      
      <a href="my_attempts.php">My Attempts</a>
      <a href="logout.php">Logout</a>
      <?php if (!empty($_SESSION['is_admin'])): ?>
       <a href="admin/index.php">Admin Dashboard</a>
      <?php endif; ?>
    </nav>
    <hr>
    <p>This is your user dashboard. From here, you can start quizzes, review your past attempts, or log out.</p>

    <div class="quiz-list">
      <h2>Available Quizzes</h2>
      <?php if (!$quizzes): ?>
        <p>No quizzes have been created yet.</p>
      <?php else: ?>
        <?php foreach ($quizzes as $q): ?>
          <div class="quiz-item">
            <strong><?= htmlspecialchars($q['title']) ?></strong><br>
            <small><?= htmlspecialchars($q['description']) ?></small><br>
            <a href="take_quiz.php?quiz_id=<?= urlencode($q['id']) ?>">Take Quiz</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
