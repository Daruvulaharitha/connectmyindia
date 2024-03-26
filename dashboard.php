<?php
session_start();
include 'databaseconnection.php'; // Make sure this is your correct database connection file

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: loginsubmit.php');
    exit();
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
          body { background-color:  yellow; }
        table {
            width: 50px;
            border-collapse: collapse;
           
            margin:1px;
           
        }
        
    </style>
</head>
<body>
<table class="table">
  <thead>
    
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
      <td>fggyhh</td>
      <tr>
    </tr>
    </tr>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


