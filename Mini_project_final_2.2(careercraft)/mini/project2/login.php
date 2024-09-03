<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Debugging: Check received email and password
    error_log("Received email: $username");
    error_log("Received password: $password");

    $sql = "SELECT * FROM reg WHERE username='$username'";
    $result = $conn->query($sql);
    
    // Debugging: Check if query returned any result
    error_log("Number of rows returned: " . $result->num_rows);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Debugging: Check the hashed password from the database
        error_log("Stored hashed password: " . $row['password']);

        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['bio'] = $row['bio'];
           // $_SESSION['profile_pic']=$row['profile_pic']
            echo "Login successful";
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
