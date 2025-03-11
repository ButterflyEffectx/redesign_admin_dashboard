<?php

$host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$user_name = getenv('DB_USER');
$user_password = getenv('DB_PASSWORD');

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>