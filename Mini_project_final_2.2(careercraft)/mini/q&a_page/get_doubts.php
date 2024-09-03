<?php
session_start();
include '../project2/db_connect.php';

header('Content-Type: application/json');

// Retrieve questions from the database
$sql = "SELECT q.id AS question_id, q.question_text, q.username AS question_username,
               r.id AS reply_id, r.reply_text, r.username AS reply_username
        FROM questions q
        LEFT JOIN replies r ON q.id = r.question_id
        ORDER BY q.id DESC, r.id ASC";

$result = $conn->query($sql);

$questions = [];
$currentQuestionId = null;
$currentQuestion = null;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['question_id'] !== $currentQuestionId) {
            if ($currentQuestionId !== null) {
                $questions[] = $currentQuestion;
            }
            $currentQuestionId = $row['question_id'];
            $currentQuestion = [
                'question_id' => $row['question_id'],
                'question_text' => $row['question_text'],
                'username' => $row['question_username'],
                'replies' => []
            ];
        }

        if (!empty($row['reply_id'])) {
            $currentQuestion['replies'][] = [
                'reply_id' => $row['reply_id'],
                'reply_text' => $row['reply_text'],
                'username' => $row['reply_username']
            ];
        }
    }
    if ($currentQuestionId !== null) {
        $questions[] = $currentQuestion;
    }
}

echo json_encode($questions);
$conn->close();
?>