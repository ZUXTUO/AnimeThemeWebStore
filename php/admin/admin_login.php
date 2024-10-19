<?php
session_start();
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE Id = :id AND password = :password");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $_SESSION['adminId'] = $admin['Id'];
            $_SESSION['adminName'] = $admin['name'];

            // 设置 Cookie 保存账号和密码
            setcookie("adminId", $id, time() + (86400 * 30), "/");
            setcookie("adminPassword", $password, time() + (86400 * 30), "/");

            $stmt = $pdo->query("SELECT Id, name, password FROM merchants");
            $merchants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'adminId' => $admin['Id'], 'name' => $admin['name'], 'merchants' => $merchants]);
        } else {
            echo json_encode(['success' => false, 'message' => '登录失败: ID 或密码错误']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "查询失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
