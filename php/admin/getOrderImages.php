<?php
header('Content-Type: application/json');

$orderId = $_GET['orderId'];
$directory = "../../order/" . $orderId;

if (!is_dir($directory)) {
    echo json_encode([]);
    exit;
}

$images = array_diff(scandir($directory), array('..', '.'));
echo json_encode(array_values($images));
?>
