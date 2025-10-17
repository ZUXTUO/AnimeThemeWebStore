<?php
include '../db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取参数
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $commodityId = isset($_POST['commodity_id']) ? (int)$_POST['commodity_id'] : 0;
    $num = isset($_POST['num']) ? (int)$_POST['num'] : 1;

    // 验证参数
    if ($userId <= 0 || $commodityId <= 0 || $num <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['error' => '参数无效']);
        exit;
    }

    // 开启事务
    $pdo->beginTransaction();

    // 使用 FOR UPDATE 锁定购物车记录
    $stmt = $pdo->prepare("SELECT num FROM shopping_car WHERE user_id = :userId AND commodity_id = :commodityId FOR UPDATE");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':commodityId', $commodityId, PDO::PARAM_INT);
    $stmt->execute();
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // 更新数量（使用原子操作）
        $newNum = $existingItem['num'] + $num;
        $stmt = $pdo->prepare("UPDATE shopping_car SET num = :num WHERE user_id = :userId AND commodity_id = :commodityId");
        $stmt->bindParam(':num', $newNum, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':commodityId', $commodityId, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // 插入新商品（使用 INSERT IGNORE 防止重复插入）
        $stmt = $pdo->prepare("INSERT INTO shopping_car (user_id, commodity_id, num) VALUES (:userId, :commodityId, :num)
                               ON DUPLICATE KEY UPDATE num = num + :num");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':commodityId', $commodityId, PDO::PARAM_INT);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
    }

    // 提交事务
    $pdo->commit();

    // 返回成功信息
    header('Content-Type: application/json');
    echo json_encode(['message' => '已添加到购物车']);

} catch (PDOException $e) {
    // 回滚事务
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
