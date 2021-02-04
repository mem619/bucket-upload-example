<?php
include_once 'config.php';

header("Content-Type:application/json");
echo json_encode(listFiles(), JSON_UNESCAPED_SLASHES);
?>