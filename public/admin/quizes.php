<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/auth.php';
require_admin();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $stmt = $pdo->prepare("INSERT INTO quizzes (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $desc]);
    header('Location: quizes.php');
    exit;
}


$quizzes = $pdo->query("SELECT * FROM quizzes ORDER BY created_at DESC")->fetchAll();
?>

<h2>Quizzes</h2>
<style>


.admin-dashboard, .page-content {
  max-width: 1000px;
  margin: 40px auto;
  padding: 20px 24px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 36px -10px rgba(0,0,0,0.08);
  font-family: system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
}

h2 {
  margin-top: 0;
  color: #1f2d3a;
  font-size: 1.8rem;
}

form {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 20px;
  align-items: flex-start;
}

form input {
  flex: 1;
  padding: 10px 14px;
  border: 1px solid #d1d9e6;
  border-radius: 6px;
  font-size: 0.95rem;
}

form button {
  padding: 10px 16px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: background .2s;
}

form button:hover {
  background: #1d4ed8;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 8px;
  font-size: 0.9rem;
}

th, td {
  padding: 10px 12px;
  border: 1px solid #e2e8f0;
  text-align: left;
}

th {
  background: #f1f5fa;
  font-weight: 600;
}

tr:nth-child(even) {
  background: #fafbfc;
}

a {
  color: #2563eb;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
<form method="post" style="margin-bottom:1em;">
  <input name="title" placeholder="Quiz title" required>
  <input name="description" placeholder="Description">
  <button type="submit">Create Quiz</button>
</form>

<table border="1" cellpadding="6" cellspacing="0">
  <tr><th>ID</th><th>Title</th><th>Created</th><th>Actions</th></tr>
  <?php foreach ($quizzes as $q): ?>
    <tr>
      <td><?= htmlspecialchars($q['id']) ?></td>
      <td><?= htmlspecialchars($q['title']) ?></td>
      <td><?= htmlspecialchars($q['created_at']) ?></td>
      <td>
        <a href="edit_quiz.php?id=<?= $q['id'] ?>">Edit</a> |
        <a href="questions.php?quiz_id=<?= $q['id'] ?>">Questions</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

