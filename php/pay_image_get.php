<?php
$orderId = $_GET['id'];
$targetDir = "../order/$orderId/";

if (is_dir($targetDir)) {
    $images = array_diff(scandir($targetDir), array('..', '.'));
    echo json_encode(array_values($images));
} else {
    echo json_encode([]);
}
?>
