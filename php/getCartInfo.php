<?php
include 'db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取用户 ID
    $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

    // 查询用户购物车中的商品
    $stmt = $pdo->prepare("SELECT * FROM shopping_car WHERE user_Id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $shoppingCartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 如果购物车为空，返回空数组
    if (empty($shoppingCartItems)) {
        echo json_encode([]);
        exit;
    }

    // 提取商品 ID
    $commodityIds = array_column($shoppingCartItems, 'commodity_id');

    // 获取商品信息和库存
    $placeholders = implode(',', array_fill(0, count($commodityIds), '?'));
    $stmt = $pdo->prepare("SELECT id AS commodity_id, merchants_id, commodity_title, price, inventory FROM commodity WHERE Id IN ($placeholders) AND view = 1");
    $stmt->execute($commodityIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 创建商品信息映射
    $productMap = [];
    foreach ($products as $product) {
        $productMap[$product['commodity_id']] = $product;
    }

    // 准备输出结果
    $result = [];
    foreach ($shoppingCartItems as $item) {
        $commodityId = $item['commodity_id'];
        if (isset($productMap[$commodityId])) {
            $inventory = $productMap[$commodityId]['inventory'];
            $num = min($item['num'], $inventory); // 确保 num 不超过 inventory

            if ($inventory == 0) {
                $num = 0; // 如果库存为 0，则 num 设为 0
            }

            // 如果 num 值需要更新，修改 shopping_car 表
            if ($num !== $item['num']) {
                $updateStmt = $pdo->prepare("UPDATE shopping_car SET num = :num WHERE Id = :cartId");
                $updateStmt->bindParam(':num', $num, PDO::PARAM_INT);
                $updateStmt->bindParam(':cartId', $item['Id'], PDO::PARAM_INT);
                $updateStmt->execute();
            }

            $result[] = [
                'cart_id' => $item['Id'],
                'merchants_id' => $productMap[$commodityId]['merchants_id'],
                'commodity_id' => $commodityId,
                'commodity_title' => $productMap[$commodityId]['commodity_title'],
                'price' => $productMap[$commodityId]['price'],
                'num' => $num
            ];
        }
    }

    // 返回 JSON 数据
    header('Content-Type: application/json');
    echo json_encode($result);

} catch (PDOException $e) {
    // 返回错误信息
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
