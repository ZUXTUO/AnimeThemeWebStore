<?php
// 从 POST 请求中获取订单 ID
$orderId = $_POST['id'];
$targetDir = "../order/$orderId/";

// 确保目标目录存在
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        echo json_encode(['success' => false, 'error' => '创建文件夹失败']);
        exit();
    }
}

if (isset($_FILES['images'])) {
    $uploadStatus = true;
    $errorMessages = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['images']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;

        // 移动上传的文件到目标文件夹，允许替换同名文件
        if (move_uploaded_file($tmpName, $targetFilePath)) {
            continue;
        } else {
            $uploadStatus = false;
            $errorMessages[] = "上传文件 $fileName 失败.";
        }
    }

    if ($uploadStatus) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => implode(", ", $errorMessages)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => '没有文件被上传']);
}
?>
