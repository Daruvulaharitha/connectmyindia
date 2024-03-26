<?php
session_start();
include 'databaseconnection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize variables
$errors = [];
$category = $post = $title = $description = "";

// Function to sanitize input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $category = test_input($_POST["category"]);
    if (empty($category)) {
        $errors[] = "Category is required";
    }

    $post = test_input($_POST["post"]);
    if (empty($post)) {
        $errors[] = "Post type is required";
    }

    $title = test_input($_POST["title"]);
    if (empty($title)) {
        $errors[] = "Title is required";
    }

    $description = test_input($_POST["description"]);
    if (empty($description)) {
        $errors[] = "Description is required";
    }

    // Handle file upload
    if (empty($_FILES['file']['name'])) {
        $errors[] = "Image is required";
    } else {
        $target_dir = "uploads/";
        $file = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            $errors[] = "Invalid image format. Allowed formats: JPG, JPEG, PNG, GIF";
        }

        // Move uploaded file
        if (empty($errors)) {
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $errors[] = "Failed to upload image";
            }
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $userId = $_SESSION['user_id']; // Assuming user_id is set correctly
        $postdate = date('Y-m-d H:i:s');
        // Fetch user name
    $puserId = $_SESSION['user_id']; // This needs to be correctly set
    $sqlUser = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sqlUser);
    $stmt->bind_param("i", $puserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $puser = $result->fetch_assoc()['name'] ?? 'Unknown';


        $stmt = $conn->prepare("INSERT INTO users1 (category, post, title, description, file, pdate, puser) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $category, $post, $title, $description, $file, $postdate, $userId);
        
        if ($stmt->execute()) {
            // Success action (e.g., redirect)
            header("Location: join.php");
            exit;
        } else {
            $errors[] = "Error inserting data into database: " . $stmt->error;
        }

        $stmt->close();
    }
}

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
    <form id="a" action="" method="POST" enctype="multipart/form-data">
        <br>
        
        <h5>Add/Edit</h5>
        <h6>Please kindly check all fields before submitting the form</h6>
        <!-- Display errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="select" class="form-text">Select Category</label>
            <select class="form-select" aria-label="Default select example" name="category" id="category">
                <option value="">-Select Category-</option>
                <option value="INDIA">India</option>
                <option value="AMERICA">America</option>
                <option value="CHINA">China</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="select" class="form-text">Select Post Type</label>
            <select class="form-select" aria-label="Default select example" name="post" id="post">
                <option value="">-Select Type-</option>
                <option value="Post">Post</option>
                <option value="VIDEO">Video</option>
                <option value="AUDIO">Audio</option>
                <option value="Gallery">Gallery</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="post-title" class="form-text">Post Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>">
        </div>
        <div class="mb-3">
            <label for="post-title" class="form-text">Post Image</label>
            <input type="file" class="form-control" id="file" name="file">
        </div>
        <div class="mb-3">
            <label for="description" class="form-text">Post Description</label>
            <textarea class="form-control" rows="10" id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
