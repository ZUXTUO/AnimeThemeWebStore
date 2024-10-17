<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];

    // 使用 prepared statements 防止 SQL 注入
    $stmt = $pdo->prepare("SELECT * FROM user WHERE Id = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);

    if ($stmt->rowCount() > 0) {
        // 获取用户数据
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 登录成功
        $_SESSION['username'] = $user['name'];
        
        // 设置 cookie，有效期为 7 天
        setcookie('username', $user['name'], time() + (86400 * 7), "/"); // "/" 表示在整个网站下都有效
        
        // 返回成功信息和 cookie 内容
        echo json_encode([
            'success' => true,
            'cookies' => $_COOKIE // 显示当前 cookies
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '用户名或密码错误']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求']);
}
?>
