<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);
require 'db.php';

// 添加缓存控制头
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

$customId = $_POST['customId'];
$avatar = $_FILES['avatar'] ?? null;

if (!$avatar || $avatar['error'] !== UPLOAD_ERR_OK) {
    echo '没有上传头像';
    exit();
}

$userDir = "../user/$customId";
$avatarPath = $userDir . '/icon.jpg';

// 确保用户目录存在
if (!is_dir($userDir)) {
    if (!mkdir($userDir, 0755, true)) {
        echo '无法创建用户目录';
        exit();
    }
}

// 处理上传的头像
$resizedImage = resizeImage($avatar['tmp_name'], 512, 512);
imagejpeg($resizedImage, $avatarPath, 90);
imagedestroy($resizedImage);

echo '成功';

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
?>
