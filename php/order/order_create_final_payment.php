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
$deposit_order = intval($data['deposit_order']); // 获取定金订单编号（如果存在）

// 获取当前时间
$order_time = date('Y-m-d H:i:s');

// 开启事务处理，确保原子性操作
try {
    // 开启事务
    $pdo->beginTransaction();
    
    // 使用 FOR UPDATE 锁定商品行，防止并发问题
    $stmt = $pdo->prepare("SELECT inventory, commodity_title FROM commodity WHERE Id = :commodity_id FOR UPDATE");
    $stmt->bindParam(':commodity_id', $commodity_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 检查商品是否存在
    if (!$product) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "error" => "商品不存在"]);
        exit;
    }
    
    // 检查库存是否充足
    if ($product['inventory'] < $num) {
        $pdo->rollBack();
        echo json_encode([
            "success" => false, 
            "error" => "库存不足！当前库存仅剩 {$product['inventory']} 件，无法购买 {$num} 件"
        ]);
        exit;
    }
    
    // 验证定金订单是否存在且属于当前用户
    $stmt = $pdo->prepare("SELECT num, order_status FROM orders WHERE Id = :deposit_order AND user_id = :user_id FOR UPDATE");
    $stmt->bindParam(':deposit_order', $deposit_order, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $depositOrder = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$depositOrder) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "error" => "定金订单不存在或不属于当前用户"]);
        exit;
    }
    
    // 检查定金订单状态是否有效（假设状态0-5为有效）
    if ($depositOrder['order_status'] == 6) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "error" => "该定金订单已经完成尾款支付"]);
        exit;
    }
    
    // 检查尾款数量是否超过定金订单数量
    if ($num > $depositOrder['num']) {
        $pdo->rollBack();
        echo json_encode([
            "success" => false, 
            "error" => "尾款数量不能超过定金订单数量！定金订单数量为 {$depositOrder['num']} 件"
        ]);
        exit;
    }
    
    // 使用商品实际名称（防止前端传入错误的商品名称）
    $commodity_title = $product['commodity_title'];
    
    // 插入尾款订单
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, commodity_id, num, merchants_id, delivery_address, order_status, order_time, consignee_name, phone_number, commodity_title) 
                            VALUES (:user_id, :commodity_id, :num, :merchants_id, :delivery_address, :order_status, :order_time, :consignee_name, :phone_number, :commodity_title)");
    
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':commodity_id', $commodity_id, PDO::PARAM_INT);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->bindParam(':merchants_id', $merchants_id, PDO::PARAM_INT);
    $stmt->bindParam(':delivery_address', $delivery_address, PDO::PARAM_STR);
    $stmt->bindParam(':order_status', $order_status, PDO::PARAM_INT);
    $stmt->bindParam(':order_time', $order_time, PDO::PARAM_STR);
    $stmt->bindParam(':consignee_name', $consignee_name, PDO::PARAM_STR);
    $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $stmt->bindParam(':commodity_title', $commodity_title, PDO::PARAM_STR);

    // 执行插入订单
    $stmt->execute();

    // 获取新订单的 ID
    $order_id = $pdo->lastInsertId();

    // 原子性更新商品库存并增加已售数量
    // 使用 WHERE 条件再次确认库存充足，防止负库存
    $stmt = $pdo->prepare("UPDATE commodity 
                           SET inventory = inventory - :num, sold = sold + :num 
                           WHERE Id = :commodity_id AND inventory >= :num");
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->bindParam(':commodity_id', $commodity_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // 检查是否成功更新（如果受影响行数为0，说明库存不足）
    if ($stmt->rowCount() === 0) {
        $pdo->rollBack();
        echo json_encode([
            "success" => false, 
            "error" => "库存更新失败，库存可能已不足"
        ]);
        exit;
    }

    // 更新定金订单状态为已完成（状态6）
    $stmt = $pdo->prepare("UPDATE orders SET order_status = 6 WHERE Id = :deposit_order");
    $stmt->bindParam(':deposit_order', $deposit_order, PDO::PARAM_INT);
    $stmt->execute();

    // 提交事务
    $pdo->commit();
    
    echo json_encode(["success" => true, "order_id" => $order_id]);
    
} catch (PDOException $e) {
    // 发生错误时回滚事务
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "error" => "创建订单失败: " . $e->getMessage()]);
}
?>
