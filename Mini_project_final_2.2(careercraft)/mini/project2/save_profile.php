<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        // Handle file upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $fileContent = file_get_contents($file['tmp_name']);
            $fileContent = $conn->real_escape_string($fileContent);

            $sql = "UPDATE reg SET profile_pic='$fileContent' WHERE username='$username'";
            if (!$conn->query($sql)) {
                echo "Error updating profile picture: " . $conn->error;
            }
        }

        // Update bio
        if (isset($_POST['bio'])) {
            $bio = $conn->real_escape_string($_POST['bio']);
            $sql = "UPDATE reg SET bio='$bio' WHERE username='$username'";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['bio'] = $bio; // Update session bio
                echo "Profile updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Bio data is missing.";
        }
    } else {
        echo "User is not logged in.";
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
