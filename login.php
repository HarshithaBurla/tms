<?php
session_start();
require 'config.php';

if(isset($_POST['login'])){

    $stmt = $con->prepare("SELECT * FROM customer WHERE fname=? AND password=?");
    $stmt->execute([$_POST['name'], $_POST['pass']]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        $_SESSION['user'] = $user['fname'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid Username or Password!";
    }
}
?>

<h2>Login</h2>
<form method="post">
Name: <input type="text" name="name" required><br><br>
Password: <input type="password" name="pass" required><br><br>
<input type="submit" name="login" value="Login">
</form>

<a href="register.php">New user? Register</a>
