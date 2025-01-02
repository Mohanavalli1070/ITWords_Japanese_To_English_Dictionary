<?php
session_start();
include 'includes/header.php';
include 'includes/db.php'; // Include DB connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data based on session ID
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: login.php');
    exit();
}

// Handle form submission to update user profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];

    // If password is updated, hash the new password
    $password_hash = $user['password_hash']; // Keep the current password if not updated
    if (!empty($_POST['password'])) {
        $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }

    // Update the user data in the database
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, security_question = ?, security_answer = ? WHERE user_id = ?");
    $stmt->bind_param("sssssi", $username, $email, $password_hash, $security_question, $security_answer, $user_id);
    if ($stmt->execute()) {
        $success_message = "プロフィールが更新されました！";
    } else {
        $error_message = "プロフィールの更新中にエラーが発生しました。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-footer {
            background-color: #f1f1f1;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 30px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header text-center">
                    <h3>ダッシュボード</h3>
                </div>
                <div class="card-body">
                    <h5>ようこそ、 <?php echo htmlspecialchars($user['username']); ?> さん！</h5>
                    <p>ここは、あなたの個人ページです。</p>
                    <div class="list-group">
                        <!-- Profile Edit Section -->
                        <a href="#editProfile" data-toggle="collapse" class="list-group-item list-group-item-action">
                            プロフィールの編集
                        </a>
                    </div>

                    <!-- Profile Edit Form (collapsed by default) -->
                    <div id="editProfile" class="collapse mt-4">
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php elseif (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label for="username">ユーザー名</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">メールアドレス</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">新しいパスワード（任意）</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="新しいパスワードを入力（任意）">
                            </div>
                            <div class="form-group">
                                <label for="security_question">セキュリティ質問</label>
                                <input type="text" class="form-control" id="security_question" name="security_question" value="<?php echo htmlspecialchars($user['security_question']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="security_answer">セキュリティの回答</label>
                                <input type="text" class="form-control" id="security_answer" name="security_answer" value="<?php echo htmlspecialchars($user['security_answer']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-custom">プロフィールを更新</button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php 
'includes/footer.php';
?>