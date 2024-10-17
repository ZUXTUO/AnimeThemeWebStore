<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// Check if the user is logged in via session or cookie
if (!isset($_SESSION['username']) && !isset($_COOKIE['username'])) {
    // User is not logged in
    echo json_encode(['error' => '用户未登录']);
    exit();
}

// Get the username from session or cookie
$username = $_SESSION['username'] ?? $_COOKIE['username'];

// 使用 prepared statements 防止 SQL 注入
$stmt = $pdo->prepare("SELECT id FROM user WHERE name = :username"); // Assuming 'name' is the correct column
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['id' => $user['id'], 'name' => $username]);
} else {
    echo json_encode(['error' => '无法获取用户信息']);
}
?>
