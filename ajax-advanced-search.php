<?php
include("db.php");

// Always use $mysqli, NOT $conn
$genre = "%" . ($_GET['genre'] ?? "") . "%";
$min   = $_GET['min'] ?? 0;
$max   = $_GET['max'] ?? 10;  // Ratings are generally between 1 and 10, so we limit the max to 10

$stmt = $mysqli->prepare("
    SELECT * FROM Books
    WHERE book_description LIKE ?
    AND rating >= ?
    AND rating <= ?
    ORDER BY book_name
");

$stmt->bind_param("sdd", $genre, $min, $max);
$stmt->execute();

$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
exit;
?>

