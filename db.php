<?php
$host = 'localhost';
$username = '2448416';
$password = '2448416';
$dbname = 'db2448416';

// Create connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to UTF-8 to avoid character encoding issues
$mysqli->set_charset("utf8");

// Optionally, define a constant for the database table name to avoid repetition
define('BOOKS_TABLE', 'Books');
?>
