<?php
$host = "localhost";
$dbname = "dbbooks";
$username = "root";
$password = "";
try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
    exit;
}
?>