<?php
session_start();
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 从 $_POST 中获取数据
    if (!isset($_POST['name']) || !isset($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => '缺少商家名称或密码']);
        exit;
    }

    $name = $_POST['name'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("INSERT INTO merchants (name, password) VALUES (:name, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $merchantId = $pdo->lastInsertId();

        echo json_encode(['success' => true, 'message' => '商家添加成功', 'merchantId' => $merchantId]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => '添加失败: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求']);
}
