<?php
include 'db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取参数
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $commodityId = isset($_POST['commodity_id']) ? (int)$_POST['commodity_id'] : 0;
    $num = isset($_POST['num']) ? (int)$_POST['num'] : 1;

    // 检查购物车中是否已存在该商品
    $stmt = $pdo->prepare("SELECT * FROM shopping_car WHERE user_id = :userId AND commodity_id = :commodityId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':commodityId', $commodityId, PDO::PARAM_INT);
    $stmt->execute();
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // 更新数量
        $newNum = $existingItem['num'] + $num;
        $stmt = $pdo->prepare("UPDATE shopping_car SET num = :num WHERE user_id = :userId AND commodity_id = :commodityId");
        $stmt->bindParam(':num', $newNum, PDO::PARAM_INT);
    } else {
        // 插入新商品
        $stmt = $pdo->prepare("INSERT INTO shopping_car (user_id, commodity_id, num) VALUES (:userId, :commodityId, :num)");
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    }

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':commodityId', $commodityId, PDO::PARAM_INT);
    $stmt->execute();

    // 返回成功信息
    header('Content-Type: application/json');
    echo json_encode(['message' => '已添加到购物车']);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
