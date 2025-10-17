<?php
header('Content-Type: application/json');
include '../db.php'; // 包含数据库连接

// 获取 POST 数据
$data = json_decode(file_get_contents("php://input"), true);
$order_id = intval($data['id']);

if ($order_id > 0) {
    try {
        // 开启事务
        $pdo->beginTransaction();
        
        // 使用 FOR UPDATE 锁定订单记录
        $stmt = $pdo->prepare("SELECT commodity_id, num, order_status FROM orders WHERE Id = :order_id FOR UPDATE");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单未找到"]);
            exit;
        }

        // 检查订单状态是否允许取消（只有状态0-2可以取消）
        if ($order['order_status'] >= 15) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "该订单已被取消，无法重复操作"]);
            exit;
        }

        if ($order['order_status'] >= 3) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单已发货或完成，无法取消"]);
            exit;
        }

        // 更新订单状态为 15（已取消）
        $stmt = $pdo->prepare("UPDATE orders SET order_status = 15 WHERE Id = :order_id AND order_status < 15");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // 检查是否成功更新
        if ($stmt->rowCount() === 0) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "error" => "订单状态已改变，无法取消"]);
            exit;
        }

        // 原子性增加库存，减少已售
        $stmt = $pdo->prepare("UPDATE commodity 
                               SET inventory = inventory + :num, sold = sold - :num 
                               WHERE Id = :commodity_id");
        $stmt->bindParam(':num', $order['num'], PDO::PARAM_INT);
        $stmt->bindParam(':commodity_id', $order['commodity_id'], PDO::PARAM_INT);
        $stmt->execute();

        // 提交事务
        $pdo->commit();

        echo json_encode(["success" => true, "message" => "订单已取消，库存已恢复"]);
        
    } catch (PDOException $e) {
        // 回滚事务
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(["success" => false, "error" => "取消订单失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "无效的订单ID"]);
}
?>
