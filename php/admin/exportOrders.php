<?php
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');

// 设置文件名为当前时间
$filename = date('Y-m-d_H-i-s') . '_orders.csv';
header("Content-Disposition: attachment; filename=\"{$filename}\"");

// 添加UTF-8 BOM
echo "\xEF\xBB\xBF";

require_once '../db.php'; // 确保路径正确

$merchantId = $_GET['merchantId'];
$status = $_GET['status'];

// 构建 SQL 查询
$query = "SELECT * FROM orders WHERE merchants_id = ?";

if ($status !== '') {
    $query .= " AND order_status = ?";
}

$stmt = $pdo->prepare($query);
$params = [$merchantId];
if ($status !== '') {
    $params[] = $status;
}
$stmt->execute($params);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 输出表头
echo "订单编号,用户 ID,商品代号,商品名,数量,状态,下单时间,收件人姓名,手机号,收货地址,运单号\n";

foreach ($orders as $order) {
    echo "{$order['Id']},{$order['user_id']},{$order['commodity_id']},{$order['commodity_title']},{$order['num']},{$order['order_status']},{$order['order_time']},{$order['consignee_name']},{$order['phone_number']},{$order['delivery_address']},{$order['waybill_number']}\n";
}
?>
