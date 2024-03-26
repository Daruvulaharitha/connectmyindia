<?php
session_start();
include 'databaseconnection.php'; // Database connection

$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

$sql = "SELECT id, password FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location:newpost.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email address.";
}
$conn->close();
