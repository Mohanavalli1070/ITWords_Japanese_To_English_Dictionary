<?php
// Start the session to manage user authentication
session_start();

// Include your database connection file
include 'includes/db.php'; // Ensure the path to db.php is correct

// Check if the user is logged in by verifying the session variable 'user_id'
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    header('Location: login.php');
    exit();
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Prepare a SQL query to fetch user data based on the user_id from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user data from the result
$user = $result->fetch_assoc();

// If no user data is found, redirect to the login page
if (!$user) {
    header('Location: login.php');
    exit();
}

// Handle logout functionality
if (isset($_POST['logout'])) {
    // Destroy the session to log the user out
    session_destroy();

    // Redirect to the homepage (index.php)
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positive Feedback - IT Terms Translation App</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include('includes/header_login4.php'); ?>
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Roboto', sans-serif;
            color: black;
            font-size: 25px;
            font-weight: bold;
            background: url('images/header1.jpg');
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Snowflake Styles */
        .snowflake-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1000;
            overflow: hidden;
        }

        .snowflake {
            position: absolute;
            font-size: 20px;
            color: white;
            opacity: 0.8;
            animation: snow 10s linear infinite, wind 5s linear infinite;
        }

        .snowflake:nth-child(odd) {
            animation-duration: 12s, 6s;
        }

        .snowflake:nth-child(even) {
            animation-duration: 10s, 4s;
        }

        @keyframes snow {
            0% {
                top: -10%;
                opacity: 0;
            }
            100% {
                top: 100%;
                opacity: 1;
            }
        }

        @keyframes wind {
            0% {
                left: 50%;
            }
            50% {
                left: 60%;
            }
            100% {
                left: 50%;
            }
        }

        .feedback-container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
            border: 10px solid transparent;
            background-image: url('images/favorite.jpg');
            background-clip: border-box;
            border-radius: 50px;
            color: black;
            font-weight: bold;
        }

        .feedback-container h2, h3 {
            background-image: url('images/paper_background4.jpg');
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: black;
        }

        .feedback-footer {
            background-image: url('images/favorite.jpg');
            margin-top: 60px;
            text-align: center;
            font-size: 1rem;
            color: #888;
        }

        .feedback-footer a {
            background-image: url('images/favorite.jpg');
            color: #ff9f00;
            text-decoration: none;
        }

        .feedback-footer a:hover {
            text-decoration: underline;
        }

        iframe {
            border: 1px solid #ccc;
            border-radius: 15px;
            width: 100%;
            height: 80vh;
        }

        @media (max-width: 768px) {
            iframe {
                height: 60vh;
            }
        }
    </style>
</head> 
<body>  
    <div class="snowflake-container" id="snowflakes"></div>
    <div class="feedback-container">
        <h2>Your Feedback Makes Us Better!</h2>
        <h3>We truly appreciate your feedback. It helps us grow and improve. Please share your thoughts, and let us know how we can make your experience even better.</h3>
 
        <!-- Embedding the Google Form -->
        <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSdgXb0kflWX9hAH9MmC0wk4C6fJdfMj58FQoZkBpfdYwYUeig/viewform?embedded=true"></iframe>
           
        <div class="feedback-footer">
            <p>Need assistance? <a href="contact.php">Contact Us</a></p>
        </div>
    </div>

  root.bind("<KeyPress>",key_down)
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function createSnowflakes() {
            const snowflakeContainer = document.getElementById('snowflakes');
            const numberOfSnowflakes = 50;

            for (let i = 0; i < numberOfSnowflakes; i++) {
                let snowflake = document.createElement('div');
                snowflake.classList.add('snowflake');
                snowflake.innerHTML = '❄';
                snowflake.style.left = `${Math.random() * 100}%`;
                snowflake.style.animationDuration = `${Math.random() * 5 + 5}s`;
                snowflake.style.fontSize = `${Math.random() * 10 + 10}px`;
                snowflake.style.animationDelay = `${Math.random() * 5}s`;
                snowflakeContainer.appendChild(snowflake);
            }
        }

        createSnowflakes();
    </script>
</body> 
</html>
