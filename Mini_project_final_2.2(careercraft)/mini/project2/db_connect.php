<?php
$servername = "localhost";
$username = "root"; // replace with your MySQL username
$password = "Bonthu@123"; // replace with your MySQL password
$dbname = "mini_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>