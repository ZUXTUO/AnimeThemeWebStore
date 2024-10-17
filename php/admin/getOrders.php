<?php
header('Content-Type: application/json');
require '../db.php';

$merchantId = isset($_GET['merchantId']) ? intval($_GET['merchantId']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$status = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null; 
$offset = ($page - 1) * $limit;

if ($merchantId > 0) {
    $query = "SELECT COUNT(*) FROM orders WHERE merchants_id = :merchantId";
    $params = [':merchantId' => $merchantId];

    if ($status !== null) {
        $query .= " AND order_status = :status";
        $params[':status'] = $status;
    }

    $countStmt = $pdo->prepare($query);
    $countStmt->execute($params);
    $totalOrders = $countStmt->fetchColumn();

    $query = "SELECT * FROM orders WHERE merchants_id = :merchantId";

    if ($status !== null) {
        $query .= " AND order_status = :status";
    }

    $query .= " LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':merchantId', $merchantId, PDO::PARAM_INT);
    
    if ($status !== null) {
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    }

    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['orders' => $orders, 'total' => $totalOrders]);
    } else {
        echo json_encode(['success' => false, 'message' => '查询订单失败']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的商家 ID']);
}
?>
