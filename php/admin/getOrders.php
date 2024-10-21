<?php
header('Content-Type: application/json');
require '../db.php';

$merchantId = isset($_GET['merchantId']) ? intval($_GET['merchantId']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$status = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null; 
$searchType = isset($_GET['searchType']) ? $_GET['searchType'] : '';
$searchInput = isset($_GET['searchInput']) ? trim($_GET['searchInput']) : '';
$offset = ($page - 1) * $limit;

if ($merchantId > 0) {
    $params = [':merchantId' => $merchantId];

    // 计算总订单数
    $query = "SELECT COUNT(*) FROM orders WHERE merchants_id = :merchantId";

    if ($status !== null) {
        $query .= " AND order_status = :status";
        $params[':status'] = $status;
    }

    // 添加搜索条件
    if ($searchType === 'orderId' && !empty($searchInput)) {
        $query .= " AND Id = :searchInput";
        $params[':searchInput'] = $searchInput;
    } elseif ($searchType === 'phoneNumber' && !empty($searchInput)) {
        $query .= " AND phone_number = :searchInput";
        $params[':searchInput'] = $searchInput;
    } elseif ($searchType === 'commodityTitle' && !empty($searchInput)) {
        $query .= " AND commodity_title LIKE :searchInput";
        $params[':searchInput'] = "%{$searchInput}%";
    } elseif ($searchType === 'userId' && !empty($searchInput)) {
        $query .= " AND user_id = :searchInput";
        $params[':searchInput'] = $searchInput;
    } elseif ($searchType === 'consigneeName' && !empty($searchInput)) {
        $query .= " AND consignee_name LIKE :searchInput";
        $params[':searchInput'] = "%{$searchInput}%";
    } elseif ($searchType === 'deliveryAddress' && !empty($searchInput)) {
        $query .= " AND delivery_address LIKE :searchInput";
        $params[':searchInput'] = "%{$searchInput}%";
    }

    $countStmt = $pdo->prepare($query);
    $countStmt->execute($params);
    $totalOrders = $countStmt->fetchColumn();

    // 查询订单
    $query = "SELECT * FROM orders WHERE merchants_id = :merchantId";

    if ($status !== null) {
        $query .= " AND order_status = :status";
    }

    // 添加搜索条件
    if ($searchType === 'orderId' && !empty($searchInput)) {
        $query .= " AND Id = :searchInput";
    } elseif ($searchType === 'phoneNumber' && !empty($searchInput)) {
        $query .= " AND phone_number = :searchInput";
    } elseif ($searchType === 'commodityTitle' && !empty($searchInput)) {
        $query .= " AND commodity_title LIKE :searchInput";
    } elseif ($searchType === 'userId' && !empty($searchInput)) {
        $query .= " AND user_id = :searchInput";
    } elseif ($searchType === 'consigneeName' && !empty($searchInput)) {
        $query .= " AND consignee_name LIKE :searchInput";
    } elseif ($searchType === 'deliveryAddress' && !empty($searchInput)) {
        $query .= " AND delivery_address LIKE :searchInput";
    }

    $query .= " LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':merchantId', $merchantId, PDO::PARAM_INT);
    
    if ($status !== null) {
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    }

    if (!empty($searchInput)) {
        $stmt->bindParam(':searchInput', $searchInput, PDO::PARAM_STR);
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
