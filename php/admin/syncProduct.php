<?php
session_start();
include '../db.php';

// 检查请求是否为 POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 提取数据
    $id = $data['Id'];
    $commodity_title = $data['commodity_title'];
    $commodity_info = $data['commodity_info'];
    $price = $data['price'];
    $inventory = $data['inventory'];
    $view = $data['view'];

    try {
        // 更新商品信息
        $stmt = $pdo->prepare("UPDATE commodity SET commodity_title = :commodity_title, commodity_info = :commodity_info, price = :price, inventory = :inventory, view = :view WHERE id = :id");
        $stmt->bindParam(':commodity_title', $commodity_title);
        $stmt->bindParam(':commodity_info', $commodity_info);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':inventory', $inventory);
        $stmt->bindParam(':view', $view);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => '商品信息同步成功']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "同步失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
?>
