<?php
session_start();
include('db.php'); // your database connection

$message = '';

// Generate simple numeric CAPTCHA if not set
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) {
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $captcha = $_POST['captcha'] ?? '';

    // Validate numeric CAPTCHA
    if ($captcha != $_SESSION['captcha_num1'] + $_SESSION['captcha_num2']) {
        $message = "Incorrect CAPTCHA answer.";
    } else if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $message = "Registration successful. <a href='login.php'>Login here</a>.";
        } else {
            if ($mysqli->errno == 1062) {
                $message = "Username or email already exists.";
            } else {
                $message = "Error: " . $mysqli->error;
            }
        }
        $stmt->close();
    }

    // regenerate CAPTCHA after each attempt
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 20px; }
form { background: #fff; padding: 20px; max-width: 400px; margin: 20px auto; border-radius: 5px; }
input { width:100%; padding:10px; margin:5px 0 15px; }
input[type=submit] { background:#28A745; color:#fff; border:none; cursor:pointer; }
.message { color:red; margin-bottom:10px; }
</style>
</head>
<body>
<h2 style="text-align:center;">Register</h2>
<form method="post">
    <div class="message"><?= $message ?></div>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <label>Solve CAPTCHA: <?= $_SESSION['captcha_num1'] ?> + <?= $_SESSION['captcha_num2'] ?> = ?</label>
    <input type="number" name="captcha" placeholder="Enter answer" required>
    <input type="submit" value="Register">
</form>
<p style="text-align:center;"><a href="login.php">Already have an account? Login here</a></p>
</body>
</html>
