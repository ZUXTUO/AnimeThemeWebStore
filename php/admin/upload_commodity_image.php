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
    $merchantId = $_POST['merchantId'];
    $productId = $_POST['productId'];
    $type = $_POST['type'];
    
    if (isset($_FILES['image'])) {
        $file = $_FILES['image']['tmp_name'];
        
        // Resize image
        $resizedImage = resizeImage($file, 1024, 1024);
        $filePath = __DIR__ . "/../../commodity/$merchantId/$productId/";

        // Create directory if it doesn't exist
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        // Save the resized image
        $imagePath = $filePath . ($type === 'main' ? 'main.jpg' : 'info.jpg');
        imagejpeg($resizedImage, $imagePath);

        // Free up memory
        imagedestroy($resizedImage);

        echo json_encode(['success' => true, 'message' => '图片上传成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '未上传文件']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求']);
}
?>
