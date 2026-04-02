<?php
session_start();
include("db.php");

$name = trim($_POST['book_name'] ?? '');
$desc = trim($_POST['book_description'] ?? '');
$date = $_POST['released_date'] ?? '';
$rating = $_POST['rating'] ?? '';

if ($name === '' || $desc === '' || $date === '' || $rating === '') {
    $_SESSION['msg'] = "Please fill in all fields.";
    header("Location: add-book.php");
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO Books (Book_name, Book_description, released_date, rating) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $name, $desc, $date, $rating);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Book added successfully.";
} else {
    $_SESSION['msg'] = "Error adding book.";
}

$stmt->close();

header("Location: list-books.php");
exit;
?>