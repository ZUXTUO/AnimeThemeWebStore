<?php
header('Content-Type: application/json');

include 'db.php';

// 获取用户ID
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    try {
        // 查询用户的所有订单，按ID排序
        $stmt = $pdo->prepare("SELECT Id, commodity_id, commodity_title, merchants_id, num, order_status, order_time FROM orders WHERE user_id = :user_id ORDER BY Id DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($orders);
    } catch (PDOException $e) {
        echo json_encode(["error" => "查询失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "无效的用户ID"]);
}
?>
