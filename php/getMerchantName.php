<?php
header('Content-Type: application/json');

include 'db.php';

// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die(json_encode(["error" => "连接数据库失败: " . $conn->connect_error]));
}

// 获取商家ID
$merchants_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($merchants_id > 0) {
    // 查询商家名称
    $stmt = $conn->prepare("SELECT name FROM merchants WHERE id = ?");
    $stmt->bind_param("i", $merchants_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    
    if ($name) {
        echo json_encode(["name" => $name]);
    } else {
        echo json_encode(["error" => "商家不存在"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "无效的商家ID"]);
}

// 关闭连接
$conn->close();
?>
