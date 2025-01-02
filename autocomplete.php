<?php
include 'includes/db.php'; // Include database connection

// Check if a search query is set
if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']); // Sanitize the query
    $sql = "SELECT en_term FROM terms WHERE en_term LIKE '%$query%' OR ja_term LIKE '%$query%' LIMIT 10"; // Query to get matching terms
    $result = mysqli_query($conn, $sql); // Execute the query

    $suggestions = []; // Initialize an array to store the suggestions
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = $row['en_term']; // Store each suggestion
    }

    // Return the suggestions as a JSON array
    echo json_encode($suggestions);
}
?>
