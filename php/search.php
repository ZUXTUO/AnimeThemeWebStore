<?php
include 'db.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 获取搜索关键词
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 使用准备好的语句进行模糊搜索
$stmt = $pdo->prepare("SELECT * FROM commodity WHERE view = 1 AND commodity_title LIKE :searchTerm");
$stmt->execute(['searchTerm' => "%$searchTerm%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 随机排序商品
shuffle($products);

// 返回 JSON 数据
header('Content-Type: application/json');
echo json_encode($products);
?>
