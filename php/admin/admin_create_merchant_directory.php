<?php
session_start();
include '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['merchantId'])) {
        echo json_encode(['success' => false, 'message' => '缺少商家ID']);
        exit;
    }

    $merchantId = $data['merchantId'];
    $dirPath = __DIR__ . "/../../commodity/$merchantId/";

    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
        echo json_encode(['success' => true, 'message' => '商家目录创建成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '商家目录已存在']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求']);
}
