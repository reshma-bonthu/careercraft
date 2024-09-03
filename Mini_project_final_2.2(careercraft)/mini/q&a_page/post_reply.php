<?php
session_start();
include '../project2/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['question_id']) && isset($_POST['reply_text'])) {
        $question_id = $_POST['question_id'];
        $reply_text = $_POST['reply_text'];
        $username = $_SESSION['username'];

        // Insert reply into the database
        $stmt = $conn->prepare("INSERT INTO replies (question_id, reply_text, username) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $question_id, $reply_text, $username);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>