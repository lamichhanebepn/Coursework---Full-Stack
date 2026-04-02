<?php
include("db.php");
header('Content-Type: application/json');

// Get the search parameters from the URL query string
$keyword = "%" . ($_GET['keyword'] ?? "") . "%";
$rating  = $_GET['rating'] ?? "";
$min     = $_GET['min_rating'] ?? 0;
$max     = $_GET['max_rating'] ?? 5;

// SQL Query to fetch books based on search parameters
$query = "SELECT * FROM Books 
          WHERE book_name LIKE ? 
          AND rating >= ? 
          AND rating <= ?
          ORDER BY released_date";

// Prepare the SQL statement
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sdd", $keyword, $min, $max);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results as an array
$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

// Return the result as a JSON response
echo json_encode($books);
exit;
?>
