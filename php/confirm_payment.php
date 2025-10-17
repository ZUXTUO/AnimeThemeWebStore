<?php
header('Content-Type: application/json');
include 'db.php'; // 包含数据库连接

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
            echo json_encode(["success" => false, "error" => "订单已被取消，无法确认支付"]);
            exit;
        }

        // 检查订单状态是否允许确认支付（通常是状态1：待确认）
        if ($currentStatus != 1) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单状态不允许确认支付（当前状态：{$currentStatus}）"]);
            exit;
        }

        // 原子性更新订单状态（再次确认当前状态）
        $stmt = $pdo->prepare("UPDATE orders SET order_status = :order_status WHERE Id = :order_id AND order_status = 1");
        $stmt->bindParam(':order_status', $order_status, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // 检查更新是否成功
        if ($stmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "支付确认成功"]);
        } else {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单状态已改变，无法确认支付"]);
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
