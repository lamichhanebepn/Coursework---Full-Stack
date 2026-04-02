<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("db.php");
include("twig-init.php");

$id = $_GET['id'] ?? '';

if ($id === '') {
    header("Location: list-books.php");
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM Books WHERE Book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    $_SESSION['msg'] = "Book not found.";
    header("Location: list-books.php");
    exit;
}

echo $twig->render('edit-book.html.twig', [
    'username' => $_SESSION['username'],
    'book' => $book
]);
?>

<form action="update-book.php" method="post">
<input type="hidden" name="id" value="<?= $row['Book_id'] ?>">

<input type="text" name="book_name" value="<?= $row['Book_name'] ?>">
<input type="text" name="book_description" value="<?= $row['Book_description'] ?>">
<input type="date" name="released_date" value="<?= $row['released_date'] ?>">
<input type="number" name="rating" value="<?= $row['rating'] ?>">

<button type="submit">Update</button>
</form>