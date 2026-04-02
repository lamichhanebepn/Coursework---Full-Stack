<?php
include("db.php");
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT Book_id, Book_name, Book_description, released_date, rating
        FROM Books
        ORDER BY Book_name";
$result = $mysqli->query($sql);

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books, JSON_UNESCAPED_UNICODE);
exit;
?>