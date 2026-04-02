<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("twig-init.php");

$msg = '';
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

echo $twig->render('add-book.html.twig', [
    'username' => $_SESSION['username'],
    'msg' => $msg
]);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a New Book</title>
</head>
<body>

<h1>Add a New Book</h1>

<form action="insert-book.php" method="post">

    <p>
        Book Name:  
        <input type="text" name="book_name" required>
    </p>

    <p>
        Book Description:  
        <textarea name="book_description" required></textarea>
    </p>

    <p>
        Rating (1 to 10):  
        <input type="number" name="rating" min="1" max="10" required>
    </p>

    <p>
        Released Date:  
        <input type="date" name="released_date" required>
    </p>

    <p>
        <input type="submit" value="Add Book">
    </p>

</form>

<a href="list-books.php">&laquo; Back to list</a>

</body>
</html>
