<?php
$host = 'localhost'; // 数据库主机
$dbname = 'shop'; // 数据库名称
$username = 'root'; // 数据库用户名
$password = 'root'; // 数据库密码

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "连接失败: " . $e->getMessage();
}
?>
