<?php

function set_flash(string $message, string $type = 'info'): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash_message'] = [
        'text' => $message,
        'type' => $type
    ];
}


function get_flash(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['flash_message'])) return null;
    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    return $flash;
}


function display_flash(): void {
    $flash = get_flash();
    if (!$flash) return;

    $type = $flash['type'];
    $text = htmlspecialchars($flash['text']);
    $bg = match ($type) {
        'success' => '#d4edda',
        'error' => '#f8d7da',
        'warning' => '#fff3cd',
        default => '#cce5ff',
    };
    $border = match ($type) {
        'success' => '#c3e6cb',
        'error' => '#f5c6cb',
        'warning' => '#ffeeba',
        default => '#b8daff',
    };
    echo <<<HTML
<div style="padding:10px; margin-bottom:12px; background:$bg; border:1px solid $border; border-radius:5px;">
  <strong>{$type}</strong>: {$text}
</div>
HTML;
}
