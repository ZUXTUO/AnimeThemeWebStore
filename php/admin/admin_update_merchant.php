<?php
session_start();
include '../db.php';

// 检查请求是否为 POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 提取数据
    $id = $data['id']; // 确保前端发送的字段名是小写
    $name = $data['name'];
    $password = $data['password'];

    try {
        // 更新商家信息
        $stmt = $pdo->prepare("UPDATE merchants SET name = :name, password = :password WHERE Id = :id"); // 使用大写的 Id
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => '商家信息更新成功']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "更新失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
?>
