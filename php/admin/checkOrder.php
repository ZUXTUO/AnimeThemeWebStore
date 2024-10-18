<?php
header('Content-Type: application/json');

include '../db.php'; // 包含数据库连接

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取当前时间
    $current_time = new DateTime();
    $threshold_time = $current_time->modify('-30 minutes')->format('Y-m-d H:i:s');

    // 查询超时的订单
    $stmt = $pdo->prepare("SELECT Id, commodity_id, num FROM orders WHERE order_status = 0 AND order_time < :threshold_time");
    $stmt->bindParam(':threshold_time', $threshold_time);
    $stmt->execute();

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 更新超时订单状态为 12，并处理库存
    foreach ($orders as $order) {
        // 更新订单状态为 12
        $updateStmt = $pdo->prepare("UPDATE orders SET order_status = 12 WHERE Id = :order_id");
        $updateStmt->bindParam(':order_id', $order['Id']);
        $updateStmt->execute();

        // 更新库存
        $inventoryStmt = $pdo->prepare("UPDATE commodity SET inventory = inventory + :num, sold = sold - :num WHERE Id = :commodity_id");
        $inventoryStmt->bindParam(':num', $order['num']);
        $inventoryStmt->bindParam(':commodity_id', $order['commodity_id']);
        $inventoryStmt->execute();
    }

    // 检查并更新所有 sold 值为非负数
    $updateSoldStmt = $pdo->prepare("UPDATE commodity SET sold = 0 WHERE sold < 0");
    $updateSoldStmt->execute();

    echo json_encode(["success" => true, "message" => count($orders)]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "数据库错误: " . $e->getMessage()]);
}
?>
