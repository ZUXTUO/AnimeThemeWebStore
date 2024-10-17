<?php
include '../db.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 获取商家 ID
$merchantId = isset($_GET['merchantId']) ? (int)$_GET['merchantId'] : 0;

// 获取对应商家 ID 的商品数据，只获取 view=1 的商品
$stmt = $pdo->prepare("SELECT * FROM commodity WHERE merchants_id = :merchantId");
$stmt->bindParam(':merchantId', $merchantId, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 返回 JSON 数据
header('Content-Type: application/json');
echo json_encode($products);
?>
