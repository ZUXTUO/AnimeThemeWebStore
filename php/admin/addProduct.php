<?php
session_start();
include '../db.php';

// 检查请求是否为 POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 提取数据
    $merchantId = $data['merchants_id']; // 当前商家 ID
    $commodity_title = $data['commodity_title'];
    $commodity_info = $data['commodity_info'];
    $price = $data['price'];
    $inventory = $data['inventory'];
    $view = $data['view'];

    try {
        // 插入新商品
        $stmt = $pdo->prepare("INSERT INTO commodity (merchants_id, commodity_title, commodity_info, price, inventory, view) VALUES (:merchants_id, :commodity_title, :commodity_info, :price, :inventory, :view)");
        $stmt->bindParam(':merchants_id', $merchantId);
        $stmt->bindParam(':commodity_title', $commodity_title);
        $stmt->bindParam(':commodity_info', $commodity_info);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':inventory', $inventory);
        $stmt->bindParam(':view', $view);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => '商品添加成功']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "添加失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
?>
