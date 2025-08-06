<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/auth.php';
require_admin();
?>

<div class="admin-dashboard">
  <h1>Admin Dashboard</h1>
  <style>
    .admin-dashboard {
  max-width: 900px;
  margin: 40px auto;
  background: #fff;
  padding: 24px 28px;
  border-radius: 10px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
  font-family: system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
}

.admin-dashboard h1 {
  margin-top: 0;
  color: #1f2d3a;
  font-size: 1.9rem;
}

.admin-nav {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 16px;
}

.admin-nav a {
  padding: 10px 16px;
  background: #2563eb;
  color: #fff;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  transition: background .2s;
}

.admin-nav a:hover {
  background: #1d4ed8;
}
  </style>
  <nav class="admin-nav">
    <a href="quizes.php">Manage Quizzes</a>
    <a href="questions.php">Manage Questions</a>
    <a href="attempts.php">View Attempts</a>
    <a href="logout.php">Logout</a>
  </nav>
</div>

