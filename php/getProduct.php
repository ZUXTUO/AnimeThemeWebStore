<?php
include 'db.php'; // 包含数据库连接文件

if (!isset($_GET['id'])) {
    echo json_encode(['error' => '缺少商品ID']);
    exit;
}

$productId = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("
        SELECT *
        FROM commodity 
        WHERE Id = :id
    ");
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // 获取商家名称
        $merchantId = $product['merchants_id'];
        $merchantStmt = $pdo->prepare("SELECT name FROM merchants WHERE id = :id");
        $merchantStmt->bindParam(':id', $merchantId);
        $merchantStmt->execute();
        $merchant = $merchantStmt->fetch(PDO::FETCH_ASSOC);
        $product['merchant_name'] = $merchant ? $merchant['name'] : "未知商家";

        echo json_encode($product);
    } else {
        echo json_encode(['error' => '未找到该商品']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => '查询失败: ' . $e->getMessage()]);
}
?>
