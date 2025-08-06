<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/auth.php';
require_admin();

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$id]);
$quiz = $stmt->fetch();
if (!$quiz) {
    die("Quiz not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $upd = $pdo->prepare("UPDATE quizzes SET title = ?, description = ? WHERE id = ?");
    $upd->execute([$title, $desc, $id]);
    header("Location: quizzes.php");
    exit;
}
?>


<div class="page-content">
  <h2>Edit Quiz #<?= htmlspecialchars($id) ?></h2>
  <style>
    .page-content {
  max-width: 800px;
  margin: 40px auto;
  padding: 24px 28px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 14px 36px -10px rgba(0,0,0,0.08);
  font-family: system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
}

.page-content h2 {
  margin-top: 0;
  color: #1f2d3a;
  font-size: 1.75rem;
}

.edit-quiz-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 12px;
}

.field input,
.field textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #d1d9e6;
  border-radius: 6px;
  font-size: 1rem;
  resize: vertical;
}

.actions {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-top: 8px;
}

.primary-btn {
  padding: 10px 18px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: background .2s;
}

.primary-btn:hover {
  background: #1d4ed8;
}

.secondary-link {
  color: #2563eb;
  text-decoration: none;
  font-size: 0.9rem;
}

.secondary-link:hover {
  text-decoration: underline;
}
  </style>
  <form method="post" class="edit-quiz-form">
    <div class="field">
      <label>Title:<br>
        <input name="title" value="<?= htmlspecialchars($quiz['title']) ?>" required>
      </label>
    </div>
    <div class="field">
      <label>Description:<br>
        <textarea name="description" rows="4"><?= htmlspecialchars($quiz['description']) ?></textarea>
      </label>
    </div>
    <div class="actions">
      <button type="submit" class="primary-btn">Save</button>
      <a href="quizes.php" class="secondary-link">← Back to quizzes</a>
    </div>
  </form>
</div>


