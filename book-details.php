<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("db.php");

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid book ID.");
}

$id = $_GET['id']; // Get the book ID from the URL

// Prepare the SQL query to fetch the book details
$stmt = $mysqli->prepare("SELECT * FROM Books WHERE book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Book not found.");
}
?>

<h1><?= htmlspecialchars($row['book_name']) ?></h1>

<p><strong>Book Description:</strong> <?= htmlspecialchars($row['book_description']) ?></p>
<p><strong>Rating:</strong> <?= htmlspecialchars($row['rating']) ?></p>
<p><strong>Released Date:</strong> <?= htmlspecialchars($row['released_date']) ?></p>

<!-- Back button -->
<p><a href="list-books.php">&laquo; Back to Book List</a></p>
