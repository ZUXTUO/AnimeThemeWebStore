<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// 检查用户是否通过会话或 Cookie 登录
if (!isset($_SESSION['username']) && !isset($_COOKIE['username'])) {
    // 用户未登录
    echo json_encode(['error' => '用户未登录']);
    exit();
}

// 从会话或 Cookie 中获取用户名
$username = $_SESSION['username'] ?? $_COOKIE['username'];

// 使用预处理语句防止 SQL 注入
$stmt = $pdo->prepare("SELECT id FROM user WHERE name = :username"); // 假设 'name' 是正确的列名
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['id' => $user['id'], 'name' => $username]);
} else {
    echo json_encode(['error' => '无法获取用户信息']);
}
?>
