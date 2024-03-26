<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
body {background-color: #617aff}
#b{
    width: 300px;
 
  height: 400px;
 align:center;
 margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}


#c{
    text-align:center;
}
</style>
</head>
<body>
   
    <div class="$box-shadow-sm p-2 md-2 bg-body-tertiary rounded" id="b">
<h2 id="c">Login</h2>
<hr>
<form action="loginsubmit.php" method="POST">
  <div class="mb-3">
    <label for="email" class="form-label">Username</label>
    <input type="email" class="form-control" id="email" name="email"  required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" required>
    <div id="email" class="form-text">Forgot Password?</div>
  </div>
  <div class="d-grid gap-2 col-6 mx-auto">
  <button class="btn rounded-4 btn-primary" type="submit" value="login">Login</button>
</div>
<br>
<div class="d-grid gap-1 col-8 mx-auto">
<div id="email" class="form-text">Not a member?<a href='signup.php'>Sign Up</a></div>
</div> 
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
