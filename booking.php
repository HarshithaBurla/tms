<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['book'])){

    $stmt = $con->prepare("INSERT INTO booking 
        (username, ffirst, flast, femail, city, fphone, fdesti)
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $_SESSION['user'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['email'],
        $_POST['city'],
        $_POST['phone'],
        $_POST['destination']
    ]);

    echo "Booking Successful!";
}
?>

<h2>Book Your Tour</h2>

<form method="post">
First Name: <input type="text" name="fname" required><br><br>
Last Name: <input type="text" name="lname" required><br><br>
Email: <input type="email" name="email" required><br><br>
City: <input type="text" name="city" required><br><br>
Phone: <input type="text" name="phone" required><br><br>
Destination:
<select name="destination">
    <option>Goa</option>
    <option>Manali</option>
    <option>Delhi</option>
    <option>Jaipur</option>
</select><br><br>

<input type="submit" name="book" value="Book Now">
</form>

<br>
<a href="dashboard.php">Back to Dashboard</a>
