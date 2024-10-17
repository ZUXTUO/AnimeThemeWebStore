<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// 检查用户是否已通过会话或 Cookie 登录
if (!isset($_SESSION['username']) && !isset($_COOKIE['username'])) {
    // 用户未登录
    echo json_encode(['error' => '用户未登录']);
    exit();
}

// 获取会话或 Cookie 中的用户名
$username = $_SESSION['username'] ?? $_COOKIE['username'];

// 使用 prepared statements 防止 SQL 注入
$stmt = $pdo->prepare("SELECT * FROM user WHERE name = :username"); // 选择用户表中的所有字段
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user); // 返回用户的全部信息
} else {
    echo json_encode(['error' => '无法获取用户信息']);
}
?>
