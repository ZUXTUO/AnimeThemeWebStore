<?php
header('Content-Type: application/json');
include '../db.php'; // 包含数据库连接

// 获取 POST 数据
$order_id = intval($_POST['id']);
$order_status = intval($_POST['order_static']);

if ($order_id > 0) {
    try {
        // 开启事务
        $pdo->beginTransaction();
        
        // 使用 FOR UPDATE 锁定订单记录
        $stmt = $pdo->prepare("SELECT order_status FROM orders WHERE Id = :order_id FOR UPDATE");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus = $stmt->fetchColumn();

        if ($currentStatus === false) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单未找到"]);
            exit;
        }

        // 检查订单是否已被取消
        if ($currentStatus >= 15) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单已被取消，无法支付"]);
            exit;
        }

        // 只有状态为 0 的订单才允许支付
        if ($currentStatus != 0) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单状态不允许支付（可能已支付或超时）"]);
            exit;
        }

        // 原子性更新订单状态（再次确认当前状态为0）
        $stmt = $pdo->prepare("UPDATE orders SET order_status = :order_status WHERE Id = :order_id AND order_status = 0");
        $stmt->bindParam(':order_status', $order_status, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // 检查更新是否成功
        if ($stmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "支付状态更新成功"]);
        } else {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单状态已改变，无法支付"]);
        }
        
    } catch (PDOException $e) {
        // 回滚事务
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(["success" => false, "error" => "更新订单状态失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "无效的订单ID或状态"]);
}
?>
