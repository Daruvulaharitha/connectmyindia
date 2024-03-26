<?php
// Initialize error variables with empty values
$nameErr = $emailErr = $phoneErr = $passwordErr = $imageErr = "";
$name = $email = $phone = $password = $image = "";
$registrationSuccess = false; // Flag to track registration success

// Function to sanitize input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

include 'databaseconnection.php'; // Include the database connection

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true; // Assume form is valid at the start

    // Validate name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $valid = false;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
            $valid = false;
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $valid = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $valid = false;
        }
    }

    // Validate phone
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
        $valid = false;
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^\d{10}$/", $phone)) {
            $phoneErr = "Phone must be 10 digits";
            $valid = false;
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $valid = false;
    } else {
        $password = test_input($_POST["password"]);
        if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
            $passwordErr = "Must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters";
            $valid = false;
        }
    }

    // Image upload validation
    if (!isset($_FILES['image']) || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
        $imageErr = "Image is required";
        $valid = false;
    } else {
        $image = $_FILES['image']['name'];
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $imageErr = "Invalid image format. Allowed formats: jpg, jpeg, png, gif";
            $valid = false;
        }
    }

    if ($valid) {
        // Sanitize and prepare data for insertion
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $password = $conn->real_escape_string(password_hash($password, PASSWORD_DEFAULT));

        // Handle the file upload
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert user data into database
            $sql = "INSERT INTO users (name, email, phone, password, image) VALUES ('$name', '$email', '$phone', '$password', '$file_name')";
            if ($conn->query($sql) === TRUE) {
                $registrationSuccess = true; // Flag that registration was successful
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $imageErr = "Failed to upload image.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .error {color: #FF0000;}
        body { background-color:  #617aff; }
        #b {
            width: 400px;
            height: 750px;
            align:center;
            margin:0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        #c {
            text-align:center;
        }
    </style>
</head>
<body>
    <div class="$box-shadow-sm p-2 md-2 bg-body-tertiary rounded" id="b">
        <h2 id="c">Sign Up</h2>
        <hr>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label"> <span class="error">*</span>Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>">
                <span class="error"><?php echo $nameErr;?></span>
            </div>
            <div class="mb-3">
  <label for="exampleInputEmail1" class="form-label"><span class="error">*</span>Email</label>
    <input type="email" class="form-control" id="email1" name="email" value="<?php echo $email;?>">
    <span class="error"><?php echo $emailErr;?></span>
  </div>
     
  <div class="mb-3">
    <label for="phone" class="form-label"> <span class="error">*</span>Phone</label>
    <input type="phone" class="form-control" id="phone" name="phone"  value="<?php echo $phone;?>">
    <span class="error"><?php echo $phoneErr;?></span>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label"><span class="error">*</span>Password</label>
    <input type="password" class="form-control" id="password" name="password"  value="<?php echo $password;?>">
    <div id="email" class="form-text">Forgot Password?</div>
    <span class="error"><?php echo $passwordErr;?></span>
  </div>
  <div class="mb-3">
    <label for="image" class="form-label"><span class="error">*</span>image</label>
    <input type="file" class="form-control" id="image" name="image"  value="<?php echo $image;?>">
    <span class="error"><?php echo $imageErr;?></span>
  </div>
  <div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
  Terms and Conditions
  </label>
</div>
  <br>
  <div class="d-grid gap-2 col-6 mx-auto">
  <button class="btn rounded-4 btn-primary" type="submit" value="Register">Register</button>
</div>

<br>
            <?php if ($registrationSuccess): ?>
                <div class="alert alert-success" role="alert">
                    Registration successful. Redirecting to login page...
                </div>
                <?php header("refresh:2;url=login.php"); ?>
            <?php endif; ?>
            <div class="d-grid gap-1 col-8 mx-auto">
                <div id="email" class="form-text">Already have an account?<a href='login.php'>Login Here</a></div>
            </div> 
            
        </form>
    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
