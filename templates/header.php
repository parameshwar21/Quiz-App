<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Quiz Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
  <nav>
    <a href="/public/index.php">Home</a> |
    <a href="/public/admin/index.php">Admin</a> |
    <a href="/logout.php">Logout</a>
  </nav>
  <hr>

  <?php
require_once __DIR__ . '/flash.php';
display_flash();
?>
