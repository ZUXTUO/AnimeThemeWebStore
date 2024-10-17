<?php
header('Content-Type: application/json');

include 'db.php';

// 获取订单ID
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id > 0) {
    try {
        // 查询指定订单的所有信息
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE Id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($order) {
            // 获取商品价格
            $commodity_id = $order['commodity_id'];
            $stmtPrice = $pdo->prepare("SELECT price FROM commodity WHERE Id = :commodity_id");
            $stmtPrice->bindParam(':commodity_id', $commodity_id, PDO::PARAM_INT);
            $stmtPrice->execute();

            $commodity = $stmtPrice->fetch(PDO::FETCH_ASSOC);
            $order['price'] = $commodity ? $commodity['price'] : null; // 将价格添加到订单信息中

            echo json_encode($order);
        } else {
            echo json_encode(["error" => "订单未找到"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "查询失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "无效的订单ID"]);
}
?>
