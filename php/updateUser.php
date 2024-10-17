<?php
session_start();
include 'db.php';

// 检查请求是否为 POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 验证和清理输入
    $id = filter_var($data['Id'], FILTER_VALIDATE_INT);
    $name = isset($data['name']) ? filter_var($data['name'], FILTER_SANITIZE_STRING) : null;
    $email = isset($data['email']) ? filter_var($data['email'], FILTER_SANITIZE_EMAIL) : null;
    $qq = isset($data['qq']) ? filter_var($data['qq'], FILTER_SANITIZE_STRING) : null;
    $weixin = isset($data['weixin']) ? filter_var($data['weixin'], FILTER_SANITIZE_STRING) : null;
    $delivery_address = isset($data['delivery_address']) ? filter_var($data['delivery_address'], FILTER_SANITIZE_STRING) : null;
    $consignee_name = isset($data['consignee_name']) ? filter_var($data['consignee_name'], FILTER_SANITIZE_STRING) : null; // 新增字段
    $phone_number = isset($data['phone_number']) ? filter_var($data['phone_number'], FILTER_SANITIZE_STRING) : null; // 新增字段

    // 构建 SQL 更新语句
    $updateFields = [];
    if ($name !== null) {
        $updateFields[] = "name = :name";
    }
    if ($email !== null) {
        $updateFields[] = "email = :email";
    }
    if ($qq !== null) {
        $updateFields[] = "qq = :qq";
    }
    if ($weixin !== null) {
        $updateFields[] = "weixin = :weixin";
    }
    if ($delivery_address !== null) {
        $updateFields[] = "delivery_address = :delivery_address";
    }
    if ($consignee_name !== null) {
        $updateFields[] = "consignee_name = :consignee_name"; // 新增字段处理
    }
    if ($phone_number !== null) {
        $updateFields[] = "phone_number = :phone_number"; // 新增字段处理
    }

    if (empty($updateFields)) {
        echo json_encode(['success' => false, 'message' => '没有要更新的数据']);
        exit;
    }

    try {
        // 创建动态 SQL 语句
        $sql = "UPDATE user SET " . implode(", ", $updateFields) . " WHERE Id = :id";
        $stmt = $pdo->prepare($sql);

        // 绑定参数
        if ($name !== null) {
            $stmt->bindParam(':name', $name);
        }
        if ($email !== null) {
            $stmt->bindParam(':email', $email);
        }
        if ($qq !== null) {
            $stmt->bindParam(':qq', $qq);
        }
        if ($weixin !== null) {
            $stmt->bindParam(':weixin', $weixin);
        }
        if ($delivery_address !== null) {
            $stmt->bindParam(':delivery_address', $delivery_address);
        }
        if ($consignee_name !== null) {
            $stmt->bindParam(':consignee_name', $consignee_name); // 绑定收货人姓名
        }
        if ($phone_number !== null) {
            $stmt->bindParam(':phone_number', $phone_number); // 绑定收货人电话
        }
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // 检查是否有行被更新
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => '账户信息同步成功']);
        } else {
            echo json_encode(['success' => false, 'message' => '没有更新任何信息']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "同步失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
