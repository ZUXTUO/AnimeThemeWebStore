<?php
function resizeImage($file, $maxWidth, $maxHeight) {
    list($width, $height) = getimagesize($file);
    
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = (int)($width * $ratio);
    $newHeight = (int)($height * $ratio);
    
    $src = imagecreatefromstring(file_get_contents($file));
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    return $dst;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    
    if (isset($_FILES['image'])) {
        $file = $_FILES['image']['tmp_name'];
        
        // 调整图像大小
        $resizedImage = resizeImage($file, 256, 256);
        $filePath = __DIR__ . "/../../user/$userId/";

        // 如果目录不存在，则创建目录
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        // 保存调整大小后的图像
        $imagePath = $filePath . 'icon.jpg';
        imagejpeg($resizedImage, $imagePath);

        // 释放内存
        imagedestroy($resizedImage);

        echo json_encode(['success' => true, 'message' => '头像上传成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '未上传文件']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求']);
}
?>
