<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
class Book {
    function getAllBooks(){
        include "connection-pdo.php";
        $sql = "SELECT
                    a.book_id,
                    a.book_title,
                    a.book_author,
                    a.book_isbn,
                    a.book_category_id,
                    a.book_year_published,
                    a.book_publisher,
                    b.cat_name
                FROM tblbooks a
                INNER JOIN tblcategories b
                ON a.book_category_id = b.cat_id
                ORDER BY a.book_title";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function insertBook($json){
        include "connection-pdo.php";
        $json = json_decode($json, true);
        $title      = isset($json['title']) ? trim($json['title']) : "";
        $author     = isset($json['author']) ? trim($json['author']) : "";
        $isbn       = isset($json['isbn']) ? trim($json['isbn']) : "";
        $categoryId = isset($json['categoryId']) ? trim($json['categoryId']) : "";
        $year       = isset($json['year']) ? trim($json['year']) : "";
        $publisher  = isset($json['publisher']) ? trim($json['publisher']) : "";
        if (
            $title === "" ||
            $author === "" ||
            $isbn === "" ||
            $categoryId === "" ||
            $year === "" ||
            $publisher === ""
        ) {
            return [
                "error" => true,
                "message" => "All fields are required."
            ];
        }
        if (!ctype_digit((string)$categoryId)) {
            return [
                "error" => true,
                "message" => "Invalid category selected."
            ];
        }
        if (!ctype_digit((string)$year) || strlen((string)$year) !== 4) {
            return [
                "error" => true,
                "message" => "Year Published must be a valid 4-digit year."
            ];
        }
        $sql = "INSERT INTO tblbooks(
                    book_title,
                    book_author,
                    book_isbn,
                    book_category_id,
                    book_year_published,
                    book_publisher
                )
                VALUES(
                    :title,
                    :author,
                    :isbn,
                    :categoryId,
                    :year,
                    :publisher
                )";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":author", $author);
        $stmt->bindParam(":isbn", $isbn);
        $stmt->bindParam(":categoryId", $categoryId);
        $stmt->bindParam(":year", $year);
        $stmt->bindParam(":publisher", $publisher);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return [
                "error" => false,
                "message" => "Book successfully added!"
            ];
        }
        return [
            "error" => true,
            "message" => "Failed to add book."
        ];
    }
}
$operation = "";
$json = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $operation = isset($_GET['operation'])
        ? $_GET['operation']
        : "";
    $json = isset($_GET['json'])
        ? $_GET['json']
        : "";
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $operation = isset($_POST['operation'])
        ? $_POST['operation']
        : "";
    $json = isset($_POST['json'])
        ? $_POST['json']
        : "";
}
$book = new Book();
try {
    switch ($operation) {
        case "getAllBooks":
            echo json_encode(
                $book->getAllBooks()
            );
            break;
        case "insertBook":
            echo json_encode(
                $book->insertBook($json)
            );
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