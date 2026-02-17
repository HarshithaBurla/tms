<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<h2>Welcome <?php echo $_SESSION['user']; ?> 🎉</h2>

<p>You are logged in successfully.</p>

<br>
<a href="booking.php">Book a Tour</a>
<br><br>
<a href="my_bookings.php">My Bookings</a>

<br><br>
<a href="logout.php">Logout</a>
<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<link rel="stylesheet" href="style.css">

<div class="navbar">
    <a href="dashboard.php">Home</a>
    <a href="booking.php">Book Tour</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2>Welcome <?php echo $_SESSION['user']; ?> 🎉</h2>
    <p>You are logged in successfully.</p>
</div>
