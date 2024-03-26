<?php
session_start();
include 'databaseconnection.php'; // Ensure this is your correct database connection file

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the post ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "No post specified.";
    exit;
}

$postId = $_GET['id'];

// Prepare and execute query to fetch the specific post by ID
$sql = "SELECT title, category, post, file, description, pdate, puser FROM users1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Post not found.";
    exit;
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($post['title']); ?> - Post Preview</title>
    <!-- Your CSS and other head content -->
</head>
<body>

<div class="post-preview">
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><em><?php echo htmlspecialchars($post['pdate']); ?></em></p>
    <?php if ($post['file']): ?>
        <img src="uploads/<?php echo htmlspecialchars($post['file']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="max-width:100%;">
    <?php endif; ?>
    <p><?php echo htmlspecialchars($post['description']); ?></p>
    <!-- Display the post content or any additional details here -->
</div>

</body>
</html>
