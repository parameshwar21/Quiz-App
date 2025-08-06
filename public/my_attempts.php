<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
require_login();
require_once __DIR__ . '/../src/db.php';

$user_id = $_SESSION['user_id'];
$sql = "
  SELECT a.*, q.title AS quiz_title
  FROM attempts a
  JOIN quizzes q ON a.quiz_id = q.id
  WHERE a.user_id = ?
  ORDER BY a.taken_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$attempts = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Attempts</title>
  <style>
    body{font-family:Arial; padding:20px; max-width:800px; margin:auto;}
    table{width:100%; border-collapse:collapse; margin-top:10px;}
    th,td{border:1px solid #ccc; padding:8px; text-align:left;}
  </style>
</head>
<body>
  <h1>My Quiz Attempts</h1>
  <?php if (!$attempts): ?>
    <p>You haven't taken any quizzes yet.</p>
  <?php else: ?>
    <table>
      <tr><th>Quiz</th><th>Score</th><th>Total</th><th>Taken At</th></tr>
      <?php foreach ($attempts as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['quiz_title']) ?></td>
          <td><?= htmlspecialchars($a['score']) ?></td>
          <td><?= htmlspecialchars($a['total_questions']) ?></td>
          <td><?= htmlspecialchars($a['taken_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
  <p><a href="index.php">Back to dashboard</a></p>
</body>
</html>
