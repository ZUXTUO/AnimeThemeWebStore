<?php
function getOrderStatus($statusCode) {
    $statusMap = [
        0 => "未支付",
        1 => "已支付，审核中",
        2 => "已支付，审核成功",
        3 => "确认成品信息",
        4 => "已确认成品信息",
        5 => "待支付尾款信息",
        6 => "已支付尾款信息，审核中",
        7 => "已支付尾款信息，审核成功",
        8 => "待发货",
        9 => "已发货",
        10 => "已送达",
        11 => "已收货",
        12 => "超时未支付，订单关闭",
        13 => "已售后",
        14 => "已退款",
        15 => "订单关闭",
        16 => "已支付，但退款中",
        17 => "已支付，退款成功",
    ];

    return isset($statusMap[$statusCode]) ? $statusMap[$statusCode] : "未知状态";
}

// 获取状态码
$statusCode = isset($_GET['status']) ? intval($_GET['status']) : 0;
$statusName = getOrderStatus($statusCode);

// 返回状态信息
echo json_encode(["statusCode" => $statusCode, "statusName" => $statusName]);
?>
