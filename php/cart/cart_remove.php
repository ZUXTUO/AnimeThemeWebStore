<?php
include '../db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取参数
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $commodityId = isset($_POST['commodity_id']) ? (int)$_POST['commodity_id'] : 0;

    // 删除购物车中的商品
    $stmt = $pdo->prepare("DELETE FROM shopping_car WHERE user_id = :userId AND commodity_id = :commodityId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':commodityId', $commodityId, PDO::PARAM_INT);
    $stmt->execute();

    // 返回成功信息
    header('Content-Type: application/json');
    echo json_encode(['message' => '已从购物车中删除']);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
