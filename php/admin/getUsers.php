<?php
include '../db.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

// 获取用户数据
$stmt = $pdo->prepare("SELECT Id, name, password FROM user LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取总用户数量
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM user");
$stmt->execute();
$totalUsers = $stmt->fetchColumn();

// 返回 JSON 数据
header('Content-Type: application/json');
echo json_encode(['users' => $users, 'total' => $totalUsers]);
?>
