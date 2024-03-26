<?php
session_start();
include 'databaseconnection.php'; // Ensure this is your correct database connection file

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the post ID is set in the URL
if(isset($_GET['id'])){
    $post_id = $_GET['id'];

    // Prepare the delete statement
    $sql = "DELETE FROM users1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);

    // Execute the delete statement
    if ($stmt->execute()) {
        // Redirect back to the page with all posts
        header('Location: join.php');
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
