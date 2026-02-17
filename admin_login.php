<?php
session_start();
require 'config.php';

if(isset($_POST['login'])){

    $stmt = $con->prepare("SELECT * FROM admin WHERE username=? AND password=?");
    $stmt->execute([$_POST['user'], $_POST['pass']]);

    if($stmt->fetch()){
        $_SESSION['admin'] = $_POST['user'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid Admin Login!";
    }
}
?>

<h2>Admin Login</h2>

<form method="post">
Username: <input type="text" name="user" required><br><br>
Password: <input type="password" name="pass" required><br><br>
<input type="submit" name="login" value="Login">
</form>

<a href="login.php">Back to User Login</a>
