<?php
session_start();
include("db.php");

$id = $_POST['id'] ?? '';
$name = trim($_POST['book_name'] ?? '');
$desc = trim($_POST['book_description'] ?? '');
$date = $_POST['released_date'] ?? '';
$rating = $_POST['rating'] ?? '';

if ($id === '' || $name === '' || $desc === '' || $date === '' || $rating === '') {
    $_SESSION['msg'] = "Please fill in all fields.";
    header("Location: list-books.php");
    exit;
}

$stmt = $mysqli->prepare("UPDATE Books SET Book_name = ?, Book_description = ?, released_date = ?, rating = ? WHERE Book_id = ?");
$stmt->bind_param("sssii", $name, $desc, $date, $rating, $id);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Book updated successfully.";
} else {
    $_SESSION['msg'] = "Error updating book.";
}

$stmt->close();

header("Location: list-books.php");
exit;
?>