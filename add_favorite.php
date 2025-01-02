<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to save favorites.";
    exit;
}

$word_id = $_POST['word_id'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO favorites (user_id, word_id) VALUES ('$user_id', '$word_id')";
if ($conn->query($sql) === TRUE) {
    echo "Favorite saved successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
