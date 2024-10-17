<?php
header('Content-Type: application/json');
include 'db.php'; // 包含数据库连接

// 获取 POST 数据
$order_id = intval($_POST['id']);
$order_status = intval($_POST['order_static']);

if ($order_id > 0) {
    try {
        // 更新订单状态
        $stmt = $pdo->prepare("UPDATE orders SET order_status = :order_status WHERE Id = :order_id");
        $stmt->bindParam(':order_status', $order_status, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // 检查更新是否成功
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "订单未找到或状态未改变"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => "更新订单状态失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "无效的订单ID或状态"]);
}
?>
