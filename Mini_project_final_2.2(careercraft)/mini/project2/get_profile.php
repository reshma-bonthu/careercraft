<?php
session_start();
include 'db_connect.php';

$response = [];

if (isset($_SESSION['username'])) {
    $username = $conn->real_escape_string($_SESSION['username']);

    $sql = "SELECT * FROM reg WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['username'] = $row['username'];
        $response['email'] = $row['email'];
        $response['bio'] = $row['bio'];
        $response['profile_pic'] = base64_encode($row['profile_pic']); // Encode image data to base64
    } else {
        $response['error'] = "User not found.";
    }
} else {
    $response['error'] = "User is not logged in.";
}

echo json_encode($response);
?>
