<?php
error_reporting(E_ERROR | E_PARSE); // 只报告错误和解析错误
ini_set('display_errors', 1);
require 'db.php';

$customId = $_POST['customId'];
$username = $_POST['username'];
$password = $_POST['password'];
$avatar = $_FILES['avatar'] ?? null; // 使用 null 合并运算符

$stmt = $pdo->prepare("SELECT * FROM user WHERE Id = :customId");
$stmt->execute(['customId' => $customId]);

if ($stmt->rowCount() > 0) {
    echo '用户ID已存在';
    exit();
}

$userDir = "../user/$customId";

if (!is_dir($userDir)) {
    if (!mkdir($userDir, 0755, true)) {
        echo '无法创建用户目录';
        exit();
    }
}

// 检查头像上传
if (!$avatar || $avatar['error'] !== UPLOAD_ERR_OK) {
    echo '没有上传头像';
    exit();
}

$avatarPath = $userDir . '/icon.jpg';
$resizedImage = resizeImage($avatar['tmp_name'], 512, 512);
imagejpeg($resizedImage, $avatarPath, 90);
imagedestroy($resizedImage);

$stmt = $pdo->prepare("INSERT INTO user (Id, name, password) VALUES (:customId, :username, :password)");
if ($stmt->execute(['customId' => $customId, 'username' => $username, 'password' => $password])) {
    echo '1'; // 注册成功返回数字1
} else {
    echo '注册失败: ' . $stmt->errorInfo()[2];
}

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
