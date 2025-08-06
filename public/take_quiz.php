<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
require_login();
require_once __DIR__ . '/../src/db.php';

$quiz_id = intval($_GET['quiz_id'] ?? 0);
if ($quiz_id <= 0) {
    die("Invalid or missing quiz_id in URL. Example: take_quiz.php?quiz_id=1");
}

$stmt = $pdo->prepare("SELECT title, description FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();
if (!$quiz) {
    die("Quiz not found (ID: {$quiz_id}).");
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($quiz['title']) ?> - Take Quiz</title>
  <style>
   body {
  font-family: Arial, sans-serif;
  background-color: #f8f9fa;
  color: #333;
  padding: 20px;
  max-width: 800px;
  margin: auto;
}

h1 {
  font-size: 28px;
  color: #2c3e50;
  margin-bottom: 10px;
}

p {
  font-size: 16px;
  color: #555;
  margin-bottom: 20px;
}

.question {
  background-color: #ffffff;
  border: 1px solid #ccc;
  padding: 15px 20px;
  margin-bottom: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.question strong {
  color: #1d3557;
}

.options label {
  display: block;
  margin: 8px 0;
  cursor: pointer;
}

input[type="radio"] {
  margin-right: 8px;
}

button#submit-btn {
  padding: 12px 20px;
  font-size: 16px;
  background-color: #1abc9c;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s;
}

button#submit-btn:hover:enabled {
  background-color: #16a085;
}

button:disabled {
  background-color: #bdc3c7;
  cursor: not-allowed;
}

#error, .error {
  color: red;
  font-weight: bold;
  margin: 10px 0;
}

#result {
  margin-top: 20px;
  font-weight: bold;
  font-size: 18px;
}
  </style>
</head>
<body>
  <h1><?= htmlspecialchars($quiz['title']) ?></h1>
  <p><?= htmlspecialchars($quiz['description']) ?></p>
  <div id="quiz-container">Loading questions...</div>
  <button id="submit-btn" disabled>Submit</button>
  <div id="result"></div>

  <script>
    const quizId = <?= json_encode($quiz_id) ?>;
    let questions = [];

    function showError(msg) {
      const container = document.getElementById('quiz-container');
      container.innerHTML = `<div class="error">${msg}</div>`;
    }

    async function loadQuiz() {
      try {
        const res = await fetch(`fetch_quiz.php?quiz_id=${quizId}`);
        if (!res.ok) {
          showError(`Failed to load quiz: ${res.status} ${res.statusText}`);
          console.error('fetch_quiz.php response', await res.text());
          return;
        }
        const data = await res.json();
        if (!data.questions || !Array.isArray(data.questions)) {
          showError('Malformed response from server. Check fetch_quiz.php output in network tab.');
          console.error('Response JSON', data);
          return;
        }
        questions = data.questions;
        const container = document.getElementById('quiz-container');
        if (!questions.length) {
          container.innerHTML = '<p>No questions available for this quiz.</p>';
          return;
        }
        container.innerHTML = '';
        questions.forEach((q, idx) => {
          const div = document.createElement('div');
          div.className = 'question';
          div.innerHTML = `
            <div><strong>Q${idx+1}:</strong> ${escapeHTML(q.question_text)}</div>
            <div class="options">
              ${renderOption(q, 'A', q.option_a)}
              ${renderOption(q, 'B', q.option_b)}
              ${renderOption(q, 'C', q.option_c)}
              ${renderOption(q, 'D', q.option_d)}
            </div>
          `;
          container.appendChild(div);
        });
        document.getElementById('submit-btn').disabled = false;
      } catch (err) {
        showError('Error fetching quiz. See console.'); 
        console.error(err);
      }
    }

    function renderOption(q, label, text) {
      if (!text) return '';
      return `<label><input type="radio" name="q_${q.id}" value="${label}"> ${label}. ${escapeHTML(text)}</label>`;
    }

    function escapeHTML(s) {
      return s ? s.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;') : '';
    }

    document.getElementById('submit-btn').addEventListener('click', async () => {
      const answers = questions.map(q => {
        const sel = document.querySelector(`input[name="q_${q.id}"]:checked`);
        return { question_id: q.id, selected: sel ? sel.value : '' };
      });
      try {
        const resp = await fetch('submit_quiz.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ quiz_id: quizId, answers })
        });
        if (!resp.ok) {
          document.getElementById('result').textContent = `Submit failed: ${resp.status} ${resp.statusText}`;
          console.error('submit_quiz.php response', await resp.text());
          return;
        }
        const result = await resp.json();
        if (result.score !== undefined) {
          document.getElementById('result').textContent = `You scored ${result.score} out of ${result.total}.`;
        } else {
          document.getElementById('result').textContent = 'Error: ' + (result.error || 'Unexpected response');
          console.error('Result JSON', result);
        }
      } catch (err) {
        document.getElementById('result').textContent = 'Network error when submitting. See console.';
        console.error(err);
      }
    });

    loadQuiz();
  </script>
</body>
</html>
