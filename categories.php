<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
class Category {
    function getCategories(){
        include "connection-pdo.php";
        $sql = "SELECT cat_id, cat_name
                FROM tblcategories
                ORDER BY cat_name";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
$operation = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $operation = isset($_GET['operation']) ? $_GET['operation'] : "";
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $operation = isset($_POST['operation']) ? $_POST['operation'] : "";
}
$category = new Category();
try {
    switch ($operation) {
        case "getCategories":
            echo json_encode($category->getCategories());
            break;
        default:
            echo json_encode([
                "error" => true,
                "message" => "Invalid operation"
            ]);
            break;
    }
} catch (Exception $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
?>