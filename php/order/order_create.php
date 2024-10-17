<?php
header('Content-Type: application/json');
include '../db.php'; // 包含数据库连接

// 获取 POST 数据
$data = json_decode(file_get_contents("php://input"), true);

// 验证数据
$user_id = intval($data['user_id']);
$commodity_id = intval($data['commodity_id']);
$num = intval($data['num']);
$merchants_id = intval($data['merchants_id']);
$delivery_address = $data['delivery_address'];
$order_status = intval($data['order_status']);
$consignee_name = $data['consignee_name'];
$phone_number = $data['phone_number'];
$commodity_title = $data['commodity_title']; // 获取商品名称

// 获取当前时间
$order_time = date('Y-m-d H:i:s');

// 插入订单
try {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, commodity_id, num, merchants_id, delivery_address, order_status, order_time, consignee_name, phone_number, commodity_title) 
                            VALUES (:user_id, :commodity_id, :num, :merchants_id, :delivery_address, :order_status, :order_time, :consignee_name, :phone_number, :commodity_title)");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':commodity_id', $commodity_id);
    $stmt->bindParam(':num', $num);
    $stmt->bindParam(':merchants_id', $merchants_id);
    $stmt->bindParam(':delivery_address', $delivery_address);
    $stmt->bindParam(':order_status', $order_status);
    $stmt->bindParam(':order_time', $order_time);
    $stmt->bindParam(':consignee_name', $consignee_name);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':commodity_title', $commodity_title); // 绑定商品名称

    // 执行插入订单
    $stmt->execute();

    // 获取新订单的 ID
    $order_id = $pdo->lastInsertId();

    // 更新商品库存并增加已售数量
    $stmt = $pdo->prepare("UPDATE commodity SET inventory = inventory - :num, sold = sold + :num WHERE Id = :commodity_id");
    $stmt->bindParam(':num', $num);
    $stmt->bindParam(':commodity_id', $commodity_id);
    $stmt->execute();

    echo json_encode(["success" => true, "order_id" => $order_id]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "创建订单失败: " . $e->getMessage()]);
}
?>
