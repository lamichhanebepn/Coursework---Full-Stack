<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("db.php");
include("twig-init.php");

$result = $mysqli->query("SELECT * FROM Books ORDER BY Book_name");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

$msg = '';
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

echo $twig->render('list-books.html.twig', [
    'books' => $books,
    'username' => $_SESSION['username'],
    'msg' => $msg
]);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Books</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .table-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            padding: 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .custom-table th {
            background: #212529;
            color: #fff;
        }

        .message {
            border-radius: 8px;
        }

        .book-desc {
            max-width: 420px;
            white-space: normal;
        }

        .ajax-dropdown-box {
            min-width: 320px;
        }

        .ajax-book-item {
            border-bottom: 1px solid #eee;
            padding: 8px 12px;
        }

        .ajax-book-item:last-child {
            border-bottom: none;
        }

        .spinner-wrap {
            padding: 16px;
            text-align: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-4 border-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">List of ALL my books!!!</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">

            <form class="d-flex me-4" onsubmit="return false;">
                <input class="form-control me-2" type="search" id="searchBox" placeholder="Search">
                <button class="btn btn-outline-light" type="button" id="searchBtn">Go!</button>
            </form>

            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item dropdown me-4">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        AJAX Features
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#booksModal">
                                Open Books Modal
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" id="loadDropdownBooks">
                                Show Books Dropdown
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <div class="ajax-dropdown-box px-2 pb-2">
                                <div id="ajaxDropdownContent" class="small text-muted px-2">
                                    Click "Show Books Dropdown"
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="nav-item me-4 text-white fw-semibold">
                    Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                </li>

                <li class="nav-item">
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">

    <?php if ($msg): ?>
        <div class="alert alert-success message">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="add-book.php" class="btn btn-primary">Add Book</a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Book Name</th>
                        <th>Description</th>
                        <th>Released Date</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="bookTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['Book_id'] ?></td>
                            <td><?= htmlspecialchars($row['Book_name']) ?></td>
                            <td class="book-desc"><?= htmlspecialchars($row['Book_description']) ?></td>
                            <td><?= htmlspecialchars($row['released_date']) ?></td>
                            <td><?= htmlspecialchars($row['rating']) ?></td>
                            <td>
                                <a href="edit-book.php?id=<?= $row['Book_id'] ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                                <a href="delete-book.php?id=<?= $row['Book_id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Delete this book?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Books Modal -->
<div class="modal fade" id="booksModal" tabindex="-1" aria-labelledby="booksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="booksModalLabel">Available Books</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Book Name</th>
                            <th>Released Date</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody id="modalBookBody">
                        <tr>
                            <td colspan="4">Open the modal to load books.</td>
                        </tr>
                    </tbody>
                </table>
                <div id="modalErrorMessage" class="text-danger"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Main page live search
function searchBooks() {
    const keyword = document.getElementById("searchBox").value;

    fetch("ajax-search.php?keyword=" + encodeURIComponent(keyword))
        .then(response => response.text())
        .then(data => {
            document.getElementById("bookTable").innerHTML = data;
        })
        .catch(() => {
            document.getElementById("bookTable").innerHTML =
                '<tr><td colspan="6">Error loading search results.</td></tr>';
        });
}

document.getElementById("searchBox").addEventListener("keyup", searchBooks);
document.getElementById("searchBtn").addEventListener("click", searchBooks);

// Modal AJAX loader
const booksModal = document.getElementById('booksModal');

booksModal.addEventListener('show.bs.modal', function () {
    const body = document.getElementById("modalBookBody");
    const errorMsg = document.getElementById("modalErrorMessage");

    body.innerHTML = `
        <tr>
            <td colspan="4" class="text-center">
                <div class="spinner-border" role="status"></div>
            </td>
        </tr>
    `;
    errorMsg.textContent = "";

    fetch("ajax.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch");
            }
            return response.json();
        })
        .then(data => {
            body.innerHTML = "";

            if (data.length === 0) {
                body.innerHTML = `<tr><td colspan="4">No books found.</td></tr>`;
                return;
            }

            data.forEach(book => {
                body.innerHTML += `
                    <tr>
                        <td>${book.Book_id}</td>
                        <td>${book.Book_name}</td>
                        <td>${book.released_date}</td>
                        <td>${book.rating}</td>
                    </tr>
                `;
            });
        })
        .catch(() => {
            body.innerHTML = "";
            errorMsg.textContent = "Error loading data.";
        });
});

// Dropdown AJAX loader
document.getElementById("loadDropdownBooks").addEventListener("click", function (e) {
    e.preventDefault();

    const box = document.getElementById("ajaxDropdownContent");

    box.innerHTML = `
        <div class="spinner-wrap">
            <div class="spinner-border spinner-border-sm" role="status"></div>
        </div>
    `;

    fetch("ajax.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch");
            }
            return response.json();
        })
        .then(data => {
            if (data.length === 0) {
                box.innerHTML = `<div class="px-2 py-2 text-muted">No books found.</div>`;
                return;
            }

            let html = "";
            data.forEach(book => {
                html += `
                    <div class="ajax-book-item">
                        <div><strong>${book.Book_name}</strong></div>
                        <div class="text-muted">Date: ${book.released_date}</div>
                        <div class="text-muted">Rating: ${book.rating}</div>
                    </div>
                `;
            });

            box.innerHTML = html;
        })
        .catch(() => {
            box.innerHTML = `<div class="px-2 py-2 text-danger">Error loading books.</div>`;
        });
});
</script>

</body>
</html>