<?php
require 'config.php';

$message = "";
$error = "";

if(isset($_POST['register'])){

    $name  = $_POST['name'];
    $pass  = $_POST['pass'];
    $email = $_POST['email'];
    $city  = $_POST['city'];
    $phone = $_POST['phone'];

    // Hash the password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $con->prepare("INSERT INTO customer 
        (fname, password, email, city, phone) 
        VALUES (?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$name, $hashedPassword, $email, $city, $phone]);
        $message = "Registration Successful! You can now login.";
    } catch(PDOException $e) {
        $error = "Username already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Create Account</h2>

    <?php if($message != ""): ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if($error != ""): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">

        <label>Username</label>
        <input type="text" name="name" required>

        <label>Password</label>
        <input type="password" name="pass" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>City</label>
        <input type="text" name="city" required>

        <label>Phone</label>
        <input type="text" name="phone" required>

        <input type="submit" name="register" value="Register">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
