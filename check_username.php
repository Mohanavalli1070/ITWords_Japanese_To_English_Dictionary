<?php
include 'includes/db.php';

$username = $_GET['username'];
$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
echo json_encode(['exists' => $stmt->rowCount() > 0]);
?>
