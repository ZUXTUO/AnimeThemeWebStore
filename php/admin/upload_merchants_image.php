<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

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
    if (!isset($_POST['merchantId']) || !isset($_POST['type'])) {
        echo json_encode(['success' => false, 'message' => '缺少必要的参数']);
        exit;
    }

    $merchantId = $_POST['merchantId'];
    $type = $_POST['type'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image']['tmp_name'];

        // Resize image
        $resizedImage = resizeImage($file, 1024, 1024);
        $filePath = __DIR__ . "/../../commodity/$merchantId/";

        // Create directory if it doesn't exist
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        // Define the image path based on type
        $imagePath = $filePath . ($type === 'icon' ? 'icon.jpg' : ($type === 'wx' ? 'wx.jpg' : 'zfb.jpg'));

        // Check if the file already exists and delete it
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the existing file
        }

        // Save the resized image
        imagejpeg($resizedImage, $imagePath);

        // Free up memory
        imagedestroy($resizedImage);

        echo json_encode(['success' => true, 'message' => '图片上传成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '未上传文件或文件上传错误']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求']);
}
