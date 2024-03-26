<?php
session_start();
include 'databaseconnection.php'; // Ensure this is your correct database connection file

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the current user's name
$puserId = $_SESSION['user_id'];
$sqlUser = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $puserId);
$stmt->execute();
$resultUser = $stmt->get_result();
$currentUserName = $resultUser->fetch_assoc()['name'] ?? 'Unknown';

// Query to select all posts from the database
$sql = "SELECT id, title, category, post, pdate, puser FROM users1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        h1 { text-align: center; }
        .list-group-item { margin-bottom: 1px; padding: 10px; border: 1px solid #ccc; }
        .odd { background-color: #f0f0f0; } /* Define background color for odd list items */
        .even { background-color: #ffffff; } /* Define background color for even list items */
        #cen{
            align:center;
            padding:120px;
            font-size:20px;
            color:#9da1ec;
        }
        #c{
            padding:80px; 
        }
        #d{
            padding: 0;
            margin: 1px;
            display: flex;
            flex-direction: row;
            justify-content: end;
            font-size:20px;
            right:2px;
            top:-39px;
            position:absolute;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-8 mx-auto">
        <ul class="list-group">
            <li class="list-group-item"><b style="font-size:30px">POSTS</b>
                <a href="join.php" id="cen">
                    <i class="fa fa-bars" aria-hidden="true"></i>&nbsp;All Posts
                </a>
                <a href="mypost.php" style="font-size:20px;color:#9da1ec;">
                    <i class="fa fa-bars" aria-hidden="true"></i>&nbsp;My Post
                </a>
                <a href="newpost.php" style="font-size:20px;color:#9da1ec;" id="c">
                    <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;New Post
                </a>
            </li>
            <div class="dropdown">
                <button class="btn btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="d">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            <?php echo $currentUserName; ?>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </ul>
    </div>
</div>
<br><br>

<?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
        <?php
        $counter = 0;
        while ($post = $result->fetch_assoc()) {
            $class = ($counter % 2 == 0) ? 'even' : 'odd'; // Determine odd/even class
            ?>
            <li class="list-group-item <?php echo $class; ?>">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p>
                    <?php echo htmlspecialchars($post['category']); ?> |
                    <?php echo htmlspecialchars($post['post']); ?> |
                    <?php echo htmlspecialchars($post['pdate']); ?> |
                    Posted by: <?php echo htmlspecialchars($post['puser']); ?>&nbsp;&nbsp;
                    <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                        <i class="fa fa-trash-o" aria-hidden="true" style="font-size:20px;color:red;"></i>
                    </a>
                    &nbsp;
                    <a href="update.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to edit this post?');">
                    
                        <i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:20px;color:red;"></i>
                    </a>
                    <a href="preview.php?id=<?php echo $post['id']; ?>">Preview</a>
                </p>
            </li>
            <?php
            $counter++; // Increment the counter after each iteration
        }
        ?>
    </ul>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<?php $conn->close(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
