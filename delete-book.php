<?php
session_start();
include("db.php");

$id = $_GET['id'] ?? '';

if ($id !== '') {
    $stmt = $mysqli->prepare("DELETE FROM Books WHERE Book_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Book deleted successfully.";
}

header("Location: list-books.php");
exit;
?>