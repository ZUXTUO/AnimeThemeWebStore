<?php
session_start(); // 启动会话

// 清除用户的会话数据
$_SESSION['user_id'] = null; // 假设你存储用户ID的会话变量
$_SESSION['username'] = null; // 假设你存储用户名的会话变量

// 销毁会话
session_destroy();

// 返回成功响应
echo json_encode(['success' => true]);
?>
