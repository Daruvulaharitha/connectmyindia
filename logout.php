<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// remove all session variables

header('Location: join.php');
// destroy the session
session_destroy();
?>


</body>
</html>