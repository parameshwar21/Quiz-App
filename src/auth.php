<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

function require_admin() {
    if (!is_admin()) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /Quiz-App/public/login.php');
        exit;
    }
}
