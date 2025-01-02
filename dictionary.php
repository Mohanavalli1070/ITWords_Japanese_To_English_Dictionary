<?php
session_start(); // Start the session
include 'includes/db.php'; // Ensure you include your database connection file

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : null;
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

// Pagination variables
$limit = 10; // Number of rows to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// After connecting to the database
mysqli_set_charset($conn, "utf8mb4");
// Fetching data from the 'terms' table with pagination
$query = "SELECT *, 
    CONCAT('https://ja.wikipedia.org/wiki/', REPLACE(ja_term, ' ', '_')) AS ja_wiki_link, 
    CONCAT('https://en.wikipedia.org/wiki/', REPLACE(en_term, ' ', '_')) AS en_wiki_link 
    FROM terms LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Count total rows for pagination
$total_query = "SELECT COUNT(*) AS total FROM terms";
$total_result = mysqli_query($conn, $total_query);
$total_rows = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_rows / $limit);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Google Drive link for downloading data
$googleDriveLink = "https://docs.google.com/spreadsheets/d/1rJhDpI3Y0sYLPjbY7JhSoDiLDDzA0w20HxCQNDuCBZo/edit?usp=sharing";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data from Terms Table</title>
   /* <link rel="stylesheet" href="css/styles.css"> <!-- Optional: Link to your CSS -->*/
    <style>
       /* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    background: url('images/encyclopedia.jpg'); 
}

.container {
    max-width: 1000px;
    margin: auto;
    background: #fff;
    background: url('images/login_background.jpg');
    padding:10px;
    margin-left:auto;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

h2 {
    color:white;
    font-size: 2em;
    font-weight: 600;
    margin-bottom: 30px;
}

/* Search/Filter Input */
.filter-input {
    padding: 12px;
    width: 100%;
    border: 2px solid #00c6ff;
    border-radius: 8px;
    font-size: 1em;
    color: teal;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.filter-input:focus {
    outline: none;
    border-color: #0072ff;
    box-shadow: 0 0 10px rgba(0, 114, 255, 0.6);
}

/* Header */
header {
    background: transparent; /* Transparent background */
    margin-top: 50px;
    padding: 20px;
    color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Light shadow for floating effect */
}

header h1 {
    font-size: 3.2em;
    color: #000; /* Bold black color for IT terms */
    font-weight: bold; /* Make the text bold */
    font-family: 'Dancing Script', cursive; /* Apply cursive font */
    text-shadow: none; /* Remove text shadow for a cleaner look */
    letter-spacing: 2px; /* Add slight space between letters for readability */
}

header span, header a,h3 {
    font-size: 1.2em;
    color: #ffffff;
    text-decoration: none;
    font-weight: bold;
}

header a:hover {
    text-decoration: underline;
    color: #00bfae;
}

/* Table Styles */
table {
    background: url('images/paper_background4.jpg');
    width: 100%;
    color:black;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 1.1em;
    transition: all 0.3s ease;
    margin-bottom:150px;
}

th, td {
   background: url('images/paper_background4.jpg');
    padding: 12px;
    color:black;
    text-align: left;
    border-bottom: 1px solid #ddd;
    background-color: #f7f7f7;
}

th {
    background: url('images/paper_background4.jpg');
    background-color: #00c6ff;
    color:teal blue;
    font-weight: bold;
}

tr:hover {
    background-color: #f0f8ff;
    background: url('images/paper_background1.jpg');
    transform: scale(1.02);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.wiki-icon {
    width: 50px;
    height: 50px;
    transition: transform 0.3s ease;
}

.wiki-icon:hover {
    transform: scale(1.2);
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.pagination a {
    color:white;
    padding: 30px 25px;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: url('images/paper_background2.jpg');
    box-shadow: 0 4px 8px rgba(0, 198, 255, 0.4);
    font-weight: bold;
}

.pagination a:hover {
    background: url('images/paper_background1.jpg');
    color:black;
    transform: scale(1.1);
    box-shadow: 0 6px 12px rgba(0, 198, 255, 0.6);
}

.pagination span {
    font-weight: bold;
    color: white;
}

/* Button Styles for Library Environment Dictionary Web App */
.download-button {
    background-color: #8B4513; /* SaddleBrown for a warm brown base */
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1em;
    transition: all 0.3s ease;
    margin-bottom: 30px;
    box-shadow: 0 4px 8px rgba(139, 69, 19, 0.3); /* Soft brown shadow */
}

.download-button:hover {
    background-color: #F0E68C; /* Pale Yellow (Khaki) on hover */
    color: #8B4513; /* Dark Brown text color when hovered */
    transform: translateY(300px); /* Move the button up (jump effect) */
    box-shadow: 0 8px 16px rgba(139, 69, 19, 0.4); /* More prominent brown shadow */
}

.download-button:focus, 
.download-button:active {
    background-color: #F0E68C; /* Pale Yellow stays when focused */
    color: #8B4513; /* Dark Brown text color when focused */
    outline: none; /* Remove the default focus outline */
}


/* Mobile Responsiveness */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    h2 {
        font-size: 1.5em;
    }

    .filter-input {
        font-size: 0.9em;
    }

    .download-button {
        font-size: 1em;
    }

    .pagination a {
        font-size: 0.9em;
    }
}
    </style>
</head>
<body>
    <header>
        <div class="logo d-flex align-items-center">
            <img src="images/websiteIcon.jpg" alt="Website Logo" class="mr-2">
        </div>       
        <li class="nav-item"><a class="nav-link text-dark font-weight-bold" href="index.php">Home</a></li>
        <?php if ($isLoggedIn): ?>
            <span><?php echo htmlspecialchars($username); ?>さん、こんにちは。</span> |
            <a href="favorites.php" style="color: lightblue;">View Favorites</a> |
            <a href="logout.php" style="color: red;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color: lightblue;">Login</a>
        <?php endif; ?>
    </header>   

	 <div class="container">
        <h2>Terms Table Data</h2>
        
	  <!-- Display Google Drive Link -->
        <h3>Click the link below to download, print, or copy the data:</h3>
        <a href="<?php echo $googleDriveLink; ?>" target="_blank" class="download-button">Download Data from Google Sheets</a>
        <span><br><br><br><br></span> 
	<form method="GET" action="search1.php">
   	 <input 
        type="text" 
        class="filter-input" 
        name="query" 
        id="searchInput" 
        placeholder="Search terms..."
        onkeypress="if(event.key === 'Enter') this.form.submit();">
</form>
           <!-- Pagination Controls -->
        <div class="pagination">
            <div>
                <span>Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_rows); ?> of <?php echo $total_rows; ?> entries</span>
            </div>
            <div>
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                <?php endif; ?>                      
            </div>
           </div>
        <span><br><br><br><br></span>	

        <table id="termsTable">
          <thead>
    <tr>
        <th>ID</th>
        <th>Japanese Term</th>
        <th>English Term</th>
        <th>Japanese Definition</th>
        <th>English Definition</th>
        <th>Wiki Link (JP)</th>
        <th>Wiki Link (EN)</th>
        <th>ImageURL</th>
    </tr>
</thead>
<tbody>
    <?php
    // Fetch and display rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ja_term']) . "</td>";
        echo "<td>" . htmlspecialchars($row['en_term']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ja_definition']) . "</td>";
        echo "<td>" . htmlspecialchars($row['en_definition']) . "</td>";
        
        // Wiki Link (JP)
        if (!empty($row['ja_wiki_link'])) {
            echo "<td><a href='" . htmlspecialchars($row['ja_wiki_link']) . "' target='_blank'><img src='images/jp_icon.png' class='wiki-icon' alt='JP Wiki Link'></a></td>";
        } else {
            echo "<td>No link</td>";
        }
        
        // Wiki Link (EN)
        if (!empty($row['en_wiki_link'])) {
            echo "<td><a href='" . htmlspecialchars($row['en_wiki_link']) . "' target='_blank'><img src='images/en_icon.png' class='wiki-icon' alt='EN Wiki Link'></a></td>";
        } else {
            echo "<td>No link</td>";
        }

        // For image_url, check for empty or NULL value
        if (empty($row['image_url']) || is_null($row['image_url'])) {
            echo "<td>Yet to Come...</td>";
        } else {
            echo "<td><img src='" . htmlspecialchars($row['image_url']) . "' alt='Image' class='image'></td>";
        }

        echo "</tr>";
    }
    ?>
</tbody>
        </table>        
        <!-- Pagination Controls -->
        <div class="pagination">
            <div>
                <span>Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_rows); ?> of <?php echo $total_rows; ?> entries</span>
            </div>
            <div>
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                <?php endif; ?>                      
            </div>
           </div>		
    </div>   
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
