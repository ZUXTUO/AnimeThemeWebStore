<?php
header('Content-Type: application/json');
include 'db.php'; // 包含数据库连接

// 获取 POST 数据
$data = json_decode(file_get_contents("php://input"), true);
$order_id = intval($data['id']);

if ($order_id > 0) {
    try {
        // 查询订单
        $stmt = $pdo->prepare("SELECT commodity_id, num FROM orders WHERE Id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // 更新订单状态为 15（已取消）
            $stmt = $pdo->prepare("UPDATE orders SET order_status = 15 WHERE Id = :order_id");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            // 增加库存，已售减少
            $stmt = $pdo->prepare("UPDATE commodity SET inventory = inventory + :num, sold = sold - :num WHERE Id = :commodity_id");
            $stmt->bindParam(':num', $order['num']);
            $stmt->bindParam(':commodity_id', $order['commodity_id']);
            $stmt->execute();

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "订单未找到"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => "取消订单失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "无效的订单ID"]);
}
?>
