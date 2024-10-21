<?php
session_start();
include '../db.php';

// 检查请求是否为 POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 提取数据
    $id = $data['userId']; // 将 'Id' 改为 'userId'
    $username = isset($data['name']) ? $data['name'] : null; // 仅在存在时获取名称
    $password = isset($data['password']) ? $data['password'] : null; // 仅在存在时获取密码

    try {
        // 准备 SQL 语句
        $sql = "UPDATE user SET ";
        $params = [];

        // 检查是否需要更新用户名
        if ($username !== null) {
            $sql .= "name = :username ";
            $params[':username'] = $username;
        }

        // 检查是否需要更新密码
        if ($password !== null) {
            if (!empty($params)) {
                $sql .= ", "; // 如果已经有参数，添加逗号
            }
            $sql .= "password = :password ";
            $params[':password'] = $password;
        }

        // 完成 SQL 语句
        $sql .= "WHERE Id = :id";
        $params[':id'] = $id;

        // 准备并执行语句
        $stmt = $pdo->prepare($sql);

        // 绑定参数
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        // 执行查询
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => '用户信息同步成功']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "同步失败: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "无效的请求方法"]);
}
?>
