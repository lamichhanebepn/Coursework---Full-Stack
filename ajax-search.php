<?php
include("db.php");

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM Books WHERE Book_name LIKE ? ORDER BY Book_name";
$stmt = $mysqli->prepare($sql);

$search = "%" . $keyword . "%";
$stmt->bind_param("s", $search);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['Book_id']}</td>
        <td>" . htmlspecialchars($row['Book_name']) . "</td>
        <td>" . htmlspecialchars($row['Book_description']) . "</td>
        <td>{$row['released_date']}</td>
        <td>{$row['rating']}</td>
        <td>
            <a href='edit-book.php?id={$row['Book_id']}' class='btn btn-sm btn-warning mb-1'>Edit</a>
            <a href='delete-book.php?id={$row['Book_id']}' class='btn btn-sm btn-danger mb-1' onclick='return confirm(\"Delete this book?\")'>Delete</a>
        </td>
    </tr>";
}
?>