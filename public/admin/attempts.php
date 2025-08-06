<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/auth.php';
require_admin();


$sql = "
SELECT a.*, u.username, q.title AS quiz_title 
FROM attempts a
JOIN users u ON a.user_id = u.id
JOIN quizzes q ON a.quiz_id = q.id
ORDER BY a.taken_at DESC
";
$attempts = $pdo->query($sql)->fetchAll();
?>


<style>
  .attempts-wrapper {
  max-width: 1000px;
  margin: 40px auto;
  padding: 24px 28px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 36px -10px rgba(0,0,0,0.08);
  font-family: system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
}

.attempts-wrapper h2 {
  margin-top: 0;
  font-size: 1.8rem;
  color: #1f2d3a;
}

table.attempts {
  width: 100%;
  border-collapse: collapse;
  margin-top: 16px;
  font-size: 0.95rem;
}

table.attempts th,
table.attempts td {
  padding: 12px 14px;
  border: 1px solid #e2e8f0;
  text-align: left;
}

table.attempts th {
  background: #f1f5fa;
  font-weight: 600;
}

table.attempts tr:nth-child(even) {
  background: #fafbfc;
}
</style>

<div class="attempts-wrapper">
  <h2>All Attempts</h2>
  <table class="attempts">
    <tr>
      <th>User</th><th>Quiz</th><th>Score</th><th>Total</th><th>Taken At</th>
    </tr>
    <?php foreach ($attempts as $a): ?>
      <tr>
        <td><?= htmlspecialchars($a['username']) ?></td>
        <td><?= htmlspecialchars($a['quiz_title']) ?></td>
        <td><?= htmlspecialchars($a['score']) ?></td>
        <td><?= htmlspecialchars($a['total_questions']) ?></td>
        <td><?= htmlspecialchars($a['taken_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
