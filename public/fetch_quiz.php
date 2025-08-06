<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
require_login();
require_once __DIR__ . '/../src/db.php';

$quiz_id = intval($_GET['quiz_id'] ?? 0);
if ($quiz_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid quiz_id']);
    exit;
}


$stmt = $pdo->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode([
    'quiz_id' => $quiz_id,
    'questions' => $questions
]);
