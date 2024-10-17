<?php
session_start();
include '../db.php';

// 检查表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    try {
        // 查询商家信息
        $stmt = $pdo->prepare("SELECT * FROM merchants WHERE id = :id AND password = :password");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // 获取商家数据
        $merchant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($merchant) {
            // 登录成功，保存商家信息到会话
            $_SESSION['merchantId'] = $merchant['id'];
            $_SESSION['merchantName'] = $merchant['name'];
            echo json_encode(['success' => true, 'merchantId' => $merchant['id'], 'name' => $merchant['name']]);
        } else {
            echo json_encode(['success' => false, 'message' => '登录失败: ID 或密码错误']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "查询失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
?>
