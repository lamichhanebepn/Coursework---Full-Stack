<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Books</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding:20px; }
        .container { width: 400px; margin: auto; background:#fff; padding:20px; border-radius:8px; }
        input, select { width:100%; padding:10px; margin-bottom:10px; }
        #searchResults { 
            background:#fff; border:1px solid #ccc; max-height:250px;
            overflow-y:auto; margin-top:10px; padding:5px;
        }
        .item { padding:8px; border-bottom:1px solid #eee; cursor:pointer; }
        .item:hover { background:#f1f1f1; }
    </style>
</head>
<body>

<div class="container">
    <h2>Search Books</h2>

    <!-- Search Form -->
    <input type="text" id="keyword" placeholder="Book name...">

    <select id="rating">
        <option value="">All Ratings</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>

    <input type="number" id="min_rating" placeholder="Min Rating">
    <input type="number" id="max_rating" placeholder="Max Rating">

    <button onclick="searchBook()">Search</button>

    <!-- Search Results -->
    <div id="searchResults"></div>

    <!-- Back Link -->
    <a href="list-books.php">&laquo; Back to Book List</a>
</div>

<script>
// Function to perform the search
function searchBook() {
    const keyword = document.getElementById("keyword").value;
    const rating = document.getElementById("rating").value;
    const min_rating = document.getElementById("min_rating").value;
    const max_rating = document.getElementById("max_rating").value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "ajax-search-books.php?keyword="
        + encodeURIComponent(keyword)
        + "&rating=" + encodeURIComponent(rating)
        + "&min_rating=" + encodeURIComponent(min_rating)
        + "&max_rating=" + encodeURIComponent(max_rating), true);

    xhr.onload = function() {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);
            let html = "";

            if (data.length > 0) {
                data.forEach(book => {
                    html += `
                        <div class='item' onclick="window.location='book-details.php?id=${book.book_id}'">
                            <strong>${book.book_name}</strong><br>
                            Rating: ${book.rating} | Released Date: ${book.released_date}
                        </div>
                    `;
                });
            } else {
                html = "<p>No results found.</p>";
            }

            document.getElementById("searchResults").innerHTML = html;
        }
    };

    xhr.send();
}
</script>

</body>
</html>
