<?php
require_once __DIR__ . '/../src/db.php';

try {
    $stmt = $pdo->query("SELECT DATABASE() as db");
    $row = $stmt->fetch();
    echo "Connected to database: " . htmlspecialchars($row['db']);
} catch (Exception $e) {
    echo "Test failed: " . $e->getMessage();
}
?>
