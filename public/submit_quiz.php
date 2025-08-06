<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
require_login();
require_once __DIR__ . '/../src/db.php';

$payload = json_decode(file_get_contents('php://input'), true);
if (!isset($payload['quiz_id'], $payload['answers']) || !is_array($payload['answers'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad request']);
    exit;
}

$quiz_id = intval($payload['quiz_id']);
$answers = $payload['answers'];

$user_id = $_SESSION['user_id'];
$total = count($answers);
if ($total === 0) {
    echo json_encode(['error' => 'No answers submitted']);
    exit;
}


$question_ids = array_map(fn($a) => intval($a['question_id']), $answers);
$placeholders = implode(',', array_fill(0, count($question_ids), '?'));
$stmt = $pdo->prepare("SELECT id, correct_option FROM questions WHERE id IN ($placeholders)");
$stmt->execute($question_ids);
$correct_map = [];
foreach ($stmt->fetchAll() as $row) {
    $correct_map[$row['id']] = strtoupper($row['correct_option']);
}

$score = 0;
foreach ($answers as $ans) {
    $qid = intval($ans['question_id']);
    $sel = strtoupper($ans['selected'] ?? '');
    if (isset($correct_map[$qid]) && $correct_map[$qid] === $sel) {
        $score++;
    }
}


$insert = $pdo->prepare("INSERT INTO attempts (user_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)");
$insert->execute([$user_id, $quiz_id, $score, $total]);

header('Content-Type: application/json');
echo json_encode([
    'score' => $score,
    'total' => $total
]);
