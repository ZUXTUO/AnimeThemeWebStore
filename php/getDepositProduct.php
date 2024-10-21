<?php
include 'db.php'; // 包含数据库连接文件

if (!isset($_GET['id']) || !isset($_GET['user_id'])) {
    echo json_encode(['error' => '缺少商品ID或用户ID']);
    exit;
}

$productId = intval($_GET['id']);
$userId = intval($_GET['user_id']);

try {
    // 查询商品信息和商家名称
    $stmt = $pdo->prepare("
        SELECT c.*, m.name AS merchant_name
        FROM commodity AS c
        LEFT JOIN merchants AS m ON c.merchants_id = m.id
        WHERE c.Id = :id
    ");
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // 查询用户的订单信息
        $orderStmt = $pdo->prepare("
            SELECT *
            FROM orders
            WHERE user_id = :user_id AND order_status = 2
        ");
        $orderStmt->bindParam(':user_id', $userId);
        $orderStmt->execute();
        $orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

        // 将订单信息和用户ID添加到返回的数据中
        $product['orders'] = $orders;
        $product['user_id'] = $userId; // 添加用户ID

        echo json_encode($product);
    } else {
        echo json_encode(['error' => '未找到该商品']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => '查询失败: ' . $e->getMessage()]);
}
?>
