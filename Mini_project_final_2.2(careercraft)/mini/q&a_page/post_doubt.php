<?php
session_start();

// Include the database connection file
include '../project2/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'Error', 'message' => 'User not logged in']);
    exit();
}

// Check if data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['question_text']) && !empty($_POST['question_text'])) {
        $username = $_SESSION['username']; // Get username from session
        $question_text = $conn->real_escape_string($_POST['question_text']);
        
        // Insert doubt into database
        $sql = "INSERT INTO questions (username, question_text) VALUES ('$username', '$question_text')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'Success', 'message' => 'Doubt posted successfully']);
        } else {
            echo json_encode(['status' => 'Error', 'message' => 'Failed to post doubt']);
        }
    } else {
        echo json_encode(['status' => 'Error', 'message' => 'Invalid input']);
    }
    $conn->close();
} else {
    echo json_encode(['status' => 'Error', 'message' => 'Invalid request method']);
}
?>