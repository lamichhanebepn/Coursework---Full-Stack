<?php
session_start();
include('db.php'); // Include your database connection file

$message = '';

// Generate simple numeric CAPTCHA if not set
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) {
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $captcha = $_POST['captcha'] ?? '';

    // Validate numeric CAPTCHA
    if ($captcha != $_SESSION['captcha_num1'] + $_SESSION['captcha_num2']) {
        $message = "Incorrect CAPTCHA answer.";
    } else {
        $stmt = $mysqli->prepare("SELECT user_id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashedPassword);
        $stmt->fetch();

        if ($hashedPassword && password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: list-books.php"); // Redirect to book list
            exit;
        } else {
            $message = "Invalid username or password.";
        }
        $stmt->close();
    }

    // Regenerate CAPTCHA after each attempt
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        form { background: #fff; padding: 20px; max-width: 400px; margin: 20px auto; border-radius: 5px; }
        input { width:100%; padding:10px; margin:5px 0 15px; }
        input[type=submit] { background:#007BFF; color:#fff; border:none; cursor:pointer; }
        .message { color:red; margin-bottom:10px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Login</h2>
    <form method="post">
        <div class="message"><?= $message ?></div>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <label>Solve CAPTCHA: <?= $_SESSION['captcha_num1'] ?> + <?= $_SESSION['captcha_num2'] ?> = ?</label>
        <input type="number" name="captcha" placeholder="Enter answer" required>
        <input type="submit" value="Login">
    </form>
    <p style="text-align:center;"><a href="register.php">Don't have an account? Register here</a></p>
</body>
</html>
