<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../project2/db_connect.php';

$doubtId = (int) $_GET['doubtId'];

$sql = "SELECT * FROM replies WHERE doubt_id = $doubtId";
$result = $conn->query($sql);

$replies = array();
while ($row = $result->fetch_assoc()) {
    $replies[] = $row;
}

echo json_encode($replies);

$conn->close();
?>