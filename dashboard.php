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

// Handle profile update when the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Prepare a SQL query to update the user's profile (username and email)
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);
    $stmt->execute();

    // Redirect to update_profile.php after updating
    header("Location: update_profile.php");
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

// Display success alert after deactivating account
if (isset($_POST['deactivate_account'])) {
    // Update user status to inactive in the database
    $stmt = $conn->prepare("UPDATE users SET status = 'inactive' WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Show deactivation success message and redirect
    echo "
    <div class='alert-container'>
        アカウントが正常に非アクティブ化されました！
        <button class='alert-button' onclick='window.location.href = \"index.php\";'>OK</button>
    </div>";
    exit();
}

// Display success alert after deleting account
if (isset($_POST['delete_account'])) {
    // Delete the user from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Show deletion success message and redirect
    echo "
    <div class='alert-container'>
        アカウントが正常に削除されました！
        <button class='alert-button' onclick='window.location.href = \"index.php\";'>OK</button>
    </div>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Meta information for character set and responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロファイルレジスター</title>

    <!-- Link to Google Fonts for styling -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Link to Bootstrap CSS for styling and layout -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link the external CSS file -->
    <link rel="stylesheet" href="css/Dashboard.css">
  <?php include('includes/header_login4.php'); ?>

</head>
<style>
/* General body styling */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    color: white;
    font-weight: bold;
    background: url('images/background3.png');
}

/* Background styling */
.background {
    position: absolute;
    background: url('images/background3.jpg');
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 162, 255, 0.7), rgba(255, 245, 120, 0.7));
    background-size: 400% 400%;
    animation: waveEffect 12s ease infinite;
    z-index: -1;
}

/* Background wave animation */
@keyframes waveEffect {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Dashboard container styling */
.dashboard-container {
    margin-top:250px;
    margin-left:center;
    align-items:center;
    margin-bottom:180px;
}

/* Card styles */
.card {
    background: linear-gradient(135deg, rgba(255, 0, 150, 0.8), rgba(0, 204, 255, 0.8), rgba(255, 245, 120, 0.8));
    background-size: 300% 300%;
    animation: slideColors 8s ease infinite;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    border: none;
}

/* Card header styles */
.card-header {
    background-color: #007bff;
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    font-weight: 600;
    text-align: center;
}

/* Card footer styles */
.card-footer {
    background-color: #444;
    padding: 15px;
    text-align: center;
    font-size: 14px;
    color: #bbb;
    border-radius: 0 0 10px 10px;
}

/* Card body styles */
.card-body {
    padding: 30px;
    text-align: center;
}

/* Profile image styles */
.profile-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 20px;
    border: 6px solid transparent;
    background: linear-gradient(135deg, #ff00ff, #00ffff);
    padding: 5px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
}

.profile-image:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.8);
}

/* Button styles */
.btn {
    border-radius: 30px;
    padding: 12px 30px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
    transition: all 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
    background: transparent;
    border: 2px solid #007bff;
    color: white;
}

.btn:hover {
    color: #000;
    background: linear-gradient(135deg, #ff00ff, #00ffff, #ffff00);
    background-size: 200% 200%;
    border-color: transparent;
    box-shadow: 0 0 20px #ff00ff, 0 0 40px #00ffff, 0 0 60px #ffff00;
}

/* Header container styling */
.header-container {
    position: fixed;
    top: 0;
    left: 100;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items:center;
    padding: 48px 40px;
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.8), rgba(255, 215, 0, 0.8));
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    font-weight: bold;
    color: #fff;
}

.header-container .logout-btn:hover {
    background-color: #f4c430;
    transform: scale(1.05);
    box-shadow: 0 0 10px #ffd700, 0 0 20px #ffd700;
}

</style>
<body>

<!-- Animated background -->
<div class="background"></div>

<!-- Dashboard container -->
<div class="container dashboard-container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    プロファイルレジスター
                </div>
                <div class="card-body text-center">
                    <!-- Profile Image -->
                    <img src="images/profile_Image.jpg" alt="Profile Image" class="profile-image">

                    <!-- Welcome message with the user's username -->
                    <h5>ようこそ、 <?php echo htmlspecialchars($user['username']); ?> さん！</h5>
                    <p>登録情報</p>

                    <!-- Profile Edit Form -->
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">ユーザー名</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <!-- Submit button to update profile -->
                        <button type="submit" name="update_profile" class="btn btn-primary">プロフィールを更新</button>
                    </form>
                    <!-- User Requirements and Feedback Button -->
			<div class="mt-4">
   			 <button class="btn btn-info" onclick="window.location.href='contact.php';">ユーザー要件とフィードバック</button>
			</div>
                    <!-- Account Settings -->
                    <div class="mt-4">
                        <h5>アカウント設定</h5>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#deactivateModal">アカウントを非アクティブ化</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">アカウントを削除</button>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" class="mt-4">
                        <button type="submit" name="logout" class="btn btn-danger">ログアウト</button>
                    </form>
                </div>
                <div class="card-footer">
			<?php
			include 'includes/Footer.php'; // Include your footer
				?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateModalLabel">アカウントの非アクティブ化</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>本当にアカウントを非アクティブ化しますか？この操作は元に戻せません。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <form method="POST">
                    <button type="submit" name="deactivate_account" class="btn btn-warning">アカウントを非アクティブ化</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">アカウントの削除</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>本当にアカウントを削除しますか？この操作は元に戻せません。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <form method="POST">
                    <button type="submit" name="delete_account" class="btn btn-danger">アカウントを削除</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
