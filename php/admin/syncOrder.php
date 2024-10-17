<?php
session_start();
include '../db.php';

// 检查请求是否为 POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 提取数据
    $orderId = $data['orderId'];
    $fieldsToUpdate = [];

    // 检查哪些字段需要更新
    if (isset($data['order_status'])) {
        $fieldsToUpdate['order_status'] = $data['order_status'];
    }
    if (isset($data['consignee_name'])) {
        $fieldsToUpdate['consignee_name'] = $data['consignee_name'];
    }
    if (isset($data['phone_number'])) {
        $fieldsToUpdate['phone_number'] = $data['phone_number'];
    }
    if (isset($data['delivery_address'])) {
        $fieldsToUpdate['delivery_address'] = $data['delivery_address'];
    }
    if (isset($data['waybill_number'])) {
        $fieldsToUpdate['waybill_number'] = $data['waybill_number'];
    }

    // 如果没有字段需要更新
    if (empty($fieldsToUpdate)) {
        echo json_encode(['success' => false, 'message' => "没有需要更新的字段"]);
        exit;
    }

    // 构建动态更新 SQL 语句
    $setPart = [];
    foreach ($fieldsToUpdate as $field => $value) {
        $setPart[] = "$field = :$field";
    }
    $setClause = implode(', ', $setPart);

    try {
        // 更新订单信息
        $stmt = $pdo->prepare("UPDATE orders SET $setClause WHERE id = :orderId");
        foreach ($fieldsToUpdate as $field => $value) {
            $stmt->bindParam(":$field", $fieldsToUpdate[$field]);
        }
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => '订单信息同步成功']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "同步失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
