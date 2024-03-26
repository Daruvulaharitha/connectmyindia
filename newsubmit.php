<?php
session_start();
include 'databaseconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    $category = $_POST['category'];
    $post = $_POST['post'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file = $_FILES['file']['name'];
  

    // Move uploaded image to desired directory
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    // Fetch user name
    $puserId = $_SESSION['user_id']; // This needs to be correctly set
    $sqlUser = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sqlUser);
    $stmt->bind_param("i", $puserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $puser = $result->fetch_assoc()['name'] ?? 'Unknown';

    $postdate = date('Y-m-d H:i:s');

    // Insert user data into database using prepared statement
    $sql = "INSERT INTO users1 (category, post, title, description, file, pdate, puser) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $category, $post, $title, $description, $file, $postdate, $puser);
    if ($stmt->execute()) {
        header("Location: join.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $conn->close();
}
?>
