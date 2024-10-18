<?php
header('Content-Type: application/json');
include '../db.php'; // 包含数据库连接

// 获取 POST 数据
$order_id = intval($_POST['id']);
$order_status = intval($_POST['order_static']);

if ($order_id > 0) {
    try {
        // 检查当前订单状态
        $stmt = $pdo->prepare("SELECT order_status FROM orders WHERE Id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus = $stmt->fetchColumn();

        if ($currentStatus === false) {
            echo json_encode(["success" => false, "error" => "订单未找到"]);
            exit;
        }

        if ($currentStatus == 0) { // 如果当前状态为 0，才允许修改
            // 更新订单状态
            $stmt = $pdo->prepare("UPDATE orders SET order_status = :order_status WHERE Id = :order_id");
            $stmt->bindParam(':order_status', $order_status, PDO::PARAM_INT);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();

            // 检查更新是否成功
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "订单状态未改变"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "超时无法支付"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => "更新订单状态失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "无效的订单ID或状态"]);
}
?>
