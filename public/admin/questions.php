<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/auth.php';
require_admin();

$quiz_id = intval($_GET['quiz_id'] ?? 0);
if (!$quiz_id) {
    die("quiz_id required.");
}

$qstmt = $pdo->prepare("SELECT title FROM quizzes WHERE id = ?");
$qstmt->execute([$quiz_id]);
$quiz = $qstmt->fetch();
if (!$quiz) die("Quiz not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
    $question_text = trim($_POST['question_text']);
    $a = trim($_POST['option_a']);
    $b = trim($_POST['option_b']);
    $c = trim($_POST['option_c']);
    $d = trim($_POST['option_d']);
    $correct = strtoupper($_POST['correct_option']);
    $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$quiz_id, $question_text, $a, $b, $c, $d, $correct]);
    header("Location: questions.php?quiz_id=$quiz_id");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();
?>


<div class="page-content">
  <h2>Questions for "<?= htmlspecialchars($quiz['title']) ?>"</h2>
  <style>
    .page-content {
  max-width: 1000px;
  margin: 40px auto;
  padding: 24px 28px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 36px -10px rgba(0,0,0,0.08);
  font-family: system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
}

.page-content h2 {
  margin-top: 0;
  font-size: 1.8rem;
  color: #1f2d3a;
}

form.add-question {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 24px;
}

.add-question .group {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
}

.add-question textarea,
.add-question input,
.add-question select {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #d1d9e6;
  border-radius: 6px;
  font-size: 1rem;
  resize: vertical;
}

.add-question .two-col {
  flex: 1 1 48%;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.add-question button {
  width: fit-content;
  padding: 10px 18px;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: background .2s;
}

.add-question button:hover {
  background: #1d4ed8;
}

.table-wrapper {
  overflow-x: auto;
}

table.questions {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
}

table.questions th,
table.questions td {
  padding: 10px 12px;
  border: 1px solid #e2e8f0;
  text-align: left;
}

table.questions th {
  background: #f1f5fa;
  font-weight: 600;
}

table.questions tr:nth-child(even) {
  background: #fafbfc;
}

.badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.75rem;
  background: #eef4ff;
  color: #2563eb;
}
  </style>

  <form method="post" class="add-question">
    <div class="group">
      <div class="two-col">
        <label>Question:<br>
          <textarea name="question_text" required rows="3"></textarea>
        </label>
      </div>
    </div>

    <div class="group">
      <div class="two-col">
        <label>Option A:<br><input name="option_a" required></label>
      </div>
      <div class="two-col">
        <label>Option B:<br><input name="option_b" required></label>
      </div>
    </div>
    <div class="group">
      <div class="two-col">
        <label>Option C:<br><input name="option_c"></label>
      </div>
      <div class="two-col">
        <label>Option D:<br><input name="option_d"></label>
      </div>
    </div>

    <div class="group">
      <div class="two-col">
        <label>Correct Option:<br>
          <select name="correct_option" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
        </label>
      </div>
    </div>

    <button type="submit">Add Question</button>
  </form>

  <div class="table-wrapper">
    <table class="questions">
      <thead>
        <tr>
          <th>ID</th>
          <th>Question</th>
          <th>Correct</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($questions as $q): ?>
          <tr>
            <td><?= htmlspecialchars($q['id']) ?></td>
            <td><?= htmlspecialchars($q['question_text']) ?></td>
            <td><span class="badge"><?= htmlspecialchars($q['correct_option']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


