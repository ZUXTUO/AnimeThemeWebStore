<?php
include 'db.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 获取所有商品数据，只获取 view=1 的商品
$stmt = $pdo->query("SELECT * FROM commodity WHERE view = 1"); 
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 随机排序商品
shuffle($products);

// 返回 JSON 数据
header('Content-Type: application/json');
echo json_encode($products);
?>
