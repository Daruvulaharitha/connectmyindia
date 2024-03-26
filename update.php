<?php
session_start();
include 'databaseconnection.php'; // Ensure this is your correct database connection file

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userData = [];

if (isset($_GET['id'])) {
    $user_id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM `users1` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        header('Location: join.php');
        exit();
    }
    $stmt->close();
}

if (isset($_POST['update'])) {
    // Assuming there's a form field named 'id' to hold the user ID for updating
    $id = $conn->real_escape_string($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $post = $conn->real_escape_string($_POST['post']);
    //to fetch your name
    $puserId = $_SESSION['user_id']; // This needs to be correctly set
    $sqlUser = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sqlUser);
    $stmt->bind_param("i", $puserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $puser = $result->fetch_assoc()['name'] ?? 'Unknown';
    $postdate = date('Y-m-d H:i:s');

    $sql = "UPDATE `users1` SET `title` = ?, `category` = ?, `post` = ?, `pdate` = ?, `puser` = ? WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssi", $title, $category, $post, $postdate, $puser, $id);
        if ($stmt->execute()) {
            header('Location: join.php');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
}
$conn->close();
?>



        <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    #background {
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      height: 750px; /* Same height as form */
      width: 5px;
      background-color: #F5F5F5;
    }

    #a {
      width: 400px;
      height: 750px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #F5F5F5;
      padding: 20px; /* Add padding for better appearance */
    }
    #input[type=file]{
      background-color:black;

      color:white;
    }
  </style>
</head>
<body>
  <div id="background"></div>
  <form id="a" action="" method="POST" enctype="multipart/form-data"> <!-- Changed action to the current script -->
    <br>
    <h5>Add/Edit</h5>
    <h6>Please kindly check all fields before submitting the form.</h6>
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($userData['id'] ?? '', ENT_QUOTES); ?>"> <!-- Hidden field for ID -->
    <div class="mb-3">
      <label for="select" class="form-label">Select Category</label>
      <select class="form-select" name="category" id="category">
        <option value="">-Select Category-</option>
        <option value="INDIA" <?php if($userData['category'] == 'INDIA') echo 'selected'; ?>>India</option>
        <option value="AMERICA" <?php if($userData['category'] == 'AMERICA') echo 'selected'; ?>>America</option>
        <option value="CHINA" <?php if($userData['category'] == 'CHINA') echo 'selected'; ?>>China</option>
      </select>
    </div>
    <div class="mb-3">
        <label for="post" class="form-label">Select Post Type</label>
        <select class="form-select" name="post" id="post">
            <option value="">-Select Type-</option>
            <option value="Post" <?php if($userData['post'] == 'Post') echo 'selected'; ?>>Post</option>
            <option value="VIDEO" <?php if($userData['post'] == 'VIDEO') echo 'selected'; ?>>Video</option>
            <option value="AUDIO" <?php if($userData['post'] == 'AUDIO') echo 'selected'; ?>>Audio</option>
            <option value="Gallery" <?php if($userData['post'] == 'Gallery') echo 'selected'; ?>>Gallery</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Post Title</label>
        <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($userData['title'] ?? '', ENT_QUOTES); ?>">
    </div>
    <!-- Since it's a file, existing file handling might be different -->
    <div class="mb-3">
        <label for="file" class="form-label">Post Image</label>
        <input type="file" class="form-control" id="file" name="file">
        <?php if (!empty($userData['file'])): ?>
            Current File: <?php echo htmlspecialchars($userData['file']); ?>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Post Description</label>
        <textarea class="form-control" rows="10" id="description" name="description"><?php echo htmlspecialchars($userData['description'] ?? '', ENT_QUOTES); ?></textarea>
    </div>
    
    <button type="submit" name="update" class="btn btn-primary">Submit</button>
</form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
  </html>
  <?php
   
?>